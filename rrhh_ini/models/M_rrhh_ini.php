<?php
class M_rrhh_ini extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function contarHorario($nombre)
	{
		$sql = "SELECT * FROM tb_horario where upper('$nombre') like upper(nombre)";
		$result = $this->db->query($sql);
		$registros = $result->num_rows();
		return $registros;
	}

	function listarHorario()
	{
		$query  = $this->db->select('*')
                   ->from('tb_horario')
                   ->where('activo',"1")
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
}