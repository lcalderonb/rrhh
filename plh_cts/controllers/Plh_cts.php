<?php
class Plh_cts extends MX_Controller
{
	function __construct()
  {
    parent::__construct();
    $this->load->model('M_plh_cts','m_plh_cts');
  }

  function index()
  {
    $rows_html = array();
    $rows_trab = $this->m_plh_cts->listar_empleados(); //-> Listar_trabajadores
    $datos["tabla_html"] = $this->tabla_html_head($rows_html);
    $datos["trabajadores"] = $rows_trab;
    $this->load->view("v_consulta", $datos);
  }

  function get_trabajadores()
  {
    $fecInicio = $this->input->post('txtFecInicio');
    $fecFin = $this->input->post('txtFecFin');

    $fecInicio .= ' 00:00:00.000';
    $fecFin .=  ' 23:59:59.997';

    $rows = $this->m_asistencia->listar_trabajadores( $fecInicio, $fecFin );
    $select ="<option value=''>TODOS</option>";
    foreach($rows as $d){
      $select .="<option value='".$d["dni"]."'>".$d["nombres"]."</option>";
    }
    echo $select;
  }

  function tareo() //* Menú: Marcación UNIFICADA HHUT
  {
    setlocale(LC_TIME, "spanish");
    $meses = array();
    $anios = array();
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

    $datos["meses"] = $meses;
    $datos["anios"] = $anios;

    $rows_html = array();
    $rows_trab = $this->m_asistencia->listar_trabajadores();
		$rows_empl = $this->m_asistencia->listar_empleados(); //-> Listar_empleados
		$departamentos 	= $this->m_asistencia->listarDepartamento();
		$condicion 			= $this->m_asistencia->listarCondicion();
		$regimen 				= $this->m_asistencia->listarRegimen();
		$turnos 				= $this->m_asistencia->listarTurno();
    $datos["trabajadores"] = $rows_trab;
    $datos["empleados"] = $rows_empl;
		$datos['departamentos'] = $departamentos->result_array();
		$datos['condicion'] 		= $condicion->result_array();
		$datos['regimen'] 			= $regimen->result_array();
		$datos['turnos'] 				= $turnos->result_array();
    $datos["tabla_html"] = $this->tareo_html($rows_html,$rows_html, date("Y"), date("n"));
    $this->load->view("v_tareo", $datos);
  }

  function tareo2() //* Menú: Marcación UNIFICADA HHUT
  {
    setlocale(LC_TIME, "spanish");
    $meses = array();
    $anios = array();
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

    $datos["meses"] = $meses;
    $datos["anios"] = $anios;

    $rows_html = array();
    $rows_trab = $this->m_asistencia->listar_trabajadores();
		$rows_empl = $this->m_asistencia->listar_empleados(); //-> Listar_empleados
    $datos["trabajadores"] = $rows_trab;
    $datos["empleados"] = $rows_empl;
    $datos["tabla_html"] = $this->tareo_html($rows_html,$rows_html, date("Y"), date("n"));
    $this->load->view("v_tareo2", $datos);
  }

  function marcacion()
  {
    $this->load->view("v_marcacion");
  }

  function busqueda()
  {
    $dni = trim($this->input->post('dni'));
    if($dni != ''){ $dni=intval($dni); }
    $nombres = ''; //strtoupper($this->input->post('txtNombres'));
    $fecInicio = fncFormatearFecha($this->input->post('txtFecInicio'));
    $fecFin = fncFormatearFecha($this->input->post('txtFecFin'));
    $fecInicio .= ' 00:00:00.000';
    $fecFin .=  ' 23:59:59.997';
    $rows = array();
		$resultado['res_html']=$this->tabla_html_head($rows);
		$resultado['det_html']=$this->tabla_html_detalle(array(
			'nombres'=> 'Perez Perales, Juan',
			'dni'=> '0011223344'
		));
		// $resultado['servidor']=array(
		// 															'nombres'=> 'Perez Perales, Juan',
		// 															'dni'=> '0011223344'
		// 														);
    echo (json_encode($resultado));
    //echo ($this->tabla_html_head($rows));
  }

  function reporte() //* Reporte unificado
  {
    $rows_unificado	= array();
    $idempleado = $this->input->post('idEmpleado');
    if($idempleado != '') {
														 $dni=intval($idempleado);
													}
    $mes = $this->input->post('mes');
    $anio = $this->input->post('anio');
    $fecha = "$anio-$mes-01";
    $fecha_fin = "$anio-$mes";
		$rows_unificado = $this->m_asistencia->listar_asistencia_unificada($fecha, $fecha_fin, $idempleado);
    echo ($this->tareo_html($rows_unificado, $anio, $mes ));
  }

  function reporte2()
  {
    $dni = $this->input->post('dni');
    if($dni != '') 	{$dni=intval($dni);}
    $nombres = ''; //strtoupper($this->input->post('txtNombres'));
    $mes = $this->input->post('mes');
    $anio = $this->input->post('anio');
    $fecha = "$anio-$mes-01"; //-> date("Y-m-d");
    $fecha_fin = "$anio-$mes";
    $rows = $this->m_asistencia->listar_asistencia_reporte2($fecha, $fecha_fin, $nombres, $dni );
    echo ($this->tareo_html2($rows, $anio, $mes ));
  }

