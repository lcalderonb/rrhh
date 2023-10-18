<?PHP echo $titulo; ?>
<div class="card shadow-none border">
    <?PHP
        print_r('Departamentos encontrados: '.$data);
    ?>
</div>
<div class="card shadow-none border">
    <div class="card-body">
        <div class="row mb-0">
      <!-- Columnas Menu -->
            <!-- Listado de Departamentos -->
            <div class="col-sm-5 border">
                <ul class="nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item menu-open" id="arbol_completo">
                        <a href="#" class="nav-link hhut_link"><i class="fa fa-sitemap mr-2"></i>
                            <P><?php print_r($nivel_cero) ?></P>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <?php echo $niveles ?>
                    </li>
                </ul>
            </div>
            <!-- --------------------- -->
            <!-- Listado de Menus de Edicion y Borrado -->
            <div class="col-sm-6 border">
                <div class="row mb-0">
                    <div class="col-md-6">
                        <div class="form-group mt-0 mb-3">
                            <label class="label_header mb-0">SELECCIONE DEPARTAMENTO:</label>
                            <select id="cmb_departamento" name="cmb_departamento" class="form-control select2_busc" style="width: 100%;">
                                <?php
                                foreach ($departamentos as $row) {
                                    echo '<option value="'.$row["depId"].'">'.$row["depNombre"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mt-0">
                        <button id="btnAgregar" type="button" onClick="" class="btn btn-success btnAgregar mt-1 mb-1"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                    <div class="col-md-2 mt-0">
                        <button id="btnEditarD" type="button" onClick="" class="btn btn-warning btnEditarD mt-1 mb-1"><i class="fas fa-edit"></i> Editar</button>
                    </div>
                    <div class="col-md-1 mt-0">
                        <button id="btnBorrarD" type="button" onClick="eliminarDepartamento()" class="btn btn-danger btnBorrarD mt-1 mb-1"><i class="fas fa-trash-alt"></i>  Borrar</button>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col col-lg-20">
                        <div class="form-group mt-1 mb-1">
                            <div class="row mb-1">
                                <label class="col-sm-3 col-form-label bg-blue" for="txtNombre" >Nombre Dpto:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="col-sm-9">
                                    <input type="text" id="txtNombre" name="txtNombre" placeholder="Escriba aqui el nombre del departamento/área ..." class="form-control" autofocus disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label class="col-sm-3 col-form-label bg-blue" for="txtNombreCorto" >Nombre Corto:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="col-sm-9">
                                    <input type="text" id="txtNombreCorto" name="txtNombreCorto" placeholder="Escriba aqui el nombre corto ..." class="form-control" autofocus disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-8">
                                <div class="form-group mt-0 mb-2">
                                        <label class="label mb-0">Departamento Dependiente:</label>
                                        <select id="cmb_dependiente" name="cmb_dependiente" class="form-control select2_busc" style="width: 100%;" disabled>
                                            <?php
                                            foreach ($departamentos as $row) {
                                                echo '<option value="'.$row["depId"].'">'.$row["depNombre"].'</option>';
                                            }
                                            ?>
                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <button id="btnGuardarD" type="button" onClick="" class="btn btn-warning btnGuardarD mt-3 mb-5" disabled><i class="fas fa-save"></i> Guardar</button>
                            <button id="btnCancelarD" type="button" onClick="" class="btn btn-secondary btnCancelarD mt-3 mb-5" disabled><i class="fas fa-window-close"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- --------------------- -->
        </div>
    </div>
</div>

<script type="text/javascript">
  var departamentos = <?php echo json_encode($departamentos);?>;
  var estado;
  $(document).on("click", ".btnAgregar", function(){
    //! habilitar campos de edición, deshabilitar el resto, para futuro guardado
    estado = "agregar";
    document.getElementById('cmb_departamento').disabled = true;
    document.getElementById('btnEditarD').disabled = true;
    document.getElementById('btnBorrarD').disabled = true;
    document.getElementById('btnAgregar').disabled = true;
    document.getElementById('txtNombre').value = "";
    document.getElementById('txtNombre').disabled = false;
    document.getElementById('txtNombreCorto').value = "";
    document.getElementById('txtNombreCorto').disabled = false;
    document.getElementById('cmb_dependiente').disabled = false;
    document.getElementById('btnGuardarD').disabled = false;
    document.getElementById('btnCancelarD').disabled = false;
  });

  $(document).on("click", ".btnEditarD", function(){
    //! habilitar campos de edición, deshabilitar el resto, para futuro guardado
    estado = "editar";
    $("#cmb_departamento").change();
    document.getElementById('cmb_departamento').disabled = true;
    document.getElementById('btnEditarD').disabled = true;
    document.getElementById('btnBorrarD').disabled = true;
    document.getElementById('btnAgregar').disabled = true;
    //document.getElementById('txtNombre').value = "";
    document.getElementById('txtNombre').disabled = false;
    //document.getElementById('txtNombreCorto').value = "";
    document.getElementById('txtNombreCorto').disabled = false;
    document.getElementById('cmb_dependiente').disabled = false;
    document.getElementById('btnGuardarD').disabled = false;
    document.getElementById('btnCancelarD').disabled = false;
  });

  $("#cmb_departamento").change(function(){
        //console.log("Entre a change");
        departamento = departamentos.filter(function(element){
            return element.depId == $("#cmb_departamento").val();
        });
        $("#txtNombre").val(departamento[0]['depNombre']);
        $("#txtNombreCorto").val(departamento[0]['depNombreCorto']);
        $("#cmb_dependiente").val(departamento[0]['SupDepId']);
        $("#cmb_dependiente").change();
  });

  $(document).on("click", ".btnCancelarD", function(){
    //! habilitar campos de edición, deshabilitar el resto, para futuro guardado
    document.getElementById('cmb_departamento').disabled = false;
    document.getElementById('btnEditarD').disabled = false;
    document.getElementById('btnBorrarD').disabled = false;
    document.getElementById('btnAgregar').disabled = false;
    document.getElementById('txtNombre').disabled = true;
    document.getElementById('txtNombreCorto').disabled = true;
    document.getElementById('cmb_dependiente').disabled = true;
    document.getElementById('btnGuardarD').disabled = true;
    document.getElementById('btnCancelarD').disabled = true;
    $("#cmb_departamento").change();
    estado = "";
  });

  $(document).on("click", ".btnGuardarD", function(){
    depId                 = $.trim($("#cmb_departamento").val());
    depNombre             = $.trim($("#txtNombre").val());
    depNombreCorto        = $.trim($("#txtNombreCorto").val());
    SupDepId              = $.trim($("#cmb_dependiente").val());
    if(estado == "agregar"){
        $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'blue',
        title: 'Creación de Nuevo Departamento!',
        content: 'Esta seguro de guardar?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-blue',
            keys: ['enter', 'shift'],
            action: function(){
                $.ajax({
                  url: "depart/guardar",
                  type: "POST",
                  dataType: "html",
                  data: {depNombre:depNombre,depNombreCorto:depNombreCorto,SupDepId:SupDepId},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                        $("#arbol_completo").load("depart/arbol_completo");
                        $("#cmb_departamento").load("depart/lista_departamentos");
                        $("#cmb_dependiente").load("depart/lista_departamentos");
                        document.getElementById('cmb_departamento').disabled = false;
                        document.getElementById('btnEditarD').disabled = false;
                        document.getElementById('btnBorrarD').disabled = false;
                        document.getElementById('btnAgregar').disabled = false;
                        document.getElementById('txtNombre').disabled = true;
                        document.getElementById('txtNombreCorto').disabled = true;
                        document.getElementById('cmb_dependiente').disabled = true;
                        document.getElementById('btnGuardarD').disabled = true;
                        document.getElementById('btnCancelarD').disabled = true;
                        $("#cmb_departamento").change();
                    }else{
                      $.alert(data.msg);
                    }
                  }
                });
            }
          },
          no: function () {
            $.alert('Cancelado!');
          }
        }
      });
    estado = "";
    };
    if(estado == "editar"){
        $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'orange',
        title: 'Edición de Departamento!',
        content: 'Esta seguro de modificar el registro?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-orange',
            keys: ['enter', 'shift'],
            action: function(){
                $.ajax({
                  url: "depart/actualizar",
                  type: "POST",
                  dataType: "html",
                  data: {depId:depId,depNombre:depNombre,depNombreCorto:depNombreCorto,SupDepId:SupDepId},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                        $("#arbol_completo").load("depart/arbol_completo");
                        $("#cmb_departamento").load("depart/lista_departamentos");
                        $("#cmb_dependiente").load("depart/lista_departamentos");
                        document.getElementById('cmb_departamento').disabled = false;
                        document.getElementById('btnEditarD').disabled = false;
                        document.getElementById('btnBorrarD').disabled = false;
                        document.getElementById('btnAgregar').disabled = false;
                        document.getElementById('txtNombre').disabled = true;
                        document.getElementById('txtNombreCorto').disabled = true;
                        document.getElementById('cmb_dependiente').disabled = true;
                        document.getElementById('btnGuardarD').disabled = true;
                        document.getElementById('btnCancelarD').disabled = true;
                        $("#cmb_departamento").change();
                    }else{
                      $.alert(data.msg);
                    }
                  }
                });
            }
          },
          no: function () {
            $.alert('Cancelado!');
          }
        }
      });
    estado = "";
    };
    return false;
  });

  function eliminarDepartamento(){
    estado="borrar";
    depId = $.trim($("#cmb_departamento").val());
    if(estado == "borrar"){
        $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'red',
        title: 'Eliminar Departamento!',
        content: 'Esta seguro de Eliminar?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-red',
            keys: ['enter', 'shift'],
            action: function(){
                $.ajax({
                  url: "depart/borrar",
                  type: "POST",
                  dataType: "html",
                  data: {depId:depId},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                        $("#arbol_completo").load("depart/arbol_completo");
                        $("#cmb_departamento").load("depart/lista_departamentos");
                        $("#cmb_dependiente").load("depart/lista_departamentos");
                        document.getElementById('cmb_departamento').disabled = false;
                        document.getElementById('btnEditarD').disabled = false;
                        document.getElementById('btnBorrarD').disabled = false;
                        document.getElementById('btnAgregar').disabled = false;
                        document.getElementById('txtNombre').disabled = true;
                        document.getElementById('txtNombreCorto').disabled = true;
                        document.getElementById('cmb_dependiente').disabled = true;
                        document.getElementById('btnGuardarD').disabled = true;
                        document.getElementById('btnCancelarD').disabled = true;
                        $("#cmb_departamento").change();
                    }else{
                      $.alert(data.msg);
                    }
                  }
                });
            }
          },
          no: function () {
            $.alert('Cancelado!');
          }
        }
      });
    estado = "";
    };
  };

</script>