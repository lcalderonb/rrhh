<?php
//* para migracion no olvidar copiar en MAIN a jquery.bootstrap-duallistbox.min (modificado),
//* base de datos tb_roles
//* menu y opcion
//* actualizar las vistas a la base de datos
ini_set('max_execution_time', 0);
class Roles extends MX_Controller
{
	var $lista_opciones = array();
	function __construct()
    {
        parent::__construct();
		$this->load->model('M_roles','m_rol');
		$meses = array();
		$anios = array();
		setlocale(LC_TIME, "spanish");
		//* obtención de MESES y AÑOS
			for( $i=1;$i<=12;$i++ ){
				$dateObj   = DateTime::createFromFormat('!m', $i);
				$monthName = strftime('%B', $dateObj->getTimestamp());
				$mes = array( 'cod' =>$i , 'desc' => strtoupper($monthName) );
				$meses[] = $mes;
			}
			for( $i=(date("Y")-5) ; $i<=date("Y"); $i++ ){
				$anio = array( 'cod' =>$i , 'desc' => $i );
				$anios[] = $anio;
			}
			$this->lista_opciones["meses"] = $meses;
			$this->lista_opciones["anios"] = $anios;
		//* \. obtención de MESES y AÑOS
		//* obtención de DEPARTAMENTO Y SERVICIO
		$rows_roles = array();
			$this->lista_opciones["Anno"] = strftime("%Y");
			$this->lista_opciones["departamentos"] = $this->m_rol->m_obtenerDepartamentos();
			$this->lista_opciones["servicios"] = $this->m_rol->m_obtenerServicios();
			$this->lista_opciones["empleados"] = $this->m_rol->m_obtenerEmpleados();
			$this->lista_opciones["profesiones"] = $this->m_rol->m_obtenerProfesiones();
			$this->lista_opciones["turnos_rol"] = $this->m_rol->m_obtenerTurnos();
			$this->lista_opciones["contratos"] = $this->m_rol->m_obtenerContratos();
			//$this->lista_opciones["horarios_asistencial"] = $this->m_rol->m_obtenerHorarioAsistencial();
			$this->lista_opciones["tabla_rol_html"] = $this->roles_html($rows_roles,2023,02);
		//* \. obtención de DEPARTAMENTO Y SERVICIO
	}

	function listado()
    {
		$data = $this->lista_opciones;
        $this->load->view("v_rol", $data);
    }
	
	function listado_roles1()
    {
		$rows_roles ="holaaaa";
		$claveRol = "20230300801605324";
		$rol_detalles = $this->m_rol->detalleRol($claveRol);
		$jornada = $rol_detalles[0]['horas_jornada'];
		print_r($jornada);
    }
	
	function listado_roles()
    {
		$anno 			= $this->input->post("anno");
		$mes			= $this->input->post("mes");
		$departamento	= $this->input->post("departamento");
		$servicio		= $this->input->post("servicio");
		$rows_roles 	= $this->m_rol->listarRoles($mes,$anno,$departamento,$servicio);
		$result["res"] = array(
			'success' => true,
			'tabla_rol_html' => $this->roles_html($rows_roles,$anno,$mes),
			//'rows_roles' => $this->roles_html($rows_roles,$anno,$mes),
			'msg' => 'El listado de roles ha sido generado correctamente!'
		);
        echo json_encode($result);
		//print_r($rows_roles);
    }

