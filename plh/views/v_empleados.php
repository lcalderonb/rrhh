<div class="seccion_totalE" id="seccion_totalE">
  <section class="content-header">
      <div class="container-fluid">
          <div class="row">
              <div class="col-sm-8">
                  <h1 class="m-0 text-dark text-bold">EMPLEADOS PLH
                    <small>Cargados desde Remuneraciones</small>
                  </h1>
              </div><!-- /.col -->
              <div class="col-sm-4">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item">
                        <a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a>
                      </li>
                      <li class="breadcrumb-item active">Empleados PLH</li>
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
          </div>
        </div><!-- ./card-body -->
      </div><!-- ./card -->
    <!-- ./FILTROS --------------------------------------------------------------------------------------------- -->
    <!-- CUERPO ------------------------------------------------------------------------------------------------ -->
			<div class="card shadow-none border" id="cuerpo_empleado">
        <div class="card-body">
        	<!--div class="row mb-12"-->
										<!-- <?php echo $tabla_html;?> -->
					<!--/div-->
					<?php print_r($EmpleadosPlh);?>
				</div>
			</div>
    <!-- ./CUERPO ---------------------------------------------------------------------------------------------- -->
    </div><!-- /.container-fluid -->
  </section>
</div>
