<div class="seccion_totalE" id="seccion_totalE">
  <section class="content-header">
      <div class="container-fluid">
          <div class="row">
              <div class="col-sm-8">
                  <h1 class="m-0 text-dark text-bold">Gestión de Empleados
                    <small>Módulo de Mantenimiento</small>
                  </h1>
              </div><!-- /.col -->
              <div class="col-sm-4">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item">
                        <a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a>
                      </li>
                      <li class="breadcrumb-item active">Empleados</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
    <!-- FILTROS ----------------------------------------------------------------------------------------------- -->
      <div class="card shadow-none border">
        <div class="card-body">
          <div class="row mb-12">
            <div class="col-md-1 mt-0">
                <a id="EmBtnAdm" class="btn btn-app btn-lg bg-success EmBtnAdm mt-1 mb-1">
                  <span class="badge bg-purple">
                  <?php
                    echo $empAdministrativos;
                  ?>
                  </span><i class="fas fa-sync-alt mr-1"></i>Administr.
                </a>
            </div>
            <div class="col-md-1 mt-0">
                <a  id="EmBtnHosp" class="btn btn-app btn-lg bg-success EmBtnHosp mt-1 mb-1">
                  <span class="badge bg-purple">
                  <?php
                    echo $empHospital;
                  ?>
                  </span><i class="fas fa-sync-alt mr-1"></i>Hospital
                </a>
            </div>
            <div class="col mt-0"></div>
            <div class="col-md-3 mt-0">
                <label class="label_header mb-1">Lista de opciones:</label>
                <select id="cmb_accionE" name="cmb_accionE" class="select2_busc" style="width: 100%;">
                  <option value="0" selected>-- ninguna --</option>
                  <?php
                    foreach ($opciones_empleado as $key => $row) {
                      echo '<option value="'.$key.'">'.$row.'</option>';
                    }
                  ?>
                </select>
                <a class="text-sm">
                  <span class="description text-sm" >
                    <i class="fa fa-users mr-1"></i>
                      Usuarios seleccionados (
                      <label id="lbl_seleccionados" name="lbl_seleccionados">0
                      </label>)
                  </span>
                </a>
            </div>
            <div class="col-md-1 mt-0">
                <button type="button" id="EmbtnAccion" class="btn btn-outline-success btn-block EmbtnAccion mt-4 mb-0"><i class="fas fa-play mr-1"></i></button>
            </div>
          </div>
        </div><!-- ./card-body -->
      </div><!-- ./card -->
    <!-- ./FILTROS --------------------------------------------------------------------------------------------- -->
    <!-- CUERPO ------------------------------------------------------------------------------------------------ -->
			<div class="card shadow-none border" id="cuerpo_empleado">
        <div class="card-body">
        	<!--div class="row mb-12"-->
										<?php echo $tabla_html;?>
					<!--/div-->
				</div>
			</div>
    <!-- ./CUERPO ---------------------------------------------------------------------------------------------- -->
    </div><!-- /.container-fluid -->
  </section>
