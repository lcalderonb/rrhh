<?php
class M_asistencia extends CI_Model
{
	//public $dbadmision;
	//public $dbf_serv;

	public function __construct()
	{
		parent::__construct();
		//$this->dbadmision = $this->load->database('admision', true);
		//$this->dbf_serv = $this->load->database('dbf_serv', true);
	}

	function listar_trabajadores() //* listado de trabajadores del RELOJ BIOSTAR
	{
		$biostar  = $this->load->database('dbbiostar', TRUE);
		$sql = "SELECT DISTINCT RIGHT('00000000' + Ltrim(Rtrim(sUserID)),8) AS dni, ( isnull(sUserName,'') + ' ('+ RIGHT('00000000' + Ltrim(Rtrim(sUserID)),8) +')' ) AS nombres FROM dbo.TB_USER";
		$res = $biostar->query($sql);
		return $res->result_array();
	}

	function listar_empleados() //* listado de trabajadores consolidados de ambos relojes
	{
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT empId , upper(TRIM(concat(nombres,' (',documentId,')'))) as nombres FROM `tb_empleado` order by nombres";
		$res = $bdHospital->query($sql);
		return $res->result_array();
	}

	function buscarIdBiostar($idempleado) //* busca el nUserID
	{
		//busqueda en base HOSPITAL_RRHH, tabla empleados. el id asociado al reloj biostar
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT hosp_id FROM `tb_empleado` where hosp_tb =1  and empId = '{$idempleado}'";
		$res1 = $bdHospital->query($sql);
		$nUsrIdn = $res1->row(0)->hosp_id;
			$bdBiostar  = $this->load->database('dbbiostar', TRUE);
			$sql = "SELECT sUserID FROM TB_USER where nUserIdn ='{$nUsrIdn}'";
			$res2 = $bdBiostar->query($sql);
			return $res2->row(0)->sUserID; //* se devuleve el sUserId para buscar marcaciones en tabla TB_EVENT_LOG
	}

	function buscarIdZkteco($idempleado) //* busca el USERID
	{
		//busqueda en base HOSPITAL_RRHH, tabla empleados. el id asociado al reloj biostar
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT adm_id FROM `tb_empleado` where adm_tb =1  and empId = '{$idempleado}'";
		$res1 = $bdHospital->query($sql);
		return $res1->row(0)->adm_id;
	}

