<!-- ENCABEZADO ----------------------------------------------------------------------------------------------- -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="m-0 text-dark text-bold">Programación de Turnos y Guardias del Servicio Asistencial<small>[Año <?php echo $Anno?>]</small></h1>
				</div><!-- /.col -->
				<div class="col-sm-4">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a></li><li class="breadcrumb-item active">Roles y Guardias</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
<!-- ./ENCABEZADO ----------------------------------------------------------------------------------------------- -->
<!-- BODY ----------------------------------------------------------------------------------------------- -->
	<section class="content">
		<div class="container-fluid">
			<!-- FILTROS ------------------------------------------------------------------------------------------------->
				<div class="card shadow-none border">
					<div class="card-body">
						<div class="row mb-0">
							<div class="col-md-2">
								<div class="form-group mt-1 mb-1">
									<label class="label_header mb-1">DEPARTAMENTO:</label>
									<select id="cmb_rol_departamento" name="cmb_rol_departamento" class="form-control select2_busc" style="width: 100%;">
										<option value="" selected>-- Seleccione Depart --</option>
										<?php
											foreach ($departamentos as $row) {
												echo '<option value="'.$row["depId"].'">'.$row["depNombre"].'</option>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group mt-1 mb-1">
										<label class="label_header mb-1">SERVICIO:</label>
										<select id="cmb_rol_servicio" name="cmb_rol_servicio" class="form-control select2_busc" style="width: 100%;">
											<option value="" selected>-- Seleccione Serv --</option>
											<?php
												foreach ($servicios as $row) {
													echo '<option value="'.$row["depId"].'">'.$row["depNombre"].'</option>';
												}
											?>
										</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group mt-1 mb-1">
									<label for="cmbMesRol" class="label_header mb-1">MES:</label>
									<select id="cmbMesRol" name="cmbMesRol" class="form-control">
										<?php
											foreach ($meses as $row) {
												$selected = ( $row["cod"] == date("n") ) ? "selected" : "";
												echo '<option value="'.$row["cod"].'" '.$selected.'>'.$row["desc"].'</option>';
											}
											?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group mt-1 mb-1">
									<label for="cmbAnioRol" class="label_header mb-1">AÑO:</label>
									<select id="cmbAnioRol" name="cmbAnioRol" class="form-control">
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
								<button type="button" onClick="roles.busqueda()" class="btn btn-lg btn-primary mt-1 mb-1"><i class="fa fa-search mr-1"></i> Buscar</button>
							</div>
							<div class="col-md-auto align-self-end">
								<button type="button" onClick="roles.nuevo()" class="btn btn-lg btn-primary mt-1 mb-1"><i class="fa fa-plus mr-1"></i> Nuevo...</button>
							</div>
						</div>
					</div><!-- ./card-body -->
				</div><!-- ./card -->
			<!-- ./FILTROS ----------------------------------------------------------------------------------------------->
			 <!-- CUERPO ------------------------------------------------------------------------------------------------ -->
				<div class="card shadow-none border">
					<div class="card-body">
						<div class="table-responsive" id="table_roles">
							<?php echo $tabla_rol_html; ?>
						</div><!-- ./table-responsive -->
					</div><!-- ./card-body -->
				</div><!-- /.card -->
			<!-- ./CUERPO ---------------------------------------------------------------------------------------------- -->
		</div><!-- /.container-fluid -->
	</section> <!-- /.content-->
<!-- ./BODY ------------------------------------------------------------------------------------------------->
<!-- MODAL ------------------------------------------------------------------------------------------------->
	<div class="modal fade" id="modal-empleados">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Nuevo Rol:</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label>Seleccione los Empleados que corresponden al Rol:</label>
									<select class="duallistbox" multiple="multiple">
									<?php
												foreach ($empleados as $row) {
													echo '<option value="'.$row["documentId"].'">'.$row["nombres"].'</option>';
												}
									?>
									</select>
								</div>
								<!-- /.form-group -->
								<div class="row mb-0">
									<div class="col-md-6">
										<div class="form-group mt-1 mb-1">
											<label class="label_header mb-1">SELECCIONE TIPO DE CARGO<small>(*)</small>:</label>
											<select id="cmb_cargo_rol" name="cmb_cargo_rol" class="form-control select2_busc" style="width: 100%;">
												<option value="" selected>-- Ninguno --</option>
												<?php
													foreach ($profesiones as $row) {
														echo '<option value="'.$row["id_profesion"].'">'.$row["des_profesion"].'</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group mt-1 mb-1">
											<label class="label_header mb-1">SELECCIONE TIPO DE CONTRATO<small>(*)</small>:</label>
											<select id="cmb_contrato_rol" name="cmb_contrato_rol" class="form-control select2_busc" style="width: 100%;">
												<option value="" selected>-- Ninguno --</option>
												<?php
													foreach ($contratos as $row) {
														echo '<option value="'.$row["contratoId"].'">'.$row["contratoNombre"].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row mb-0">
									<div class="col-md-6">
										<div class="form-group mt-1 mb-1">
											<label class="label_header mb-1">SELECCIONE TIPO DE TURNO<small>(*)</small>:</label>
											<select id="cmb_turno_rol" name="cmb_turno_rol" class="form-control select2_busc" style="width: 100%;">
												<option value="" selected>-- Ninguno --</option>
												<?php
													foreach ($turnos_rol as $row) {
														echo '<option value="'.$row["turnoId"].'">'.$row["Descripcion"].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
              				<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.card-body -->
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" id="btnGenerarRol" class="btn btn-primary">Generar Rol</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
<!-- ./MODAL ------------------------------------------------------------------------------------------------->
<script type="text/javascript">
	roles = {};
	roles.nuevo = function()
	{
		if ($("#cmb_rol_departamento").val()!="" && $("#cmb_rol_servicio").val() !="")
		{
			console.log(	$("#cmb_rol_departamento").val(),
							$("#cmb_rol_servicio").val(),
							$("#cmbMesRol").val(),
							$("#cmbAnioRol").val(),
						);
			console.log($('.duallistbox').val().length , $('.duallistbox').val());
			$(".modal-title").text("Nuevo Rol:["+$("#cmbAnioRol").val()+"]["+$("#cmbMesRol").val()+"]");
			$(".modal-header").css("background-color", "#000588");
      		$(".modal-header").css("color", "white");
			$("#modal-empleados").modal("show");
		} else {
			$.confirm({
				icon: 'fa fa-exclamation-circle',
				animation: 'scale',
				type: 'red',
				title: 'Error Encontrado!',
				content: 'Debe seleccionar el Departamento y el Servicio, para generar Rol',
				buttons: {
							tryAgain: {
									text: 'Volver a intentar!',
									btnClass: 'btn-red',
									action: function(){
											}
							},
						}
			})
		}
	}
	roles.busqueda = function()
	{
		$("#loadMe").modal("show")
		$.post('roles/listado_roles',
				{ 	anno 			: $("#cmbAnioRol").val(),
					mes				: $("#cmbMesRol").val(),
					departamento	: $("#cmb_rol_departamento").val(),
					servicio		: $("#cmb_rol_servicio").val(),
				},
				function(data)
				{
					let reg1 = JSON.parse(data);
					//console.log(reg1);
					if (reg1.res.success) {
						$("#table_roles").html(reg1.res.tabla_rol_html);
						$("#loadMe").modal("hide");
						//console.log(reg1.res.tabla_rol_html);
						//console.log(reg1.res.rows_roles);
						//toastr.success(reg1.res.msg);
					} else {
						//toastr.error(reg1.res.msg);
						$(document).Toasts('create', {
												class: 'bg-danger',
												title: 'Alerta',
												subtitle: 'Listado de Roles fallido.',
												body: reg1.res.msg
											})
					}
				}
			);
	}
	//Bootstrap Duallistbox
	$('.duallistbox').bootstrapDualListbox({ //* activación del control
	});

	$(".duallistbox").change(function(){
		document.getElementById("btnGenerarRol").disabled = false;
	});

	$("#btnGenerarRol").click(function(e){ //*Botón de Generar Rol
		if ($('.duallistbox').val().length > 0 && $("#cmb_contrato_rol").val()!="" && $("#cmb_cargo_rol").val() !="")
		{
			switch (document.getElementById("btnGenerarRol").textContent) {
				case "Generar Rol":  //* Generar Rol NUEVO
					console.log($('.duallistbox').val().length , $('.duallistbox').val());
					$("#modal-empleados").modal("hide");
					$.post('roles/nuevoRol',
						{ 	anno 			: $("#cmbAnioRol").val(),
							mes				: $("#cmbMesRol").val(),
							departamento	: $("#cmb_rol_departamento").val(),
							servicio		: $("#cmb_rol_servicio").val(),
							cargo			: $("#cmb_cargo_rol").val(),
							contrato		: $("#cmb_contrato_rol").val(),
							turno			: $("#cmb_turno_rol").val(),
							empleados_rol	: $('.duallistbox').val(),
						},
						function(data)
						{
							let reg1 = JSON.parse(data);
							console.log(reg1);
							if (reg1.res.success) {
								toastr.success(reg1.res.msg);
								$('.duallistbox').val(Array())
								$('.duallistbox').bootstrapDualListbox('refresh')
							} else {
								//toastr.error(reg1.res.msg);
								$(document).Toasts('create', {
														class: 'bg-danger',
														title: 'Alerta',
														subtitle: 'Selección de Empleados',
														body: reg1.res.msg
													})
							}
						}
					);
					break;
				case "Modificar Rol": //*SECCION DE MODIFICACION DE DATOS
					//console.log('pruebas de Modificar Rol');
					$.confirm({
						icon: 'fa fa-question-circle-o',
						animation: 'scale',
						type: 'orange',
						title: 'Modificando Rol!',
						content: 'Si modifica la lista de empleados, se limipará la programacion de cada uno de ellos. Desea proseguir?',
						buttons: {
							si: {
								text: 'Si, estoy seguro',
								btnClass: 'btn-orange',
								keys: ['enter', 'shift'],
								action: function(){
									$("#modal-empleados").modal("hide");
									$.post('roles/modificaRol',
									{ 	anno 			: $("#cmbAnioRol").val(),
										mes				: $("#cmbMesRol").val(),
										departamento	: $("#cmb_rol_departamento").val(),
										servicio		: $("#cmb_rol_servicio").val(),
										cargo			: $("#cmb_cargo_rol").val(),
										contrato		: $("#cmb_contrato_rol").val(),
										empleados_rol	: $('.duallistbox').val(),
									},
									function(data)
									{
										let reg1 = JSON.parse(data);
										console.log(reg1);
										if (reg1.res.success) {
											toastr.success(reg1.res.msg);
											$('.duallistbox').val(Array())
											$('.duallistbox').bootstrapDualListbox('refresh')
										} else {
											//toastr.error(reg1.res.msg);
											$(document).Toasts('create', {
																	class: 'bg-danger',
																	title: 'Alerta',
																	subtitle: 'Selección de Empleados',
																	body: reg1.res.msg
																})
										}
									});
								}
							},
							no: function () {
								toastr.error('Modificación cancelada! ');
								//$.alert('Canceladoooo!');
							}
						}
					});
					break;
			}
		} else { //* al no haberse seleccionado empleados ...
			$.confirm({
				icon: 'fa fa-exclamation-circle',
				animation: 'scale',
				type: 'red',
				title: 'Error Encontrado!',
				content: 'No se puede generar el rol. Debe seleccionar al menos un empleado, el cargo y contrato en común',
				buttons: {
							tryAgain: {
									text: 'Volver a intentar!',
									btnClass: 'btn-red',
									action: function(){
											}
							},
						}
			})
		}
	});

	/*$(document).on("click", ".btnVerRol", function(){
		fila = $(this).closest("tr");
      	let arr =  fila.find('td:eq(1)').text().trim().substr(0,17);
		console.log('VER ROL ->',arr);
		$.get('roles/listado',
				{ 	claveRol		: arr,
				},
				function(data)
				{
					// let reg1 = JSON.parse(data);
					// //console.log(reg1);
					// if (reg1.res.success) {
					// 	//toastr.success(reg1.res.msg);
					// 	$('.duallistbox').val(reg1.res.data);
					// 	$('.duallistbox').bootstrapDualListbox('refresh');
					// } else {
					// 	//toastr.error(reg1.res.msg);
					// 	$(document).Toasts('create', {
					// 							class: 'bg-danger',
					// 							title: 'Alerta',
					// 							subtitle: 'Selección de Empleados',
					// 							body: reg1.res.msg
					// 						})
					// }
				}
			);
	});*/

	$(document).on("click", ".btnEditarRol", function(){
		fila = $(this).closest("tr");
      	let arr =  fila.find('td:eq(1)').text().trim().substr(0,17);
		//console.log('EDITAR ROL ->',arr);
		$(".modal-title").text("Edita Rol:["+arr.substr(0,4)+"]["+arr.substr(4,2)+"]");
		$(".modal-header").css("background-color", "#a06122");
      	$(".modal-header").css("color", "white");
		document.getElementById("btnGenerarRol").textContent = 'Modificar Rol';
		document.getElementById("btnGenerarRol").setAttribute('class','btn btn-warning');
		document.getElementById("btnGenerarRol").disabled = true;
		$("#modal-empleados").modal("show");
		$("#cmb_cargo_rol").val(parseInt(arr.substr(12,3) ,10)).trigger('change');
		document.getElementById("cmb_cargo_rol").disabled = true;
		$("#cmb_contrato_rol").val(parseInt(arr.substr(15,2) ,10)).trigger('change');
		document.getElementById("cmb_contrato_rol").disabled = true;

		$.post('roles/miembrosRol',
				{ 	claveRol		: arr,
				},
				function(data)
				{
					let reg1 = JSON.parse(data);
					//console.log(reg1);
					if (reg1.res.success) {
						//toastr.success(reg1.res.msg);
						$('.duallistbox').val(reg1.res.data);
						$('.duallistbox').bootstrapDualListbox('refresh');
					} else {
						//toastr.error(reg1.res.msg);
						$(document).Toasts('create', {
												class: 'bg-danger',
												title: 'Alerta',
												subtitle: 'Selección de Empleados',
												body: reg1.res.msg
											})
					}
					// if (reg1.res.success) {
					// 	toastr.success(reg1.res.msg);
					// } else {
					// 	//toastr.error(reg1.res.msg);
					// 	$(document).Toasts('create', {
					// 							class: 'bg-danger',
					// 							title: 'Alerta',
					// 							subtitle: 'Selección de Empleados',
					// 							body: reg1.res.msg
					// 						})
					// }
				}
			);
	});

	$(document).on("click", ".btnSubirRol", function(){
		fila = $(this).closest("tr");
      	let arr =  fila.find('td:eq(1)').text().trim().substr(0,17);
		console.log('SUBIR ROL ->',arr);
	});

	$(document).on("click", ".btnSupervisarRol", function(){
		fila = $(this).closest("tr");
      	let arr =  fila.find('td:eq(1)').text().trim().substr(0,17);
		console.log('SUPERVISAR ROL ->',arr);
	});

	$(document).on("click", ".btnAutorizarRol", function(){
		fila = $(this).closest("tr");
      	let arr =  fila.find('td:eq(1)').text().trim().substr(0,17);
		console.log('AUTORIZAR ROL ->',arr);
	});

	$(document).on("click", ".btnAnularRol", function(){
		fila = $(this).closest("tr");
      	let arr =  fila.find('td:eq(1)').text().trim().substr(0,17);
		console.log('ANULAR ROL ->',arr);
	});
</script>
