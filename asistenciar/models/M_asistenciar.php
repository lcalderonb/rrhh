<?php
class M_asistenciar extends CI_Model
{
	public $dbadmision;
	public $dbf_serv;

	public function __construct()
	{
		parent::__construct();
		$this->dbadmision = $this->load->database('admision', true);
		$this->dbf_serv = $this->load->database('dbf_serv', true);
	}

	function listar_trabajadores() //-> $fech_ini, $fech_fin
	{
		$zktimedb  = $this->load->database('zktimedb', TRUE);
		/*
		$sql = "SELECT DISTINCT RIGHT('00000000' + Ltrim(Rtrim(nUserID)),8) AS dni, ( isnull(sUserName,'') + ' ('+ RIGHT('00000000' + Ltrim(Rtrim(nUserID)),8) +')' ) AS nombres
						FROM dbo.TB_EVENT_LOG LEFT JOIN dbo.TB_USER ON CAST(TB_EVENT_LOG.nUserID AS float) = CAST(dbo.TB_USER.sUserID as float)
						WHERE nUserID<>0 AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '{$fech_ini}' AND '{$fech_fin}'";
		*/
		$sql = "SELECT DISTINCT RIGHT('00000000' + Ltrim(Rtrim(USERID)),8) AS dni, ( isnull(NAME,'') + ' ('+ RIGHT('00000000' + Ltrim(Rtrim(ssn)),8) +')' ) AS nombres FROM dbo.USERINFO where ssn is not null order by nombres";
		$res = $zktimedb->query($sql);
		return $res->result_array();
	}
	function last_update()
	{
		$zktimedb  = $this->load->database('zktimedb', TRUE);
		$sql = "SELECT max(CHECKINOUT.CHECKTIME) as fecha FROM CHECKINOUT";
		$res = $zktimedb->query($sql);
		return $res->result_array();
	}
	/*
	function listar_trabajadores( $fech_ini, $fech_fin )
	{
		$biostar  = $this->load->database('dbbiostar', TRUE);
		$sql = "SELECT
							RIGHT('00000000' + Ltrim(Rtrim(nUserID)),8) AS dni,
							( isnull(sUserName,'') + ' ('+ RIGHT('00000000' + Ltrim(Rtrim(nUserID)),8) +')' ) AS nombres,
							( CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) + ' ' + CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 24) ) as m ,
							CONVERT(VARCHAR(20), DATEADD(s, nDateTime, '1970-01-01'), 103) as fecha,
							TB_EVENT_DATA.sName
						FROM
							dbo.TB_EVENT_LOG LEFT JOIN
							dbo.TB_USER ON CAST(TB_EVENT_LOG.nUserID AS float) = CAST(dbo.TB_USER.sUserID as float)
							LEFT JOIN dbo.TB_EVENT_DATA ON TB_EVENT_LOG.nEventIdn = TB_EVENT_DATA.nEventIdn
						WHERE nUserID<>0 AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '{$fech_ini}' AND '{$fech_fin}'
						ORDER BY sUserName ASC, CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) DESC";
		//AND ('{$pers_cod}' = '' OR CAST(TB_EVENT_LOG.nUserID AS varchar) IN ('{$pers_cod}') )
		//AND ('{$personal}' = '' OR TB_USER.sUserName LIKE ('%{$personal}%'))
		//echo $sql;
		$res = $biostar->query($sql);
		return $res->result_array();
	}
	*/
	function listar_asistencia_import($fech_ini, $fech_fin, $personal='', $pers_cod = '')
	{
		//$unidad_operativa_id = intval($unidad_operativa_id);
		$fi = DateTime::createFromFormat('Y-m-d',$fech_ini);
		$ff = DateTime::createFromFormat('Y-m-d',$fech_fin);

		//$fi->sub(new DateInterval('P1D'));
		//$ff->add(new DateInterval('P1D'));

		$biostar  = $this->load->database('zktimedb', TRUE);
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
							checktime as m,
							CHECKINOUT.userid,
							ssn as dni,
							[dbo].USERINFO.name as nombres,
							USERINFO.DEFAULTDEPTID,
							DEPARTMENTS.DEPTNAME AS sDepartment,
							CHECKINOUT.VERIFYCODE as estado
						FROM [dbo].[CHECKINOUT]
							LEFT JOIN [dbo].[USERINFO] ON CHECKINOUT.USERID = USERINFO.USERID
							LEFT JOIN [dbo].[DEPARTMENTS] ON USERINFO.DEFAULTDEPTID = DEPARTMENTS.DEPTID
						WHERE
							[dbo].USERINFO.SSN is not null
							and USERINFO.ATT = 1
							AND ('{$pers_cod}' = '' OR CAST(CHECKINOUT.userid AS varchar) in ('{$pers_cod}') )
							AND checktime BETWEEN '{$fech_ini}' AND '{$fech_fin}'
						ORDER BY m";
		//echo $sql;
		//ORIGINAL AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) BETWEEN '{$fech_ini}' AND '{$fech_fin}'
		//AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) >= '{$fech_ini}'
		//AND CONVERT(DATETIME, DATEADD(s, nDateTime, '1970-01-01'), 20) <= '{$fech_fin}'
		//set_time_limit (180);
		$res = $biostar->query($sql);
		return $res->result_array();
	}

	function listar_asistencia_reporte($fecha, $fecha_fin, $personal='', $pers_cod = '')
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
}
?>