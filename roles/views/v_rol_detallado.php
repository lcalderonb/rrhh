<!-- ENCABEZADO ----------------------------------------------------------------------------------------------- -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="m-0 text-dark text-bold">Programación de Turnos y Guardias del Servicio Asistencial</h1>
					<h1 class="m-0 text-dark ">[Año: <?php echo $anno?>][Mes: <?php echo $mes?>]</h1>
					<p class="m-0 text-dark text-bold">[DEPARTAMENTO: <?php echo strtoupper($departamento['depNombre'])?>]</p>
					<p class="m-0 text-dark text-bold">[SERVICIO: <?php echo strtoupper($servicio['depNombre'])?>]</p>
				</div><!-- /.col -->
				<div class="col-sm-4">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a></li>
						<li class="breadcrumb-item"><a href="#" onclick="cargar_formulario_params('roles/listado');">Roles y Guardias</a></li>
						<li id="claveRol" class="breadcrumb-item active"><?php echo $claveRol?></li>
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
							<div class="col-md-auto align-self-end">
								<a onclick="rol_detalle.guardar_rol()" class="btn btn-app bg-blue">
									<i class="fas fa-save"></i> Guardar Rol
								</a>
							</div>
							<div class="col-md-auto align-self-end">
								<a class="btn btn-app bg-warning">
									<i class="fas fa-user-check"></i> Supervisar
								</a>
							</div>
							<div class="col-md-auto align-self-end">
								<a class="btn btn-app bg-warning disabled" >
									<i class="fas fa-user-check"></i> Autorizar
								</a>
							</div>
							<div class="col-md-auto align-self-end">
								<a href="roles/print_rol_pdf/<?php echo $claveRol?>" class="btn btn-app bg-secondary" target="_blank">
									<i class="fas fa-print"></i> Imprimir Rol
								</a>
							</div>
							<div class="col-md-auto align-self-end">
								<a class="btn btn-app bg-success">
									<i class="fas fa-clone"></i> Clonar Anterior
								</a>
							</div>
						</div>
					</div><!-- ./card-body -->
				</div><!-- ./card -->
			<!-- ./FILTROS ----------------------------------------------------------------------------------------------->
			<!-- CUERPO ------------------------------------------------------------------------------------------------ -->
				<div class="card shadow-none border">
					<div class="card-body">
						<div class="table-responsive" id="tabla_rol_detalle">
							<?php echo $html_rol_detalle; ?>
						</div><!-- ./table-responsive -->
					</div><!-- ./card-body -->
				</div><!-- /.card -->
				<div class="card shadow-none border">
					<div class="card-body">
						<small>
							<div class="table-responsive" id="tabla_rol_detalle_footer">
								LEYENDA DE HORARIOS:<br>
								<?php echo $html_leyenda; ?>
							</div><!-- ./card-body -->
						</small>
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
	var pclaverol = "";
	var pempleado = "";
	var pnombres = "";
	var fila = new Array();
	//*  alerta de abandono de página
		var bPreguntar = true;
		window.onbeforeunload = preguntarAntesDeSalir;
		function preguntarAntesDeSalir()
		{
			if (bPreguntar)
			return "¿Seguro que quieres salir?";
		};
	//*  ./alerta de abandono de página
	rol_detalle = {};
	rol_detalle.imprimir_rol = function ($claveRol) {
		console.log("------>"+$claveRol);
		cargar_formulario_params('roles/print_rol_pdf/20230301701801002');
	}
	rol_detalle.guardar_rol = function()
	{
		var tabla_rol = document.getElementById('tabla_completaE');
		var claveRol = $("#claveRol").text();
		const fila_rol = new Array();
		const rol_general = new Array();
		const rol_arreglo = new Array();
		paño = claveRol.toString().substr(0,4);
		pmes = claveRol.toString().substr(4,2);
		dias_max = rol_detalle.diasEnUnMes(pmes,paño);
		rol_arreglo[0] = claveRol;
		for (let i=1; i<= tabla_rol.rows.length-1; i++)
		{
			lista_dni = tabla_rol.rows[i].id;
			rol_arreglo[1] = lista_dni
			for (y = 1; y <= dias_max; y++)
			{
				boton_renueva = '#'+lista_dni+'_'+y;
				fila_rol[y] = $(boton_renueva).text().trim()!=''?$(boton_renueva).text().trim():'-1';
			};
			rol_arreglo[2] = [].concat(fila_rol);
			rol_general[i-1] = [].concat(rol_arreglo);
		}
		//console.log("Iniciando! Calculo de Roles...",rol_general);
		$.post('roles/GuardarRoles',
				{ 	Rol_General		: rol_general,
				},
				function(data)
				{
					let reg1 = JSON.parse(data);
					if (reg1.success) {
						// boton_actual.innerHTML = reg1.nombre;
						// boton_actual.title = reg1.descripcion;
						// boton_actual.style.backgroundColor= reg1.color;
						toastr.success(reg1.msg);
						//console.log(reg1);
					} else {
								//toastr.error(reg1.res.msg);
								$(document).Toasts('create', {
														class: 'bg-danger',
														title: 'Alerta',
														subtitle: 'Guardado de Guardias',
														body: "Error en Guardado de Guardias"
													})
							}
				}
			);
		//console.log("Prueba de Salida en guardado");
	}
	rol_detalle.programar = function(claverol,empleado,nombres,programa)
	{
		boton_renueva = '#'+empleado+'_'+programa;
		var boton_actual = document.getElementById(empleado+'_'+programa);
		$.post('roles/tipoProgramaRol',
				{ 	programaActual		: $(boton_renueva).text(),
					claverol			: claverol
				},
				function(data)
				{
					let reg1 = JSON.parse(data);
					if (reg1) {
						boton_actual.innerHTML = reg1.nombre;
						boton_actual.title = reg1.descripcion;
						boton_actual.style.backgroundColor= reg1.color;
						//console.log(reg1);
					} else {
								//toastr.error(reg1.res.msg);
								$(document).Toasts('create', {
														class: 'bg-danger',
														title: 'Alerta',
														subtitle: 'Error en programación de Guardias',
														body: "Error en programación de Guardias"
													})
							}
				}
			);
		/*console.log(	claverol,
						empleado,
						programa,
						boton_actual.innerText);*/
		fila[0]=empleado;
		fila[programa] = $(boton_renueva).text();
		pclaverol = claverol;
		pempleado = empleado;
		pnombres = nombres;
	};
	rol_detalle.diasEnUnMes = function diasEnUnMes(mes, año)
	{
		return new Date(año, mes, 0).getDate();
	};
	$(".tableRow").on("mouseleave", function (e)
						{
							if (pclaverol != "")
							{
								paño = pclaverol.toString().substr(0,4);
								pmes = pclaverol.toString().substr(4,2);
								dias_max = rol_detalle.diasEnUnMes(pmes,paño);
								for (i = 1; i <= dias_max; i++)
								{
									boton_renueva = '#'+pempleado+'_'+i;
									fila[i] = $(boton_renueva).text().trim();
								}
								//console.log("Iniciando! Calculo de Roles...",pclaverol,pempleado,dias_max,fila);
								var boton_total = document.getElementById(pempleado+'_T');
								if (pclaverol!="" && pempleado!="")
								{
									$.post('roles/calculaFila',
										{ 	claveRol		: pclaverol,
											empleado		: pempleado,
											nombres			: pnombres,
											programacion	: fila
										},
										function(data)
										{
											let reg2 = JSON.parse(data);
											//console.log(reg2);
											if (reg2.res.success) {
												//toastr.success(reg1.res.msg);
												//$('.duallistbox').val(reg1.res.data);
												//$('.duallistbox').bootstrapDualListbox('refresh');
												boton_total.innerHTML = reg2.res.data;
												//console.log(reg2);
												//"<font size=1>M: 5</font><br><font size=1>T: 5</font><br><font size=1>N: 5</font><br><font size=1>GD: 5</font><br><font size=1>LXO: 1</font><br><strong><font size=1>TOTAL: 150H</font></strong>"
											} else {
												//toastr.error(reg1.res.msg);
												$(document).Toasts('create', {
																		class: 'bg-danger',
																		title: 'Alerta',
																		subtitle: 'Programación de Guardias',
																		body: reg1.res.msg
																	})
											}
										})
								//$("#"+pempleado).html('<td style="text-align: center;vertical-align:middle;width:145px; height: 70px;"><small>CALDERON</small><br>47753741</td><td><button id="47753741_1" style="	width:30px;height: 70px;" type="button" onclick="rol_detalle.programar(20230200801604108,`47753741`,1)" class="btn btn-block btn-outline-secondary btn-sm btn-xs">M</button></td>');
								}
								pclaverol = "";
								pempleado = "";
								pnombres = "";
								fila = [];
							} /*else {
								console.log("Sin variables para Calculo de Roles");
							}*/
						})
</script>