  function tabla_html_detalle($rows)
  {
		$html = '
		<div class="input-group mb-1">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-user-tie" title="Nombre de Empleado"></i></span>
				</div>
				<input type="text" id="txtNombreServidor" class="form-control" value="valor 1" placeholder="Empleado" disabled>
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-id-card" title="Documento de Identidad"></i></span>
				</div>
				<input type="text" id="txtdocIdServidor" class="form-control" placeholder="Documento de Identidad" disabled>
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-id-card" title="Documento de Identidad"></i></span>
				</div>
				<input type="text" id="txtdocCnServidor" class="form-control" placeholder="Condición" disabled>
		</div>
		<div class="input-group mb-1">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-id-card" title="Documento de Identidad"></i></span>
				</div>
				<input type="text" id="txtdocCrServidor" class="form-control" placeholder="Cargo y Nivel" disabled>
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-id-card" title="Documento de Identidad"></i></span>
				</div>
				<input type="text" id="txtdocFiServidor" class="form-control" placeholder="Fecha de Ingreso" disabled>
		</div>';
		return $html;
	}
	function tabla_html_head($rows)
  {
      $html = "";
      $html .= '<table class="table table-bordered table-striped table-sm datatable_sm">';
      $html .= '<colgroup>
									<col style="width: 1%"/>
									<col style="width: 3%"/>
									<col style="width: 2%"/>
									<col style="width: 2%"/>
									<col style="width: 30%"/>
									<col style="width: 10%"/>
									<col style="width: 2%"/>
									<col style="width: 2%"/>
									<col style="width: 2%"/>
									<col style="width: 10%"/>
								</colgroup>';
      $html .= '<thead class="thead-light">';
      $html .= '	<tr align="CENTER">';
      $html .= '		<th rowspan="2">ID°</th>';
      $html .= '		<th rowspan="2">AÑO/MES</th>';
      $html .= '		<th class="d-none" rowspan="2">DATA_INGRESO</th>';
      $html .= '		<th colspan="2">TIEMPO DE SERV.</th>';
      $html .= '		<th colspan="2">INGRESOS</th>';
      $html .= '		<th colspan="3">OBSERVACIONES</th>';
      $html .= '		<th rowspan="2">ACCIONES</th>';
      $html .= '	</tr>';
			$html .= '		<tr align="CENTER">';
      $html .= '			<th>M</th>';
      $html .= '			<th>D</th>';
      $html .= '			<th>CONCEPTOS</th>';
      $html .= '			<th>TOTAL</th>';
			$html .= '			<th>VACAC</th>';
      $html .= '			<th>LICENP</th>';
      $html .= '			<th>LICSUB</th>';
      $html .= '		</tr>';
      $html .= '</thead>';
      $html .= '<tbody>';
					$html .= '<tr>';
          $html .= '<td class="text-right">1</td>';
          $html .= '<td class="text-center text-primary">2023-09</td>';
          $html .= '<td class="d-none">TEXTO OCULTO</td>';
          $html .= '<td class="text-center">1</td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td>[<span class="badge bg-warning">VPRI-65</span> -> <span class="font-weight-bold">S/. 2906.15</span>] [<span class="badge bg-warning">VPRI-35</span> -> <span class="font-weight-bold">S/. 1564.85</span>]</td>';
          $html .= '<td class="text-right"><span class="font-weight-bold">S/. 4471.00</span></td></td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td class="text-center">
                                    <button id="btnEditarAM" type="button" class="btn btn-block bg-gradient-warning btn-xs btnEditarAM mt-1 mb-1"><i class="fas fa-edit"></i> Editar1</button>
                                    <button id="btnBorrarAM" type="button" class="btn btn-block bg-gradient-primary btn-xs btnGuardarAM mt-1 mb-1"><i class="fas fa-save"></i> Guardar</button>
                    </td>';
          $html .= '</tr>';
					$html .= '<tr>';
          $html .= '<td class="text-right">2</td>';
          $html .= '<td class="text-center text-primary">2023-08</td>';
          $html .= '<td class="d-none">TEXTO OCULTO</td>';
          $html .= '<td class="text-center">1</td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td>[<span class="badge bg-warning">VPRI-65</span> -> <span class="font-weight-bold">S/. 2906.15</span>] [<span class="badge bg-warning">VPRI-35</span> -> <span class="font-weight-bold">S/. 1564.85</span>]</td>';
          $html .= '<td class="text-right"><span class="font-weight-bold">S/. 4471.00</span></td></td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td class="text-center">4</td>';
          $html .= '<td class="text-center">
                                    <button id="btnEditarAM" type="button" class="btn btn-block bg-gradient-warning btn-xs btnEditarAM mt-1 mb-1"><i class="fas fa-edit"></i> Editar1</button>
                                    <button id="btnBorrarAM" type="button" class="btn btn-block bg-gradient-primary btn-xs btnGuardarAM mt-1 mb-1"><i class="fas fa-save"></i> Guardar</button>
                    </td>';
          $html .= '</tr>';
					$html .= '<tr>';
          $html .= '<td class="text-right">3</td>';
          $html .= '<td class="text-center text-primary">2023-07</td>';
          $html .= '<td class="d-none">TEXTO OCULTO</td>';
          $html .= '<td class="text-center">1</td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td>[<span class="badge bg-warning">VPRI-65</span> -> <span class="font-weight-bold">S/. 2906.15</span>] [<span class="badge bg-warning">VPRI-35</span> -> <span class="font-weight-bold">S/. 1564.85</span>]</td>';
          $html .= '<td class="text-right"><span class="font-weight-bold">S/. 4471.00</span></td></td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td class="text-center">0</td>';
          $html .= '<td class="text-center">15</td>';
          $html .= '<td class="text-center">
                                    <button id="btnEditarAM" type="button" class="btn btn-block bg-gradient-warning btn-xs btnEditarAM mt-1 mb-1"><i class="fas fa-edit"></i> Editar1</button>
                                    <button id="btnBorrarAM" type="button" class="btn btn-block bg-gradient-primary btn-xs btnGuardarAM mt-1 mb-1"><i class="fas fa-save"></i> Guardar</button>
                    </td>';
          $html .= '</tr>';
      if(!empty($rows)){
        $contador = 0;
        foreach ($rows as $row) {
          //habria q consultar a la base de datos mysql...
          //$fecha_cons = date_create( $row['fecons'] );
          //$reg = $this->m_anulafua->m_preanulacion_filtro( $row['cdhis'], $row['codcons'], date_format($fecha_cons, 'Y-m-d') );
          //$html .= '<td>'.date_format($fecha_cons, 'd/m/Y').'</td>';
          $contador++;
          $html .= '<tr>';
          $html .= '<td>'.$row['nombres'].'</td>';
          $html .= '<td class="text-center text-primary">'.$row['dni'].'</td>';
          $html .= '<td class="text-center">'.$row['m'].'</td>';
          //$html .= '<td '.$estilo.' >'.$row['fecha'].'</td>';
          $html .= '<td>'.$row['sDepartment'].'</td>';
          $html .= '<td>'.$row['estado'].'</td>';
          //$html .= '<td>'.date_format($fecha_cons, 'd/m/Y').'</td>';
          $html .= '</tr>';
        }
      }
      $html .= '</tbody>';
      $html .= '</table>';
      return $html;
  }