	function listar_asistencia_import($fech_ini, $fech_fin, $personal='', $pers_cod = '')
	{
		//$unidad_operativa_id = intval($unidad_operativa_id);
		$fi = DateTime::createFromFormat('Y-m-d',$fech_ini);
		$ff = DateTime::createFromFormat('Y-m-d',$fech_fin);

		//$fi->sub(new DateInterval('P1D'));
		//$ff->add(new DateInterval('P1D'));

		$biostar  = $this->load->database('dbbiostar', TRUE);
		//$personal = $this->db->escape_str($personal);
		//$personal = str_replace(' ', '%', $personal);

		//$pers_cod = "43479239";
		//$personal = "";

		//echo var_dump($personal);
		//exit;
		//Obteniendo Marcaciones del Biométrico
		//120
		//CONVERT(datetime, DATEADD(s, nDateTime, '1970-01-01'), 103) as m
		//CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) AS m original creo
		//CONVERT(VARCHAR(10), DATEADD(s, nDateTime, '1970-01-01'), 103) AS fecha
		//CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 24) AS m
		$sql = "SELECT
							RIGHT('00000000' + Ltrim(Rtrim(nUserID)),8) AS dni,
							isnull(sUserName,'') AS nombres,
							( CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) + ' ' + CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 24) ) as m ,
							CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) as fecha,
							TB_USER_DEPT.sDepartment,
							TB_EVENT_DATA.sName as estado
						FROM
							dbo.TB_EVENT_LOG LEFT JOIN
							dbo.TB_USER ON CAST(TB_EVENT_LOG.nUserID AS float) = CAST(dbo.TB_USER.sUserID as float)
							LEFT JOIN dbo.TB_EVENT_DATA ON TB_EVENT_LOG.nEventIdn = TB_EVENT_DATA.nEventIdn
							LEFT JOIN dbo.TB_USER_DEPT ON TB_USER.nDepartmentIdn = TB_USER_DEPT.nDepartmentIdn
						WHERE
							nUserID<>0
							AND ('{$pers_cod}' = '' OR CAST(TB_EVENT_LOG.nUserID AS varchar) IN ('{$pers_cod}') )
							AND ('{$personal}' = '' OR TB_USER.sUserName LIKE ('%{$personal}%'))
							AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '{$fech_ini}' AND '{$fech_fin}'
						ORDER BY
								sUserName ASC, CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) DESC";
		//echo $sql;
		//ORIGINAL AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '{$fech_ini}' AND '{$fech_fin}'
		//AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) >= '{$fech_ini}'
		//AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) <= '{$fech_fin}'
		//set_time_limit (180); 
		$res = $biostar->query($sql);
		return $res->result_array();
	}

	function listar_asistencia_unificada($fecha, $fecha_fin, $pers_cod = '')
	{
		$max = date('t', strtotime( $fecha ) );
		$asistencia = array();
		for($i=1; $i<=$max; $i++ ){
			$asistencia['d'.$i] = '';
			$asistencia['ds'.$i] = '';
			$asistencia['dr'.$i] = '';	//*reloj de ingreso
			$asistencia['dsr'.$i] = '';	//*reloj de salida
		}
		$fech_ini = $fecha.' 00:00:00.000';
		//*busqueda en base HOSPITAL_RRHH, tabla empleados. NOMBRES COMPLETOS
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT * FROM `tb_empleado` where empId = '{$pers_cod}'";
		$res0 = $bdHospital->query($sql);
		$nombre_completo = $res0->row(0)->nombres;
		$turno_id = $res0->row(0)->turno_id;
		//*************************************************************************************************
		//*************************************************************************************************
		//****************************           RELOJ BIOSTAR                       **********************
		//*************************************************************************************************
		//*************************************************************************************************
		$marca_biostar	=	array();
		//*busqueda en base HOSPITAL_RRHH, tabla empleados. el id asociado al reloj biostar
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT hosp_id FROM `tb_empleado` where hosp_tb =1  and empId = '{$pers_cod}'";
		$res1 = $bdHospital->query($sql);
		$nUsrIdn = ($res1->num_rows() > 0) ? $res1->row(0)->hosp_id : 0;
		//* busqueda ahora del sUserId
		$bdBiostar  = $this->load->database('dbbiostar', TRUE);
		$sql = "SELECT sUserID FROM TB_USER where nUserIdn ='{$nUsrIdn}'";
		$res2 = $bdBiostar->query($sql);
		$sUserId = ($res2->num_rows() > 0) ? $res2->row(0)->sUserID : 0; 	//* se devuleve el sUserId para buscar
		//* marcaciones en tabla TB_EVENT_LOG
		//* Obtencion de marcaciones de BIOSTAR
		$asistencia['sUserId'] = $sUserId;
		$biostar  	= $this->load->database('dbbiostar', TRUE);
		$fech_fin = "$fecha_fin-$max 23:59:59.997";
		$sql_biostar = "SELECT
							RIGHT('00000000' + Ltrim(Rtrim(nUserID)),8) 							AS dni,
							isnull(sUserName,'') 													AS nombres,
							( CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) +
							' ' + CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 24) )	AS m,
							CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 20) 			AS fecha,
							TB_EVENT_DATA.sName														AS forma,
							reloj = 'biostar'
							FROM
								dbo.TB_EVENT_LOG
								LEFT JOIN dbo.TB_USER ON CAST(TB_EVENT_LOG.nUserID AS float) = CAST(dbo.TB_USER.sUserID as float)
								LEFT JOIN dbo.TB_EVENT_DATA ON TB_EVENT_LOG.nEventIdn = TB_EVENT_DATA.nEventIdn
							WHERE
								nUserID<>0
								AND ('{$sUserId}' = '' OR CAST(TB_EVENT_LOG.nUserID AS varchar) IN ('{$sUserId}') )
								AND ( CAST( TB_EVENT_DATA.nEventIdn AS varchar) IN ('55','58','47','23','99','61') )
								AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '{$fech_ini}' AND '{$fech_fin}'
							ORDER BY m ASC
						";
		$res_biostar = $biostar->query($sql_biostar);
		$marca_biostar = $res_biostar->result_array();
		//$asistencia['biostar_sql'] = $marca_biostar;
		//****************************           /RELOJ BIOSTAR                      **********************

		//*************************************************************************************************
		//*************************************************************************************************
		//****************************            RELOJ ZKTECO                       **********************
		//*************************************************************************************************
		//*************************************************************************************************
		$marca_zkteco	=	array();
		//*busqueda en base HOSPITAL_RRHH, tabla empleados. el id asociado al reloj ZKTECO
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT adm_id FROM `tb_empleado` where adm_tb =1  and empId = '{$pers_cod}'";
		$res2 = $bdHospital->query($sql);
		$usrId = ($res2->num_rows() > 0) ? $res2->row(0)->adm_id : 0;
		$asistencia['usrId'] = $usrId;
		//* obtencion de marcaciones de ZKTECO
		$zkteco  	= $this->load->database('zktimedb', TRUE);
		$sql_zkteco = "SELECT
							ssn											AS dni,
							isnull(USERINFO.name,'') 					AS nombres,
							( CONVERT(VARCHAR(20), checktime, 103) +
							' ' + CONVERT(VARCHAR(20),checktime, 24) ) 	AS m,
							CONVERT(VARCHAR(20), checktime, 20) 		AS fecha,
							CHECKINOUT.VERIFYCODE						AS forma,
							reloj = 'zkteco'
						FROM
						[dbo].[CHECKINOUT]
						LEFT JOIN [dbo].[USERINFO] ON CHECKINOUT.USERID = USERINFO.USERID
						WHERE
						[dbo].USERINFO.SSN is not null and USERINFO.ATT = 1
						AND ('{$usrId}' = '' OR CAST(CHECKINOUT.userid AS varchar) in ('{$usrId}') )
						AND ( CAST( CHECKINOUT.VERIFYCODE AS varchar) IN ('1','0') )
						AND checktime  BETWEEN '{$fech_ini}' AND '{$fech_fin}'
						ORDER BY m
						";
		$res_zkteco = $zkteco->query($sql_zkteco);
		$marca_zkteco = $res_zkteco->result_array();
		//$asistencia['zkteco_sql'] = $marca_zkteco;
		//****************************           /RELOJ ZKTECO                     ***********************

		//************* UNION DE MARCACIONES DE AMBOS RELOJES ********************************************
		$marca = array_merge($marca_biostar, $marca_zkteco);
		$asistencia['unificado_sql'] = $marca;
		//************* /UNION DE MARCACIONES DE AMBOS RELOJES *******************************************
		$pers_cods = array();
		foreach ($marca as $m) {
			$pers_cods[] = $m['dni'];
		}
		$pers_cods = array_unique($pers_cods);
		$dat = array();
		foreach ($pers_cods as $p) {
			$mar_per = array();
			$found = null;
			$nombres = '';
			for($j = 0; $j < count($marca); $j++){
				if ($marca[$j]['dni'] == $p){
					$mar_per[] = $marca[$j];
					$found = true;
					$nombres = $marca[$j]['nombres'];
				} else if($found) {
					break;
				}
			}
			$reg = $asistencia;
			$reg['dni'] = $p;
			$reg['nombres'] = $nombre_completo;
			$reg['turno_id'] = $turno_id;
			$flag = true;
			$marcacion_temp = new DateTime( date('Y-m-d') );
			$contador = 0;
			$ingreso = null;
			$salida = null;
			foreach( $mar_per as $row ){
				$marcacion = new DateTime( $row['fecha'] ); //*toma el día correspndiente
				if (($reg['d'.intval( $marcacion->format('d') ) ] == null) ||
				 	($reg['d'.intval( $marcacion->format('d') ) ] > $row['fecha']))
				{
					$fecha_temporal = $reg['d'.intval( $marcacion->format('d') ) ];
					$reloj_temporal = $reg['dr'.intval( $marcacion->format('d') ) ];
					$reg['d'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					$reg['dr'.intval( $marcacion->format('d') ) ] = $row['reloj'];
					if ($reg['ds'.intval( $marcacion->format('d') ) ] == null) {
						$reg['ds'.intval( $marcacion->format('d') ) ] 	= $fecha_temporal;
						$reg['dsr'.intval( $marcacion->format('d') ) ] 	= $reloj_temporal;
					} else {
						if ($reg['ds'.intval( $marcacion->format('d') ) ] < $fecha_temporal)
							{
								$reg['ds'.intval( $marcacion->format('d') ) ] 	= $fecha_temporal;
								$reg['dsr'.intval( $marcacion->format('d') ) ] 	= $reloj_temporal;
							}
					}
				} elseif (	($reg['ds'.intval( $marcacion->format('d') ) ] == null) ||
							($reg['ds'.intval( $marcacion->format('d') ) ] < $row['fecha']))
				{
					$reg['ds'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					$reg['dsr'.intval( $marcacion->format('d') ) ] = $row['reloj'];
				}
				/*
				if ( $flag ) {
					$marcacion_temp = new DateTime( $row['fecha'] );
					$flag = false;
				}
				$marcacion = new DateTime( $row['fecha'] );
				if( $marcacion->format("Y-m-d") == $marcacion_temp->format("Y-m-d") ){
					//es el mismo dia
					$contador++;
					if( $contador == 1 ){
						//$ingreso = 	$marcacion;
						$reg['d'.intval( $marcacion->format('d') ) ] = $row['fecha'];
						$reg['dr'.intval( $marcacion->format('d') ) ] = $row['reloj'];
					}
					if( $contador >= 2 ){
						//$salida  = 	$marcacion;
						$reg['ds'.intval( $marcacion->format('d') ) ] = $row['fecha'];
						$reg['dsr'.intval( $marcacion->format('d') ) ] = $row['reloj'];
					}
				}else{//otro dia
					$contador = 1;
					$marcacion_temp = new DateTime( $row['fecha'] );
					if( $contador == 1 ){
						$reg['d'.intval( $marcacion->format('d') ) ] = $row['fecha'];
						$reg['dr'.intval( $marcacion->format('d') ) ] = $row['reloj'];
					}
				}*/
			}
			$dat[] = $reg;
			//$asistencia['dat'] = $dat;
		}//terminando una persona
		return $dat;
	}

	function listar_asistencia_zkteco($fecha, $fecha_fin, $pers_cod = '')
	{
		$biostar  = $this->load->database('zktimedb', TRUE);
		//$fech_ini, $fech_fin
		//$max = date('t', strtotime( date("Y-m-d") ) );
		$max = date('t', strtotime( $fecha ) );
		$asistencia = array();
		for($i=1; $i<=$max; $i++ ){
			$asistencia['d'.$i] = '';
			$asistencia['ds'.$i] = '';
		}
		$fech_ini = $fecha.' 00:00:00.000';
		$fech_fin = "$fecha_fin-$max 23:59:59.997";
		//AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '--01 00:00:00.000' AND '--31 23:59:59.997
		$sql = "SELECT
							CHECKINOUT.userid, ssn as dni,
							isnull(USERINFO.name,'') AS nombres,
							( CONVERT(VARCHAR(20), checktime, 103) + ' ' + CONVERT(VARCHAR(20),checktime, 24) ) as m ,
							--CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) as fecha,
							CONVERT(VARCHAR(20), checktime, 20) as fecha,
							CHECKINOUT.VERIFYCODE
						FROM
							[dbo].[CHECKINOUT]
							LEFT JOIN [dbo].[USERINFO] ON CHECKINOUT.USERID = USERINFO.USERID
						WHERE
							[dbo].USERINFO.SSN is not null and USERINFO.ATT = 1
							AND ('{$pers_cod}' = '' OR CAST(CHECKINOUT.userid AS varchar) in ('{$pers_cod}') )
							AND ( CAST( CHECKINOUT.VERIFYCODE AS varchar) IN ('1','0') )
							AND checktime BETWEEN '{$fech_ini}' AND '{$fech_fin}'
						ORDER BY
							USERINFO.name ASC, checktime ASC";
		$res = $biostar->query($sql);
		$marca = $res->result_array();
		//_Obteniendo Lista de Personal a Ingresar (pers_cod)
		$pers_cods = array();
		foreach ($marca as $m) {
			$pers_cods[] = $m['dni'];
		}
		$pers_cods = array_unique($pers_cods);
		$dat = array();
		foreach ($pers_cods as $p) {
			$mar_per = array();
			$found = null;
			$nombres = '';
			for($j = 0; $j < count($marca); $j++){
				if ($marca[$j]['dni'] == $p){
					$mar_per[] = $marca[$j];
					$found = true;
					$nombres = $marca[$j]['nombres'];
				} else if($found) {
					break;
				}
			}
			$reg = $asistencia;
			$reg['dni'] = $p;
			$reg['nombres'] = $nombres;
			$flag = true;
			$marcacion_temp = new DateTime( date('Y-m-d') );
			$contador = 0;
			$ingreso = null;
			$salida = null;
			foreach( $mar_per as $row ){
				if ( $flag ) {
					$marcacion_temp = new DateTime( $row['fecha'] );
					$flag = false;
				}
				$marcacion = new DateTime( $row['fecha'] );
				if( $marcacion->format("Y-m-d") == $marcacion_temp->format("Y-m-d") ){
					//es el mismo dia
					$contador++;
					if( $contador == 1 ){
						//$ingreso = 	$marcacion;
						$reg['d'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					}
					if( $contador >= 2 ){
						//$salida  = 	$marcacion;
						$reg['ds'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					}
				}else{//otro dia
					$contador = 1;
					$marcacion_temp = new DateTime( $row['fecha'] );
					if( $contador == 1 ){
						$reg['d'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					}
				}
			}
			$dat[] = $reg;
		}//terminando una persona
		return $dat;
	}

	function listar_asistencia_reporte2($fecha, $fecha_fin, $personal='', $pers_cod = '')
	{
		$biostar  = $this->load->database('dbbiostar', TRUE);
		//$fech_ini, $fech_fin
		//$max = date('t', strtotime( date("Y-m-d") ) );
		$max = date('t', strtotime( $fecha ) );
		$asistencia = array();
		for($i=1; $i<=$max; $i++ ){
			$asistencia['d'.$i] = '';
			$asistencia['ds'.$i] = '';
		}
		$fech_ini = $fecha.' 00:00:00.000';
		$fech_fin = "$fecha_fin-$max 23:59:59.997";
		//AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '--01 00:00:00.000' AND '--31 23:59:59.997
		$sql = "SELECT
							RIGHT('00000000' + Ltrim(Rtrim(nUserID)),8) AS dni,
							isnull(sUserName,'') AS nombres, 
							( CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) + ' ' + CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 24) ) as m ,
							--CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) as fecha,
							CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 20) as fecha,
							TB_EVENT_DATA.sName
						FROM
							dbo.TB_EVENT_LOG LEFT JOIN
							dbo.TB_USER ON CAST(TB_EVENT_LOG.nUserID AS float) = CAST(dbo.TB_USER.sUserID as float)
							LEFT JOIN dbo.TB_EVENT_DATA ON TB_EVENT_LOG.nEventIdn = TB_EVENT_DATA.nEventIdn
						WHERE
							nUserID<>0
							AND ('{$pers_cod}' = '' OR CAST(TB_EVENT_LOG.nUserID AS varchar) IN ('{$pers_cod}') )
							AND ( CAST( TB_EVENT_DATA.nEventIdn AS varchar) IN ('55','58','47','23','99','61') )
							AND ('{$personal}' = '' OR TB_USER.sUserName LIKE ('%{$personal}%'))
							AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '{$fech_ini}' AND '{$fech_fin}'
						ORDER BY
							sUserName ASC, CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) ASC";
		$res = $biostar->query($sql);
		$marca = $res->result_array();
		//_Obteniendo Lista de Personal a Ingresar (pers_cod)
		$pers_cods = array();
		foreach ($marca as $m) {
			$pers_cods[] = $m['dni'];
		}
		$pers_cods = array_unique($pers_cods);
		$dat = array();
		foreach ($pers_cods as $p) {
			$mar_per = array();
			$found = null;
			$nombres = '';
			for($j = 0; $j < count($marca); $j++){
				if ($marca[$j]['dni'] == $p){
					$mar_per[] = $marca[$j];
					$found = true;
					$nombres = $marca[$j]['nombres'];
				} else if($found) {
					break;
				}
			}
			$reg = $asistencia;
			$reg['dni'] = $p;
			$reg['nombres'] = $nombres;
			$flag = true;
			$marcacion_temp = new DateTime( date('Y-m-d') );
			$contador = 0;
			$ingreso = null;
			$salida = null;
			foreach( $mar_per as $row ){
				if ( $flag ) {
					$marcacion_temp = new DateTime( $row['fecha'] );
					$flag = false;
				}
				$marcacion = new DateTime( $row['fecha'] );
				if( $marcacion->format("Y-m-d") == $marcacion_temp->format("Y-m-d") ){
					//es el mismo dia
					$contador++;
					if( $contador == 1 ){
						//$ingreso = 	$marcacion;
						$reg['d'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					}
					if( $contador >= 2 ){
						//$salida  = 	$marcacion;
						$reg['ds'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					}
				}else{//otro dia
					$contador = 1;
					$marcacion_temp = new DateTime( $row['fecha'] );
					if( $contador == 1 ){
						$reg['d'.intval( $marcacion->format('d') ) ] = $row['fecha'];
					}
				}
			}
			$dat[] = $reg;
		}//terminando una persona
		return $dat;
	}

	function listarDepartamento() //* busca el nUserID
	{
		//busqueda en base HOSPITAL_RRHH, tabla empleados. el id asociado al reloj biostar
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT * FROM tb_departamento";
		$result = $bdHospital->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}

	function listarTurno() //* busca el nUserID
	{
		//busqueda en base HOSPITAL_RRHH, vista Turnos. el id asociado al turno
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT * FROM v_turnos";
		$result = $bdHospital->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}

	function listarTurnoxId($id) //* busca el nUserID
	{
		//busqueda en base HOSPITAL_RRHH, vista Turnos. el id asociado al turno
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT
					tb_turno.*,
					tb_horario.nombre,
					tb_horario.activo,
					tb_horario.hora_entrada,
					tb_horario.hora_salida,
					tb_horario.entrada_tolerancia,
					tb_horario.salida_tolerancia,
					tb_horario.tolerancia_acumulable,
					tb_horario.entrada_tardanza,
					tb_horario.tardanza1,
					tb_horario.tardanza2,
					tb_horario.tardanza3,
					tb_horario.empieza_entrada,
					tb_horario.termina_entrada,
					tb_horario.empieza_salida,
					tb_horario.termina_salida,
					tb_horario.color,
					tb_horario.jornada_laboral
				FROM
					tb_turno
					LEFT JOIN
					tb_horario
					ON
						tb_turno.id_horario = tb_horario.id_horario
				WHERE
					turnoId = ({$id})";
		$result = $bdHospital->query($sql);
        //echo json_encode($result);
		return $result->result_array();
	}

	function listarCondicion() //* busca el nUserID
	{
		//busqueda en base HOSPITAL_RRHH, vista Turnos. el id asociado al turno
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT * FROM tb_contrato WHERE SupContratoId > 0";
		$result = $bdHospital->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}

	function listarRegimen() //* busca el nUserID
	{
		//busqueda en base HOSPITAL_RRHH, vista Turnos. el id asociado al turno
		$bdHospital  = $this->load->database('default', TRUE);
		$sql = "SELECT * FROM tb_regimen WHERE SupRegimenId > 0";
		$result = $bdHospital->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}
}
?>
