<?php
class M_roles extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function m_obtenerDepartamentos()
	{
		$query  = $this->db->select('*')
						->from('tb_departamento')
						->where("SupDepId <= 2")
						->get();
		return $query->result_array();
	}

	function m_obtenerServicios()
	{
		$query  = $this->db->select('*')
						->from('tb_departamento')
						->where("SupDepId > 2")
						->get();
		return $query->result_array();
	}

	function m_obtenerEmpleados()
	{
		$query  = $this->db->select('*')
						->from('v_empleados_activos')
						->get();
		return $query->result_array();
	}

	function m_obtenerProfesiones()
	{
		$query  = $this->db->select('*')
						->from('v_profesiones')
						->get();
		return $query->result_array();
	}

	function m_obtenerTurnos()
	{
		$query  = $this->db->select('*')
						->from('v_turnos_asistencial')
						->get();
		return $query->result_array();
	}

	function m_obtenerContratos()
	{
		$query  = $this->db->select('*')
						->from('v_contratos')
						->get();
		return $query->result_array();
	}

	function ingresarRol($data)
	{
		$this->db->trans_begin();
		$this->db->insert("tb_roles", $data);
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

	function GuardaRol($claveRol,$data)
	{
		$this->db->trans_begin();
		$this->db->where('claveRol',$claveRol)
					->update_batch("tb_roles", $data,'documentId');
		if($this->db->trans_status()==false){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "guardar rol: error al guardar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Guardando rol! <br> Los roles han sido guardados correctamente.";
		}
		return $result;
	}

	function existeRolxClave($claveRol)
	{
		$query  = $this->db->select('*')
						->from('v_rolesxclave')
						->where('claveRol',$claveRol)
						->get();
		if ($query->num_rows() > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	function eliminaRolxClave($claveRol)
	{
		$this->db->trans_begin();
		$this->db->where('claveRol', $claveRol)
				->delete('tb_roles');
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			return false;
			//terminar con exit
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

	function existeEmpleadoRolMes($empleado,$anno,$mes)
	{
		$query  = $this->db->select('*')
						->from('v_empleadoRolMes')
						->where('documentId = "'.$empleado.'" AND anno = "'.$anno.'" AND mes = "'.$mes.'"')
						->get();
		return $query;
	}

	function miembrosRol($claveRol)
	{
		$query  = $this->db->select('*')
						->from('v_MiembrosRol')
						->where('claveRol',$claveRol)
						->get();
		return $query->result_array();
	}

	function listarRoles($mes,$anno,$departamento,$servicio)
	{
		//$sql = "SELECT * FROM tb_horario where upper('$nombre') like upper(nombre)";
		$sql = "SELECT
					*
				FROM
					v_ListarRoles
				WHERE
					(anno='$anno' and mes = '$mes' and departamentoId = $departamento AND servicioId = $servicio)
				";
		$result = $this->db->query($sql);
		$registros = $result->result_array();
		return $registros;
	}

	function detalleRol($claveRol)
	{
		//$sql = "SELECT * FROM tb_horario where upper('$nombre') like upper(nombre)";
		$sql = "SELECT
					*
				FROM
					v_ListarRoles
				WHERE
					(claveRol = $claveRol)
				";
		$result = $this->db->query($sql);
		$registros = $result->result_array();
		return $registros;
	}

	function m_obtenerHorarioAsistencial($claveRol)
	{
		//$sql = "SELECT * FROM tb_horario where upper('$nombre') like upper(nombre)";
		$sql = "SELECT
					*
				FROM
					v_horario_asistencial
				WHERE
					(
						activo = 1 AND
						claveRol = $claveRol
					)
				ORDER BY orden
				";
		$result = $this->db->query($sql);
		$registros = $result->result_array();
		return $registros;
	}
}