	function roles_html($rows, $anio, $mes )
  	{
		$html = "";
		// listado de encabezado de tabla
		$html .= '<table class="table table-striped projects">
              			<thead>
							<tr>
								<th style="width: 1%">
									Periodo
								</th>
								<th style="width: 15%">
									Departamento/Servicio
								</th>
								<th style="width: 30%">
									Empleados
								</th>
								<th>
									Progreso del Rol
								</th>
								<th style="width: 3%" align="center">
									Estado
								</th>
								<th style="width: 13%">
								</th>
								<th style="width: 13%">
								</th>
							</tr>
             			</thead>';
		if(!empty($rows))
		{
			$html .= '<tbody>';
			foreach($rows as $row)
			{
				$avance_porcentual = $this->calculaAvanceRol($row['claveRol']);
				$html .='<tr>
							<td>'.$row["anno"].'<br>';
				$fecha_temp = DateTime::createFromFormat('!m', $row["mes"]);
				$mes_name = ucfirst(strftime("%B", $fecha_temp->getTimestamp()));
				$html .=$mes_name.'
							</td>';
				$html .='	<td>
								<small>'.$row["claveRol"].'</small><br>
								<strong>'.$row["depNombre"].'</strong>
								<br/>
								<small>
									<strong>'.$row["servNombre"].'</strong>
								</small>
							</td>';
				$miembros = $this->m_rol->miembrosRol($row['claveRol']);
				$html .='	<td>
								<small>
									'.(sizeof($miembros)).' miembro(s) registrado(s)<br>
									<strong>'.$row["des_profesion"].'(s) - </strong>
									<strong>'.$row["contratoNombre"].'(s)</strong>
								</small>
								<ul class="list-inline">';
								foreach ($miembros as $miembro)
								{
									$nombres =(ucwords(mb_strtolower($miembro["nombres"],"UTF-8")).', '.$miembro["ape_paterno"].' '.$miembro["ape_materno"]);
									$usuario = $miembro["nombres"][0].ucfirst(strtolower($miembro["ape_paterno"])).$miembro["ape_materno"][0];
									if (empty(trim($usuario))){
																$usuario='['.$miembro["documentId"].']';
																$nombres=$miembro["nombre_completo"].'&#010;-Actualizar datos del Empleado-';
															}
				$html .='			<li class="list-inline-item">
										<img alt="('.$usuario.')" title="'.$nombres.'" class="table-avatar" src="data:image/jpeg;base64,'.$miembro["foto"].'">
									</li>';
								}
				$html .='		</ul>
							</td>';
				$html .='	<td class="project_progress">
								<div class="progress progress-sm">
									<div 	class="progress-bar '.($avance_porcentual=="100"?"bg-green":"bg-red").'"
											role="progressbar"
											aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" 
											style="width: '.$avance_porcentual.'%">
									</div>
								</div>
								<small>
									'.$avance_porcentual.'% Completado.
								</small>
								<br>
								<small>
									Creado por: '.$row["autor_nombres"].', el
									'.$row["fechahora_autor"].'
								</small>
							</td>';
				$html .='	<td class="project-state">
								<span class="badge badge-warning">En proceso</span>
							</td>';
				$html .='	<td class="project-actions text-right">
									<button onclick="cargar_formulario_params(`roles/visualizarRol`,{claveRol: `'.$row["claveRol"].'`});"
									id="btnVerRol" type="button" class="btn btn-block btn-outline-primary btn-xs btnVerRol">
										<i class="fas fa-pencil-alt">
										</i> Ver Rol
									</button>
								<button id="btnEditarRol" type="button" class="btn btn-block btn-outline-warning btn-xs btnEditarRol">
									<i class="fas fa-pencil-alt">
									</i> Editar Rol
								</button>
								<button id="btnSubirRol" type="button" class="btn btn-block btn-outline-danger btn-xs btnSubirRol" disabled>
									<i class="fas fa-upload">
									</i> Subir Digital
								</button>
							</td>
						';
				$html .='	<td class="project-actions text-right">
								<button id="btnSupervisarRol" type="button" class="btn btn-block btn-outline-warning btn-xs btnSupervisarRol">
									<i class="fas fa-user-check">
									</i> Supervisar
								</button>
								<button id="btnAutorizarRol" type="button" class="btn btn-block btn-outline-warning btn-xs btnAutorizarRol" disabled>
									<i class="fas fa-user-check">
									</i> Autorizar
								</button>
								<button id="btnAnularRol" type="button" class="btn btn-block btn-outline-danger btn-xs btnAnularRol">
									<i class="fas fa-trash">
									</i> Anular
								</button>
							</td>
						</tr>';
			};
			$html .= '</tbody>';
			$html .= '</table>';
		} else { //*se visualiza la tabla en vacio
			$html .= '<tbody>
							<tr>
								<td>
									#
								</td>
								<td>
									<strong>
										-
									</strong>
									<br/>
									<small>
										-
									</small>
								</td>
								<td>
									  <small>
									  <strong> - </strong>
									  <strong> - </strong>
									  </small>
								</td>
								<td class="project_progress">
									-
								</td>
								<td class="project-state">
									<span class="badge badge-danger"></span>
								</td>
								<td class="project-actions text-right">
								</td>';
			$html .= '		</tr>
						</tbody>
					</table>';
		}
		return $html;
	}

