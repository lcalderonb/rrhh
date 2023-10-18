<?php
class Contratos extends MX_Controller
{
	function __construct()
    {
        parent::__construct();
        $this->load->model('M_contratos','m_contrato');
        if( !$this->session->userdata('usuario')  ){ redirect(base_url()); }
    }

    function index()
    {
    }

    function profundidadTree($nivel)
    {
        $contratos = $this->m_contrato->listarContrato()->result_array();
        $profundidad=0;
        $bandera=1;
        while ($bandera>0){
            foreach($contratos as $contrato){
                if ($nivel==$contrato['contratoId']){
                //print_r($contrato['contratoId'].' - '.$contrato['SupContratoId'].'<br>');
                $profundidad=$profundidad+1;
                $bandera=intval($contrato['SupContratoId']);
                }
            }
            $nivel =$bandera;
        }
        return $profundidad;
    }

    function imprimeTree($nivel)
    {
        $html ="";
        $contratos = $this->m_contrato->contratoNivel($nivel);
        if ($contratos->num_rows()>0) {
            foreach($contratos->result_array() as $contrato){
                $html .= '<ul class="nav nav-treeview">
                            <li class="nav-item menu-open"">';
                $html1 = $this->imprimeTree($contrato['contratoId']);
                $profundidad = $this->profundidadTree($nivel);
                $color = $profundidad*3;
                if ($html1==""){
                    $html .= '  <a href="#" class="nav-link hhut_link"><i class="fa fa-minus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                } else {
                    $html .= '  <a href="#" class="nav-link hhut_link"><i class="fa fa-plus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                }
                $html .= '  <span style="color: #00'.$color.'090">'.$contrato['contratoNombre'].'</span>
                                <i class="fas fa-angle-left right"></i>
                            </a>';
                $html .= $html1;
                $html .= ' </li>
                        </ul>';
            }
        }
        return $html;
    }

    function profundidadTreeR($nivel)
    {
        $regimen = $this->m_contrato->listarRegimen()->result_array();
        $profundidad=0;
        $bandera=1;
        while ($bandera>0){
            foreach($regimen as $row){
                if ($nivel==$row['id_regimen']){
                //print_r($contrato['contratoId'].' - '.$contrato['SupContratoId'].'<br>');
                $profundidad=$profundidad+1;
                $bandera=intval($row['SupRegimenId']);
                }
            }
            $nivel =$bandera;
        }
        return $profundidad;
    }

    function imprimeTreeR($nivel)
    {
        $html ="";
        $regimen = $this->m_contrato->regimenNivel($nivel);
        if ($regimen->num_rows()>0) {
            foreach($regimen->result_array() as $row){
                $html .= '<ul class="nav nav-treeview">
                            <li class="nav-item menu-open"">';
                $html1 = $this->imprimeTree($row['id_regimen']);
                $profundidad = $this->profundidadTreeR($nivel);
                $color = $profundidad*3;
                if ($html1==""){
                    $html .= '  <a href="#" class="nav-link hhut_link"><i class="fa fa-minus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                } else {
                    $html .= '  <a href="#" class="nav-link hhut_link"><i class="fa fa-plus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                }
                $html .= '  <span style="color: #00'.$color.'090">'.$row['descripcion'].'</span>
                                <i class="fas fa-angle-left right"></i>
                            </a>';
                $html .= $html1;
                $html .= ' </li>
                        </ul>';
            }
        }
        return $html;
    }

    function lista_contratos()
    {
        $html ="";
        $contratos = $this->m_contrato->listarContrato()->result_array();
        $html .='<select id="cmb_departamento" name="cmb_departamento" class="form-control select2_busc" style="width: 100%;">';

        foreach ($contratos as $row) {
            $html .= '<option value="'.$row["contratoId"].'">'.$row["contratoNombre"].'</option>';
        }

        $html .='</select>';
        echo $html;
    }

    function lista_regimen()
    {
        $html ="";
        $regimen = $this->m_contrato->listarRegimen()->result_array();
        $html .='<select id="cmb_regimen" name="cmb_regimen" class="form-control select2_busc" style="width: 100%;">';

        foreach ($regimen as $row) {
            $html .= '<option value="'.$row["id_regimen"].'">'.$row["descripcion"].'</option>';
        }

        $html .='</select>';
        echo $html;
    }

    function listado()
    {
        $rows_html = array();
        //$contratos = array();
        $contratos = $this->m_contrato->listarContrato();
        $nivel_cero = $this->m_contrato->contratoNivel(0);
        //$rows_html = $this->m_rrhh->listarHorario();
        //$data["tabla_html"] =$this->horarios_html($rows_html);
        $data['titulo'] = "Módulo de Condición Laboral";
        $data['nivel_cero'] = $nivel_cero->result_array()[0]['contratoNombre'];
        $data['niveles'] = $this->imprimeTree(1);
        $data['contratos'] = $contratos->result_array();
        $data['data'] = $contratos->num_rows();
        $this->load->view("v_contratos", $data);
    }

    function listado2()
    {
        $rows_html = array();
        //$contratos = array();
        $regimen = $this->m_contrato->listarRegimen();
        $nivel_cero = $this->m_contrato->regimenNivel(0);
        //$rows_html = $this->m_rrhh->listarHorario();
        //$data["tabla_html"] =$this->horarios_html($rows_html);
        $data['titulo'] = "Módulo de Régimen Laboral";
        $data['nivel_cero'] = $nivel_cero->result_array()[0]['descripcion'];
        $data['niveles'] = $this->imprimeTreeR(1);
        $data['regimen'] = $regimen->result_array();
        $data['data'] = $regimen->num_rows();
        $this->load->view("v_contratos2", $data);
    }

    function arbol_completo()
    {
        $html ="";
        $nivel_cero = $this->m_contrato->contratoNivel(0)->result_array()[0]['contratoNombre'];
        $arbol_completo = $this->imprimeTree(1);
        $html .='<li class="nav-item menu-open" id="arbol_completo">';
        $html .='   <a href="#" class="nav-link hhut_link"><i class="fa fa-sitemap mr-2"></i>';
        $html .='       <P>'.$nivel_cero.'</P>';
        $html .='       <i class="fas fa-angle-left right"></i>';
        $html .='   </a>';
        $html .=$arbol_completo;
        $html .='</li>';
        echo $html;
    }

    function arbol_completo_regimen()
    {
        $html ="";
        $nivel_cero = $this->m_contrato->regimenNivel(0)->result_array()[0]['descripcion'];
        $arbol_completo = $this->imprimeTreeR(1);
        $html .='<li class="nav-item menu-open" id="arbol_completo_regimen">';
        $html .='   <a href="#" class="nav-link hhut_link"><i class="fa fa-sitemap mr-2"></i>';
        $html .='       <P>'.$nivel_cero.'</P>';
        $html .='       <i class="fas fa-angle-left right"></i>';
        $html .='   </a>';
        $html .=$arbol_completo;
        $html .='</li>';
        echo $html;
    }

    function guardar()
    {
        $contratoNombre          = $this->input->post("contratoNombre");
        $contratoNombreCorto     = $this->input->post("contratoNombreCorto");
        $SupContratoId           = $this->input->post("SupContratoId");
        //* validar campos ni vacios ni duplicados
            if ($contratoNombre==""){
                $result["success"] = false;
                $result["msg"] = "El Nombre de Departamento está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($this->m_contrato->contarContrato($contratoNombre)>0){
                $result["success"] = false;
                $result["msg"] = "Nombre Departamento ya existe.";
                echo json_encode($result);
                exit(0);
            }
            if ($contratoNombreCorto==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Corto está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //! Preparar datos para ingreso a BD
        $data = array(
            'contratoNombre'          => $contratoNombre,
            'contratoNombreCorto'     => $contratoNombreCorto,
            'SupContratoId'           => $SupContratoId
        );
        $result = $this->m_contrato->crearContrato($data);
        //$result["data"] = $data;
        echo json_encode($result);
    }

    function guardar_regimen()
    {
        $descripcion          = $this->input->post("descripcion");
        $SupRegimenId           = $this->input->post("SupRegimenId");
        //* validar campos ni vacios ni duplicados
            if ($descripcion==""){
                $result["success"] = false;
                $result["msg"] = "El Nombre del Régimen está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($this->m_contrato->contarRegimen($descripcion)>0){
                $result["success"] = false;
                $result["msg"] = "Nombre Régimen ya existe.";
                echo json_encode($result);
                exit(0);
            }
        //* Preparar datos para ingreso a BD
        $data = array(
            'descripcion'          => $descripcion,
            'SupRegimenId'           => $SupRegimenId
        );
        $result = $this->m_contrato->crearRegimen($data);
		$regimen = $this->m_contrato->listarRegimen();
		$result['regimen'] = $regimen->result_array();
        //$result["data"] = $data;
        echo json_encode($result);
    }

    function actualizar()
    {
        $contratoId             = $this->input->post("contratoId");
        $contratoNombre         = $this->input->post("contratoNombre");
        $contratoNombreCorto    = $this->input->post("contratoNombreCorto");
        $SupContratoId          = $this->input->post("SupContratoId");
        //* validar campos ni vacios ni duplicados
            if ($contratoNombre==""){
                $result["success"] = false;
                $result["msg"] = "El Nombre de Departamento está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($contratoNombreCorto==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Corto está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //* Preparar datos para ingreso a BD
        $data = array(
            'contratoNombre'          => $contratoNombre,
            'contratoNombreCorto'     => $contratoNombreCorto,
            'SupContratoId'           => $SupContratoId
        );
        $result = $this->m_contrato->actualizaContrato($contratoId,$data);
        echo json_encode($result);
    }

    function actualizar_regimen()
    {
        $id_regimen             = $this->input->post("id_regimen");
        $descripcion         	= $this->input->post("descripcion");
        //* validar campos ni vacios ni duplicados
            if ($descripcion==""){
                $result["success"] = false;
                $result["msg"] = "El Nombre de Régimen está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //* Preparar datos para ingreso a BD
        $data = array(
            'descripcion'          => $descripcion,
        );
        $result = $this->m_contrato->actualizaRegimen($id_regimen,$data);
        echo json_encode($result);
    }

    function borrar()
    {
        $contratoId  = $this->input->post("contratoId");
        $dependientes = $this->m_contrato->contratoNivel($contratoId);
        //* validar campos ni vacios ni duplicados
            if ($contratoId=="1"){
                $result["success"] = false;
                $result["msg"] = "Contrato Superior solo es editable.";
                echo json_encode($result);
                exit(0);
            }
            if ($dependientes->num_rows()>0){
                $result["success"] = false;
                $result["msg"] = "Contrato tiene dependientes.";
                echo json_encode($result);
                exit(0);
            }
			//! verificar si tiene dependientes e otras tablas, para ser eleiminado
			//! incluido en tabla tb_emppleado
            /*if ($contratoNombreCorto==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Corto está vacio.";
                echo json_encode($result);
                exit(0);
            }*/
        //* Eliminando el registro
        $result = $this->m_contrato->eliminaContrato($contratoId);
        echo json_encode($result);
    }

    function borrar_regimen()
    {
        $id_regimen  = $this->input->post("id_regimen");
        //* validar campos ni vacios ni duplicados
            if ($id_regimen=="1"){
                $result["success"] = false;
                $result["msg"] = "Este nivel solo es editable.";
                echo json_encode($result);
                exit(0);
            }
			//! verificar si tiene dependientes e otras tablas, para ser eleiminado
			//! incluido en tabla tb_emppleado
        //* Eliminando el registro
        $result = $this->m_contrato->eliminaRegimen($id_regimen);
        echo json_encode($result);
    }
}
?>
