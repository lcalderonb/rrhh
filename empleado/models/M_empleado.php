<?php
class M_empleado extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function listarDepartamento()
	{
		$sql = "SELECT * FROM tb_departamento";
		$result = $this->db->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}

	function contratoNivel($nivel)
	{
		$sql = "SELECT * FROM tb_contrato where supcontratoid = $nivel";
		$result = $this->db->query($sql);
        //echo json_encode($result);
		return $result;
	}

	function listarContrato()
	{
		$sql = "SELECT * FROM tb_contrato";
		$result = $this->db->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}

	function listaAdministrativos()
	{
		$zktimedb  = $this->load->database('zktimedb', TRUE);
		$sql = "SELECT
					USERINFO.USERID,
					USERINFO.BADGENUMBER,
					USERINFO.SSN,
					USERINFO.NAME,
					USERINFO.GENDER,
					USERINFO.TITLE,
					USERINFO.PAGER,
					USERINFO.ATT,
					USERINFO.BIRTHDAY,
					USERINFO.HIREDDAY,
					USERINFO.FPHONE,
					USERINFO.DEFAULTDEPTID,
					DEPARTMENTS.DEPTNAME
				FROM
					dbo.USERINFO
					INNER JOIN dbo.DEPARTMENTS ON USERINFO.DEFAULTDEPTID = DEPARTMENTS.DEPTID
				WHERE
					NOT ( USERINFO.SSN IS NULL )
				ORDER BY
					USERINFO.NAME
		";
		$result = $zktimedb->query($sql);
		return $result;
	}

	function listaHospital() //-> $fech_ini, $fech_fin
	{
		$biostar  = $this->load->database('dbbiostar', TRUE);
		$sql = "SELECT
					TB_USER.nUserIdn,
					TB_USER.sUserName,
					RIGHT ( '00000000' + Ltrim( Rtrim( TB_USER.sUserID ) ), 8 ) AS DNI,
					TB_USER.nDepartmentIdn,
					TB_USER_DEPT.sDepartment,
					TB_USER.sTelNumber,
					TB_USER.sEmail,
					CONVERT ( DATETIME, DATEADD( s, TB_USER.nStartDate, '1970-01-01' ), 20 ) AS nStartDate,
					CONVERT ( DATETIME, DATEADD( s, TB_USER.nEndDate, '1970-01-01' ), 20 ) AS nEndDate
				FROM
					dbo.TB_USER
					INNER JOIN dbo.TB_USER_DEPT ON TB_USER.nDepartmentIdn = TB_USER_DEPT.nDepartmentIdn
				ORDER BY
					TB_USER.sUserName
		";
		$res = $biostar->query($sql);
		return $res;
	}

	function contarHorario($nombre)
	{
		$sql = "SELECT * FROM tb_horario where upper('$nombre') like upper(nombre)";
		$result = $this->db->query($sql);
		$registros = $result->num_rows();
		return $registros;
	}

	function listarEmpleado()
	{
		$query  = $this->db->select('*')
                   ->from('tb_empleado')
                   ->get();
		return $query->result_array();
	}

	function crearHorario($data)
	{
		$this->db->trans_begin();
		$this->db->insert("tb_horario", $data);
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se guardaron los datos correctamente.";
		}
		return $result;
	}

	function ingresarEmpleado($data)
	{
		$this->db->trans_begin();
		$this->db->insert("tb_empleado", $data);
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se guardaron los datos correctamente.";
		}
		return $result;
	}

	function ingresarPersona($data)
	{
		$this->db->trans_begin();
		$this->db->insert("tb_persona", $data);
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se guardaron los datos correctamente.";
		}
		return $result;
	}

	function actualizaEmpleados($data)
	{
		$this->db->trans_begin();
		$this->db->update_batch("tb_empleado", $data,"empId");
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se actualizaron los datos correctamente.";
		}
		return $result;
	}

	function actualizaEmpleado($empId,$data)
	{
		$this->db->trans_begin();
		$this->db->where("empId", $empId);
		$this->db->update("tb_empleado", $data);
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se actualizaron los datos correctamente.";
		}
		return $result;
	}

	function actualizaEmpleadoxDni($docId,$data)
	{
		$this->db->trans_begin();
		$this->db->where("documentId", $docId);
		$this->db->update("tb_empleado", $data);
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se actualizaron los datos correctamente.";
		}
		return $result;
	}

	function actualizaPersona($dni,$data)
	{
		$this->db->trans_begin();
		$this->db->where("num_documento", $dni);
		$this->db->update("tb_persona", $data);
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se actualizaron los datos correctamente.";
		}
		return $result;
	}

	function actualizaHorario($nombre,$data)
	{
		$this->db->trans_begin();
		$this->db->where("nombre", $nombre);
		$this->db->update("tb_horario", $data);
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se guardaron los datos correctamente.";
		}
		return $result;
	}

	function desactivaHorario($id_horario)
	{
		$this->db->trans_begin();
		$this->db->set("activo", "0");
		$this->db->where("id_horario", $id_horario);
		$this->db->update("tb_horario");
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al insertar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se guardaron los datos correctamente. ". $id_horario;
		}
		return $result;
	}

	function existePersona($key,$opcion)
	{
		if ($opcion==0) { 							//* busqueda por ID
			$this->db->where('id_personal',$key);
			$query = $this->db->get('tb_persona');
			if ($query->num_rows() > 0){
				$resultado['res'] = true;
			}
			else{
				$resultado['res'] = false;
			}
		} elseif ($opcion == 1) {					//* busqueda por DNI
			$this->db->where('num_documento',$key);
			$query = $this->db->get('tb_persona');
			if ($query->num_rows() > 0){
				$resultado['res'] = true;
				$resultado['data'] = $query->result_array()[0];
			}
			else{
				$resultado['res'] = false;
			}
		}
		return $resultado;
	}

	function existeAdministrativo($key,$opcion)
	{
		if ($opcion==0) {
			$this->db->where('adm_id',$key);
			$query = $this->db->get('tb_empleado');
			if ($query->num_rows() > 0){
				$resultado['res'] = true;
			}
			else{
				$resultado['res'] = false;
			}
		} elseif ($opcion == 1) {
			$this->db->where('documentId',$key);
			$query = $this->db->get('tb_empleado');
			if ($query->num_rows() > 0){
				$resultado['res'] = true;
				$resultado['val'] = $query->result_array()[0]['empId'];
			}
			else{
				$resultado['res'] = false;
			}
		}
		return $resultado;
	}

	function existeHospital($key,$opcion)
	{
		if ($opcion==0) {
			$this->db->where('hosp_id',$key);
			$query = $this->db->get('tb_empleado');
			if ($query->num_rows() > 0){
				$resultado['res'] = true;
			}
			else{
				$resultado['res'] = false;
			}
		} elseif ($opcion == 1) {
			$this->db->where('documentId',$key);
			$query = $this->db->get('tb_empleado');
			if ($query->num_rows() > 0){
				$resultado['res'] = true;
				$resultado['val'] = $query->result_array()[0]['empId'];
			}
			else{
				$resultado['res'] = false;
			}
		}
		return $resultado;
	}

	function empleadoxDni($dni) {
		$sql = "SELECT *
				FROM tb_empleado
				WHERE documentId = $dni";
		$q = $this->db->query($sql);
		return $q->row_array();
	}
}
