<?PHP echo $titulo; ?>
<div class="card shadow-none border">
    <?PHP
        print_r('Régimen Laboral encontrados: '.$data);
    ?>
</div>
<div class="card shadow-none border">
    <div class="card-body">
        <div class="row mb-0">
      <!-- Columnas Menu -->
            <!-- Listado de Régimenes -->
            <div class="col-sm-5 border">
                <ul class="nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item menu-open" id="arbol_completo_regimen">
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
                            <label class="label_header mb-0">SELECCIONE RÉGIMEN:</label>
                            <select id="cmb_regimen" name="cmb_regimen" class="form-control select2_busc" style="width: 100%;">
                                <?php
                                foreach ($regimen as $row) {
                                    echo '<option value="'.$row["id_regimen"].'">'.$row["descripcion"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mt-0">
                        <button id="btnAgregarR" type="button" onClick="" class="btn btn-success btnAgregarR mt-1 mb-1"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                    <div class="col-md-2 mt-0">
                        <button id="btnEditarR" type="button" onClick="" class="btn btn-warning btnEditarR mt-1 mb-1"><i class="fas fa-edit"></i> Editar</button>
                    </div>
                    <div class="col-md-1 mt-0">
                        <button id="btnBorrarR" type="button" onClick="eliminarRegimen()" class="btn btn-danger btnBorrarR mt-1 mb-1"><i class="fas fa-trash-alt"></i>  Borrar</button>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col col-lg-20">
                        <div class="form-group mt-1 mb-1">
                            <div class="row mb-1">
                                <label class="col-sm-3 col-form-label bg-blue" for="txtNombreR" >Título Régimen:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="col-sm-9">
                                    <input type="text" id="txtNombreR" name="txtNombreR" placeholder="Escriba aqui el nombre del Contrato ..." class="form-control" autofocus disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-8">
                                <div class="form-group mt-0 mb-2">
                                        <label class="label mb-0">Contrato Dependiente:</label>
                                        <select id="cmb_dependienteR" name="cmb_dependienteR" class="form-control select2_busc" style="width: 100%;" disabled>
                                            <?php
                                            foreach ($regimen as $row) {
                                                echo '<option value="'.$row["id_regimen"].'">'.$row["descripcion"].'</option>';
                                            }
                                            ?>
                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <button id="btnGuardarR" type="button" onClick="" class="btn btn-warning btnGuardarR mt-3 mb-5" disabled><i class="fas fa-save"></i> Guardar</button>
                            <button id="btnCancelarR" type="button" onClick="" class="btn btn-secondary btnCancelarR mt-3 mb-5" disabled><i class="fas fa-window-close"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- --------------------- -->
        </div>
    </div>
</div>

<script type="text/javascript">
  var regimenes = <?php echo json_encode($regimen);?>;
  var estado;
  $(document).on("click", ".btnAgregarR", function(){
    //! habilitar campos de edición, deshabilitar el resto, para futuro guardado
    estado = "agregar";
    document.getElementById('cmb_regimen').disabled = true;
    document.getElementById('btnEditarR').disabled = true;
    document.getElementById('btnBorrarR').disabled = true;
    document.getElementById('btnAgregarR').disabled = true;
    document.getElementById('txtNombreR').value = "";
    document.getElementById('txtNombreR').disabled = false;
    document.getElementById('cmb_dependienteR').disabled = true;
    document.getElementById('btnGuardarR').disabled = false;
    document.getElementById('btnCancelarR').disabled = false;
  });

  $(document).on("click", ".btnEditarR", function(){
    //! habilitar campos de edición, deshabilitar el resto, para futuro guardado
    estado = "editar";
    $("#cmb_regimen").change();
    document.getElementById('cmb_regimen').disabled = true;
    document.getElementById('btnEditarR').disabled = true;
    document.getElementById('btnBorrarR').disabled = true;
    document.getElementById('btnAgregarR').disabled = true;
    //document.getElementById('txtNombre').value = "";
    document.getElementById('txtNombreR').disabled = false;
    //document.getElementById('txtNombreCorto').value = "";
    document.getElementById('cmb_dependienteR').disabled = true;
    document.getElementById('btnGuardarR').disabled = false;
    document.getElementById('btnCancelarR').disabled = false;
  });

  $("#cmb_regimen").change(function(){
        //console.log("Entre a change");
        regimen = regimenes.filter(function(element){
            return element.id_regimen == $("#cmb_regimen").val();
        });
        $("#txtNombreR").val(regimen[0]['descripcion']);
        $("#cmb_dependienteR").val(regimen[0]['SupRegimenId']);
        $("#cmb_dependienteR").change();
  });

  $(document).on("click", ".btnCancelarR", function(){
    //! habilitar campos de edición, deshabilitar el resto, para futuro guardado
    document.getElementById('cmb_regimen').disabled = false;
    document.getElementById('btnEditarR').disabled = false;
    document.getElementById('btnBorrarR').disabled = false;
    document.getElementById('btnAgregarR').disabled = false;
    document.getElementById('txtNombreR').disabled = true;
    document.getElementById('cmb_dependienteR').disabled = true;
    document.getElementById('btnGuardarR').disabled = true;
    document.getElementById('btnCancelarR').disabled = true;
    $("#cmb_regimen").change();
    estado = "";
  });

  $(document).on("click", ".btnGuardarR", function(){
    id_regimen                = $.trim($("#cmb_regimen").val());
    descripcion             	= $.trim($("#txtNombreR").val());
    SupRegimenId              = $.trim($("#cmb_dependienteR").val());
    if(estado == "agregar"){
        $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'blue',
        title: 'Creación de Nuevo Régimen!',
        content: 'Esta seguro de guardar?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-blue',
            keys: ['enter', 'shift'],
            action: function(){
                $.ajax({
                  url: "contratos/guardar_regimen",
                  type: "POST",
                  dataType: "html",
                  data: {descripcion:descripcion,SupRegimenId:SupRegimenId},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                        $("#arbol_completo_regimen").load("contratos/arbol_completo_regimen");
                        $("#cmb_regimen").load("contratos/lista_regimen");
                        $("#cmb_dependienteR").load("contratos/lista_regimen");
                        document.getElementById('cmb_regimen').disabled = false;
                        document.getElementById('btnEditarR').disabled = false;
                        document.getElementById('btnBorrarR').disabled = false;
                        document.getElementById('btnAgregarR').disabled = false;
                        document.getElementById('txtNombreR').disabled = true;
                        document.getElementById('cmb_dependienteR').disabled = true;
                        document.getElementById('btnGuardarR').disabled = true;
                        document.getElementById('btnCancelarR').disabled = true;
                        regimenes = data.regimen;
												//! Falta cargar la variable global regimenes, se sugiere hacerlo llamando un data.regimenes
												//! del modelo que agrega un regimen
                        //$("#cmb_regimen").change();
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
        title: 'Edición de Régimen!',
        content: 'Esta seguro de modificar el registro?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-orange',
            keys: ['enter', 'shift'],
            action: function(){
                $.ajax({
                  url: "contratos/actualizar_regimen",
                  type: "POST",
                  dataType: "html",
                  data: {id_regimen:id_regimen,descripcion:descripcion},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                        $("#arbol_completo_regimen").load("contratos/arbol_completo_regimen");
                        $("#cmb_regimen").load("contratos/lista_regimen");
                        $("#cmb_dependienteR").load("contratos/lista_regimen");
                        document.getElementById('cmb_regimen').disabled = false;
                        document.getElementById('btnEditarR').disabled = false;
                        document.getElementById('btnBorrarR').disabled = false;
                        document.getElementById('btnAgregarR').disabled = false;
                        document.getElementById('txtNombreR').disabled = true;
                        document.getElementById('cmb_dependienteR').disabled = true;
                        document.getElementById('btnGuardarR').disabled = true;
                        document.getElementById('btnCancelarR').disabled = true;
                        $("#cmb_regimen").change();
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

  function eliminarRegimen(){
    estado="borrar";
    id_regimen = $.trim($("#cmb_regimen").val());
    if(estado == "borrar"){
        $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'red',
        title: 'Eliminar Regimen!',
        content: 'Esta seguro de Eliminar?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-red',
            keys: ['enter', 'shift'],
            action: function(){
                $.ajax({
                  url: "contratos/borrar_regimen",
                  type: "POST",
                  dataType: "html",
                  data: {id_regimen:id_regimen},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                        $("#arbol_completo_regimen").load("contratos/arbol_completo_regimen");
                        $("#cmb_regimen").load("contratos/lista_regimen");
                        $("#cmb_dependienteR").load("contratos/lista_regimen");
                        document.getElementById('cmb_regimen').disabled = false;
                        document.getElementById('btnEditarR').disabled = false;
                        document.getElementById('btnBorrarR').disabled = false;
                        document.getElementById('btnAgregarR').disabled = false;
                        document.getElementById('txtNombreR').disabled = true;
                        document.getElementById('cmb_dependienteR').disabled = true;
                        document.getElementById('btnGuardarR').disabled = true;
                        document.getElementById('btnCancelarR').disabled = true;
                        $("#cmb_regimen").change();
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
