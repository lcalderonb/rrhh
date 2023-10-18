<section class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-8">
        <h1 class="m-0 text-dark text-bold">REPORTE CTS<small>BD PLH</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a></li><li class="breadcrumb-item active">PLH Reporte PLH</li>
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
              <label class="label_header mb-1">APELLIDOS Y NOMBRES:</label>
              <select id="cmb_trabajador" name="cmb_trabajador" class="form-control select2_busc" style="width: 100%;">
                <option value="" selected>-- TODOS --</option>
                <?php
                  foreach ($trabajadores as $row) {
                    echo '<option value="'.$row["LIBELE"].'">'.$row["NOMBRE"].'</option>';
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mt-1 mb-1">
              <label for="txtFecInicio" class="label_header mb-1">FECHA DE CESE:</label>
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
              <label for="txtFecFin" class="label_header mb-1">ULTMOS # MESES:</label>
              <div class="input-group col-md-7">
                    <button type="button" class="btn btn-danger btn-number"  data-type="minus" data-field="in_meses">
                      <span class="fas fa-minus"></span>
                    </button>
                <input type="text" id="in_meses" name="in_meses" class="form-control input-number" value="36" min="36" max="60">
                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="in_meses">
                        <span class="fas fa-plus"></span>
                    </button>
              </div>
            </div>
          </div>
          <div class="col-md-auto align-self-end">
            <button type="button" onClick="asistencia.busqueda()" class="btn btn-lg btn-primary mt-1 mb-1"><i class="fa fa-search mr-1"></i> Buscar</button>
          </div>
        </div>
				<div id="info_trabajador" name="info_trabajador" class="callout callout-info">
				Ningún empleado ha sido seleccionado.
        </div>
      </div><!-- ./card-body -->
    </div><!-- ./card -->
  <!-- ./FILTROS --------------------------------------------------------------------------------------------- -->
  <!-- CUERPO ------------------------------------------------------------------------------------------------ -->
    <div class="card shadow-none border">
      <div class="card-body">
        <div class="table-responsive" id="id_list_remuneraciones">
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
					url: "plh_cts/busqueda",
					data: params,
					dataType: "html",
					beforeSend: function() {
						if($("#loadMe").length>0){ $("#loadMe").modal("show"); }
					},
					success: function(data){
						var datos =  JSON.parse(data);
						//console.log(datos.res_html.length);
						if(datos.res_html.length>0){
							$("#id_list_remuneraciones").html(datos.res_html);
							fncDatatable();
							$("#info_trabajador").html(datos.det_html);
							//console.log(datos.det_html);
					 }
					if($("#loadMe").length>0){ $("#loadMe").modal("hide"); }
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					if($("#loadMe").length>0){ $("#loadMe").modal("hide"); }
				}
			});
  }
	$('.btn-number').click(function(e){
    e.preventDefault();
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }
        }
    } else {
        input.val(0);
    }
	});
	$('.input-number').change(function() {
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }
	});
</script>