  function verifica_incidencia($row,$anio,$mes)
  {
    //* Inicialización de Incidencia[] a Código(-1)
    $Incidencia=array(
      "d1" => -1,"d2" => -1,"d3" => -1,"d4" => -1,"d5" => -1,"d6" => -1,"d7" => -1,
      "d8" => -1,"d9" => -1,"d10" => -1,"d11" => -1,"d12" => -1,"d13" => -1,"d14" => -1,
      "d15" => -1,"d16" => -1,"d17" => -1,"d18" => -1,"d19" => -1,"d20" => -1,"d21" => -1,
      "d22" => -1,"d23" => -1,"d24" => -1,"d25" => -1,"d26" => -1,"d27" => -1,"d28" => -1,
      "d29" => -1,"d30" => -1,"d31" => -1,
      "tardanzas"=>0,
      "inasistencia"=>0
    );

    //* Verificar que turno tiene asignado MANUAL(PROGRAMADO) ó AUTOMATICO(rutinario todo el año) $row['turno_id']
    $turno_empleado = $this->m_asistencia->listarTurnoxId($row['turno_id']);
    $automatica = intval($turno_empleado[0]['Programacion']);

    //* Obtener el número de días a analizar, de acuerdo al mes y año, si es del presente calcular la diferencia
    $fecha = "$anio-$mes-01";
    if ($anio==date("Y") && $mes==date("n") ){ // el presente
      $fech_ini = new DateTime("$anio-$mes-01");
      $fech_fin = new DateTime();
      $diff = $fech_ini->diff($fech_fin);
      $max = $diff->days+1;
    } else { //cualquier otro mes hacia atras
      $max = date('t', strtotime( $fecha ) );
    } //* Se obtiene la variable $max, que determina los días a analizar del mes y año seleccionado

    //! ----VERIFICAR_ASISTENCIA---- CODIGO 00 00 00 01
      if ($automatica) { //* analisis automatico, segun turno
        //* desde dia 1 hasta el día $max, verificar asistencias
        $semana = $turno_empleado[0]['Semana'];
        $empieza_entrada		= date('H:i:s',strtotime($turno_empleado[0]['empieza_entrada']));
        $termina_entrada		= date('H:i:s',strtotime($turno_empleado[0]['termina_entrada']));
        $hora_entrada				= date('H:i:s',strtotime($turno_empleado[0]['hora_entrada']));
        $hora_salida				= date('H:i:s',strtotime($turno_empleado[0]['hora_salida']));
        $tolerancia					= '+'.$turno_empleado[0]['entrada_tolerancia'].' minutes';
        $entrada_tolerancia	= date('H:i:s',strtotime($tolerancia,strtotime($hora_entrada)));

        $toleranciaT1					= '+'.$turno_empleado[0]['tardanza1'].' minutes';
        $toleranciaT2					= '+'.$turno_empleado[0]['tardanza2'].' minutes';
        $toleranciaT3					= '+'.$turno_empleado[0]['tardanza3'].' minutes';
        $entrada_tardanza1	= date('H:i:s',strtotime($toleranciaT1,strtotime($entrada_tolerancia)));
        $entrada_tardanza2	= date('H:i:s',strtotime($toleranciaT2,strtotime($entrada_tardanza1)));
        $entrada_tardanza3	= date('H:i:s',strtotime($toleranciaT3,strtotime($entrada_tardanza2)));
        $Incidencia['empieza_entrada']=$empieza_entrada;
        $Incidencia['termina_entrada']=$termina_entrada;
        $Incidencia['entrada_tolerancia']=$entrada_tolerancia;
        $Incidencia['entrada_tardanza1']=$entrada_tardanza1;
        $Incidencia['entrada_tardanza2']=$entrada_tardanza2;
        $Incidencia['entrada_tardanza3']=$entrada_tardanza3;
        $Incidencia['max']=$max;
        $acumulado_tardanza = 0;
        $acumulado_inasistencia = 0;
        for ( $i = 1; $i<=$max; $i++ )
        {
          //* verificar que día de la semana es y comparar con turno.semana
          $bs=false;
          if (intval($semana[date('N', strtotime( $anio.'-'.$mes.'-'.$i))-1]))
          { //* Corresponde analizar asistencia, de accuerdo al día i
            //* No marcó entrada ni salida. No vino a trabajar Código 1000001(65)
            if (($row['d'.$i]==null) && ($row['ds'.$i]==null) ) {
              $Incidencia['d'.$i]=65;
              $acumulado_inasistencia +=1;
            } else
            { //*EMPIEZA ANALISIS DE LA ASISTENCIA
              //*Si esta vacia, no marco entrada
              $hora_marcado_entrada = date('H:i:s',strtotime($row['d'.$i]));
              if ($hora_marcado_entrada < $hora_salida)
              { //existe entrada
                //! Entrada
                if (($empieza_entrada<=$hora_marcado_entrada)&&($hora_marcado_entrada<=$termina_entrada))
                {
                  if ($hora_marcado_entrada<=$hora_entrada)		//marcado normal
                  {
                    //*llego temprano. Incidencia 0
                    $Incidencia['d'.$i] = 0;
                  } elseif ($hora_marcado_entrada<=$entrada_tolerancia) // marcado con tolerancia
                  {
                    //*Llego temprano con Tolerancia Aceptable. Incidencia 0
                    $Incidencia['d'.$i] = 0;
                  } elseif ($hora_marcado_entrada<=$entrada_tardanza1) //marcado con Grado de Inpuntualidad LEVE
                  {
                    //*Llegó Tarde, acumula 1 tardanza  Codigo 0011(3)
                    $Incidencia['d'.$i]=3;
                    $acumulado_tardanza +=1;
                  } elseif ($hora_marcado_entrada<=$entrada_tardanza2) //marcado con Grado de Inpuntualidad REGULAR
                  {
                    //*Llegó Tarde, acumula 2 tardanzas Codigo 0101(5)
                    $Incidencia['d'.$i]=5;
                    $acumulado_tardanza +=2;
                  } elseif ($hora_marcado_entrada<=$entrada_tardanza3) //marcado con Grado de Inpuntualidad GRAVE
                  {
                    //*Llegó Tarde, acumula 3 tardanzas Codigo 1001(9)
                    $Incidencia['d'.$i]=9;
                    $acumulado_tardanza +=3;
                  }
                } else {
                  //*Marcó entrada fuera del rango de marcado de entrada. Inasistencia
                  //*Es equivalente a descuento de 1 día Codigo 10001(17)
                  $Incidencia['d'.$i]=17;
                  $acumulado_inasistencia +=1;
                }
              } elseif ($row['ds'.$i] == '')
              {
                //*no marco entrada, NME 100001(33)
                $Incidencia['d'.$i]=33;
                $bs=true;
                $acumulado_inasistencia +=1;
              } else
              { //No marcó entrada, y existe $hora de marcado para analizar
                $Incidencia['d'.$i]=33;
                $acumulado_inasistencia +=1;
              }
              //* Salida
              //*Si esta vacia, no marco salida
              if ($bs==false){
                $hora_marcado_salida = date('H:i:s',strtotime($row['ds'.$i]));
                if ($hora_marcado_salida<$hora_salida)
                    { //* No marco salida. Codigo = 384
                      $Incidencia['d'.$i]+=384;
                    } else {
                      //no marco salida
                      $Incidencia['d'.$i]+= 0;
                    }
                }
            }
          }
        }
        $Incidencia['tardanzas']=$acumulado_tardanza;
        $Incidencia['inasistencia']=$acumulado_inasistencia;
      } else
      {
        //!analisis manual por GUARDIAS
        $Incidencia['tardanzas']='Módulo aun en desarrollo';
        $Incidencia['inasistencia']='Módulo aun en desarrollo';
      }
    //! --./VERIFICAR_ASISTENCIA---- CODIGO 00 00 00 01

    return $Incidencia;
  }

