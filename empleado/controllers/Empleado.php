<?php
ini_set('max_execution_time', 0);
class Empleado extends MX_Controller
{
    var $lista_opciones = array(
                                    "1"=>"Asignar Turno(s)",
                                    "2"=>"Habilitar Usuario(s)",
                                    "3"=>"Deshabilitar Usuario(s)",
                                    "4"=>"Asignar Departamento",
                                    "5"=>"Asignar Contrato",
                                );
    var $departamentosArray = array();
    var $contratosArray = array();
    var $departamentos = array();
    var $contratos = array();
	function __construct()
    {
        parent::__construct();
        $this->load->model('M_empleado','m_emple');
        $departamentos = $this->m_emple->listarDepartamento()->result_array();
        $this->contratos = $this->m_emple->listarContrato()->result_array();
        $this->departamentosArray[null]= array('depId'=>0,
                                                'depNombre'=>'(no asignado)');
        foreach($departamentos as $key => $value){
            $this->departamentosArray[$value['depId']] = $value;
        }
        $this->contratosArray[null]= array('contratoId'=>0,
                                                'contratoNombre'=>'(no asignado)');
        foreach($this->contratos as $key => $value){
            $this->contratosArray[$value['contratoId']] = $value;
        }
        if( !$this->session->userdata('usuario')  ){ redirect(base_url()); }
    }

    function index()
    {
    }

    function profundidadTree($nivel)
    {
        $contratos = $this->contratos;
        $profundidad=0;
        $bandera=1;
        while ($bandera>0){
            foreach($contratos as $contrato){
                if ($nivel==$contrato['contratoId']){
                //print_r($contrato['contratoId'].' - '.$contrato['SupContratoId'].'<br>');
                $profundidad=$profundidad+1;
                $bandera=intval($contrato['SupContratoId']);
                }
            }
            $nivel =$bandera;
        }
        return $profundidad;
    }

