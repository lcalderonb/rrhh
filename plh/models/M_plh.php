<?php
class M_plh extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function listarEmpleadosPlh()
	{
		$query  = $this->db->select('*')
                   ->from('v_plh_empleados')
                   ->get();
		return $query->result_array();
	}
}