	function nuevoRol(){
		$anno 			= $this->input->post("anno");
		$mes			= $this->input->post("mes");
		$departamento	= $this->input->post("departamento");
		$servicio		= $this->input->post("servicio");
		$cargo			= $this->input->post("cargo");
		$contrato		= $this->input->post("contrato");
		$turno			= $this->input->post("turno");
		$empleados_rol	= $this->input->post("empleados_rol");
	//*		Inicializa Rol vacio
		$fecha = "$anno-$mes-01";
		$max = date('t', strtotime( $fecha ) );
		$rol_dias = Array();
		for ($i = 1; $i <= $max; $i++) {
			$rol_dias['d'.$i]=-1;
		}
	//*		./Inicializa Rol vacio
	//*		Recorriendo por cada empleado del Rol
		$data = array(
			'claveRol'			=>	$anno.(str_pad($mes, 2, "0", STR_PAD_LEFT)).
									(str_pad($departamento, 3, "0", STR_PAD_LEFT)).
									(str_pad($servicio, 3, "0", STR_PAD_LEFT)).
									(str_pad($cargo, 3, "0", STR_PAD_LEFT)).
									(str_pad($contrato, 2, "0", STR_PAD_LEFT)),
			'anno'				=>	$anno,
			'mes'				=>	$mes,
			'departamentoId'	=>	$departamento,
			'servicioId'		=>	$servicio,
			'turno'				=>	$turno,
			'tipo_cargo'		=>	$cargo,
			'tipo_contrato'		=>	$contrato,
			'programacion'		=>	json_encode($rol_dias),
			'autor'				=>	$this->session->userdata('dni'),
		);
		$result["res"] = array();
		//* Verificar que no se encuentre en un rol.cargo.contrato del mismo mes y año, o un empleado en otro rol del mes y año
		$problems = false;
		if ($this->m_rol->existeRolxClave($data['claveRol']))
		{
			$result["res"] = array(
				'success' => false,
				'not' => 'Generar Rol',
				'msg' => 'El Rol que se desea generar, ya existe en la lista.<br>Presione Buscar, para listar los roles'
			);
			$problems = true;
		} else {
			$empleado_en_rol = "";
			foreach($empleados_rol as $empleado)
				{	//* verificar de c/u si existe el rol en el año y mes, y que departmaneto y autor lo ha creado
					$analisis = $this->m_rol->existeEmpleadoRolMes($empleado,$anno,$mes);
					if ($analisis->num_rows() > 0) {
						$problems += 1;
						$empleado_en_rol .= '<li><small>Usuario:['.($analisis->result_array()[0]['documentId']).']';
						$empleado_en_rol .= '<br>Departamento:['.($analisis->result_array()[0]['depNombre']).']';
						$empleado_en_rol .= '<br>Servicio:['.($analisis->result_array()[0]['serNombre']).']';
						$empleado_en_rol .= '<br>Creador:['.($analisis->result_array()[0]['nombres']).']';
						$empleado_en_rol .= '<br>Fecha:['.($analisis->result_array()[0]['fechahora_autor']).']</small></li>';
					};
					$result["res"] = array(
						'success' => false,
						'not' => 'Selección de Empleados',
						'msg' => 'Existe duplicidad de Rol de los siguientes empleados: <ul>'.$empleado_en_rol.'</ul>'
					);
				}
		}

		if (!$problems)
		{
			foreach($empleados_rol as $empleado)
				{
					$data['documentId'] = $empleado;
					$data['fechahora_autor'] = date('Y-m-d H:i:s');
					//* agregar con un modelo al empleado en el rol correspondiente
					array_push($result["res"], $this->m_rol->ingresarRol($data));
					$result["res"] = array(
						'success' => true,
						'not' => 'Rol Generado',
						'msg' => 'El rol '.$data['claveRol'].' ha sido generado correctamente!'
					);
				}
		}
	//*		./Recorriendo por cada empleado del Rol
        echo json_encode($result);
    }

	function modificaRol(){
		$anno 			= $this->input->post("anno");
		$mes			= $this->input->post("mes");
		$departamento	= $this->input->post("departamento");
		$servicio		= $this->input->post("servicio");
		$cargo			= $this->input->post("cargo");
		$contrato		= $this->input->post("contrato");
		$empleados_rol	= $this->input->post("empleados_rol");
	//*		Inicializa Rol vacio
		$fecha = "$anno-$mes-01";
		$max = date('t', strtotime( $fecha ) );
		$rol_dias = Array();
		for ($i = 1; $i <= $max; $i++) {
			$rol_dias['d'.$i]=-1;
		}
	//*		./Inicializa Rol vacio
	//*		Recorriendo por cada empleado del Rol
		$data = array(
			'claveRol'			=>	$anno.(str_pad($mes, 2, "0", STR_PAD_LEFT)).
									(str_pad($departamento, 3, "0", STR_PAD_LEFT)).
									(str_pad($servicio, 3, "0", STR_PAD_LEFT)).
									(str_pad($cargo, 3, "0", STR_PAD_LEFT)).
									(str_pad($contrato, 2, "0", STR_PAD_LEFT)),
			'anno'				=>	$anno,
			'mes'				=>	$mes,
			'departamentoId'	=>	$departamento,
			'servicioId'		=>	$servicio,
			'tipo_cargo'		=>	$cargo,
			'tipo_contrato'		=>	$contrato,
			'programacion'		=>	json_encode($rol_dias),
			'autor'				=>	$this->session->userdata('dni'),
		);
		$result["res"] = array();
		//* Borra empleados de la misma claveRol
		$problems = false;
		if ($this->m_rol->eliminaRolxClave($data['claveRol']))
		{
			$problems = false;
		}
		else {
			$result["res"] = array(
				'success' => true,
				'not' => 'Modificar Rol',
				'msg' => 'El Rol que se desea modificar, ha tenido problemas.<br>Presione Buscar, para listar los roles'
			);
			$problems = true;
		}

		if (!$problems)
		{
			foreach($empleados_rol as $empleado)
				{
					$data['documentId'] = $empleado;
					$data['fechahora_autor'] = date('Y-m-d H:i:s');
					//* agregar con un modelo al empleado en el rol correspondiente
					array_push($result["res"], $this->m_rol->ingresarRol($data));
					$result["res"] = array(
						'success' => true,
						'not' => 'Rol Modificado',
						'msg' => 'El rol '.$data['claveRol'].' ha sido modificado correctamente!'
					);
				}
		}
	//*		./Recorriendo por cada empleado del Rol
        echo json_encode($result);
    }