    function imprimeTree($nivel)
    {
        $html ="";
        $contratos = $this->m_emple->contratoNivel($nivel);
        if ($contratos->num_rows()>0) {
            foreach($contratos->result_array() as $contrato){
                $html .= '<ul class="nav nav-treeview">
                            <li class="nav-item menu-open">';
                $html1 = $this->imprimeTree($contrato['contratoId']);
                $profundidad = $this->profundidadTree($nivel);
                $color = $profundidad*3;
                $dato1 = $contrato['contratoId'];
                $dato2 = $contrato['contratoNombre'];
                if ($html1==""){
                    $html .= '  <a  onClick="asignarContrato('.$dato1.',\''.$dato2.'\')" class="nav-link hhut_link"><i class="fa fa-minus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                } else {
                    $html .= '  <a  onClick="asignarContrato('.$dato1.',\''.$dato2.'\')" class="nav-link hhut_link"><i class="fa fa-plus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                }
                $html .= '  <span class="contratoNombre" style="color: #00'.$color.'090">'.$contrato['contratoNombre'].'</span>
                                <i class="fas fa-angle-left right"></i>
                            </a>';
                $html .= $html1;
                $html .= ' </li>
                        </ul>';
            }
        }
        return $html;
    }

    function listado()
    {
        $rows_html = array();
        $rows_html = $this->m_emple->listarEmpleado();
        $nivel_cero = $this->m_emple->contratoNivel(0);
        $departamentos = $this->m_emple->listarDepartamento();
        $data['departamentos'] = $departamentos->result_array();
        $data['contratos'] = $this->contratos;
        $data['niveles'] = $this->imprimeTree(1);
        $data['nivel_cero'] = $nivel_cero->result_array()[0]['contratoNombre'];
        $data["empAdministrativos"]   =   $this->m_emple->listaAdministrativos()->num_rows();
        $data["empHospital"]          =   $this->m_emple->listaHospital()->num_rows();
        $data["tabla_html"] =$this->empleado_html($rows_html);
        $data["opciones_empleado"] =$this->lista_opciones;
        $data['txt_Prueba'] = "Modulo de Horario";
        $data['data'] = array();
        $this->load->view("v_empleado", $data);
    }

    function listado_tabla()
    {
        $rows_html = array();
        $rows_html = $this->m_emple->listarEmpleado();
        $data["empAdministrativos"]   =   $this->m_emple->listaAdministrativos()->num_rows();
        $data["empHospital"]          =   $this->m_emple->listaHospital()->num_rows();
        $data["tabla_html"] =$this->empleado_html($rows_html);
        $data["opciones_empleado"] =$this->lista_opciones;
        $data['txt_Prueba'] = "Modulo de Horario";
        $data['data'] = array();
        $this->load->view("v_empleado_tabla", $data);
    }

    function sincronizarAdm()
    {
        $empAdministrativos   =   $this->m_emple->listaAdministrativos()->result_array();
        $no_ingresados = 0;
        $ingresados = 0;
        $actualizados = 0;
        foreach ($empAdministrativos as $empleado){
            //* Comprobar si existe USERID en adm_tb
            $adm_habilitado = ($empleado['ATT']==1)? TRUE : FALSE;
            if ($this->m_emple->existeAdministrativo($empleado['USERID'],0)['res']) {
                $data = array(
                    //'empId'      => $nombre,
                    //'documentId' => $empleado['SSN'],
                    'nombres'    => $empleado['NAME'],
                    //'hosp_tb'    => $hora_salida,
                    //'hosp_id'    => $empieza_entrada,
                    'adm_tb'     => TRUE,
                    'adm_id'     => $empleado['USERID'],
                    'adm_habilitado'  => $adm_habilitado,
                    //'turno_id'   => $termina_salida,
                    'empActivo'  => TRUE,
                    //'depId'      => $jornada_laboral,
                    //'contId'     => $salida_tolerancia
                );
                $empId = $this->m_emple->existeAdministrativo($empleado['SSN'],1)['val'];
                $result = $this->m_emple->actualizaEmpleado($empId,$data);
                $ingresados +=1; //ya se encuentra registrado
            } elseif ($this->m_emple->existeAdministrativo($empleado['SSN'],1)['res']) {
                //* Comprobacion si ya existe algun Documento de Identidad duplicado
                $data = array(
                    //'empId'      => $nombre,
                    //'documentId' => $empleado['SSN'],
                    'nombres'    => $empleado['NAME'],
                    //'hosp_tb'    => $hora_salida,
                    //'hosp_id'    => $empieza_entrada,
                    'adm_tb'     => TRUE,
                    'adm_id'     => $empleado['USERID'],
                    'adm_habilitado'  => $adm_habilitado,
                    //'turno_id'   => $termina_salida,
                    'empActivo'  => TRUE,
                    //'depId'      => $jornada_laboral,
                    //'contId'     => $salida_tolerancia
                );
                $empId = $this->m_emple->existeAdministrativo($empleado['SSN'],1)['val'];
                $result = $this->m_emple->actualizaEmpleado($empId,$data);
                print_r($empId);
                echo ' -> ';
                print_r($data);
                echo ' -> ';
                print_r($result);
                echo '<br>';
                $actualizados +=1;
            } else {
                //! Preparar datos para ingreso a BD
                $data = array(
                    //'empId'      => $nombre,
                    'documentId' => $empleado['SSN'],
                    'nombres'    => $empleado['NAME'],
                    //'hosp_tb'    => $hora_salida,
                    //'hosp_id'    => $empieza_entrada,
                    'adm_tb'     => TRUE,
                    'adm_id'     => $empleado['USERID'],
                    'adm_habilitado'  => $adm_habilitado,
                    //'turno_id'   => $termina_salida,
                    'empActivo'  => TRUE,
                    //'depId'      => $jornada_laboral,
                    //'contId'     => $salida_tolerancia
                );
                $result = $this->m_emple->ingresarEmpleado($data);
                $no_ingresados +=1; //nuevo ingreso de persona
            }
        }
        $resultado['existentes'] = $ingresados;
        $resultado['nuevo_ingreso'] = $no_ingresados;
        $resultado['actualizados'] = $actualizados;
        return $resultado;
    }

    function sincronizarHosp()
    {
        $empHospital   =   $this->m_emple->listaHospital()->result_array();
        $no_ingresados = 0;
        $ingresados = 0;
        $actualizados = 0;
        foreach ($empHospital as $empleado){
            $fecha_fin = strtotime($empleado['nEndDate']);
            $fecha_hoy = strtotime(date('Y-m-d',time()));
            $hosp_habilitado = ($fecha_hoy > $fecha_fin) ? FALSE : TRUE;
            if ($this->m_emple->existeHospital($empleado['nUserIdn'],0)['res']) {
                $data = array(
                    //'empId'      => $nombre,
                    //'documentId' => $empleado['SSN'],
                    'nombres'           => $empleado['sUserName'],
                    'hosp_tb'           => TRUE,
                    'hosp_id'           => $empleado['nUserIdn'],
                    'hosp_habilitado'   => $hosp_habilitado,
                    //'adm_tb'     => TRUE,
                    //'adm_id'     => $empleado['USERID'],
                    //'turno_id'   => $termina_salida,
                    'empActivo'         => TRUE,
                    //'depId'      => $jornada_laboral,
                    //'contId'     => $salida_tolerancia
                );
                $empId = $this->m_emple->existeHospital($empleado['DNI'],1)['val'];
                $result = $this->m_emple->actualizaEmpleado($empId,$data);
                $ingresados +=1; //ya se encuentra registrado
            } elseif ($this->m_emple->existeHospital($empleado['DNI'],1)['res']) {
                //* Comprobacion si ya existe algun Documento de Identidad duplicado
                $data = array(
                    //'empId'      => $nombre,
                    //'documentId' => $empleado['SSN'],
                    'nombres'           => $empleado['sUserName'],
                    'hosp_tb'           => TRUE,
                    'hosp_id'           => $empleado['nUserIdn'],
                    'hosp_habilitado'   => $hosp_habilitado,
                    //'adm_tb'     => TRUE,
                    //'adm_id'     => $empleado['USERID'],
                    //'turno_id'   => $termina_salida,
                    'empActivo'         => TRUE,
                    //'depId'      => $jornada_laboral,
                    //'contId'     => $salida_tolerancia
                );
                $empId = $this->m_emple->existeHospital($empleado['DNI'],1)['val'];
                $result = $this->m_emple->actualizaEmpleado($empId,$data);
                $actualizados +=1;
            } else {
                //! Preparar datos para ingreso a BD
                $data = array(
                    //'empId'      => $nombre,
                    'documentId' => $empleado['DNI'],
                    'nombres'    => $empleado['sUserName'],
                    'hosp_tb'    => TRUE,
                    'hosp_id'    => $empleado['nUserIdn'],
                    'hosp_habilitado'   => $hosp_habilitado,
                    //'adm_tb'     => TRUE,
                    //'adm_id'     => $empleado['USERID'],
                    //'turno_id'   => $termina_salida,
                    'empActivo'  => TRUE,
                    //'depId'      => $jornada_laboral,
                    //'contId'     => $salida_tolerancia
                );
                $result = $this->m_emple->ingresarEmpleado($data);
                $no_ingresados +=1; //nuevo ingreso de persona
            }
        }
        $resultado['existentes'] = $ingresados;
        $resultado['nuevo_ingreso'] = $no_ingresados;
        $resultado['actualizados'] = $actualizados;
        return $resultado;
    }

    function sincronizarHospTemp()
    {
        $empHospital   =   $this->m_emple->listaHospital()->result_array();
        $no_ingresados = 0;
        $ingresados = 0;
        $actualizados = 0;
        echo date('Y-m-d').'<br>';
        foreach ($empHospital as $empleado){
            echo $empleado['nUserIdn'].'->'.$empleado['sUserName'].' ---- -- -->'.$empleado['nEndDate'].' ---- -- -->';
            $fecha_fin = strtotime($empleado['nEndDate']);
            $fecha_hoy = strtotime(date('Y-m-d',time()));
            $hosp_habilitado = ($fecha_hoy > $fecha_fin) ?'(USUARIO INACTIVO) <BR>' : '(USUARIO ACTIVO) <BR>';
                echo $hosp_habilitado;
        }
    }

    function getDatosXDni($dni)
    {
		$token = 'apis-token-2468.C0-TN10p3JStuEeRJKe5IjlDkiiwqccJ';
		//$dni = '00514595';
		// Iniciar llamada a API
		$curl = curl_init();
		// Buscar dni
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://api.apis.net.pe/v1/dni?numero=' . $dni,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 2,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_POST           => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  //CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'Referer: https://apis.net.pe/consulta-dni-api',
			'Authorization: Bearer ' . $token
		  ),
		));
		//$ch = curl_init( $url );
		//curl_setopt_array( $ch, $options );
		$err = curl_errno( $curl );
		$server_output = curl_exec($curl);
		$resultado = json_decode($server_output);
		//print_r($resultado);
		curl_close ($curl);
		if ( is_object($resultado))   {
			$output = array(
				'success' => true,
				'data' => $resultado
			);
			//print_r ($output);
			return $output;
			}
		else{
			$error = array(
				'success' => false,
				'msg' => 'Error'
			);
			//print_r($error);
			return $error;
		}
    }

    /*function getDatosXDni($dni)
    {
        //$dni = $this->input->post("dni");
        $url = "http://qamaqi.hospitaltacna.gob.pe/documento/consultaReniec?numero=".$dni;
        //$url = "http://200.191.101.4/documento/consultaReniec?numero=".$dni;
        $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
        CURLOPT_POST           => 1,
        CURLOPT_HTTPHEADER     => array('Content-Type: application/json')
        );
        $ch = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $server_output = curl_exec($ch);
        $resultado = json_decode($server_output);
        $err = curl_errno( $ch );
        curl_close ($ch);
        if ( $err == '0'){
            //print_r ($resultado);
            return $resultado;
        } else {
            $error = array(
                'success' => false,
                'msg' => 'Error'
            );
           return $error;
        }
    }*/

    function buscarDNI($dni){
        //*Inicio de ALgoritmo
        //* -- Verificar si el DNI, existe en la tabla persona
        $encontrado = false;
		$registro = $this->getDatosXDni($dni);
		/*if (!$registro['success']) {
			echo '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
			  <img src="..." class="rounded mr-2" alt="...">
			  <strong class="mr-auto">Bootstrap</strong>
			  <small>11 mins ago</small>
			  <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="toast-body">
			  Hello, world! This is a toast message.
			</div>
		  </div>';
		} else {
			echo '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
			  <img src="..." class="rounded mr-2" alt="...">
			  <strong class="mr-auto">Bootstrap</strong>
			  <small>11 mins ago</small>
			  <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="toast-body">
			  Error, world! This is a toast message.
			</div>
		  </div>';
		}*/
        if ($this->m_emple->existePersona($dni,1)['res']){
            /*echo "Existe un registro en la Tabla <br>";
            print_r($this->m_emple->existePersona($dni,1)['data']);
            $html = '<img id="foto" src="data:image/jpeg;base64,'.$this->m_emple->existePersona($dni,1)["data"]["foto"].'">';
            echo "<br>";
            echo $html."<br>";
            echo "<br>";*/
            if (is_null($this->m_emple->existePersona($dni,1)['data']['foto'])){
                //* SOLO VERIFICA SI LA PERSONA TIENE FOTO EN TABLA PERSONA
                //* Entonces actualizar datos de RENIEC en el registro encontrado
                //* preguntar antes si existe en RENIEC
				//print_r($registro);
                if ($registro['success']){
                    $data = array(
                        //'id_personal'      => $registro->data->,
                        //'ape_paterno'    => $registro['data']->apellidoPaterno,
                        //'ape_materno'    => $registro['data']->apellidoMaterno,
                        //'nombres'    => $registro['data']->nombres,
                        //'sexo'   => $registro->data->,
                        'tipo_documento'   => 1,
                        //'num_documento'   => $registro->data->,
                        //'estado_civil'   => $registro->data->,
                        //'estado_civil_reniec'   => $registro->data->estadociv,
                        //'nom_padre'   => $registro->data->,
                        //'nom_madre'   => $registro->data->,
                        //'fecha_nacimiento'   => $registro->data->,
                        //'lugar_nacimiento'   => $registro->data->,
                        //'lugar_residencia'   => $registro->data->,
                        //'nacionalidad'   => $registro->data->,
                        //'etnia'   => $registro->data->,
                        //'direccion_reniec'   => $registro->data->direccion,
                        //'direccion_residencia'   => $registro->data->,
                        //'direccion_referencia'   => $registro->data->,
                        //'fijo'   => $registro->data->,
                        //'celular1'   => $registro->data->,
                        //'celular2'   => $registro->data->,
                        //'dactilar_derecho'   => $registro->data->,
                        //'dactilar_izquierdo'   => $registro->data->,
                        //'direccion_electronica1'   => $registro->data->,
                        //'direccion_electronica2'   => $registro->data->,
                        //'foto'   => $registro->data->foto64,
                        //'direccion_electronica_inst'   => $registro->data->,
                        //'clave_direccion_electronica_inst'   => $registro->data->,
                        //'ver_contacto'   => $registro->data->,
                        //'fec_reg'   => $registro->data->,
                        //'usu_reg'   => $registro->data->,
                        //'origen'   => $registro->data->,
                        'validado_reniec'   => 1, //antes 1
                        //'ubigeo_reniec'   => $registro->data->ubigeo
                    );
                    $result = $this->m_emple->actualizaPersona($dni,$data);
                } else { //* Si no existe en RENIEC , validado_reniec cambiarlo a 3. no existe en RENIEC
                    $data = array(
                        //'id_personal'      => $registro->data->,
                        //'ape_paterno'    => $registro->data->paterno,
                        //'ape_materno'    => $registro->data->materno,
                        //'nombres'    => $registro->data->nombres,
                        //'sexo'   => $registro->data->,
                        //'tipo_documento'   => 1,
                        //'num_documento'   => $registro->data->,
                        //'estado_civil'   => $registro->data->,
                        //'nom_padre'   => $registro->data->,
                        //'nom_madre'   => $registro->data->,
                        //'fecha_nacimiento'   => $registro->data->,
                        //'lugar_nacimiento'   => $registro->data->,
                        //'lugar_residencia'   => $registro->data->,
                        //'nacionalidad'   => $registro->data->,
                        //'etnia'   => $registro->data->,
                        //'direccion_residencia'   => $registro->data->,
                        //'direccion_referencia'   => $registro->data->,
                        //'direccion_reniec'   => $registro->data->direccion,
                        //'fijo'   => $registro->data->,
                        //'celular1'   => $registro->data->,
                        //'celular2'   => $registro->data->,
                        //'dactilar_derecho'   => $registro->data->,
                        //'dactilar_izquierdo'   => $registro->data->,
                        //'direccion_electronica1'   => $registro->data->,
                        //'direccion_electronica2'   => $registro->data->,
                        //'foto'   => $registro->data->foto64,
                        //'direccion_electronica_inst'   => $registro->data->,
                        //'clave_direccion_electronica_inst'   => $registro->data->,
                        //'ver_contacto'   => $registro->data->,
                        //'fec_reg'   => $registro->data->,
                        //'usu_reg'   => $registro->data->,
                        //'origen'   => $registro->data->,
                        'validado_reniec'   => 3,
                        //'ubigeo_reniec'   => $registro->data->ubigeo
                    );
                    $result = $this->m_emple->actualizaPersona($dni,$data);
                }
            }   //*Fin de comprobacion si tiene FOTO
            $encontrado = true; //* la persona fue encontrada
        } else {
            //echo "No Existe un registro en la Tabla <br>";
            //*preguntar antes si existe en RENIEC
            if ($registro['success']){
                $data = array(          //* Si existe ingresar nuevo usuario con datos RENIEC
                    //'id_personal'      => $registro->data->,
                    'ape_paterno'    => $registro['data']->apellidoPaterno,
                    'ape_materno'    => $registro['data']->apellidoMaterno,
                    'nombres'    => $registro['data']->nombres,
                    //'sexo'   => $registro->data->,
                    'tipo_documento'   => 1,
                    'num_documento'   => $dni,
                    //'estado_civil'   => $registro->data->,
                    //'estado_civil_reniec'   => $registro->data->estadociv,
                    //'nom_padre'   => $registro->data->,
                    //'nom_madre'   => $registro->data->,
                    //'fecha_nacimiento'   => $registro->data->,
                    //'lugar_nacimiento'   => $registro->data->,
                    //'lugar_residencia'   => $registro->data->,
                    //'nacionalidad'   => $registro->data->,
                    //'etnia'   => $registro->data->,
                    //'direccion_reniec'   => $registro->data->direccion,
                    //'direccion_residencia'   => $registro->data->,
                    //'direccion_referencia'   => $registro->data->,
                    //'fijo'   => $registro->data->,
                    //'celular1'   => $registro->data->,
                    //'celular2'   => $registro->data->,
                    //'dactilar_derecho'   => $registro->data->,
                    //'dactilar_izquierdo'   => $registro->data->,
                    //'direccion_electronica1'   => $registro->data->,
                    //'direccion_electronica2'   => $registro->data->,
                    //'foto'   => $registro->data->foto64,
                    //'direccion_electronica_inst'   => $registro->data->,
                    //'clave_direccion_electronica_inst'   => $registro->data->,
                    //'ver_contacto'   => $registro->data->,
                    //'fec_reg'   => $registro->data->,
                    //'usu_reg'   => $registro->data->,
                    //'origen'   => $registro->data->,
                    'validado_reniec'   => 1,
                    //'ubigeo_reniec'   => $registro->data->ubigeo
                );
                $result = $this->m_emple->ingresarPersona($data);
                $encontrado = true; //* la persona fue encontrada
            } else { //* Si no existe en RENIEC , no ingresar persona, sugerir ingreso Manual
                $encontrado = false; //* la persona fue encontrada
                $mensaje = 'Se sugiere ingresar registro manualmente';
            }
        }
        //$registro = $this->getDatosXDni($dni);
        //print_r($registro->data->foto64);
        //$html = '<img id="foto" src="data:image/jpeg;base64,'.$registro->data->foto64.'">';
        //echo $html;
        //* Si se encontro sacar la data de la tabla persona, sino mensaje
        if ($encontrado) {
            $mensaje = 'Registro encontrado.';
            $data = $this->m_emple->existePersona($dni,1)['data'];
            $data2 = $this->m_emple->empleadoxDni($dni);
            //$html = '<img id="foto" src="data:image/jpeg;base64,'.$data["foto"].'">';
            //echo $html."<br>";
        } else {
            $mensaje .= ' Se sugiere ingresar registro manualmente o revisar el número de documento de identidad';
            $data = array();
        }
        $resultado['encontrado']    = $encontrado;
        $resultado['mensaje']       = $mensaje;
        $resultado['data']          = $data;
        $resultado['data2']          = $data2;
        echo (json_encode($resultado));
    }
    function empleado_html($rows)
    {
        $html = "";
        if(!empty($rows)){
            $html .= '<table class="table table-bordered table-sm" id="tabla_completaE">';
            $html .= '<thead class="thead-light">';
            $html .= '<tr>';
            $html .= '<th class="d-none">id</th>';
            $html .= '<th class="d-none">jsonAdmin</th>';
            $html .= '<th class="d-none">jsonEmple</th>';
            $html .= '<th>°ID</th>';
            $html .= '<th class="text-center">Nombre Completo</th>';
            $html .= '<th class="text-center">Departamento</th>';
            $html .= '<th class="text-center">Contrato</th>';
            $html .= '<th class="text-center">Activo</th>';
            $html .= '<th class="text-center">Turno</th>';
            $html .= '<th  class="text-center">Admin</th>';
            $html .= '<th  class="text-center">Hospit</th>';
            $html .= '<th>Opciones</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $contador = 0;
            foreach ($rows as $row) {
                        $contador++;
                        $html .= '<tr>';
                        $html .= '<td class="d-none">'.$row['empId'].'</td>';
                        $html .= '<td class="d-none">'.json_encode($row).'</td>';
                        $html .= '<td class="d-none">'.json_encode($row).'</td>';
                        $html .= '<td class="text-center">'.$row['documentId'].'</td>';
                        //$nombres = $this->getDatosXDni($row['documentId']);
                        $html .= '<td class="text-left">'.strtoupper($row['nombres']).'</td>';
                        $html .= '<td class="text-left">'.$this->departamentosArray[$row['depId']]['depNombre'].'</td>';
                        $html .= '<td class="text-center">'.$this->contratosArray[$row['contId']]['contratoNombre'].'</td>';
                        $html .= '<td class="text-center">
                                    <div class="custom-control custom-checkbox">';
                        if ($row['empActivo']==1){
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                        } else {
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">';
                        }
                        $html .= '                <label  class="custom-control-label"></label>
                                    </div>
                                </td>';
                        $html .=  '<td class="text-center"><div class="text-success"><i class="fas fa-sign-in-alt"></i>  '
                                    .substr($row['documentId'],0,5).'</div>
                                    <div class="text-danger">';
                        $html .= substr($row['documentId'],0,5).'  <i class="fas fa-sign-out-alt"></i></div></td>';
                        $html .= '<td class="text-center">
                                    <div class="custom-control custom-checkbox">';
                        if ($row['adm_tb']==1){
                            if ($row['adm_habilitado']==1) {
                                $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            } else {
                                $html .= '<input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            }
                        } else {
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">';
                        }
                        $html .= '                <label  class="custom-control-label"></label>
                                    </div>
                                </td>';
                        $html .= '<td class="text-center">
                                    <div class="custom-control custom-checkbox">';
                        if ($row['hosp_tb']==1){
                            if ($row['hosp_habilitado']==1) {
                                $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            } else {
                                $html .= '<input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            }
                        } else {
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">';
                        }
                        $html .= '                <label  class="custom-control-label"></label>
                                    </div>
                                </td>';
                        $html .= '<td class="text-center">
                                    <button id="btnEditarEmp" type="button" class="btn bg-gradient-primary btn-xs btnEditarEmp mt-1 mb-1"><i class="fas fa-edit"></i> Editar</button>
                                    <button id="btnBorrarEmp" type="button" class="btn bg-gradient-danger btn-xs btnBorrarEmp mt-1 mb-1"><i class="fas fa-trash-alt"></i> Desactivar</button>
                                  </td>
                                </div>';
                        $html .= '</tr>';
                    }
            $html .= '</tbody>';
            $html .= '</table>';
        }else{
            $html .= '<table class="table table-bordered table-sm" id="tabla_completaE">';
            $html .= '<thead class="thead-light">';
            $html .= '<tr>';
            $html .= '<th class="d-none">id</th>';
            $html .= '<th class="d-none">jsonAdmin</th>';
            $html .= '<th class="d-none">jsonEmple</th>';
            $html .= '<th>°ID</th>';
            $html .= '<th class="text-center">Nombre Completo</th>';
            $html .= '<th class="text-center">Departamento</th>';
            $html .= '<th class="text-center">Contrato</th>';
            $html .= '<th class="text-center">Activo</th>';
            $html .= '<th class="text-center">Turno</th>';
            $html .= '<th  class="text-center">Admin</th>';
            $html .= '<th  class="text-center">Hospit</th>';
            $html .= '<th>Opciones</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            /*espacio de llenado de filas*/
            $html .= '</tbody>';
            $html .= '</table>';
        }
        return $html;
    }

    function guardar()
    {
        $nombre                  = $this->input->post("nombre");
        $hora_entrada            = $this->input->post("hora_entrada");
        $hora_salida             = $this->input->post("hora_salida");
        $empieza_entrada         = $this->input->post("empieza_entrada");
        $termina_entrada         = $this->input->post("termina_entrada");
        $empieza_salida          = $this->input->post("empieza_salida");
        $termina_salida          = $this->input->post("termina_salida");
        $entrada_tolerancia      = $this->input->post("entrada_tolerancia");
        $jornada_laboral         = $this->input->post("jornada_laboral");
        $salida_tolerancia       = $this->input->post("salida_tolerancia");
        $tolerancia_acumulable   = $this->input->post("tolerancia_acumulable");
        $entrada_tardanza        = $this->input->post("entrada_tardanza");
        $color                   = $this->input->post("color");
        //* validar campos ni vacios ni duplicados
            if ($nombre==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Horario está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($this->m_emple->contarHorario($nombre)>0){
                $result["success"] = false;
                $result["msg"] = "Nombre Horario ya existe.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Entrada desde está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Empieza salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //! Preparar datos para ingreso a BD
        $data = array(
            'nombre'                => $nombre,
            'hora_entrada'          => $hora_entrada,
            'hora_salida'           => $hora_salida,
            'empieza_entrada'       => $empieza_entrada,
            'termina_entrada'       => $termina_entrada,
            'empieza_salida'        => $empieza_salida,
            'termina_salida'        => $termina_salida,
            'entrada_tolerancia'    => $entrada_tolerancia,
            'jornada_laboral'       => $jornada_laboral,
            'salida_tolerancia'     => $salida_tolerancia,
            'tolerancia_acumulable' => $tolerancia_acumulable,
            'entrada_tardanza'      => $entrada_tardanza,
            'color'                 => $color,
        );
        $result = $this->m_emple->crearHorario($data);
        //$result["data"] = $data;
        echo json_encode($result);
    }

    function actualizar()
    {
        $nombre                  = $this->input->post("nombre");
        $hora_entrada            = $this->input->post("hora_entrada");
        $hora_salida             = $this->input->post("hora_salida");
        $empieza_entrada         = $this->input->post("empieza_entrada");
        $termina_entrada         = $this->input->post("termina_entrada");
        $empieza_salida          = $this->input->post("empieza_salida");
        $termina_salida          = $this->input->post("termina_salida");
        $entrada_tolerancia      = $this->input->post("entrada_tolerancia");
        $jornada_laboral         = $this->input->post("jornada_laboral");
        $salida_tolerancia       = $this->input->post("salida_tolerancia");
        $tolerancia_acumulable   = $this->input->post("tolerancia_acumulable");
        $entrada_tardanza        = $this->input->post("entrada_tardanza");
        $color                   = $this->input->post("color");
        //* validar campos ni vacios ni duplicados
            if ($nombre==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Horario está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Entrada desde está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Empieza salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //! Preparar datos para ingreso a BD
        $data = array(
            'hora_entrada'          => $hora_entrada,
            'hora_salida'           => $hora_salida,
            'empieza_entrada'       => $empieza_entrada,
            'termina_entrada'       => $termina_entrada,
            'empieza_salida'        => $empieza_salida,
            'termina_salida'        => $termina_salida,
            'entrada_tolerancia'    => $entrada_tolerancia,
            'jornada_laboral'       => $jornada_laboral,
            'salida_tolerancia'     => $salida_tolerancia,
            'tolerancia_acumulable' => $tolerancia_acumulable,
            'entrada_tardanza'      => $entrada_tardanza,
            'color'                 => $color,
        );
        $result = $this->m_emple->actualizaHorario($nombre,$data);
        //$result["data"] = $data;
        echo json_encode($result);
    }

    function asignarDepartamento(){
        $depId                = $this->input->post("depId");
        $empleados            = $this->input->post("empleados");
        $result["depId"] = $depId;
        //$result["empleados"] = $empleados;
        //$i=array();
        $data=array();
        foreach($empleados as $key => $value) {
            //$i[]=$value[0];
            $data[] = array(
                'empId'      => $value[0],
                //'documentId' => $empleado['DNI'],
                //'nombres'    => $empleado['sUserName'],
                //'hosp_tb'    => TRUE,
                //'hosp_id'    => $empleado['nUserIdn'],
                //'hosp_habilitado'   => $hosp_habilitado,
                //'adm_tb'     => TRUE,
                //'adm_id'     => $empleado['USERID'],
                //'turno_id'   => $termina_salida,
                //'empActivo'  => TRUE,
                'depId'      => $depId,
                //'contId'     => $salida_tolerancia
            );
        }
        //$result["contador"] = $i;
        $result["data"] = $data;
        $result["res"] = $this->m_emple->actualizaEmpleados($data);
        echo json_encode($result);
    }

    function asignarContrato(){
        $contId               = $this->input->post("contId");
        $empleados            = $this->input->post("empleados");
        $result["contId"] = $contId;
        //$result["empleados"] = $empleados;
        //$i=array();
        $data=array();
        foreach($empleados as $key => $value) {
            //$i[]=$value[0];
            $data[] = array(
                'empId'      => $value[0],
                //'documentId' => $empleado['DNI'],
                //'nombres'    => $empleado['sUserName'],
                //'hosp_tb'    => TRUE,
                //'hosp_id'    => $empleado['nUserIdn'],
                //'hosp_habilitado'   => $hosp_habilitado,
                //'adm_tb'     => TRUE,
                //'adm_id'     => $empleado['USERID'],
                //'turno_id'   => $termina_salida,
                //'empActivo'  => TRUE,
                'contId'      => $contId,
                //'contId'     => $salida_tolerancia
            );
        }
        //$result["contador"] = $i;
        $result["data"] = $data;
        $result["res"] = $this->m_emple->actualizaEmpleados($data);
        echo json_encode($result);
    }

	function actualizarContrato(){
		$rcontrato        = $this->input->post("rcontrato");
        $rnombramiento    = $this->input->post("rnombramiento");
        $empId            = $this->input->post("empId");
		$data = array(
			//'fcontrato'      	=> $fcontrato,
			//'fnombramiento' 	=> $fnombramiento,
			'rcontrato'    		=> $rcontrato,
			'rnombramiento'    	=> $rnombramiento,
			'legajo_activo'    	=> TRUE,
		);
		if (!(empty($this->input->post("fcontrato")))){
			$data['fcontrato'] = date_format(date_create_from_format('d/m/Y', $this->input->post("fcontrato")),'Y-m-d');
		} else {$data['fcontrato'] = null;}
		if (!(empty($this->input->post("fnombramiento")))){
			$data['fnombramiento'] = date_format(date_create_from_format('d/m/Y', $this->input->post("fnombramiento")),'Y-m-d');
		} else {$data['fnombramiento'] = null;}
        $result["data"] = $data;
        $result["res"] = $this->m_emple->actualizaEmpleado($empId,$data);
        echo json_encode($result);
    }

	function actualizarDepartamento(){
		$documentId        = $this->input->post("documentId");
        $depId    			= $this->input->post("depId");
		$data = array(
			'depId'    		=> $depId,
		);
        //$result["data"] = $data;
        $result["res"] = $this->m_emple->actualizaEmpleadoxDni($documentId,$data);
        echo json_encode($result);
    }

	function actualizarTurno(){
		$documentId        	= $this->input->post("documentId");
        $turno_id    		= $this->input->post("turno_id");
		$data = array(
			'turno_id'    		=> $turno_id,
		);
        //$result["data"] = $data;
        $result["res"] = $this->m_emple->actualizaEmpleadoxDni($documentId,$data);
        echo json_encode($result);
    }

	function actualizarLaboral(){
		$documentId        	= $this->input->post("documentId");
        $contId    			= $this->input->post("contId");
        $regimenId    		= $this->input->post("regimenId");
		$data = array(
			'contId'    		=> $contId,
			'regimenId'    		=> $regimenId,
		);
        //$result["data"] = $data;
        $result["res"] = $this->m_emple->actualizaEmpleadoxDni($documentId,$data);
        echo json_encode($result);
    }

	function empleadoxDni(){

        $dni = $this->input->post('dni');
        //$row = $this->m_emple->empleadoxDni($dni);

        //calculo edad
        //$fec_nac = new DateTime($row['fecha_nacimiento']);
        //$hoy_dia = new DateTime();
       // $annos = $hoy_dia->diff($fec_nac);
        //$row['edad'] = $annos->y.'a '.$annos->m.'m '.$annos->d.'d';
		$row = $this->buscarDNI($dni);
        echo $row;
    }

}
