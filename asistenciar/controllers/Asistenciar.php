<?php
class Asistenciar extends MX_Controller
{
	function __construct()
  {
    parent::__construct();
    $this->load->model('M_asistenciar','m_asistenciar');
  }

  function index()
  {
    $rows_html = array();
    $rows_trab = $this->m_asistenciar->listar_trabajadores(); //-> Listar_trabajadores
    $datos["tabla_html"] = $this->tabla_html_head($rows_html);
    $ultima_actualizacion = $this->m_asistenciar->last_update();
    $datos["actualizacion"] = $ultima_actualizacion[0]['fecha'];
    $datos["trabajadores"] = $rows_trab;
    $this->load->view("v_consultar", $datos);
  }

  function get_trabajadores()
  {
    $fecInicio = $this->input->post('txtFecInicio');
    $fecFin = $this->input->post('txtFecFin');

    $fecInicio .= ' 00:00:00.000';
    $fecFin .=  ' 23:59:59.997';

    $rows = $this->m_asistenciar->listar_trabajadores( $fecInicio, $fecFin );
    $select ="<option value=''>TODOS</option>";
    foreach($rows as $d){
      $select .="<option value='".$d["dni"]."'>".$d["nombres"]."</option>";
    }
    echo $select;
  }

  function tareo()
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
    $rows_trab = $this->m_asistenciar->listar_trabajadores();
    $ultima_actualizacion = $this->m_asistenciar->last_update();
    $datos["actualizacion"] = $ultima_actualizacion[0]['fecha'];
    $datos["trabajadores"] = $rows_trab;
    $datos["tabla_html"] = $this->tareo_html($rows_html, date("Y"), date("n"));
    $this->load->view("v_tareor", $datos);
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
    $rows = $this->m_asistenciar->listar_asistencia_import($fecInicio, $fecFin, $nombres, $dni); //-> $fech_ini, $fech_fin, $personal='', $pers_cod = ''
    echo ($this->tabla_html_head($rows));
  }

  function reporte()
  {
    $dni = trim($this->input->post('dni'));
    if($dni != ''){ $dni=intval($dni); }
    $nombres = ''; //strtoupper($this->input->post('txtNombres'));
    $mes = $this->input->post('mes');
    $anio = $this->input->post('anio');
    $fecha = "$anio-$mes-01"; //-> date("Y-m-d");
    $fecha_fin = "$anio-$mes";
    $rows = $this->m_asistenciar->listar_asistencia_reporte($fecha, $fecha_fin, $nombres, $dni );
    echo ($this->tareo_html($rows, $anio, $mes ));
  }

  function tabla_html_head($rows)
  {
      $html = "";
      $html .= '<table class="table table-bordered table-striped table-sm datatable_sm">';
      $html .= '<thead class="thead-light">';
      $html .= '<tr>';
      $html .= '<th>Nombres</th>';
      $html .= '<th>Dni</th>';
      $html .= '<th>Marcación</th>';
      $html .= '<th>Tipo Contrado/Departamento</th>';
      $html .= '<th>Tipo Marc.</th>';
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody>';
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
          if($row['estado']=='1'){ $tipo_marcado="Dactilar";} else {$tipo_marcado = "Clave";};
          $html .= '<td>'.$tipo_marcado.'</td>';
          //$html .= '<td>'.date_format($fecha_cons, 'd/m/Y').'</td>';
          $html .= '</tr>';
        }
      }
      $html .= '</tbody>';
      $html .= '</table>';
      return $html;
  }

  function tareo_html($rows, $anio, $mes )
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
  /*
    function busqueda()
    {
      $filtro = strtoupper($this->input->post('filtro')); //historia clinica
      $cod_consultorio = strtoupper($this->input->post('cod_consultorio'));
      $fecha_consulta = date("Y-m-d");
      //$this->input->post('fecha_consulta');
      $rows = $this->m_anulafua->m_buscar($filtro, $cod_consultorio, $fecha_consulta );
      echo ($this->tabla_html_head($rows));
    }
  */
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