	function miembrosRol()
	{
		$claveRol = $this->input->post("claveRol");
		$miembros = $this->m_rol->miembrosRol($claveRol);
		$resultado = array();
		$result = array();
		foreach ($miembros as $miembro)
		{
			array_push($result, $miembro['documentId']);
		}
		$resultado["res"] = array(
			'success' => true,
			'data' => $result
		);
		echo json_encode($resultado);
	}

	function calculaAvanceRol($claveRol,$jornada=150)
	{
		$miembros = $this->m_rol->miembrosRol($claveRol);
		$rol_detalles = $this->m_rol->detalleRol($claveRol);
		$jornada = $rol_detalles[0]['horas_jornada'];
		$resultado = array();
		$result = array();
		$cantidad_miembros = sizeof($miembros);		
		$horas_total = $jornada*$cantidad_miembros;
		$totales = array();
		foreach ($miembros as $miembro)
		{
			$resultado = json_decode($miembro['programacion'],true);
			foreach ($resultado as $prog)
			{
				array_push($result,$prog);
				if ($prog!="-1")
					{
						if (isset($totales[$prog]))
						{
							$totales[$prog] += 1;
						} else {
							$totales[$prog] = 1;
						};
					}
			}
		}
			$salida="";
			$total_horas_programadas = 0;
			foreach ($totales as $key=>$index)
			{				
				$result = $this->tipoProgramaRol("actual",$key,$claveRol);
				$salida .=  '<font size=1>'.$key . ': '.$totales[$key].'</font><br>';
				$total_horas_programadas = $total_horas_programadas + $result["jornada_laboral"]*$totales[$key];
			}
		$porcentaje = ($total_horas_programadas*100)/$horas_total;
		return (round($porcentaje,2));
	}