  function tareo_html($rows, $anio, $mes )
  {
    $html = "";
    if(!empty($rows)){
      $fecha = "$anio-$mes-01";
      $max = date('t', strtotime( $fecha ) );
      $fecha = new DateTime( $fecha );
      $dias = array('LUN','MAR','MIE','JUE','VIE','<span style="color:red;">SAB</span>','<span style="color:red;">DOM</span>');
      $html .= '<table>';
      $html .= '	<tr>';
      $html .= '		<td width="20%">';
			$html .= '			<table class="table table-bordered table-striped table-sm">';
      $html .= '				<thead>';
      $html .= '					<tr>';
			$html .= '						<th width="220">NOMBRES</th>';
      $html .= '						<th>DNI</th>';
      $html .= '					</tr>';
      $html .= '				</thead>';
      $html .= '				<tbody>';
			foreach ($rows as $row) {
					if ($row['turno_id']!=''){
						//! inicializa el proceso de análisis del empleado.
						$tagConcedeAnalisis=true;
            $Incidencia = $this->verifica_incidencia($row,$anio,$mes);
						//! se precede a el analisis de row, para emitir un resultado validado en un codigo decimal, que en
						//! realidad es un binario, que representa estados distintos de Estado de asistencia
						//! mientras sea cero la cabeera será verde.

						//! Se creará un array Incidencia['dxx'] -1(ignorar),0(sin incidencias),...binarios(incidencia reportada)
					} else {
						$tagConcedeAnalisis=false;
						print("	<script>
											$(document).Toasts('create', {
												class: 'bg-danger',
												title: 'Alerta',
												subtitle: 'Asignación de Turno',
												body: 'El Empleado ".$row['nombres']." no tiene asigando un TURNO. No se podrá analizar su asistencia.'
											})
										</script>"
									);
					}
			$html .= '					<td width="180">';
      $html .= '							<button type="button" onClick="tareo.empleadoUp(`'.$row['dni'].'`)" class="btn btn-block btn-outline-info btn-flat">'.$row['nombres'].'</button>';
			$html .= '					</td>';
			$html .= '					<td class="text-center text-bold" data-toggle="tooltip" data-title="'.$row['nombres'].'">'.$row['dni'].'</td>';
			}
      $html .= '				</tbody>';
			$html .= '			</table>';
			$html .= '		</td>';
      $html .= '		<td width="80%">';
			$si = (date('N', strtotime( $anio.'-'.$mes.'-1')))-1;
			for ($sem = 0; $sem <= (int)($max/7)+1; $sem++)
			{
				$html .= '			<table class="table table-bordered table-striped table-sm">';
				$html .= '				<thead>';
				$html .= '					<tr>';
				for($i=1+($sem*7)-$si; $i<=7+($sem*7)-$si; $i++ )
					{
						if (($i>0) && ($i<=$max)) {
							if ($tagConcedeAnalisis) {
							//! boton cabecera .-se implenta aqui la visualizacion de alerta en la cabecera de la asistencia
              //! de acuerdo a la cabecera
              switch ($Incidencia['d'.$i]) {
                case -1 :
                  $boton_alerta='btn-outline-info';
                  break;
                case 0 :
                  $boton_alerta='btn-outline-success';
                  break;
                default :
                  $boton_alerta='btn-outline-danger';
              }
							//! boton_cabecera
							} else {$boton_alerta='btn-outline-info';};
						$html .= '<th width="80"><button type="button" onClick="tareo.info('.$anio.','.$mes.','.$i.',`'.$row['dni'].'`)" class="btn btn-block '.$boton_alerta.' btn-flat"><center>'.$i.'/'.$mes.'/'.$anio.'<br>'.$dias[(date('N', strtotime( $anio.'-'.$mes.'-'.$i ))) - 1].'</center></button></th>';
					} else {
						$html .= '<th width="80"></th>';
						}
					}
				$html .= '					</tr>';
				$html .= '				</thead>';
				$html .= '				<tbody>';
				$html .= '				<tr>';
				foreach ($rows as $row) {
					for($i=1+($sem*7)-$si; $i<=7+($sem*7)-$si; $i++ )
					{
						if (($i>0) && ($i<=$max)) {
							$html .= '<td class="text-center" data-toggle="tooltip"><span style="color:blue;">';
							$html .= (!($row['d'.$i]==null))?'<i class="fas fa-sign-in-alt" style="color:'.(($row['dr'.$i]=='biostar')?'green':'red').';" title="'.$row['dr'.$i].'"></i> '.substr($row['d'.$i], 10, 18).'</span>':'';
							$html .= (!($row['ds'.$i]==null))?'<br><i class="fas fa-sign-out-alt" style="color:'.(($row['dsr'.$i]=='biostar')?'green':'red').';" title="'.$row['dsr'.$i].'"></i> '.substr($row['ds'.$i], 10, 18).'</td>':'';
						} else {
							$html .= '<td width="80"></td>';
						}
					}
				}
				$html .= '				</tr>';
				$html .= '				<tr>';
				$html .= '				</tbody>';
				$html .= '			</table>';
			}
      $html .= '		</td>';
      $html .= '	</tr>';
      $html .= '</table>';
			$html .= '<br>';
      if ($tagConcedeAnalisis){
				$html .= 'Tardanzas: '.$Incidencia['tardanzas'];
				$html .= '<br>';
				$html .= 'Inasistencias: '.$Incidencia['inasistencia'];}
      //$html .= print_r($rows);
    }else{
      $html .= '<table class="table table-bordered table-striped table-sm datatable_sm">';
      $html .= '<thead class="thead-light">';
      $html .= '<tr>';
      $html .= '<th width="120">Nombres</th>';
      $html .= '<th>DNI</th>';
      $html .= '<th>Mes Completo</th>';
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody>';
      $html .= '</tbody>';
      $html .= '</table>';
    }
    return $html;
  }

