<div class="seccion_total" id="seccion_total">
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark text-bold">HORARIOS<small>Mantenimiento de Horarios</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#" onclick="fncCargarMenuPrincipal(this)">Principal</a></li><li class="breadcrumb-item active">Horarios</li>
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
        <div class="col-md-auto align-self-end">
            <button type="button" class="btn btn-lg btn-primary btnCrear mt-1 mb-1"><i class="fas fa-money-check mr-1"></i> Crear Horario</button>
          </div>
        </div>
      </div><!-- ./card-body -->
    </div><!-- ./card -->
  <!-- ./FILTROS --------------------------------------------------------------------------------------------- -->
  <!-- CUERPO ------------------------------------------------------------------------------------------------ -->
    <div class="card shadow-none border">
      <div class="card-body">
        <div class="table-responsive" id="id_lista_inter_asigna">
          <?php echo $tabla_html;?>
        </div><!-- ./table-responsive -->
      </div><!-- ./card-body -->
    </div><!-- /.card -->
  <!-- ./CUERPO ---------------------------------------------------------------------------------------------- -->
  </div><!-- /.container-fluid -->
</section>
<!-- VENTANA MODAL ------------------------------------------------------------------------------------------- -->
<div class="modal fade" id="win_horario">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="frm_CreaHorario" class="form-horizontal" onsubmit="return false;" >
          <div class="modal-body">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label bg-blue" for="txtNombre" >Nombre Horario : </label>
              <div class="col-sm-9">
                <input type="text" id="txtNombre" name="txtNombre" placeholder="Escriba aqui el nombre..." class="form-control" autofocus enable>
              </div>
            </div><hr>
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label bg-green" for="txtHora_Entrada">Hora de Entrada :</label>
              <div class="col-sm-3">
                <input type="time" id="txtHora_Entrada" name="txtHora_Entrada" class="form-control" title="Hora de entrada establecida." enabled>
              </div>
              <label class="col-sm-3 col-form-label bg-red" for="txtHora_Salida">Hora de Salida :</label>
              <div class="col-sm-3">
                <input type="time" id="txtHora_Salida" name="txtHora_Salida" class="form-control" title="Hora de salida establecida." enabled>
              </div>
            </div><hr>
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label bg-green" for="txtEmpieza_Entrada">Entrada desde :</label>
              <div class="col-sm-3">
                <input type="time" id="txtEmpieza_Entrada" name="txtEmpieza_Entrada" class="form-control" title="Se establece hora que se considerará como entrada, antes de la hora de entrada establecida." enabled>
              </div>
              <label class="col-sm-3 col-form-label bg-red"" for="txtEmpieza_Salida">Salida desde :</label>
              <div class="col-sm-3">
                <input type="time" id="txtEmpieza_Salida" name="txtEmpieza_Salida" class="form-control" title="Se establece hora que se considerará como salida, antes de la hora de salida establecida." enabled>
              </div>
            </div>
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label bg-green" for="txtTermina_Entrada">Entrada hasta :</label>
              <div class="col-sm-3">
                <input type="time" id="txtTermina_Entrada" name="txtTermina_Entrada" class="form-control" title="Se establece hora que se considerará como entrada, después de la hora de entrada establecida." enabled>
              </div>
              <label class="col-sm-3 col-form-label bg-red"" for="txtTermina_Salida">Salida hasta :</label>
              <div class="col-sm-3">
                <input type="time" id="txtTermina_Salida" name="txtTermina_Salida" class="form-control" title="Se establece hora que se considerará como salida, despues de la hora de salida establecida." enabled>
              </div>
            </div><hr>
            <div class="form-group row mb-1">
              <label class="col-sm-4 col-form-label" for="txtEntrada_Tolerancia">Tolerancia Entrada (min):</label>
              <div class="col-sm-2">
                <input type="number" id="txtEntrada_Tolerancia" name="txtEntrada_Tolerancia" class="form-control" title="Minutos tolerables después del marcado de entrada." value="0" enabled >
              </div>
              <label class="col-sm-4 col-form-label" for="txtSalida_Tolerancia">Tolerancia Salida (min):</label>
              <div class="col-sm-2">
                <input type="number" id="txtSalida_Tolerancia" name="txtSalida_Tolerancia" class="form-control" title="Minutos tolerables antes del marcado de salida." value="0" enabled>
              </div>
            </div>
            <div class="form-group row mb-1">
              <label class="col-sm-4 col-form-label" for="txtTolerancia_Acumulable"><small>Tolerancia Acumulable (min):</small></label>
              <div class="col-sm-2">
                <input type="number" id="txtTolerancia_Acumulable" name="txtTolerancia_Acumulable" class="form-control" title="Tope acumulable de la tolerancia establecida." value="0" enabled>
              </div>
              <label class="col-sm-4 col-form-label" for="txtJornadaLaboral"><small>Jornada Laboral (hr):</small></label>
              <div class="col-sm-2">
                <input type="number" id="txtJornadaLaboral" name="txtJornadaLaboral" class="form-control" title="Cantidad de Horas de Jornada laboral." value="8" enabled>
              </div>
            </div>
            <div class="form-group row mb-1">
              <label class="col-sm-4 col-form-label" for="txtEntrada_Tardanza"><small>Rango de Tardanza (min):</small></label>
              <div class="col-sm-2">
                <input type="number" id="txtEntrada_Tardanza" name="txtEntrada_Tardanza" class="form-control" title="A partir de la hora de entrada y la tolerancia, se considera tardanza hasta lo que se establezca aquí. Luego de esto se considera inasistencia." value="0" enabled>
              </div>
              <label class="col-sm-2 col-form-label" for="txtNormativa"><small>Normativa:</small></label>
              <div class="col-sm-4">
                <input type="file" id="txtNormativa" name="txtNormativa" class="form-control" title="Subir resolución/directiva que respalda horario."  enabled>
              </div>
            </div>
            <div class="form-group row mb-1">
                  <div class="form-group" for="txtColor">
                      <label for="cp1">Color Identificador :</label>
                      <input type="color" id="txtColor" name="txtColor" value="#18f743">
                  </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="far fa-save mr-1"></i>Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle mr-1"></i>Salir</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<!-- ./VENTANA MODAL ----------------------------------------------------------------------------------------- -->