</div>
  <!-- VENTANA MODAL ------------------------------------------------------------------------------------------- -->
    <div class="modal fade" id="win_empleado">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
						<input type="hidden" id="empID" class="form-control" placeholder="empID" disabled>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="frm_EditaEmpleado" class="form-horizontal" onsubmit="return false;" >
            <div class="modal-body">
						<div>
								<div class="input-group mb-1">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-user-tie" title="Nombre de Empleado"></i></span>
										</div>
										<input type="text" id="txtNombreEmpleado" class="form-control" placeholder="Empleado" disabled>
								</div>
								<div class="input-group mb-1">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-id-card" title="Documento de Identidad"></i></span>
										</div>
										<input type="text" id="txtdocIdEmpleado" class="form-control" placeholder="Documento de Identidad" disabled>
								</div>
						</div>
            <div class="card card-warning card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Datos Personales</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Departamento</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Turno</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Contrato</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                          <div class="card-body">
                            <div class="row mb-0">
                            <!-- Columnas Menu -->
                                <div class="col-sm-7 border">
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-user-check" title="Nombres del Empleado"></i></span>
                                        </div>
                                        <input type="text" id="txtNombres" class="form-control" placeholder="Nombres" disabled>
                                    </div>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-user-check" title="Apellido Paterno del Empleado"></i></span>
                                        </div>
                                        <input type="text" id="txtPaterno" class="form-control" placeholder="Apellido Paterno" disabled>
                                    </div>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-user-check" title="Apellido Materno del Empleado"></i></span>
                                        </div>
                                        <input type="text" id="txtMaterno" class="form-control" placeholder="Apellido Materno" disabled>
                                    </div>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-id-card" title="Documento de Identidad"></i></span>
                                        </div>
                                        <input type="text" id="txtdocId" class="form-control" placeholder="Documento de Identidad" disabled>
                                    </div>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-birthday-cake" title="Fecha de Nacimiento"></i></span>
                                        </div>
                                        <input type="date" id="txtFecNac" class="form-control" placeholder="Fecha de Nacimiento" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-5 border">
                                  <div class="text-center pt-0 pb-0" id="objFoto">
                                    Realice una Búsqueda
                                  </div>
                                </div>
                            </div>
														<div class="row mb-0 border">
																		<div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-home" title="Domicilio Declarado"></i></span>
                                        </div>
                                        <input type="text" id="txtDomicilio" class="form-control" placeholder="Domicilio Declarado" disabled>
                                    </div>
																		<div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-id-card" title="Referencia Domiciliaria"></i></span>
                                        </div>
                                        <input type="text" id="txtDReferencia" class="form-control" placeholder="Referencia de domicilio" disabled>
                                    </div>
																		<div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-home" title="Domicilio Reniec"></i></span>
                                        </div>
                                        <input type="text" id="txtDReniec" class="form-control" placeholder="Domicilio Reniec" disabled>
                                    </div>

																	<div class="col-sm-6 border">
																			<div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-phone-square" title="Telefono Fijo"></i></span>
                                        </div>
                                        <input type="text" id="txtFijo" class="form-control" placeholder="Telefono Fijo" disabled>
                                    	</div>
                                  </div>

																	<div class="col-sm-6 border">
																			<div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-mobile" title="Celular"></i></span>
                                        </div>
                                        <input type="text" id="txtCel1" class="form-control" placeholder="Celular" disabled>
                                    	</div>
																	</div>

																	<div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-envelope" title="Direccion de Correo 1"></i></span>
                                        </div>
                                        <input type="text" id="txtCorreo1" class="form-control" placeholder="Direccion de Correo 1" disabled>
                                  </div>
																	<div class="input-group mb-1">
																			<div class="input-group-prepend">
																				<span class="input-group-text"><i class="fa fa-envelope" title="Direccion de Correo 2"></i></span>
																			</div>
																			<input type="text" id="txtCorreo2" class="form-control" placeholder="Direccion de Correo 2" disabled>
																	</div>
														</div>
                          </div>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                     Luchis tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                     Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
													<div class="card-body">
                            <div class="row mb-0">
                            <!-- Columnas Menu -->
                                <div class="col-sm-5 border">
                                    <!-- Date -->
                                    <div class="form-group">
                                      <label>Fecha de Contrato:</label>
                                      <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                          </div>
                                          <input type="text" id="txtFecContrato" name="txtFecContrato" class="form-control float-right datepicker" value="">
                                      </div>
                                    </div>
                                    <!-- /.form group -->
                                    <!-- Date -->
                                    <div class="form-group">
                                      <label>Fecha de Nombramiento:</label>
                                      <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                          </div>
                                          <input type="text" id="txtFecNombramiento" name="txtFecNombramiento" class="form-control float-right datepicker" value="">
                                      </div>
                                    </div>
                                </div>
                                <div class="col-sm-7 border">
                                  <div class="form-group">
                                    <label>Resolución de Contrato</label>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-user-tie" title="Resolución de Contrato"></i></span>
                                        </div>
                                        <input type="text" id="txtRContrato" class="form-control" placeholder="Resolución de Contrato" enabled>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Resolución de Nombramiento</label>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-user-tie" title="Resolución de Nombramiento"></i></span>
                                        </div>
                                        <input type="text" id="txtRNombramiento" class="form-control" placeholder="Resolución de Nombramiento" enabled>
                                    </div>
                                  </div>
                                </div>
                            </div>
                          </div>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
            <div class="modal-footer">
              <button type="submit" id="btnAsignaFCont" class="btn btn-primary"><i class="far fa-save mr-1"></i>Guardar</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle mr-1"></i>Salir</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <!-- ./VENTANA MODAL ----------------------------------------------------------------------------------------- -->