  function tareo_html2($rows, $anio, $mes )
  {
    $html = "";
    if(!empty($rows)){
      $fecha = "$anio-$mes-01";
      $max = date('t', strtotime( $fecha ) );
      $fecha = new DateTime( $fecha );
      $dias = array('LUN','MAR','MIE','JUE','VIE','<span style="color:blue;">SAB</span>','<span style="color:blue;">DOM</span>');
      $html .= '<table class="table table-bordered table-striped table-sm datatable_sm">';
      $html .= '<thead class="thead-light">';
      $html .= '<tr>';
      $html .= '<th width="150">Nombres</th>';
      $html .= '<th>DNI</th>';
      for($i=1; $i<=$max; $i++ ){ $html .= '<th>'.$i.'/'.$mes.'/'.$anio.'<br>'.$dias[(date('N', strtotime( $anio.'-'.$mes.'-'.$i ))) - 1].'</th>'; }
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody>';
      $contador = 0;
      foreach ($rows as $row) {
        $contador++;
        $html .= '<tr>';
        $html .= '<td>'.$row['nombres'].'</td>';
        $html .= '<td class="text-center text-bold" data-toggle="tooltip" data-title="'.$row['nombres'].'">'.$row['dni'].'</td>';
        for($i=1; $i<=$max; $i++ ){
          $html .= '<td class="text-center" data-toggle="tooltip" data-title="'.$row['nombres'].'"><span style="color:blue;">'.substr($row['d'.$i], 10, 18).'</span><br>'.substr($row['ds'.$i], 10, 18).'</td>';
        }
        $html .= '</tr>';
      }
      $html .= '</tbody>';
      $html .= '</table>';
    }else{
      $html .= '<table class="table table-bordered table-striped table-sm datatable_sm">';
      $html .= '<thead class="thead-light">';
      $html .= '<tr>';
      $html .= '<th width="120">Nombres</th>';
      $html .= '<th>DNI</th>';
      $html .= '<th>Mes Completo</th>';
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody>';
      $html .= '</tbody>';
      $html .= '</table>';
    }
    return $html;
  }