	function visualizarRol(){
		$dias = array('LUN','MAR','MIE','JUE','VIE','<span style="color:red;">SAB</span>','<span style="color:red;">DOM</span>');
		$claveRol =$this->input->post("claveRol");
		$this->lista_opciones["horarios_asistencial"] = $this->m_rol->m_obtenerHorarioAsistencial($claveRol);
		$data['claveRol'] = $claveRol;
		$rol_detalles = $this->m_rol->detalleRol($claveRol);
		$jornada = $rol_detalles[0]['horas_jornada'];
		$data['anno'] = substr($claveRol,0,4);
		$fecha_temp = DateTime::createFromFormat('!m', substr($claveRol,4,2));
		$mes_name = ucfirst(strftime("%B", $fecha_temp->getTimestamp()));
		$data['mes'] = $mes_name;
		//* OBTENCION DE DEPARTAMENTO */
			$array_departamento = array();
			foreach ($this->lista_opciones["departamentos"] as $val)
			{
				$array_departamento["{$val['depId']}"] = $val;
			};
			$data['departamento'] = $array_departamento[intval(substr($claveRol,6,3),10)];
		//* ./OBTENCION DE DEPARTAMENTO */
		//* OBTENCION DE SERVICIO */
			$array_servicio = array();
			foreach ($this->lista_opciones["servicios"] as $val)
			{
				$array_servicio["{$val['depId']}"] = $val;
			};
			$data['servicio'] = $array_servicio[intval(substr($claveRol,9,3),10)];
		//* ./OBTENCION DE SERVICIO */
		//* procesando html leyenda de la lista de horarios
			$html_leyenda = "";
			foreach($this->lista_opciones["horarios_asistencial"] as $hor)
			{
				$html_leyenda .="	<button style='width: 30px;border: 0; background-color: ".$hor['color'].";'>
				".$hor['nombre']."
				</button>
				<span style='color: gray; font-size: small;'>
				".$hor['descripcion']." (".$hor['jornada_laboral']." hrs.)
				</span><br>";
			}
			$data['html_leyenda'] = $html_leyenda;
		//* \.procesando html leyenda de la lista de horarios
		//* OBTENCION DE EMPLEADOS DEL ROL ELEGIDO */
			$miembros = $this->m_rol->miembrosRol($claveRol);
		//* ./OBTENCION DE EMPLEADOS DEL ROL ELEGIDO */
		//* ./CONSTRUCCION DEL HTML ROL DETALLE */
			$fecha = "{$data['anno']}-".substr($claveRol,4,2)."-01";
			$max = date('t', strtotime( $fecha ) );
			$html = "";
			$html .= '	<table class="table table-bordered table-hover table-sm" id="tabla_completaE">';
			$html .= '		<thead class="thead-light">';
			$html .= '			<tr>';
			$html .= '				<th style="text-align: center;vertical-align:middle;">
										COLABORADOR
									</th>';
			for ($i = 1; $i <= $max; $i++) {
				$dia_texto = $dias[(date("N", strtotime(substr($claveRol,0,4).'-'.substr($claveRol,4,2).'-'.$i ))) - 1];
				$html .= '			<th align="center"><small><center>'.$dia_texto.'<br>'.$i.'</center></small></th>';
			}
			$html .= '				<th style="text-align: center;vertical-align:middle;">TOTAL
									<br><small>Jornada:</small> '.$jornada.'<small> Horas.</small>
									</th>';
			$html .= '			</tr>';
			$html .= '		</thead>';
			$html .= '	<tbody id="body_roles">';
			foreach ($miembros as $miembro)
			{
			$html .= '		<tr id="'.$miembro['documentId'].'" class="tableRow">
								<td style="text-align: center;vertical-align:middle;width:145px; height: 70px;">
									<small>'	.$miembro['nombre_completo'].'</small><br>'
												.$miembro['documentId'].
								'</td>';
			$prog_json = json_decode($miembro['programacion']);
			$programacion = (array)$prog_json;
				$diaprog = 1;
				$totales = array();
				foreach ($programacion as $prog_dia)
				{
					if ($prog_dia != '-1')
					{	
						if (isset($totales[$prog_dia])) //sumatoria de totales
						{
							$totales[$prog_dia] += 1;
						} else {
							$totales[$prog_dia] = 1;
						};
					};
					$prog_celda = $prog_dia=='-1'?array("nombre"=>"","descripcion"=>"","color"=>""):$this->tipoProgramaRol('actual',$prog_dia,$claveRol);
					// if ($prog_celda['nombre']=='M') {
					// 	$alineacion_vertical = "top";
					// 	$altura_boton  = '23px';
					// } elseif ($prog_celda['nombre']=='T') {
					// 	$alineacion_vertical = "middle";
					// 	$altura_boton  = '23px';
					// } elseif ($prog_celda['nombre']=='N') {
					// 	$alineacion_vertical = "bottom";
					// 	$altura_boton  = '23px';
					// } elseif ($prog_celda['nombre']=='MT') {
					// 	$alineacion_vertical = "top";
					// 	$altura_boton  = '46px';
					// } else {
						$alineacion_vertical = "middle";
						$altura_boton  = '70px';
					// }
					$html .= '			<td style="vertical-align:'.$alineacion_vertical.'">
											<button
												id="'.$miembro['documentId'].'_'.$diaprog.'"
												style	=	"	width:30px;
																height: '.$altura_boton.';
																line-height: 75%;
																background:'.$prog_celda['color'].'
															"
												title = "'.$prog_celda['descripcion'].'"
												type	=	"button"
												onClick="rol_detalle.programar('.$claveRol.',`'.$miembro['documentId'].'`,`'.$miembro['nombre_completo'].'`,'.$diaprog.')"
												class="btn btn-block btn-outline-secondary btn-sm btn-xs">
													<small>
														'.$prog_celda['nombre'].'
													</small>
											</button>
										</td>';
					$diaprog +=1;
				}
				$salida="";
				$total_horas = 0;
				foreach ($totales as $key=>$index)
				{
					if ("nombre_fila"!=$key)
						{
							$result = $this->tipoProgramaRol("actual",$key,$claveRol);
							$salida .=  '<font size=1>'.$key . ': '.$totales[$key].'</font><br>';
							$total_horas = $total_horas + $result["jornada_laboral"]*$totales[$key];
						}
				}
				if ($total_horas < $jornada)
				{	
					$salida .= '<br><strong><font size=2 style="background-color:orange;">TOTAL: '.$total_horas.'H</font></strong>';
				} elseif ($total_horas == $jornada)
					{ 
						$salida .= '<br><strong><font size=2 color=white style="background-color:green;">TOTAL: '.$total_horas.'H</font></strong>';
					} else {
							$salida .= '<br><strong><font size=2 color=white style="background-color:red;">TOTAL: '.$total_horas.'H</font></strong>';
							}
			$html .= '		<td style="line-height: 70%;" id="'.$miembro['documentId'].'_T">'.$salida.'</td>';
			$html .= '		</tr>';
			}
			$html .= '	</tbody>
						</table>';
		//* ./CONSTRUCCION DEL HTML ROL DETALLE */
		$data['html_rol_detalle'] = $html;
		$this->load->view("v_rol_detallado", $data);
	}

