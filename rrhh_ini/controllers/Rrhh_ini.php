<?php
class Rrhh_ini extends MX_Controller
{
	function __construct()
    {
        parent::__construct();
        $this->load->model('M_rrhh_ini','m_rrhh');
        if( !$this->session->userdata('usuario')  ){ redirect(base_url()); }
    }

    function index()
    {
    }

    function horario()
    {
        $rows_html = array();
        $rows_html = $this->m_rrhh->listarHorario();
        $data["tabla_html"] =$this->horarios_html($rows_html);
        $data['txt_Prueba'] = "Modulo de Horario";
        $data['data'] = array();
        $this->load->view("v_horario", $data);
    }

    function horario_tabla()
    {
        $rows_html = array();
        $rows_html = $this->m_rrhh->listarHorario();
        $data["tabla_html"] =$this->horarios_html($rows_html);
        $data['txt_Prueba'] = "Modulo de Horario";
        $data['data'] = array();
        $this->load->view("v_horario_tabla", $data);
    }

    function horarios_html($rows)
    {
        $html = "";
        if(!empty($rows)){
            $html .= '<table class="table table-bordered table-striped table-sm datatable_sm" id="tabla_completa">';
            $html .= '<thead class="thead-light">';
            $html .= '<tr>';
            $html .= '<th class="d-none">id</th>';
            $html .= '<th class="d-none">json</th>';
            $html .= '<th>Horario</th>';
            $html .= '<th class="text-center">H.Entrada/Salida</th>';
            $html .= '<th class="text-center">Ent. Desde/Hasta</th>';
            $html .= '<th class="text-center">Sal. Desde/Hasta</th>';
            $html .= '<th class="text-center">Tolerancias</th>';
            $html .= '<th  class="text-center">Jor. Labor</th>';
            $html .= '<th>Opciones</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $contador = 0;
            foreach ($rows as $row) {
                        $contador++;
                        $html .= '<tr>';
                        $html .= '<td class="d-none">'.$row['id_horario'].'</td>';
                        $html .= '<td class="d-none">'.json_encode($row).'</td>';
                        $html .= '<td bgcolor="'.$row['color'].'" class="text-center text-light">'.$row['nombre'].'</td>';
                        $html .= '<td class="text-center"><div class="text-success"><i class="fas fa-sign-in-alt"></i>  '
                                .substr($row['hora_entrada'],0,5).'</div>
                                <div class="text-danger">';
                        $html .= substr($row['hora_salida'],0,5).'  <i class="fas fa-sign-out-alt"></i></div></td>';
                        $html .= '<td class="text-center text-success" title="Rango de hora en el que se el reloj aceptará como marcación de entrada">
                                <i class="fas fa-user-clock"></i>  '.substr($row['empieza_entrada'],0,5).' - ';
                        $html .= substr($row['termina_entrada'],0,5).'</td>';
                        $html .= '<td class="text-center text-danger" title="Rango de hora en el que se el reloj aceptará como marcación de salida">
                                '.substr($row['empieza_salida'],0,5).' - ';
                        $html .= substr($row['termina_salida'],0,5).'  <i class="fas fa-user-clock"></i></td>';
                        $hora_tardanza = new DateTime(substr($row['hora_entrada'],0,5));
                        $hora_tardanza->modify($row['entrada_tolerancia'].' minute');
                        $hora_tardanza->modify($row['entrada_tardanza'].' minute');
                        $html .= '<td><div class="text-success"><i class="fas fa-clock"></i> Tol. Entrada: '
                                .$row['entrada_tolerancia'].' minutos.</div><div class="text-warning"><i class="fas fa-clock"></i> Acumulable: '
                                .$row['tolerancia_acumulable'].' min/mes</div><div class="text-danger"><i class="fas fa-clock"></i> Tard. después de: ';
                        $html .= $hora_tardanza->format('H:i').'</div></td>';
                        $html .= '<td class="text-center"><div class="text-primary"><i class="fas fa-clock"></i>  '
                                .$row['jornada_laboral'].'horas </div>
                                <div class="text-danger">';
                        $html .= '<td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary btnEditar mt-1 mb-1"><i class="fas fa-edit"></i> Editar</button>
                                    <button type="button" class="btn btn-sm btn-danger btnBorrar mt-1 mb-1"><i class="fas fa-trash-alt"></i> Borrar</button>
                                  </td>';
                        $html .= '</tr>';
                    }
            $html .= '</tbody>';
            $html .= '</table>';
        }else{
            $html .= '<table class="table table-bordered table-striped table-sm datatable_sm">';
            $html .= '<thead class="thead-light">';
            $html .= '<tr>';
            $html .= '<th class="d-none">id_horario</th>';
            $html .= '<th class="d-none">json</th>';
            $html .= '<th>Horario</th>';
            $html .= '<th class="text-center">H.Entrada/Salida</th>';
            $html .= '<th class="text-center">Ent. Desde/Hasta</th>';
            $html .= '<th class="text-center">Sal. Desde/Hasta</th>';
            $html .= '<th class="text-center">Tolerancias</th>';
            $html .= '<th  class="text-center">Jor. Labor</th>';
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

    function guardar()
    {
        $nombre                  = $this->input->post("nombre");
        $hora_entrada            = $this->input->post("hora_entrada");
        $hora_salida             = $this->input->post("hora_salida");
        $empieza_entrada         = $this->input->post("empieza_entrada");
        $termina_entrada         = $this->input->post("termina_entrada");
        $empieza_salida          = $this->input->post("empieza_salida");
        $termina_salida          = $this->input->post("termina_salida");
        $entrada_tolerancia      = $this->input->post("entrada_tolerancia");
        $jornada_laboral         = $this->input->post("jornada_laboral");
        $salida_tolerancia       = $this->input->post("salida_tolerancia");
        $tolerancia_acumulable   = $this->input->post("tolerancia_acumulable");
        $entrada_tardanza        = $this->input->post("entrada_tardanza");
        $color                   = $this->input->post("color");
        //* validar campos ni vacios ni duplicados
            if ($nombre==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Horario está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($this->m_rrhh->contarHorario($nombre)>0){
                $result["success"] = false;
                $result["msg"] = "Nombre Horario ya existe.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Entrada desde está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Empieza salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //! Preparar datos para ingreso a BD
        $data = array(
            'nombre'                => $nombre,
            'hora_entrada'          => $hora_entrada,
            'hora_salida'           => $hora_salida,
            'empieza_entrada'       => $empieza_entrada,
            'termina_entrada'       => $termina_entrada,
            'empieza_salida'        => $empieza_salida,
            'termina_salida'        => $termina_salida,
            'entrada_tolerancia'    => $entrada_tolerancia,
            'jornada_laboral'       => $jornada_laboral,
            'salida_tolerancia'     => $salida_tolerancia,
            'tolerancia_acumulable' => $tolerancia_acumulable,
            'entrada_tardanza'      => $entrada_tardanza,
            'color'                 => $color,
        );
        $result = $this->m_rrhh->crearHorario($data);
        //$result["data"] = $data;
        echo json_encode($result);
    }

    function actualizar()
    {
        $nombre                  = $this->input->post("nombre");
        $hora_entrada            = $this->input->post("hora_entrada");
        $hora_salida             = $this->input->post("hora_salida");
        $empieza_entrada         = $this->input->post("empieza_entrada");
        $termina_entrada         = $this->input->post("termina_entrada");
        $empieza_salida          = $this->input->post("empieza_salida");
        $termina_salida          = $this->input->post("termina_salida");
        $entrada_tolerancia      = $this->input->post("entrada_tolerancia");
        $jornada_laboral         = $this->input->post("jornada_laboral");
        $salida_tolerancia       = $this->input->post("salida_tolerancia");
        $tolerancia_acumulable   = $this->input->post("tolerancia_acumulable");
        $entrada_tardanza        = $this->input->post("entrada_tardanza");
        $color                   = $this->input->post("color");
        //* validar campos ni vacios ni duplicados
            if ($nombre==""){
                $result["success"] = false;
                $result["msg"] = "El campo Nombre Horario está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($hora_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Hora de Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Entrada desde está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_entrada==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Entrada está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($empieza_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Empieza salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
            if ($termina_salida==""){
                $result["success"] = false;
                $result["msg"] = "El campo Termina Salida está vacio.";
                echo json_encode($result);
                exit(0);
            }
        //! Preparar datos para ingreso a BD
        $data = array(
            'hora_entrada'          => $hora_entrada,
            'hora_salida'           => $hora_salida,
            'empieza_entrada'       => $empieza_entrada,
            'termina_entrada'       => $termina_entrada,
            'empieza_salida'        => $empieza_salida,
            'termina_salida'        => $termina_salida,
            'entrada_tolerancia'    => $entrada_tolerancia,
            'jornada_laboral'       => $jornada_laboral,
            'salida_tolerancia'     => $salida_tolerancia,
            'tolerancia_acumulable' => $tolerancia_acumulable,
            'entrada_tardanza'      => $entrada_tardanza,
            'color'                 => $color,
        );
        $result = $this->m_rrhh->actualizaHorario($nombre,$data);
        //$result["data"] = $data;
        echo json_encode($result);
    }

    function borrar()
    {
        $id_horario = $this->input->post("id_horario");
        //! Preparar datos para ingreso a BD
        $result = $this->m_rrhh->desactivaHorario($id_horario);
        //$result["data"] = $data;
        echo json_encode($result);
    }
}