<!-- VENTANA MODAL 2------------------------------------------------------------------------------------------- -->
    <div class="modal fade" id="modal_seleccion">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="frm_AsignaDepartamento" class="form-horizontal" onsubmit="return false;" >
            <div class="modal-body">
            <div class="card card-warning card-tabs">
                    <div class="col-md-8">
                        <div class="form-group mt-0 mb-3">
                            <label class="label_header mb-0">SELECCIONE DEPARTAMENTO:</label>
                            <select id="cmb_departamentoE" name="cmb_departamentoE" class="form-control select2_busc" style="width: 100%;">
                                <?php
                                foreach ($departamentos as $row) {
                                    echo '<option value="'.$row["depId"].'">'.$row["depNombre"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
              <!-- /.card -->
            </div>
            <div class="modal-footer">
              <button type="button" id="btnAsignaDepa" class="btn btn-primary"><i class="far fa-save btnAsignaDepa mr-1"></i>Guardar</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle mr-1"></i>Salir</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  <!-- ./VENTANA MODAL 2 ----------------------------------------------------------------------------------------- -->
<!-- VENTANA MODAL 3------------------------------------------------------------------------------------------- -->
    <div class="modal fade" id="modal_contrato">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="frm_AsignaDepartamento" class="form-horizontal" onsubmit="return false;" >
            <div class="modal-body">
            <div class="card card-warning card-tabs">
            <label id="personasSeleccionadas" class="label_header mb-0">SELECCIONE CONTRATO: </label>
              <div class="col-sm-12 border">
                  <ul id="u_arbol_completo_e" class="nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                      <li class="nav-item menu-open" id="arbol_completo_e">
                          <a href="#" class="nav-link hhut_link"><i class="fa fa-sitemap mr-2"></i>
                              <P><?php print_r($nivel_cero) ?></P>
                              <i class="fas fa-angle-left right"></i>
                          </a>
                          <?php echo $niveles ?>
                      </li>
                  </ul>
              </div>
              <!-- /.card -->
            </div>
            <label id="contratoSeleccionado" class="label_header mb-0">Contrato Seleccionado:</label>
            <div class="modal-footer">
              <button type="button" id="btnAsignaCont" class="btn btn-primary"><i class="far fa-save btnAsignaDepa mr-1"></i>Guardar</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle mr-1"></i>Salir</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  <!-- ./VENTANA MODAL 3 ----------------------------------------------------------------------------------------- -->
<script type="text/javascript">
  empleado = {};
  contratoId = 0;
  var table = $('#tabla_completaE').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
			"order": [[ 4, "asc" ]],
    });
  $('#tabla_completaE tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        $("#lbl_seleccionados").text(table.rows('.selected').data().length);
        $("#personasSeleccionadas").text("SELECCIONE CONTRATO: Para ("+table.rows('.selected').data().length+") usuarios seleccionados.");
    });

  function asignarContrato(a,b){
    //$.alert("Seleccionado contrato "+a+" - "+b);
    $("#contratoSeleccionado").text("Contrato Seleccionado: " + b);
    contratoId = a;
    //$("#cmb_departamento").change();
    //console.log($("#cmb_departamento").val());
  }

  $("#btnAsignaDepa").click(function(e){
      //var opcion = $("#cmb_accionE").val();
      var departamento = $("#cmb_departamentoE").val();
      var seleccionados =  JSON.parse(JSON.stringify(table.rows('.selected').data().toArray()));
      //var tamanno =  table.rows('.selected').data().length;
      /*for (k in seleccionados){
        console.log(seleccionados[k][0])
      }*/
      $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'orange',
        title: 'Asignar departamento!',
        content: 'Esta seguro de asignar departamento?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-orange',
            keys: ['enter', 'shift'],
            action: function(){
              $.ajax({
                  url: "empleado/asignarDepartamento",
                  type: "POST",
                  dataType: "html",
                  data: {depId:departamento,empleados:seleccionados},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.res.success){
                      //$.alert("En opcion correcta");
                      $("#modal_seleccion").modal("hide");
                    // $.alert(data.msg);
                    //console.log(data);
                      toastr.success('Asignación de departamento exitoso! ');
                      $("#seccion_totalE").load("empleado/listado");
                    }else{
                      $.alert(data.res.msg);
                    }
                  }
              });
            }
          },
          no: function () {
            toastr.error('Guardado Cancelado! ' + departamento);
            //$.alert('Canceladoooo!');
          }
        }
      })
  });

  $("#btnAsignaFCont").click(function(e){
      //var opcion = $("#cmb_accionE").val();
      var fechaContrato = $("#txtFecContrato").val();
      var fechaNombramiento = $("#txtFecNombramiento").val();
      var resContrato = $("#txtRContrato").val();
      var resNombramiento = $("#txtRNombramiento").val();
      var empId = $("#empID").val();
      console.log( fechaContrato, fechaNombramiento);
      console.log( resContrato, resNombramiento, empId);
      //var seleccionados =  JSON.parse(JSON.stringify(table.rows('.selected').data().toArray()));
      //var tamanno =  table.rows('.selected').data().length;
      /*for (k in seleccionados){
        console.log(seleccionados[k][0])
      }*/
      $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'orange',
        title: 'Actualizar Contrato',
        content: 'Esta seguro de Actualizar Contrato?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-orange',
            keys: ['enter', 'shift'],
            action: function(){
              $.ajax({
                  url: "empleado/actualizarContrato",
                  type: "POST",
                  dataType: "html",
                  data: { fcontrato:fechaContrato,
                          fnombramiento:fechaNombramiento,
                          rcontrato:resContrato,
                          rnombramiento:resNombramiento,
                          empId:empId
                        },
                  success: function(data){
                    data = JSON.parse(data);
                    console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.res.success){
                      //$.alert("En opcion correcta");
                      $("#win_empleado").modal("hide");
                    // $.alert(data.msg);
                    //console.log(data);
                      toastr.success('Actualización de Contrato exitoso! ');
                      $("#seccion_totalE").load("empleado/listado");
                    }else{
                      $.alert(data.res.msg);
                    }
                  }
              });
            }
          },
          no: function () {
            toastr.error('Guardado Cancelado! ');
            //$.alert('Canceladoooo!');
          }
        }
      })
  });

  $("#btnAsignaCont").click(function(e){
      var seleccionados =  JSON.parse(JSON.stringify(table.rows('.selected').data().toArray()));
      /*console.log(contratoId);
      for (k in seleccionados){
        console.log(seleccionados[k][0])
      }*/
      if (contratoId==0){
        $.alert("Debe seleccionar un contrato de la lista.");
      } else {
          $.confirm({
            icon: 'fa fa-question-circle-o',
            animation: 'scale',
            type: 'purple',
            title: 'Asignar Contrato!',
            content: 'Esta seguro de asignar contrato?',
            buttons: {
              si: {
                text: 'Si, estoy seguro',
                btnClass: 'btn-purple',
                keys: ['enter', 'shift'],
                action: function(){
                  $.ajax({
                      url: "empleado/asignarContrato",
                      type: "POST",
                      dataType: "html",
                      data: {contId:contratoId,empleados:seleccionados},
                      success: function(data){
                        data = JSON.parse(data);
                        //console.log(data);
                        if(data=="sesion"){ fncSesionExpirada(); return false; }
                        if(data.res.success){
                          //$.alert("En opcion correcta");
                          $("#modal_contrato").modal("hide");
                          // $.alert(data.msg);
                          //console.log(data);
                          toastr.success('Asignación de contrato exitoso! ');
                          $("#seccion_totalE").load("empleado/listado");
                        }else{
                          $.alert(data.res.msg);
                        }
                      }
                  });
                }
              },
              no: function () {
                toastr.error('Asignación Cancelada!');
                //$.alert('Canceladoooo!');
              }
            }
          })
      }
  });

  $("#EmbtnAccion").click(function(e){
      var opcion = $("#cmb_accionE").val();
      if (table.rows('.selected').data().length!=0){
          if (opcion==4) { //accion: Asignar Departamento
            $(".modal-header").css("background-color", "#106122");
            $(".modal-header").css("color", "white");
            $(".modal-title").text("Asignar Departamento:");
            $("#modal_seleccion").modal("show");
          } else if (opcion==5) { //accion: Asignar Contrato
            $(".modal-header").css("background-color", "#006688");
            $(".modal-header").css("color", "white");
            $(".modal-title").text("Asignar Contrato:");
            $("#modal_contrato").modal("show");
          } else {
            toastr.error('Acción aún no implementada! ' + opcion);
          };
      } else {
        toastr.error('No se han seleccionado empleados! ');
      }
  });

  $("#EmBtnAdm").click(function(e){
      $.post('<?php echo base_url() ?>empleado/sincronizarAdm')
            .done(function(resp){
            })
            .fail(function(err){
                console.log(err);
            });
      $("#seccion_totalE").load("empleado/listado");
      //$.alert("Actualizando la lista de Administrativos");
      toastr.success('Actualizando Personal de Administrativos!');
  });

  $("#EmBtnHosp").click(function(e){
    $.post('<?php echo base_url() ?>empleado/sincronizarHosp')
            .done(function(resp){
            })
            .fail(function(err){
                console.log(err);
            });
      $("#seccion_totalE").load("empleado/listado");
      //$.alert("Actualizando la lista de Administrativos");
      toastr.success('Actualizando Personal del Hospital!');
  });

  $(document).on("click", ".btnEditarEmp", function(){
      fila = $(this).closest("tr");
      let arr =  JSON.parse(fila.find('td:eq(1)').text());
      //console.log(arr);
      $(".modal-header").css("background-color", "#a06122");
      $(".modal-header").css("color", "white");
      $(".modal-title").text("Editar empleado: ");
      $("#empID").val(arr.empId);;
      $("#objFoto").html("");
      $("#txtNombreEmpleado").val(arr.nombres);
      $("#txtdocIdEmpleado").val(arr.documentId);

      if (!(arr.fcontrato==null)) {
        fecha1 = new Date(arr.fcontrato);
				fecha1.setMinutes(fecha1.getMinutes() + fecha1.getTimezoneOffset())
        $("#txtFecContrato").val(fecha1.toLocaleDateString('es-PE'));
				//console.log(fecha1.toLocaleDateString('es-PE'));
      } else $("#txtFecContrato").val('');

      if (!(arr.fnombramiento==null)) {
        fecha2 = new Date(arr.fnombramiento);
				fecha2.setMinutes(fecha2.getMinutes() + fecha2.getTimezoneOffset())
        $("#txtFecNombramiento").val(fecha2.toLocaleDateString('es-PE'));
				//console.log(fecha2.toLocaleDateString());
      } else $("#txtFecNombramiento").val('');

			$("#txtRContrato").val(arr.rcontrato);
      $("#txtRNombramiento").val(arr.rnombramiento);
      //var data = $(".formu").serialize();
            $.post('<?php echo base_url() ?>empleado/buscarDni/'+arr.documentId)
            .done(function(resp){
                var persona =  JSON.parse(resp);
                //$(".resultado").html(resp);
                if($.trim(persona.data.foto)!=""){
                  $("#objFoto").html("");
                  let img = $("<img id='foto'>");
                  img.attr("src", "data:image/jpeg;base64, " + persona.data.foto);
                  img.appendTo("#objFoto");
                }
              //console.log(persona);
							$("#txtNombres").val(persona.data.nombres);
							$("#txtPaterno").val(persona.data.ape_paterno);
							$("#txtMaterno").val(persona.data.ape_materno);
							$("#txtdocId").val(persona.data.num_documento);
							$("#txtFecNac").val(persona.data.fecha_nacimiento);
							$("#txtDomicilio").val(persona.data.direccion_residencia);
							$("#txtDReferencia").val(persona.data.direccion_referencia);
							$("#txtDReniec").val(persona.data.direccion_reniec);
							$("#txtFijo").val(persona.data.fijo);
							$("#txtCel1").val(persona.data.celular1);
							$("#txtCorreo1").val(persona.data.direccion_electronica1);
							$("#txtCorreo2").val(persona.data.direccion_electronica2);
            })
            .fail(function(err){
                //$(".resultado").html(err);
                console.log(err);
            });
      $("#win_empleado").modal("show");
  });

</script>