  function calendario( )
  {
    //leer
    //$events = $this->m_teleconsulta->m_teleconsultas_programadas();
    //if (empty ($events) ) $events = array();
    $events = array(
      array(
        'title'           => 'Meeting',
        'start'           => date('Y-m-d'),
        'allDay'          => false,
        'backgroundColor' => '#0073b7',
        'borderColor'     => '#0073b7'
      ),
      array(
        'title'           => 'Reunion',
        'start'           => date('Y-m-d'),
        'allDay'          => false,
        'backgroundColor' => '#0073b7',
        'borderColor'     => '#0073b7'
      )
    );
    $datos["events"] = json_encode($events);
    $this->load->view("v_calendario", $datos);
  }

  function pre_anulacion()
  {
    $hc = $this->input->post('hc');
    $cod_cons = $this->input->post('codcons');
    $fecha_cons = $this->input->post('fecha');
    $ciudadano = $this->input->post('ciudadano');
    $fecha_cons = strtotime(str_replace('/', '-', $fecha_cons) );
    $data = array(
      'cod_historia'=> $hc,
      'consultorio' => $cod_cons,
      'fec_atencion'=> date('Y-m-d',$fecha_cons),
      'ciudadano'   => $ciudadano,
      'estado'      => 'ANULADO',
      //'estado' => 'PRE ANULACION' esto era lo anterior....,
      'responsable' => $this->session->userdata('responsable'),
      'usu_registro'=> $this->session->userdata('usuario'),
      'fec_registro'=> date("Y-m-d H:i:s"),
      'activo' =>'A'
    );
    $rows = $this->m_anulafua->ins_reg_anulacion($data);
    //anulacion en fox
    /*$hc = $this->input->post('hc');
    $cod_cons = $this->input->post('codcons');
    $fecha_cons = $this->input->post('fecha');
    */
    $rows = $this->m_anulafua->m_anular($hc, $cod_cons, $fecha_cons);
    echo json_encode($rows);
  }
  //_Vista de pre anulados
  function view_ver()
  {
      $fecha_pre_anu = date("d/m/Y");
      $fecha_pre_anu = strtotime( str_replace('/', '-', $fecha_pre_anu ) );
      $fecha_pre_anu = date('Y-m-d', $fecha_pre_anu);
      //$rows = $this->m_anulafua->m_pre_anulacion_ver( $fecha_pre_anu );
      //$datos["tabla_html"] = $this->tabla_html_ver_preanulacion($rows);
      $rows = array();
      $datos["tabla_html"] = $this->tabla_html_ver_preanulacion($rows);
      $this->load->view( "v_verpreanula", $datos );
  }

  function tabla_html_ver_preanulacion($rows)
  {
    $html = "";
    $html .= '<table id="example1" class="table table-bordered table-striped" style="font-size:13px;">';
    $html .= '<thead>';
    $html .= '<tr bgcolor="#EEEEFD">';
    $html .= '<th>N°--</th>';
    $html .= '<th>Cod.Historia</th>';
    $html .= '<th>Paciente</th>';
    $html .= '<th>Fec.Consulta</th>';
    $html .= '<th>Consultorio</th>';
    $html .= '<th>Estado</th>';
    $html .= '<th>Fec.Anulación</th>';
    $html .= '<th>Usuario</th>';
    $html .= '<th>Opción</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    if(!empty($rows)){
      $contador = 0;
      foreach ($rows as $row) {
        $contador++;
        $fecha_atencion = date_create($row['fec_atencion']);
        $fecha_pre_registro = date_create($row['fec_registro']);
        $html .= '<tr>';
        $html .= '<td align="center">'.substr("00".$contador,-2).' </td>';
        $html .= '<td style="color:blue;">'.$row['cod_historia'].'</td>';
        $html .= '<td>'.$row['ciudadano'].'</td>';
        $html .= '<td>'.date_format($fecha_atencion, 'd/m/Y').'</td>';
        $html .= '<td style="color:blue;">'.$row['consultorio'].'</td>';
        $html .= '<td style="color:orange;">'.$row['estado'].'</td>';
        $html .= '<td>'.date_format( $fecha_pre_registro, 'd/m/Y H:i:s' ).'</td>';
        //$html .= '<td>'.$row['usu_registro'].'</td>';
        $html .= '<td>'.$row['nom_usuario'].'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
      }
    }
    $html .= '</tbody>';
    $html .= '</table>';
    return $html;
  }