	function tipoProgramaRol($fila="siguiente",$programa="",$claveRol="")
	{
		$programaActual = $this->input->post("programaActual");
		$lista_opciones["horarios_asistencial"] = $fila=="siguiente"?$this->m_rol->m_obtenerHorarioAsistencial($this->input->post("claverol")):$this->m_rol->m_obtenerHorarioAsistencial($claveRol);
		$resultado["res"] = array(
			'success' => true,
			'data' => $lista_opciones["horarios_asistencial"]
		);
		//$clave =array_search("'nombre':'M'",$resultado["res"]["data"]);
		//echo "Valor ".$clave;
		$horarios_asistencial = $lista_opciones["horarios_asistencial"];
		$busqueda = "";
		if ($fila=='siguiente')
			{
				foreach ($horarios_asistencial as $i=>$index){
					//echo $i;
					$busqueda = $horarios_asistencial[$i]["nombre"]==$programaActual?$i:$busqueda;
				};
				// echo sizeof($horarios_asistencial);
				if (strlen(trim($programaActual))==0)
				{
					echo json_encode($horarios_asistencial[0]);
				} elseif (sizeof($horarios_asistencial)-1 == $busqueda) {
						$resultado = array(	"nombre"=>"",
											"descripcion"=>"",
											"color"=>""
											);
						echo json_encode($resultado);
					} else {
						echo json_encode($horarios_asistencial[$busqueda+1]);
						//echo $busqueda;
					}
			} else {
				foreach ($horarios_asistencial as $i=>$index){
					//echo $i;
					$busqueda = $horarios_asistencial[$i]["nombre"]==$programa?$i:$busqueda;
				};
				return ($horarios_asistencial[$busqueda]);
				//print_r ($horarios_asistencial[$busqueda]);
				//echo json_encode($horarios_asistencial[intval($busqueda,10)-1]);
				//echo (intval($busqueda,10));
			}
	}

	function calculaFila(){
		$claveRol 		=$this->input->post("claveRol");
		$empleado 		=$this->input->post("empleado");
		$nombres 		=$this->input->post("nombres");
		$arreglo_programa 		=$this->input->post("programacion");
		$rol_detalles = $this->m_rol->detalleRol($claveRol);
		$jornada = $rol_detalles[0]['horas_jornada'];
		//$horarios_asistencial 	= $this->lista_opciones["horarios_asistencial"];
		$totales = array();
		$totales['nombre_fila'] = $arreglo_programa[0];
		for ($i=1;$i<=(sizeof($arreglo_programa)-1);$i++)
			{
				if ($arreglo_programa[$i]!="")
					{
						if (isset($totales[$arreglo_programa[$i]]))
						{
							$totales[$arreglo_programa[$i]] += 1;
						} else {
							$totales[$arreglo_programa[$i]] = 1;
						};
					}
			}
		$salida="";
		$total_horas = 0;
		foreach ($totales as $key=>$index)
		{
			if ("nombre_fila"!=$key)
				{
					$result = $this->tipoProgramaRol("actual",$key,$claveRol);
					$salida .=  '<font size=1>'.$key . ': '.$totales[$key].'</font><br>';
					$total_horas = $total_horas + $result["jornada_laboral"]*$totales[$key];
				}
		}
		if ($total_horas < $jornada)
				{	
					$salida .= '<br><strong><font size=2 style="background-color:orange;">TOTAL: '.$total_horas.'H</font></strong>';
				} elseif ($total_horas == $jornada)
					{ 
						$salida .= '<br><strong><font size=2 color=white style="background-color:green;">TOTAL: '.$total_horas.'H</font></strong>';
					} else {
							$salida .= '<br><strong><font size=2 color=white style="background-color:red;">TOTAL: '.$total_horas.'H</font></strong>';
							}
		//$salida .= '<strong><font size=1>TOTAL: '.$total_horas.'H</font></strong>';
		$resultado["res"] = array(
			'success' => true,
			'data' => $salida
		);
		echo json_encode($resultado);
	}

	function GuardarRoles(){
		$Roles 			=$this->input->post("Rol_General");
		$data = array();
		$resultado['res']=array();
		foreach ($Roles as $rol)
		{
			$claveRol = $rol[0];
			$prog = array();
			for ($i=1;$i<=sizeof($rol[2])-1;$i++)
			{
				$prog['d'.$i]=$rol[2][$i];
			}
			array_push($data,array(
									'documentId'=>$rol[1],
									'programacion'=>json_encode($prog)
						));
		}
		$resultado=$this->m_rol->GuardaRol($claveRol,$data);
		//array_push($resultado['res'],$this->m_rol->GuardaRol($data));
		echo json_encode($resultado);
	}

