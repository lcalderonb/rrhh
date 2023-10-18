<?php
class M_depart extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	function contarDepartamento($nombre)
	{
		$sql = "SELECT * FROM tb_departamento where upper('$nombre') like upper(depNombre)";
		$result = $this->db->query($sql);
		$registros = $result->num_rows();
		return $registros;
	}

	function listarDepartamento()
	{
		$sql = "SELECT * FROM tb_departamento";
		$result = $this->db->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}

	function departamentoNivel($nivel)
	{
		$sql = "SELECT * FROM tb_departamento where supdepid = $nivel";
		$result = $this->db->query($sql);
        //echo json_encode($result);
		return $result;
	}

	function crearDepartamento($data)
	{
		$this->db->trans_begin();
		$this->db->insert("tb_departamento", $data);
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

	function actualizaDepartamento($id,$data)
	{
		$this->db->trans_begin();
		$this->db->where("depId", $id);
		$this->db->update("tb_departamento", $data);
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

	function eliminaDepartamento($data)
	{
		$this->db->trans_begin();
		$this->db->where("depId", $data);
        $this->db->delete("tb_departamento");
		if($this->db->trans_status()==FALSE){
			$this->db->trans_rollback();
			$result["success"]=false;
			$result["msg"]= "Error al eliminar los datos";
			//terminar con exit
		}else{
			$this->db->trans_commit();
			$result["success"]=true;
			$result["msg"] = "Se eliminaron los datos correctamente.";
		}
		return $result;
	}
}
?>