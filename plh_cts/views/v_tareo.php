<section class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark text-bold">MARCACIÓN MENSUAL<small>Tareo</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a></li><li class="breadcrumb-item active">Marcación Mensual</li>
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
        <div class="row mb-0">
          <div class="col-md-4">
            <div class="form-group mt-1 mb-1">
              <label class="label_header mb-1">NOMBRES Y APELLIDOS:</label>
              <select id="cmb_trabajador" name="cmb_trabajador" class="form-control select2_busc" style="width: 100%;">
                <option value="" selected>-- TODOS --</option>
                <?php
                  foreach ($empleados as $row) {
                    echo '<option value="'.$row["empId"].'">'.$row["nombres"].'</option>';
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mt-1 mb-1">
              <label for="cmbMes" class="label_header mb-1">MES:</label>
              <select id="cmbMes" name="cmbMes" class="form-control">
                <?php
                  foreach ($meses as $row) {
                    $selected = ( $row["cod"] == date("n") ) ? "selected" : "";
                    echo '<option value="'.$row["cod"].'" '.$selected.'>'.$row["desc"].'</option>';
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mt-1 mb-1">
              <label for="cmbAnio" class="label_header mb-1">AÑO:</label>
              <select id="cmbAnio" name="cmbAnio" class="form-control">
                <?php
                  foreach ($anios as $row) {
                    $selected = ( $row["cod"] == date("Y") ) ? "selected" : "";
                    echo '<option value="'.$row["cod"].'" '.$selected.'>'.$row["desc"].'</option>';
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-auto align-self-end">
            <button type="button" onClick="tareo.busqueda()" class="btn btn-lg btn-primary mt-1 mb-1"><i class="fa fa-search mr-1"></i> Buscar</button>
          </div>
        </div>
      </div><!-- ./card-body -->
    </div><!-- ./card -->
  <!-- ./FILTROS --------------------------------------------------------------------------------------------- -->
  <!-- CUERPO ------------------------------------------------------------------------------------------------ -->
    <div class="card shadow-none border">
      <div class="card-body">
        <div class="table-responsive" id="id_lista_inter_asigna">
          <?php echo $tabla_html; ?>
        </div><!-- ./table-responsive -->
      </div><!-- ./card-body -->
    </div><!-- /.card -->
  <!-- ./CUERPO ---------------------------------------------------------------------------------------------- -->
  </div><!-- /.container-fluid -->
</section>
 	<!-- VENTANA MODAL ------------------------------------------------------------------------------------------- -->
	<div class="modal fade" id="win_empleado_tareo">
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
									<a class="nav-link active" id="tab01-Datos-Personales-tab" data-toggle="pill" href="#tab01-Datos-Personales" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Datos Personales</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="tab02-Departamento-tab" data-toggle="pill" href="#tab02-Departamento" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Departamento</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="tab03-Turno-tab" data-toggle="pill" href="#tab03-Turno" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Turno</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="tab04-Contrato-tab" data-toggle="pill" href="#tab04-Contrato" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Régimen y Cond. Laboral</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="custom-tabs-one-tabContent">
								<div class="tab-pane fade show active" id="tab01-Datos-Personales" role="tabpanel" aria-labelledby="tab01-Datos-Personales-tab">
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
								<div class="tab-pane fade" id="tab02-Departamento" role="tabpanel" aria-labelledby="tab02-Departamento-tab">
													<label class="label_header mb-0">SELECCIONE DEPARTAMENTO:</label>
													<select id="cmb_departamento" name="cmb_departamento" class="form-control select2_busc" style="width: 100%;">
														<option value="0" selected>-- Seleccione un Departamento --</option>
														<?php
															foreach ($departamentos as $row) {
																echo '<option value="'.$row["depId"].'" >'.$row["depNombre"].'</option>';
															}
														?>
													</select>
								</div>
								<div class="tab-pane fade" id="tab03-Turno" role="tabpanel" aria-labelledby="tab03-Turno-tab">
													<label class="label_header mb-0">SELECCIONE TURNO:</label>
													<select id="cmb_turno" name="cmb_turno" class="form-control select2_busc" style="width: 100%;">
														<option value="99" selected>-- Seleccione un Turno --</option>
														<?php
															foreach ($turnos as $row) {
																echo '<option value="'.$row["turnoid"].'">'.$row["Descrip"].'</option>';
															}
														?>
													</select>
								</div>
								<div class="tab-pane fade" id="tab04-Contrato" role="tabpanel" aria-labelledby="tab04-Contrato-tab">
												<div class="card-body">
													<div class="row mb-0">
													<!-- Columnas Menu -->
															<div class="col-sm-12 border">
																	<!-- Condicion Laboral -->
																	<div class="form-group">
																		<label class="label_header mb-0">SELECCIONE CONDICION:</label>
																		<select id="cmb_condicion" name="cmb_condicion" class="form-control select2_busc" style="width: 100%;">
																			<option value="99" selected>-- Seleccione una condición --</option>
																			<?php
																				foreach ($condicion as $row) {
																					echo '<option value="'.$row["contratoId"].'">'.$row["contratoNombre"].'</option>';
																				}
																			?>
																		</select>
																	</div>
																	<!-- /.form group -->
																	<!-- Régimen Laboral -->
																	<div class="form-group">
																	<label class="label_header mb-0">SELECCIONE RÉGIMEN:</label>
																		<select id="cmb_regimen" name="cmb_regimen" class="form-control select2_busc" style="width: 100%;">
																			<option value="99" selected>-- Seleccione un régimen --</option>
																			<?php
																				foreach ($regimen as $row) {
																					echo '<option value="'.$row["id_regimen"].'">'.$row["descripcion"].'</option>';
																				}
																			?>
																		</select>
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
						<button type="submit" id="btnAsignaData" class="btn btn-primary"><i class="far fa-save mr-1"></i>Guardar</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle mr-1"></i>Salir</button>
					</div>
				</form>
			</div>
		</div>
	</div>
  <!-- ./VENTANA MODAL ----------------------------------------------------------------------------------------- -->

<script type="text/javascript">
tareo = {};
tareo.busqueda = function()
	{
		if ($("#cmb_trabajador").val()!=""){
			let params = 	{
								idEmpleado: $("#cmb_trabajador").val(),
								mes : $("#cmbMes").val(),
								anio : $("#cmbAnio").val()
								//txtNombres : $("#txtNombres").val(),
							}
			//console.log(params);
			$.ajax({
				type: "POST",
				url: "asistencia/reporte",
				data: params,
				dataType: "html",
				beforeSend: function() {
					if($("#loadMe").length>0){ $("#loadMe").modal("show"); }
				},
				success: function(data){
					if(data.length>0){
						$("#id_lista_inter_asigna").html(data);
						fncDatatable(); fncEfectosBootstrap();
					}
					if($("#loadMe").length>0){ $("#loadMe").modal("hide"); }
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					if($("#loadMe").length>0){ $("#loadMe").modal("hide"); }
				}
			});
		} else {
			alert("Debe seleccionar un empleado!!")
		}
	}
tareo.info = function($anno,$mes,$dia,$id_empleado)
	{
		console.log($anno,$mes,$dia,$id_empleado);
	}
tareo.empleadoUp = function($id_empleado)
  	{
		//console.log($id_empleado);
		let n_dni = $id_empleado;
		$.post('empleado/empleadoxDni',{ dni : n_dni }, function(data){
			let reg = JSON.parse(data);
			console.log(reg);
			if (reg.encontrado) {
				if($.trim(reg.data.foto)!=""){
					$("#objFoto").html("");
					let img = $("<img id='foto'>");
					img.attr("src", "data:image/jpeg;base64, " + reg.data.foto);
					img.appendTo("#objFoto");
				} else {$("#objFoto").html("");}
				$("#txtNombreEmpleado").val(reg.data.nombres + ', ' + reg.data.ape_paterno + ' ' +  reg.data.ape_materno);
				$("#txtdocIdEmpleado").val(reg.data.num_documento);
				$("#txtNombres").val(reg.data.nombres);
				$("#txtPaterno").val(reg.data.ape_paterno);
				$("#txtMaterno").val(reg.data.ape_materno);
				$("#txtFecNac").val(reg.data.fecha_nacimiento);
				$("#txtdocId").val(reg.data.num_documento);
				$("#txtDomicilio").val(reg.data.direccion_residencia);
				$("#txtDReniec").val(reg.data.direccion_reniec);
				$("#txtDReferencia").val(reg.data.direccion_referencia);
				$("#txtFijo").val(reg.data.fijo);
				$("#txtCel1").val(reg.data.celular1);
				$("#txtCorreo1").val(reg.data.direccion_electronica1);
				$("#txtCorreo2").val(reg.data.direccion_electronica2);
				//******* TAB -> DEPARTAMENTO, TURNO, CONDICION Y REG. LAB. *********/
				if ($.trim(reg.data2.depId)!="")		{selectElement('cmb_departamento',reg.data2.depId)} else
														{selectElement('cmb_departamento',0)};
				if ($.trim(reg.data2.turno_id)!="") 	{selectElement('cmb_turno',reg.data2.turno_id)} else
														{selectElement('cmb_turno',99)};
				if ($.trim(reg.data2.contId)!="") 		{selectElement('cmb_condicion',reg.data2.contId)} else
														{selectElement('cmb_condicion',99)};
				if ($.trim(reg.data2.regimenId)!="")	{selectElement('cmb_regimen',reg.data2.regimenId)} else
														{selectElement('cmb_regimen',99)};
			}
		});
		$(".modal-header").css("background-color", "#a06122");
    	$(".modal-header").css("color", "white");
    	$(".modal-title").text("Editar empleado:");
		$("#win_empleado_tareo").modal("show");
	}

$(document).on("click", "#btnAsignaData", function(){ //* ejecuta accion sobre el TAB seleccionado
    //console.log($("#txtdocIdEmpleado").val());
	switch (activeTab){
		case "tab01-Datos-Personales-tab":
			console.log($("#txtdocIdEmpleado").val(),"Actualizando Datos Personales");
			break;
		case "tab02-Departamento-tab":
			$.post('empleado/actualizarDepartamento',{ documentId : $("#txtdocIdEmpleado").val(), depId: $("#cmb_departamento").val()}, function(data){
					let reg1 = JSON.parse(data);
					console.log(reg1);
					if (reg1.res.success) {
						toastr.success(reg1.res.msg);
					} else {
						toastr.error(reg1.res.msg);
					}
				});
			break;
		case "tab03-Turno-tab":
			$.post('empleado/actualizarTurno',{ documentId : $("#txtdocIdEmpleado").val(), turno_id: $("#cmb_turno").val()}, function(data){
					let reg1 = JSON.parse(data);
					console.log(reg1);
					if (reg1.res.success) {
						toastr.success(reg1.res.msg);
					} else {
						toastr.error(reg1.res.msg);
					}
				});
			break;
		case "tab04-Contrato-tab":
			console.log($("#txtdocIdEmpleado").val(),"Actualizando Contrato");
			$.post(	'empleado/actualizarLaboral',
					{ 	documentId 	: $("#txtdocIdEmpleado").val(),
						contId		: $("#cmb_condicion").val(),
						regimenId	: $("#cmb_regimen").val()
					}, function(data){
					let reg1 = JSON.parse(data);
					console.log(reg1);
					if (reg1.res.success) {
						toastr.success(reg1.res.msg);
					} else {
						toastr.error(reg1.res.msg);
					}
				});
			break;
	}

})

var activeTab = 'tab01-Datos-Personales-tab';  //* Define el TAB seleccionado o activo
$("#custom-tabs-one-tab").on('shown.bs.tab', function (e) {
	//show selected tab / active
	activeTab = $(e.target).attr('id') ;
});

function selectElement(id, valueToSelect) {
    let element = document.getElementById(id);
    element.value = valueToSelect;
	element.dispatchEvent(new Event('change'))
}
</script>
