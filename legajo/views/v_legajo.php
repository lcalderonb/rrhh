  <!-- Content Wrapper. Contains page content
  <div class="content-wrapper">-->
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
				<div class="row">
              <div class="col-sm-8">
                  <h1 class="m-0 text-dark text-bold"><?php echo $html_titulo1 ?>
                    <small><?php echo $html_titulo2 ?></small>
                  </h1>
              </div><!-- /.col -->
              <div class="col-sm-4">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item">
                        <a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a>
                      </li>
                      <li class="breadcrumb-item active">Legajo</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
								<table class="table table-bordered table-sm" id="listaLegajo">
			  					<?php echo $tabla_html;?>
								</table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content-->
	<script type="text/javascript">
	var table = $("#listaLegajo").DataTable({
      "responsive": true,
			//"paging": true,
			"lengthChange": false,
			"autoWidth": false,
			"buttons": ["excel", "pdf", "print", "colvis"],
			"order": [[ 2, "asc" ]],
    }).buttons().container().appendTo('#listaLegajo_wrapper .col-md-6:eq(0)');
  /*$(function () {
    $("#listaLegajo").DataTable({
      "responsive": true,
			//"paging": true,
			"lengthChange": false,
			"autoWidth": false,
      "buttons": ["excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#listaEmpleado_wrapper .col-md-6:eq(0)');
  });*/
</script>