<script type="text/javascript">
  //$(document).off("click", ".btnCrear");
  var modified;
  var es_nuevo;
  $(document).on("click", ".btnCrear", function(){
      //des_sismed = fila.find('td:eq(6)').text();

      //$('#cod_producto').attr('value', cod_producto);

      //_Seteando el modal
      $(".modal-header").css("background-color", "#1cc88a");
      $(".modal-header").css("color", "white");
      $(".modal-title").text("Crear Horario");
      $("#win_horario").modal("show");
      document.getElementById('txtNombre').disabled = false;
      $("#txtNombre").val("");
      $("#txtHora_Entrada").val("");
      $("#txtHora_Salida").val("");
      $("#txtEmpieza_Entrada").val("");
      $("#txtTermina_Entrada").val("");
      $("#txtEmpieza_Salida").val("");
      $("#txtTermina_Salida").val("");
      $("#txtEntrada_Tolerancia").val("0");
      $("#txtSalida_Tolerancia").val("0");
      $("#txtTolerancia_Acumulable").val("0");
      $("#txtEntrada_Tardanza").val("0");
      $("#txtColor").val("#18f743");
      es_nuevo = true;
  });
  $(document).ready(function ()
    {
      $("input, select").change(function () {
        modified = true;
      });
    });
  $(document).on("click", ".btnEditar", function(){
      fila = $(this).closest("tr");
      let arr =  JSON.parse(fila.find('td:eq(1)').text());
      //console.log(arr);
      $(".modal-header").css("background-color", "#a06122");
      $(".modal-header").css("color", "white");
      $(".modal-title").text("Actualizar Horario (" + arr.id_horario+")");
      $("#txtNombre").val(arr.nombre);
      let hora_entrada = arr.hora_entrada;
      $("#txtHora_Entrada").val(hora_entrada.substring(0, 5));
      let hora_salida = arr.hora_salida;
      $("#txtHora_Salida").val(hora_salida.substring(0, 5));
      let empieza_entrada = arr.empieza_entrada;
      $("#txtEmpieza_Entrada").val(empieza_entrada.substring(0, 5));
      let termina_entrada = arr.termina_entrada;
      $("#txtTermina_Entrada").val(termina_entrada.substring(0, 5));
      let empieza_salida = arr.empieza_salida;
      $("#txtEmpieza_Salida").val(empieza_salida.substring(0, 5));
      let termina_salida = arr.termina_salida;
      $("#txtTermina_Salida").val(termina_salida.substring(0, 5));
      let entrada_tolerancia = arr.entrada_tolerancia;
      $("#txtEntrada_Tolerancia").val(entrada_tolerancia.substring(0, 5));
      let salida_tolerancia = arr.salida_tolerancia;
      $("#txtSalida_Tolerancia").val(salida_tolerancia.substring(0, 5));
      let tolerancia_acumulable = arr.tolerancia_acumulable;
      $("#txtTolerancia_Acumulable").val(tolerancia_acumulable.substring(0, 5));
      let entrada_tardanza = arr.entrada_tardanza;
      $("#txtEntrada_Tardanza").val(entrada_tardanza.substring(0, 5));
      let jornada_laboral = arr.jornada_laboral;
      $("#txtJornadaLaboral").val(jornada_laboral);
      let color = arr.color;
      $("#txtColor").val(color);
      $("#win_horario").modal("show");
      document.getElementById('txtNombre').disabled = true;
      modified = false;
      es_nuevo = false;
  });
  $(document).on("click", ".btnBorrar", function(){
      fila = $(this).closest("tr");
      let arr =  JSON.parse(fila.find('td:eq(1)').text());
      //console.log(arr.id_horario);
      $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'red',
        title: 'Eliminar Horario!',
        content: 'Esta seguro de eliminar ' +arr.nombre+'?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-red',
            keys: ['enter', 'shift'],
            action: function(){
              $.ajax({
                  url: "rrhh_ini/borrar",
                  type: "POST",
                  dataType: "html",
                  data: {id_horario:arr.id_horario},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                      $("#win_horario").modal("hide");
                      //tbproducto.row(fila).data([cod_producto,descripcion,ffarm,pres,conc,cod_sismed,des_sismed]).draw();
                      //tbproducto.row(fila).data([cod_producto,descripcion,ffarm,pres,conc,cod_sismed,data.descripcion,boton]).draw();
                    // $.alert(data.msg);
                    //console.log(data);
                    }else{
                      $.alert(data.msg);
                    }
                  }
              });
              $("#tabla_completa").load("rrhh_ini/horario_tabla");
            }
          },
          no: function () {
            $.alert('Cancelado!');
          }/*,
          talvéz: {
            btnClass: 'btn-green',
            action: function(){
              $.alert('Talvez!');
            }
          }*/
        }
        })
  });

  $("#frm_CreaHorario").submit(function(e){
    e.preventDefault();
    nombre                = $.trim($("#txtNombre").val());
    hora_entrada          = $.trim($("#txtHora_Entrada").val());
    hora_salida           = $.trim($("#txtHora_Salida").val());
    empieza_entrada       = $.trim($("#txtEmpieza_Entrada").val());
    termina_entrada       = $.trim($("#txtTermina_Entrada").val());
    empieza_salida        = $.trim($("#txtEmpieza_Salida").val());
    termina_salida        = $.trim($("#txtTermina_Salida").val());
    entrada_tolerancia    = $.trim($("#txtEntrada_Tolerancia").val());
    jornada_laboral       = $.trim($("#txtJornadaLaboral").val());
    salida_tolerancia     = $.trim($("#txtSalida_Tolerancia").val());
    tolerancia_acumulable = $.trim($("#txtTolerancia_Acumulable").val());
    entrada_tardanza      = $.trim($("#txtEntrada_Tardanza").val());
    color                 = $.trim($("#txtColor").val());
    //$boton = '<button class="btn btn-sm btn-primary btnAsignar">Asignar</button>';
    if(es_nuevo){
        //$.alert("Se guaradara NUEVO");
        $.confirm({
        icon: 'fa fa-question-circle-o',
        animation: 'scale',
        type: 'blue',
        title: 'Creación de Nuevo Horario!',
        content: 'Esta seguro de guardar?',
        buttons: {
          si: {
            text: 'Si, estoy seguro',
            btnClass: 'btn-blue',
            keys: ['enter', 'shift'],
            action: function(){
              $.ajax({
                  url: "rrhh_ini/guardar",
                  type: "POST",
                  dataType: "html",
                  data: { nombre:nombre,                              hora_entrada:hora_entrada,
                          hora_salida:hora_salida,                    empieza_entrada:empieza_entrada,
                          termina_entrada:termina_entrada,            empieza_salida:empieza_salida,
                          termina_salida:termina_salida,              entrada_tolerancia:entrada_tolerancia,
                          jornada_laboral:jornada_laboral,            salida_tolerancia:salida_tolerancia,
                          tolerancia_acumulable:tolerancia_acumulable,entrada_tardanza:entrada_tardanza,
                          color:color},
                  success: function(data){
                    data = JSON.parse(data);
                    //console.log(data);
                    if(data=="sesion"){ fncSesionExpirada(); return false; }
                    if(data.success){
                      //$.alert("En opcion correcta");
                      $("#win_horario").modal("hide");
                      //tbproducto.row(fila).data([cod_producto,descripcion,ffarm,pres,conc,cod_sismed,des_sismed]).draw();
                      //tbproducto.row(fila).data([cod_producto,descripcion,ffarm,pres,conc,cod_sismed,data.descripcion,boton]).draw();
                    // $.alert(data.msg);
                    //console.log(data);
                    }else{
                      $.alert(data.msg);
                    }
                  }
              });
              $("#tabla_completa").load("rrhh_ini/horario_tabla");
            }
          },
          no: function () {
            //$.alert('Cancelado!');
          }
        }
      });
    } else {
        if (modified){
          //$.alert("Se ACTUALIZARA registro: " + nombre);
          $.confirm({
          icon: 'fa fa-question-circle-o',
          animation: 'scale',
          type: 'orange',
          title: 'Actualizando Horario!',
          content: 'Esta seguro de actualizar?',
          buttons: {
            si: {
              text: 'Si, estoy seguro',
              btnClass: 'btn-orange',
              keys: ['enter', 'shift'],
              action: function(){
                $.ajax({
                    url: "rrhh_ini/actualizar",
                    type: "POST",
                    dataType: "html",
                    data: { nombre:nombre,                              hora_entrada:hora_entrada,
                            hora_salida:hora_salida,                    empieza_entrada:empieza_entrada,
                            termina_entrada:termina_entrada,            empieza_salida:empieza_salida,
                            termina_salida:termina_salida,              entrada_tolerancia:entrada_tolerancia,
                            jornada_laboral:jornada_laboral,            salida_tolerancia:salida_tolerancia,
                            tolerancia_acumulable:tolerancia_acumulable,entrada_tardanza:entrada_tardanza,
                            color:color},
                    success: function(data){
                      data = JSON.parse(data);
                      //console.log(data);
                      if(data=="sesion"){ fncSesionExpirada(); return false; }
                      if(data.success){
                        //$.alert("En opcion correcta");
                        $("#win_horario").modal("hide");
                        //tbproducto.row(fila).data([cod_producto,descripcion,ffarm,pres,conc,cod_sismed,des_sismed]).draw();
                        //tbproducto.row(fila).data([cod_producto,descripcion,ffarm,pres,conc,cod_sismed,data.descripcion,boton]).draw();
                      // $.alert(data.msg);
                      //console.log(data);
                      }else{
                        $.alert(data.msg);
                      }
                    }
                });
                $("#tabla_completa").load("rrhh_ini/horario_tabla");
                /*$.get('rrhh_ini/horario', { userId : 1234 }, function(resp) {
                    console.log(resp);
                });*/
              }
            },
            no: function () {
              //$.alert('Cancelado!');
            }
          },
        });
      } else {
        $.alert("No hay cambios en el registro");
      }
    }
    return false;
  });

</script>