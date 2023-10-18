<?php
class Plh extends MX_Controller
{
	var $EmpleadosPlh 			= array();

	function __construct(){ //inicialización de controlador
		parent::__construct();
        $this->load->model('M_plh','m_plh');
        /*$this->contratos = $this->m_legajo->listarContrato()->result_array();
        $this->departamentosArray[null]= array('depId'=>0,
                                                'depNombre'=>'(no asignado)');
        foreach($departamentos as $key => $value){
            $this->departamentosArray[$value['depId']] = $value;
        }
        $this->contratosArray[null]= array('contratoId'=>0,
                                                'contratoNombre'=>'(no asignado)');
        foreach($this->contratos as $key => $value){
            $this->contratosArray[$value['contratoId']] = $value;
        }
        if( !$this->session->userdata('usuario')  ){ redirect(base_url()); } */
	}

	function listatotal()
    {
		$rEmpleadosPlh = array();
		$rEmpleadosPlh = $this->m_plh->listarEmpleadosPlh();
		$data["tabla_html"] =$this->empleado_html($rEmpleadosPlh);
		$this->load->view("v_empleados",$data);
	}

	function empleado_html($rows)
    {
        $html = "";
        if(!empty($rows)){
            $html .= '<table class="table table-bordered table-sm" id="tabla_completaE">';
            $html .= '<thead class="thead-light">';
            $html .= '<tr>';
            $html .= '<th class="d-none">id</th>';
            $html .= '<th class="d-none">jsonAdmin</th>';
            $html .= '<th class="d-none">jsonEmple</th>';
            $html .= '<th>°ID</th>';
            $html .= '<th class="text-center">Nombre Completo</th>';
            $html .= '<th class="text-center">Departamento</th>';
            $html .= '<th class="text-center">Contrato</th>';
            $html .= '<th class="text-center">Activo</th>';
            $html .= '<th class="text-center">Turno</th>';
            $html .= '<th  class="text-center">Admin</th>';
            $html .= '<th  class="text-center">Hospit</th>';
            $html .= '<th>Opciones</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $contador = 0;
            foreach ($rows as $row) {
                        $contador++;
                        $html .= '<tr>';
                        $html .= '<td class="d-none">'.$row['empId'].'</td>';
                        $html .= '<td class="d-none">'.json_encode($row).'</td>';
                        $html .= '<td class="d-none">'.json_encode($row).'</td>';
                        $html .= '<td class="text-center">'.$row['documentId'].'</td>';
                        //$nombres = $this->getDatosXDni($row['documentId']);
                        $html .= '<td class="text-left">'.strtoupper($row['nombres']).'</td>';
                        $html .= '<td class="text-left">'.$this->departamentosArray[$row['depId']]['depNombre'].'</td>';
                        $html .= '<td class="text-center">'.$this->contratosArray[$row['contId']]['contratoNombre'].'</td>';
                        $html .= '<td class="text-center">
                                    <div class="custom-control custom-checkbox">';
                        if ($row['empActivo']==1){
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                        } else {
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">';
                        }
                        $html .= '                <label  class="custom-control-label"></label>
                                    </div>
                                </td>';
                        $html .=  '<td class="text-center"><div class="text-success"><i class="fas fa-sign-in-alt"></i>  '
                                    .substr($row['documentId'],0,5).'</div>
                                    <div class="text-danger">';
                        $html .= substr($row['documentId'],0,5).'  <i class="fas fa-sign-out-alt"></i></div></td>';
                        $html .= '<td class="text-center">
                                    <div class="custom-control custom-checkbox">';
                        if ($row['adm_tb']==1){
                            if ($row['adm_habilitado']==1) {
                                $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            } else {
                                $html .= '<input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            }
                        } else {
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">';
                        }
                        $html .= '                <label  class="custom-control-label"></label>
                                    </div>
                                </td>';
                        $html .= '<td class="text-center">
                                    <div class="custom-control custom-checkbox">';
                        if ($row['hosp_tb']==1){
                            if ($row['hosp_habilitado']==1) {
                                $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            } else {
                                $html .= '<input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="checkbox" id="customCheckbox5" checked>';
                            }
                        } else {
                            $html .= '<input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">';
                        }
                        $html .= '                <label  class="custom-control-label"></label>
                                    </div>
                                </td>';
                        $html .= '<td class="text-center">
                                    <button id="btnEditarEmp" type="button" class="btn bg-gradient-primary btn-xs btnEditarEmp mt-1 mb-1"><i class="fas fa-edit"></i> Editar</button>
                                    <button id="btnBorrarEmp" type="button" class="btn bg-gradient-danger btn-xs btnBorrarEmp mt-1 mb-1"><i class="fas fa-trash-alt"></i> Desactivar</button>
                                  </td>
                                </div>';
                        $html .= '</tr>';
                    }
            $html .= '</tbody>';
            $html .= '</table>';
        }else{
            $html .= '<table class="table table-bordered table-sm" id="tabla_completaE">';
            $html .= '<thead class="thead-light">';
            $html .= '<tr>';
            $html .= '<th class="d-none">id</th>';
            $html .= '<th class="d-none">jsonAdmin</th>';
            $html .= '<th class="d-none">jsonEmple</th>';
            $html .= '<th>°ID</th>';
            $html .= '<th class="text-center">Nombre Completo</th>';
            $html .= '<th class="text-center">Departamento</th>';
            $html .= '<th class="text-center">Contrato</th>';
            $html .= '<th class="text-center">Activo</th>';
            $html .= '<th class="text-center">Turno</th>';
            $html .= '<th  class="text-center">Admin</th>';
            $html .= '<th  class="text-center">Hospit</th>';
            $html .= '<th>Opciones</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            /*espacio de llenado de filas*/
            $html .= '</tbody>';
            $html .= '</table>';
        }
        return $html;
    }
}