	function print_rol_pdf( $claveRol ="20230300801605324")
	{
		$dias = array('LUN','MAR','MIE','JUE','VIE','<span style="color:red;">SAB</span>','<span style="color:red;">DOM</span>');
		$data = array();
		setlocale(LC_TIME, "spanish");
		ob_end_clean();
	//! obtencion de año, mes y visualizacion de claveRol
		$data['claveRol'] = $claveRol;
		$rol_detalles = $this->m_rol->detalleRol($claveRol);
		$jornada = $rol_detalles[0]['horas_jornada'];
		$autorizado = $rol_detalles[0]['fechahora_autorizar']==null?false:true;
		$data['anno'] = substr($claveRol,0,4);
		$fecha_temp = DateTime::createFromFormat('!m', substr($claveRol,4,2));
		$mes_name = ucfirst(strftime("%B", $fecha_temp->getTimestamp()));
		$data['mes'] = $mes_name;
	// ./
	//* OBTENCION DE DEPARTAMENTO */
		$array_departamento = array();
		foreach ($this->lista_opciones["departamentos"] as $val)
		{
			$array_departamento["{$val['depId']}"] = $val;
		};
		$data['departamento'] = $array_departamento[intval(substr($claveRol,6,3),10)];
	//* ./OBTENCION DE DEPARTAMENTO */
	//* OBTENCION DE SERVICIO */
		$array_servicio = array();
		foreach ($this->lista_opciones["servicios"] as $val)
		{
			$array_servicio["{$val['depId']}"] = $val;
		};
		$data['servicio'] = $array_servicio[intval(substr($claveRol,9,3),10)];
	//* ./OBTENCION DE SERVICIO */
	//* OBTENCION DE EMPLEADOS DEL ROL ELEGIDO */
		$miembros = $this->m_rol->miembrosRol($claveRol);
	//* ./OBTENCION DE EMPLEADOS DEL ROL ELEGIDO */
	//* procesando html leyenda de la lista de horarios
	$html_leyenda = "";
	$horarios_asistencial = $this->m_rol->m_obtenerHorarioAsistencial($claveRol);
	foreach($horarios_asistencial as $hor)
	{
		$html_leyenda .="	<button style='width: 30px;border: 0; background-color: ".$hor['color'].";'>
		".$hor['nombre']."
		</button>=<span style='color: gray; font-size: small;'>".$hor['descripcion']." (".$hor['jornada_laboral']." hrs.)</span>&nbsp;&nbsp;";
	}
//* \.procesando html leyenda de la lista de horarios
	//* ./CONSTRUCCION DEL HTML ROL DETALLE */
		$fecha = "{$data['anno']}-".substr($claveRol,4,2)."-01";
		$max = date('t', strtotime( $fecha ) );
	//* GENERANDO PDF
		$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetFont('helvetica', 'N', 8);
		$pdf->AddPage('L', 'A4');
		$html = '
				<br>
				<br>
				<h2 style="line-height:1.50%" >Programación de Turnos y Guardias del Servicio Asistencial</h2>
				<h3 style="line-height:50%">[Año: '.$data['anno'].'][Mes: '.$data['mes'].']</h3>
				<h3 style="line-height:50%">[Departamento: '.$data['departamento']['depNombre'].']</h3>
				<h3 style="line-height:50%">[Servicio: '.$data['servicio']['depNombre'].']</h3>
				<hr>
				<br>'.$claveRol.'
				<br>
				<table style="border:red 30px solid;" cellspacing="1" cellpadding="2">
					<thead>
						<tr style="background-color:#e9ecef;">
							<th width="115" align="center">COLABORADOR</th>';
		for ($i=1; $i<=$max; $i++)
		{
			$dia_texto = $dias[(date("N", strtotime(substr($claveRol,0,4).'-'.substr($claveRol,4,2).'-'.$i ))) - 1];
			$html .= '			<th width="19" align="center"><small>'.$dia_texto.'<br>'.$i.'</small></th>';
		}
		$html .= '			<th width="70" align="center"><small>TOTAL<br>Jornada:</small>'.$jornada.'<small>Hr.</small></th>
						</tr>
					</thead>
					<tbody>';
		foreach ($miembros as $miembro)
		{
		$html .= '		<tr border="1">
							<td style="border: 0.5px solid #98a9b1 ;" width="115" align="center">
								<small>'	.$miembro['nombre_completo'].'</small><br>'
											.$miembro['documentId'].
							'</td>';
		$prog_json = json_decode($miembro['programacion']);
		$programacion = (array)$prog_json;
			$diaprog = 1;
			$totales = array();
			foreach ($programacion as $prog_dia)
			{
				if ($prog_dia != '-1')
				{
					if (isset($totales[$prog_dia])) //sumatoria de totales
					{
						$totales[$prog_dia] += 1;
					} else {
						$totales[$prog_dia] = 1;
					};
				};
				$prog_celda = $prog_dia=='-1'?array("nombre"=>"","descripcion"=>"","color"=>""):$this->tipoProgramaRol('actual',$prog_dia,$claveRol);
				$html .= '			<td style="border: 1px solid black; " width="19" valign="middle" align="center" >
										<table>';
				switch ($prog_celda['nombre'])
				{
					case 'M':
					$html .= '			<tr><td style="border: black 1px solid; " width="14" bgcolor="'.$prog_celda['color'].'">
											<font size="7.5">'.$prog_celda['nombre'].'</font>
										</td></tr>
										<tr>
											<td style="border: black 1px solid; " width="14"></td>
										</tr>
										<tr>
											<td style="border: black 1px solid; " width="14"></td>
										</tr>';
					break;
				    case 'T':
					$html .= '			<tr>
											<td style="border: black 1px solid; " width="14"></td>
										</tr>
										<tr><td style="border: black 1px solid; " width="14" bgcolor="'.$prog_celda['color'].'">
										<font size="7.5">'.$prog_celda['nombre'].'</font>
										</td></tr>
										<tr>
											<td style="border: black 1px solid; " width="14"></td>
										</tr>';
					break;
					case 'N':
					$html .= '			<tr>
											<td style="border: black 1px solid; " width="14"></td>
										</tr>
										<tr><td style="border: black 1px solid; " width="14"></td>
										</tr>
										<tr><td style="border: black 1px solid; " width="14" bgcolor="'.$prog_celda['color'].'">
										<font size="7.5">'.$prog_celda['nombre'].'</font>
										</td></tr>';
					break;
					case 'MT':
					$html .= '			<tr><td rowspan="2" style="border: black 1px solid;" width="14" bgcolor="'.$prog_celda['color'].'">
											<font size="6.7">'.$prog_celda['nombre'].'</font>
										</td></tr>
										<tr><td></td></tr>
										<tr><td style="border: black 1px solid; " width="14"></td></tr>
										';
					break;
					case 'GD':
					$html .= '			<tr><td rowspan="2" style="border: black 1px solid; " width="14" bgcolor="'.$prog_celda['color'].'">
											<font size="6.7">'.$prog_celda['nombre'].'</font>
										</td></tr>
										<tr><td></td></tr>
										<tr><td style="border: black 1px solid; " width="14"></td></tr>
										';
					break;
					case 'GN':
					$html .= '			<tr><td style="border: black 1px solid; " width="14"></td></tr>
										<tr><td rowspan="2" style="border: black 1px solid; " width="14" bgcolor="'.$prog_celda['color'].'">
											<font size="6.7">'.$prog_celda['nombre'].'</font>
										</td></tr>
										<tr><td></td></tr>
										';
					break;
					case 'LXO':
					$html .= '			<tr><td style=" " width="14" bgcolor="'.$prog_celda['color'].'"></td></tr>
										<tr><td width="14" bgcolor="'.$prog_celda['color'].'">
											<small>'.$prog_celda['nombre'].'</small>
										</td></tr>
										<tr><td style=" " width="14" bgcolor="'.$prog_celda['color'].'"></td></tr>
										';
					break;
					case 'LXE':
					$html .= '			<tr><td style=" " width="14" bgcolor="'.$prog_celda['color'].'"></td></tr>
										<tr><td width="14" bgcolor="'.$prog_celda['color'].'">
											<small>'.$prog_celda['nombre'].'</small>
										</td></tr>
										<tr><td style=" " width="14" bgcolor="'.$prog_celda['color'].'"></td></tr>
										';
					break;
					case 'VAC':
					$html .= '			<tr><td style=" " width="14" bgcolor="'.$prog_celda['color'].'"></td></tr>
										<tr><td width="14" bgcolor="'.$prog_celda['color'].'">
											<small>'.$prog_celda['nombre'].'</small>
										</td></tr>
										<tr><td style=" " width="14" bgcolor="'.$prog_celda['color'].'"></td></tr>
										';
					break;
					default:
					$html .= '			<tr><td style="border: black 1px solid; " width="14" ></td></tr>
										<tr><td style="border: black 1px solid; " width="14" ></td></tr>
										<tr><td style="border: black 1px solid; " width="14" ></td></tr>
										';
					break;
				}
				$html .= '				</table>
									</td>';
				$diaprog +=1;
			}
			$salida="";
			$total_horas = 0;
			$i=1;
			foreach ($totales as $key=>$index)
			{
				if ("nombre_fila"!=$key)
					{
						$result = $this->tipoProgramaRol("actual",$key,$claveRol);
						$etiqueta = $i%3==0?'<br>':'&nbsp;&nbsp;';
						$salida .=  '<font size="6">'.$key . ': '.$totales[$key].'</font>'.$etiqueta;
						$total_horas = $total_horas + $result["jornada_laboral"]*$totales[$key];
						$i +=1;
					}
			}
			$salida .= ($i-1)%3==0?'':'<br>';
			if ($total_horas < $jornada)
			{
				$salida .= '<strong><font size="8" color="white" bgcolor="orange">TOTAL: '.$total_horas.'H</font></strong>';
			} elseif ($total_horas == $jornada)
				{
					$salida .= '<font size="8" color="white" bgcolor="green">TOTAL: '.$total_horas.'H</font>';
				} else {
						$salida .= '<strong><font size="8" color="white" bgcolor="red">TOTAL: '.$total_horas.'H</font></strong>';
						}
		$html .= '		<td width="70">'.$salida.'</td>';
		$html .= '		</tr>';
		}
		$html .= '	</tbody>
				</table>
				<br>Leyenda: <small>'.$html_leyenda.'</small>';

		$pdf->writeHTML($html, true, false, false, false, '');
		if ($autorizado) {
		} else {
			$img_file = K_PATH_IMAGES.'fondo_borrador.png';
			$pdf->setxy(1, 1);
			$pdf->SetAlpha(0.25);
			$pdf->image($img_file, '', '', 280, 180, '', '', 'T', false, 150, '', false, false, 1, false, true, 72);
			$pdf->SetAlpha(1);
		}
		$nombre_archivo = utf8_decode("rol_".$claveRol.".pdf");
		$pdf->Output($nombre_archivo, 'I');
	}
}
