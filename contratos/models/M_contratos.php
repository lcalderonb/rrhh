<?php
class M_contratos extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	function contarContrato($nombre)
	{
		$sql = "SELECT * FROM tb_contrato where upper('$nombre') like upper(contratoNombre)";
		$result = $this->db->query($sql);
		$registros = $result->num_rows();
		return $registros;
	}

	function contarRegimen($nombre)
	{
		$sql = "SELECT * FROM tb_regimen where upper('$nombre') like upper(descripcion)";
		$result = $this->db->query($sql);
		$registros = $result->num_rows();
		return $registros;
	}

	function listarContrato()
	{
		$sql = "SELECT * FROM tb_contrato";
		$result = $this->db->query($sql);
        //echo json_encode($result);
		return $result; //->result_array();
	}

	function listarRegimen()
	{
		$sql = "SELECT * FROM tb_regimen";
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

	function regimenNivel($nivel)
	{
		$sql = "SELECT * FROM tb_regimen where supregimenid = $nivel";
		$result = $this->db->query($sql);
        //echo json_encode($result);
		return $result;
	}

	function crearContrato($data)
	{
		$this->db->trans_begin();
		$this->db->insert("tb_contrato", $data);
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

	function crearRegimen($data)
	{
		$this->db->trans_begin();
		$this->db->insert("tb_regimen", $data);
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

	function actualizaContrato($id,$data)
	{
		$this->db->trans_begin();
		$this->db->where("contratoId", $id);
		$this->db->update("tb_contrato", $data);
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

	function actualizaRegimen($id,$data)
	{
		$this->db->trans_begin();
		$this->db->where("id_regimen", $id);
		$this->db->update("tb_regimen", $data);
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

	function eliminaContrato($data)
	{
		$this->db->trans_begin();
		$this->db->where("contratoId", $data);
        $this->db->delete("tb_contrato");
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

	function eliminaRegimen($data)
	{
		$this->db->trans_begin();
		$this->db->where("id_regimen", $data);
        $this->db->delete("tb_regimen");
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
