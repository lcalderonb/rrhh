<?php
class M_legajo extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function listarEmpleado()
	{
		$query  = $this->db->select('*')
                   ->from('tb_empleado')
                   ->get();
		return $query->result_array();
	}

	function listarDepartamento()
	{
		$sql = "SELECT * FROM tb_departamento";
		$result = $this->db->query($sql);
		return $result;
	}

	function listarContrato()
	{
		$sql = "SELECT * FROM tb_contrato";
		$result = $this->db->query($sql);
		return $result;
	}
}