/*---------------------borrar-------------------*/
  //_Vista de autorizacion
  function view_autorizar()
  {
    $fecha_pre_anu = $this->input->post("fecha_pre_anulacion");
    if( ! isset($fecha_pre_anu) ) $fecha_pre_anu = date("Y-m-d");
    $rows = array();
    $rows = $this->m_anulafua->m_pre_anulacion( $fecha_pre_anu );
    $datos["tabla_html"] = $this->tabla_html_autorizacion($rows);
    $this->load->view("v_autoanula", $datos);
  }
  //_Vista de anulados
  function view_veranula()
  {
    $fecha_pre_anu = date("Y-m-d");
    $rows = $this->m_anulafua->m_ver_anulados( $fecha_pre_anu );
    $datos["tabla_html"] = $this->tabla_html_autorizacion($rows);
    $this->load->view( "v_veranula", $datos );
  }
  //_Lista de preanulados
  function pre_anulacion_ver()
  {
      $fecha_pre_anu = $this->input->post( 'fecha_pre_anulacion' );
      $fecha_pre_anu = strtotime( str_replace('/', '-', $fecha_pre_anu ));
      $fecha_pre_anu = date('Y-m-d',$fecha_pre_anu);
      $rows = $this->m_anulafua->m_pre_anulacion_ver( $fecha_pre_anu );
      echo ( $this->tabla_html_ver_preanulacion($rows) );
  }

  function ver_pre_anulados()
  {
    $fecha_pre_anu = $this->input->post( 'fecha_pre_anulacion' );
    $fecha_pre_anu = strtotime( str_replace('/', '-', $fecha_pre_anu ));
    $fecha_pre_anu = date('Y-m-d',$fecha_pre_anu);
    $rows = $this->m_anulafua->m_pre_anulacion( $fecha_pre_anu );
    echo ( $this->tabla_html_autorizacion($rows) );
  }

  function anular_todos()
  {
    $fecha_pre_anulacion = $this->input->post( 'fecha_pre_anulacion' );
    $rows = $this->m_anulafua->m_anular_todos( $fecha_pre_anulacion );
    echo json_encode( $rows );
  }

  function ver_anulados()
  {
    $fecha_pre_anulacion = $this->input->post( 'fecha_pre_anulacion' );
    $rows = $this->m_anulafua->m_ver_anulados( $fecha_pre_anulacion );
    echo ( $this->tabla_html_autorizacion($rows) );
  }

  function anular()
  {
    $hc = $this->input->post('hc');
    $cod_cons = $this->input->post('codcons');
    $fecha_cons = $this->input->post('fecha');
    $rows = $this->m_anulafua->m_anular($hc, $cod_cons, $fecha_cons);
    echo json_encode($rows);
  }

  function importar_maestro()
  {
    $this->m_consulta->importar_maestro();
  }

  function tabla_html_autorizacion($rows)
  {
    $html = "";
    $html .= '<table id="example1" class="table table-bordered table-striped table-sm">';
    $html .= '<thead>';
    $html .= '<tr bgcolor="#EEEEFD">';
    $html .= '<th>N°</th>';
    $html .= '<th>Cod.Historia</th>';
    $html .= '<th>Paciente</th>';
    $html .= '<th>Fec.Consulta</th>';
    $html .= '<th>Consultorio</th>';
    $html .= '<th>Estado</th>';
    $html .= '<th>Fec.Anulación</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    if(!empty($rows)){
      $contador = 0;
      foreach ($rows as $row) {
        $contador++;
        $fecha_atencion = date_create($row['fec_atencion']);
        $fecha_autorizacion = date_create($row['fec_autorizacion']);
        $html .= '<tr>';
        $html .= '<td align="center">'.substr("00".$contador,-2).' </td>';
        $html .= '<td><b>'.$row['cod_historia'].'</b></td>';
        $html .= '<td>'.$row['ciudadano'].'</td>';
        $html .= '<td>'.date_format($fecha_atencion, 'd/m/Y').'</td>';
        $html .= '<td>'.$row['consultorio'].'</td>';
        $html .= '<td style="color:blue;">'.$row['estado'].'</td>';
        $html .= '<td>'.date_format( $fecha_autorizacion, 'd/m/Y' ).'</td>';
        $html .= '</tr>';
      }
    }
    $html .= '</tbody>';
    $html .= '</table>';
    return $html;
  }
  //_Listar en pdf los registros pre anulados
  function preanulados_pdf()
  {
    $fecha_pre_anu = $this->input->get( 'fecha_pre_anulacion' );
    $fecha_pre_anu_totime = strtotime( str_replace('/', '-', $fecha_pre_anu ));
    $fecha_pre_anu = date('Y-m-d',$fecha_pre_anu_totime);
    $rows = $this->m_anulafua->m_pre_anulacion_ver( $fecha_pre_anu );

    ob_end_clean();
    $this->load->library('Pdf');
    $pdf = new ListaAnuladosPdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('HHU TACNA');
    $pdf->SetTitle('');

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(13, 15, 13);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER); //PDF_MARGIN_HEADER
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetHeaderData('', 5, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,0,0), array(0,0,0));
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
    $pdf->setFontSubsetting(true);
    $pdf->SetFont('helvetica', '', 8, '', true);
    //$pdf->SetFont('courier', '', 10, '', true);
    $pdf->AddPage('P', 'mm', 'A4');

    $html  = '';
    $html .= '<span align="center" style="font-size:18px;"><b>REGISTROS ANULADOS</b></span><br/>';
    $html .= '<br/>';
    $html .= '<span align="center" style="font-size:15px;"><b>'.date('d/m/Y',$fecha_pre_anu_totime).'</b></span><br/>';
    $html .= '<br/><br/>';
    $html .= '<table width="650" border="1" cellspacing="0" cellpadding="4" >';
    $html .= '<thead>';
    $html .= '<tr align = "center" style="font-weight: bold;">';
    $html .= '<th width="30">N°</th>';
    $html .= '<th width="60">Historia</th>';
    $html .= '<th width="230">Paciente</th>';
    $html .= '<th width="80">Fec.Consulta</th>';
    $html .= '<th >Consultorio</th>';
    $html .= '<th>Estado</th>';
    $html .= '<th width="80">Fec.Pre-Anulación</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    $contador = 0;
    foreach( $rows as $row){
      $contador++;
      $fecha_atencion = date_create($row['fec_atencion']);
      $fecha_pre_registro = date_create($row['fec_registro']);
      $html .= '<tr>';
      $html .= '<td width="30" align="center" >'.$contador.'</td>';
      $html .= '<td width="60" align="center" >'.$row['cod_historia'].'</td>';
      $html .= '<td width="230" >'.$row['ciudadano'].'</td>';
      $html .= '<td width="80" >'.date_format($fecha_atencion, 'd/m/Y').'</td>';
      $html .= '<td >'.$row['consultorio'].'</td>';
      $html .= '<td >'.$row['estado'].'</td>';
      $html .= '<td width="80" align="right">'.date_format( $fecha_pre_registro, 'd/m/Y' ).'</td>';
      $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $pdf->writeHTMLcell($w=0,$h=0,$x='',$y='',$html,$border=0,$ln=1,$fill=0,$reseth=true,$align='',$autopadding=true);
    $nombre_archivo = utf8_decode("lista_preanulados.pdf");
    $pdf->Output($nombre_archivo, 'I');
  }

  //_Listar en pdf los registros anulados
  function anulados_pdf()
  {
    $fecha_pre_anu = $this->input->get( 'fecha_pre_anulacion' );
    $fecha_pre_anu_totime = strtotime( str_replace('/', '-', $fecha_pre_anu ));
    $fecha_pre_anu = date('Y-m-d',$fecha_pre_anu_totime);
    $rows = $this->m_anulafua->m_ver_anulados( $fecha_pre_anu );

    ob_end_clean();
    $this->load->library('Pdf');
    $pdf = new ListaAnuladosPdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('HHU TACNA');
    $pdf->SetTitle('');

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(13, 15, 13);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER); //PDF_MARGIN_HEADER
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetHeaderData('', 5, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,0,0), array(0,0,0));
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
    $pdf->setFontSubsetting(true);
    $pdf->SetFont('helvetica', '', 8, '', true);
    //$pdf->SetFont('courier', '', 10, '', true);
    $pdf->AddPage('P', 'mm', 'A4');

    $html  = '';
    $html .= '<span align="center" style="font-size:18px;"><b>REGISTROS ANULADOS</b></span><br/>';
    $html .= '<br/>';
    $html .= '<span align="center" style="font-size:15px;"><b>'.date('d/m/Y',$fecha_pre_anu_totime).'</b></span><br/>';

    $html .= '<br/><br/>';
    //$html .= '<span align="right"><b>H.CL: </b>'.$reg['cdhis'].'</span><br/>';
    $html .= '<table width="650" border="1" cellspacing="0" cellpadding="4" >';
    $html .= '<thead>';
    $html .= '<tr align = "center" style="font-weight: bold;">';
    $html .= '<th width="30">N°</th>';
    $html .= '<th width="60">Historia</th>';
    $html .= '<th width="230">Paciente</th>';
    $html .= '<th width="80">Fec.Consulta</th>';
    $html .= '<th >Consultorio</th>';
    $html .= '<th>Estado</th>';
    $html .= '<th width="80">Fec.Anulación</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    $contador = 0;
    foreach( $rows as $row){
      $contador++;
      $fecha_atencion = date_create($row['fec_atencion']);
      $fecha_pre_registro = date_create($row['fec_autorizacion']);
      $html .= '<tr>';
      $html .= '<td width="30" align="center" >'.$contador.'</td>';
      $html .= '<td width="60" align="center" >'.$row['cod_historia'].'</td>';
      $html .= '<td width="230" >'.$row['ciudadano'].'</td>';
      $html .= '<td width="80" >'.date_format($fecha_atencion, 'd/m/Y').'</td>';
      $html .= '<td >'.$row['consultorio'].'</td>';
      $html .= '<td >'.$row['estado'].'</td>';
      $html .= '<td width="80" align="right">'.date_format( $fecha_pre_registro, 'd/m/Y' ).'</td>';
      $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $pdf->writeHTMLcell($w=0,$h=0,$x='',$y='',$html,$border=0,$ln=1,$fill=0,$reseth=true,$align='',$autopadding=true);
    $nombre_archivo = utf8_decode("lista_anulados.pdf");
    $pdf->Output($nombre_archivo, 'I');
  }

  function tabla_html($rows)
  {
    $html = "";
    $html .= '<thead>';
    $html .= '<tr bgcolor="#EEEEFD">';
    $html .= '<th>Nro.</th>';
    $html .= '<th>Cod.Historia</th>';
    $html .= '<th>Nombres</th>';
    $html .= '<th>Apellidos</th>';
    $html .= '<th>DNI</th>';
    $html .= '<th>Direccion</th>';
    $html .= '<th>OPC</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    if(!empty($rows)){
      $contador = 0;
      foreach ($rows as $row) {
        $contador++;
        $html .= '<tr>';
        $html .= '<td align="center">'.substr("0000000".$contador,-7).' </td>';
        $html .= '<td>'.$row['cdhis'].'</td>';
        $html .= '<td>'.$row['apenom'].'</td>';
        $html .= '<td>'.$row['nom_pat'].'</td>';
        $html .= '<td>'.$row['cdle'].'</td>';
        $html .= '<td>'.$row['dire'].'</td>';
        $html .= '<td><a href="#" onclick="EditDeclaratoria('.$row["cdle"].')" ><i class="fa fa-eye"></i> Entrar</a> </td>';
        $html .= '</tr>';
      }
    }
    $html .= '</tbody>';
    return $html;
  }


}
?>
