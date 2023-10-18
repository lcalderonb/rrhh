<section class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark text-bold">ASISTENCIA ADMINISTRATIVOS<small>Consultar Asistencia</small></h1>
        <h1 class="m-0 text-dark text-bold"><small>Ultima Actualización: <?php echo $actualizacion; ?></small></h1>
      </div><!-- /.col -->
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a></li><li class="breadcrumb-item active">Marcación</li>
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
                  foreach ($trabajadores as $row) {
                    echo '<option value="'.$row["dni"].'">'.$row["nombres"].'</option>';
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mt-1 mb-1">
              <label for="txtFecInicio" class="label_header mb-1">FECHA DE INICIO:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="txtFecInicio" name="txtFecInicio" class="form-control float-right datepicker" value="<?php echo date("d/m/Y"); ?>">
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mt-1 mb-1">
              <label for="txtFecFin" class="label_header mb-1">FECHA DE FIN:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="txtFecFin" name="txtFecFin" class="form-control float-right datepicker" value="<?php echo date("d/m/Y"); ?>">
              </div>
            </div>
          </div>
          <div class="col-md-auto align-self-end">
            <button type="button" onClick="asistencia.busqueda()" class="btn btn-lg btn-primary mt-1 mb-1"><i class="fa fa-search mr-1"></i> Buscar</button>
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
<script type="text/javascript">
  asistencia = {};
  asistencia.busqueda = function()
  {
    let params ={
                  txtFecFin : $("#txtFecFin").val(),
                  txtFecInicio : $("#txtFecInicio").val(),
                  dni : $("#cmb_trabajador").val(),
                }
    $.ajax({
      type: "POST",
      url: "asistenciar/busqueda",
      data: params,
      dataType: "html",
      beforeSend: function() {
        if($("#loadMe").length>0){ $("#loadMe").modal("show"); }
      },
      success: function(data){
        if(data.length>0){
          $("#id_lista_inter_asigna").html(data);
          fncDatatable();
        }
        if($("#loadMe").length>0){ $("#loadMe").modal("hide"); }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        if($("#loadMe").length>0){ $("#loadMe").modal("hide"); }
      }
    });
  }
</script>