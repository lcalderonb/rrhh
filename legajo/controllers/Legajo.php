<?php
class Legajo extends MX_Controller
{
	var $departamentosArray = array();
	var $contratosArray 	= array();
	var $departamentos 		= array();
	var $contratos 			= array();

	function __construct(){ //inicialización de controlador
		parent::__construct();
        $this->load->model('M_legajo','m_legajo');
        $departamentos = $this->m_legajo->listarDepartamento()->result_array();
        $this->contratos = $this->m_legajo->listarContrato()->result_array();
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
        if( !$this->session->userdata('usuario')  ){ redirect(base_url()); }
	}

	function listado()
    {
        /*$rows_html = array();
        $nivel_cero = $this->m_emple->contratoNivel(0);
        $departamentos = $this->m_emple->listarDepartamento();
        $data['departamentos'] = $departamentos->result_array();
        $data['contratos'] = $this->contratos;
        $data['niveles'] = $this->imprimeTree(1);
        $data['nivel_cero'] = $nivel_cero->result_array()[0]['contratoNombre'];
        $data["empAdministrativos"]   =   $this->m_emple->listaAdministrativos()->num_rows();
        $data["empHospital"]          =   $this->m_emple->listaHospital()->num_rows();
        $data["opciones_empleado"] =$this->lista_opciones;*/
        $rows_html = $this->m_legajo->listarEmpleado();
        $data["tabla_html"] =$this->empleado_html($rows_html);
        $data['html_titulo1'] = "Gestión de Empleados";
        $data['html_titulo2'] = "Módulo de Legajo";
        //$data['data'] = $rows_html;
        //$data['data'] = array();
        $this->load->view("v_legajo", $data);
    }

	function empleado_html($rows)
    {
        $html = "";
        if(!empty($rows)){
            //$html .= '<table class="table table-bordered table-sm" id="listaLegajo">';
            $html .= '<thead class="thead-light">';
            $html .= '<tr>';
            $html .= '<th class="d-none">id</th>';
            $html .= '<th>°ID</th>';
            $html .= '<th class="text-center">Nombre Completo</th>';
            $html .= '<th class="text-center">Departamento</th>';
            $html .= '<th class="text-center">Contrato</th>';
            $html .= '<th class="text-center">Activo/Cesado</th>';
            $html .= '<th class="text-center">Fecha Contrato</th>';
            $html .= '<th  class="text-center">Fecha Nombrado</th>';
            $html .= '<th  class="text-center">Record Trabajado</th>';
            $html .= '<th>Opciones</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $contador = 0;
            foreach ($rows as $row) {
                        $contador++;
                        $html .= '<tr>';
                        $html .= '<td class="d-none">'.$row['empId'].'</td>';
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
                        $html .=  '<td class="text-left"><div class="text-normal"><i class="fa fa-calendar-alt"></i>&nbsp  '
                                    .$row['fcontrato'].'</div></td>';

                        $html .=  '<td class="text-left"><div class="text-normal"><i class="fa fa-calendar-alt"></i>&nbsp  '
                                    .$row['fnombramiento'].'</div></td>';

                            $fecha_hoy          =   new DateTime(date("Y-m-d"));

                            if (!(is_null($row['fcontrato']) || empty($row['fcontrato']) || $row['fcontrato'] == '0000-00-00')) {
                                //calcular desde la fecha de contrato
                                $fecha_contrato     = new DateTime($row['fcontrato']);
                                $intct = $fecha_contrato->diff($fecha_hoy);
                                $salidact = $intct->y . "a " . $intct->m."m ".$intct->d."d";
                                $html .=  '<td class="text-left"><div class="text-normal">
                                            <i class="fas fa-clock"></i>&nbsp'.$salidact.'</div></td>';
                            } elseif (!(is_null($row['fnombramiento']) || empty($row['fnombramiento']) || $row['fnombramiento'] == '0000-00-00')) {
                                //calcular desde la fecha de nombramiento
                                $fecha_nombramiento = new DateTime($row['fnombramiento']);
                                $intnb = $fecha_nombramiento->diff($fecha_hoy);
                                $salidanb = $intnb->y . "a " . $intnb->m."m ".$intnb->d."d";
                                $html .=  '<td class="text-left"><div class="text-normal">
                                            <i class="fas fa-clock"></i>&nbsp'.$salidanb.'</div></td>';
                            } else {
                                $html .=  '<td class="text-left"><div class="text-normal">
                                            <i class="fas fa-clock"></i> S/D </div></td>';
                            }

                        $html .= '<td class="text-center">
                                    <button id="btnEditarEmp" type="button" class="btn bg-gradient-primary btn-xs btnEditarEmp mt-1 mb-1"><i class="fas fa-edit"></i> Editar</button>
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
            $html .= '<th>°ID</th>';
            $html .= '<th class="text-center">Nombre Completo</th>';
            $html .= '<th class="text-center">Departamento</th>';
            $html .= '<th class="text-center">Contrato</th>';
            $html .= '<th class="text-center">Activo/Cesado</th>';
            $html .= '<th class="text-center">Fecha Contrato</th>';
            $html .= '<th  class="text-center">Fecha Nombrado</th>';
            $html .= '<th  class="text-center">Record Trabajado</th>';
            $html .= '<th>Opciones</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            /*espacio de llenado de filas*/
            $html .= '</tbody>';
            //$html .= '</table>';
        }
        return $html;
    }
}
