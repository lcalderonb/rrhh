<?php
class Depart extends MX_Controller
{
	function __construct()
    {
        parent::__construct();
        $this->load->model('M_depart','m_dep');
        if( !$this->session->userdata('usuario')  ){ redirect(base_url()); }
    }

    function index()
    {
    }

    function profundidadTree($nivel)
    {
        $departamentos = $this->m_dep->listarDepartamento()->result_array();
        $profundidad=0;
        $bandera=1;
        while ($bandera>0){
            foreach($departamentos as $departamento){
                if ($nivel==$departamento['depId']){
                //print_r($departamento['depId'].' - '.$departamento['SupDepId'].'<br>');
                $profundidad=$profundidad+1;
                $bandera=intval($departamento['SupDepId']);
                }
            }
            $nivel =$bandera;
        }
        return $profundidad;
    }
    function imprimeTree($nivel)
    {
        $html ="";
        $departamentos = $this->m_dep->departamentoNivel($nivel);
        if ($departamentos->num_rows()>0) {
            foreach($departamentos->result_array() as $departamento){
                $html .= '<ul class="nav nav-treeview">
                            <li class="nav-item menu-open"">';
                $html1 = $this->imprimeTree($departamento['depId']);
                $profundidad = $this->profundidadTree($nivel);
                $color = $profundidad*3;
                if ($html1==""){
                    $html .= '  <a href="#" class="nav-link hhut_link"><i class="fa fa-minus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                } else {
                    $html .= '  <a href="#" class="nav-link hhut_link"><i class="fa fa-plus-square nav-icon mr-0 mt-1 text-sm float-left"></i>';
                }
                $html .= '  <span style="color: #00'.$color.'090">'.$departamento['depNombre'].'</span>
                                <i class="fas fa-angle-left right"></i>
                            </a>';
                $html .= $html1;
                $html .= ' </li>
                        </ul>';
            }
        }
        return $html;
    }

    function lista_departamentos()
    {
        $html ="";
        $departamentos = $this->m_dep->listarDepartamento()->result_array();
        $html .='<select id="cmb_departamento" name="cmb_departamento" class="form-control select2_busc" style="width: 100%;">';

        foreach ($departamentos as $row) {
            $html .= '<option value="'.$row["depId"].'">'.$row["depNombre"].'</option>';
        }

        $html .='</select>';
        echo $html;
    }

    function listado()
    {
        $rows_html = array();
        //$departamentos = array();
        $departamentos = $this->m_dep->listarDepartamento();
        $nivel_cero = $this->m_dep->departamentoNivel(0);
        //$rows_html = $this->m_rrhh->listarHorario();
        //$data["tabla_html"] =$this->horarios_html($rows_html);
        $data['titulo'] = "Modulo de Departamentos";
        $data['nivel_cero'] = $nivel_cero->result_array()[0]['depNombre'];
        $data['niveles'] = $this->imprimeTree(1);
        $data['departamentos'] = $departamentos->result_array();
        $data['data'] = $departamentos->num_rows();
        $this->load->view("v_departamentos", $data);
    }

    function arbol_completo()
    {
        $html ="";
        $nivel_cero = $this->m_dep->departamentoNivel(0)->result_array()[0]['depNombre'];
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

    function guardar()
    {
        $depNombre          = $this->input->post("depNombre");
        $depNombreCorto     = $this->input->post("depNombreCorto");
        $SupDepId           = $this->input->post("SupDepId");
        //* validar campos ni vacios ni duplicados
            if ($depNombre==""){
                $result["success"] = false;
                $result["msg"] = "El Nombre de Departamento está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($this->m_dep->contarDepartamento($depNombre)>0){
                $result["success"] = false;
                $result["msg"] = "Nombre Departamento ya existe.";
                echo json_encode($result);
                exit(0);
            }
            if ($depNombreCorto==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Corto está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //! Preparar datos para ingreso a BD
        $data = array(
            'depNombre'          => $depNombre,
            'depNombreCorto'     => $depNombreCorto,
            'SupDepId'           => $SupDepId
        );
        $result = $this->m_dep->crearDepartamento($data);
        //$result["data"] = $data;
        echo json_encode($result);
    }

    function actualizar()
    {
        $depID             = $this->input->post("depId");
        $depNombre         = $this->input->post("depNombre");
        $depNombreCorto    = $this->input->post("depNombreCorto");
        $SupDepId          = $this->input->post("SupDepId");
        //* validar campos ni vacios ni duplicados
            if ($depNombre==""){
                $result["success"] = false;
                $result["msg"] = "El Nombre de Departamento está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($depNombreCorto==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Corto está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //! Preparar datos para ingreso a BD
        $data = array(
            'depNombre'          => $depNombre,
            'depNombreCorto'     => $depNombreCorto,
            'SupDepId'           => $SupDepId
        );
        $result = $this->m_dep->actualizaDepartamento($depID,$data);
        echo json_encode($result);
    }

    function borrar()
    {
        $depId  = $this->input->post("depId");
        $dependientes = $this->m_dep->departamentoNivel($depId);
        //* validar campos ni vacios ni duplicados
            if ($depId=="1"){
                $result["success"] = false;
                $result["msg"] = "Departamento Superior solo es editable.";
                echo json_encode($result);
                exit(0);
            }
            if ($dependientes->num_rows()>0){
                $result["success"] = false;
                $result["msg"] = "Departamento tiene dependientes.";
                echo json_encode($result);
                exit(0);
            }
            //! verificar si tiene dependientes e otras tablas, para ser eleiminado
            /*if ($depNombreCorto==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Corto está vacio.";
                echo json_encode($result);
                exit(0);
            }*/
        //* Eliminando el registro
        $result = $this->m_dep->eliminaDepartamento($depId);
        echo json_encode($result);
    }
}
?>