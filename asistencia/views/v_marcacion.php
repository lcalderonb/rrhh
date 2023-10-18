        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Marcación del Personal
            <small>Marcación</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>main"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Marcación</li>
          </ol>

          <br>
          
          <div class="row">
            <div class="col-xs-12">
              <!--
              <label class="col-sm-1 control-label">Buscar:</label>
              
              <div class="col-sm-4">
                <input type="text" id="id_filtro" class="form-control" style="text-transform: uppercase;" >                
              </div>
              -->

              <div class="col-sm-2">
                <div class="form-group">
                  <label>N° DNI</label>
                  <div class="input-group">
                    <input type="text" name="dni" id="dni" class="form-control" style="text-transform: uppercase;" >
                  </div>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group">
                  <label>Nombres y Apellidos</label>
                  <div class="input-group">
                    <input type="text" class="form-control" style="text-transform: uppercase;" name="txtNombres" id= "txtNombres">
                  </div>
                </div>
              </div>

              <!--

                <div class="col-sm-3">
                <div class="form-group">
                  <label>Fecha de Consulta</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>                    
                    <input type="date" class="form-control" name="fecha_consulta" id="fecha_consulta" value="<?php echo date("Y-m-d"); ?>" />                    
                  </div>                
                </div>                
              </div>


              -->

              <div class="col-sm-3">
              </div>
              
              <div class="col-sm-2">
                <div class="form-group">
                  <label>Fecha de Inicio</label>
                  <div class="input-group">                    
                    <input type="date" class="form-control" name="txtFecInicio" id="txtFecInicio" value="<?php echo date("Y-m-d"); ?>" />                    
                  </div>                
                </div>                
              </div>

              <div class="col-sm-2">
                <div class="form-group">
                  <label>Fecha de Fin</label>
                  <div class="input-group">                    
                    <input type="date" class="form-control" name="txtFecFin" id="txtFecFin" value="<?php echo date("Y-m-d"); ?>" />                    
                  </div>                
                </div>                
              </div>             
                                     

            </div>
          </div>

          <div class="row">
            <div class="col-xs-12">
              <div class="col-md-12">                
                <button  onClick='anula.busqueda()' class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
        </section>
        <!-- Main content -->
        <section class="content">          
          <!-- Your Page Content Here -->          
          <div class="row">          
            <div class="col-xs-12">
              <!-- /.box -->
         <div class="box">
                <div class="box-body" id="id_lista_inter_asigna">                                  
                    <?php echo $tabla_html; ?>                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
      <div class="row" style="text-align:center">
                        
      </div>

      

      </section><!-- /.content -->

    <script type="text/javascript">
      anula = {};

      //timer 
      //var timeoutID;
      //function delayedAlert() {
          //timeoutID = setTimeout(showAlert, 2000);
      //}
      
      function showAlert() {
          console.log("hola");
      }
      
      function clearAlert() {
          clearTimeout(timeoutID);
      }

      let i = 0;
      let flag = true
      while ( flag ) {

          task(i);
          i++;
          if (i == 100 ) flag = false;
      }

      function task(i) { 
        setTimeout(function() { 
            anula.busqueda();
        }, 30000 * i); 
      } 

      //Datemask dd/mm/yyyy      
      //$("#fecha_consulta").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});

      $(function () {
        
        $("#cod_hc").focus();
                
        $('#example1').dataTable({
              //"bPaginate": true,
              //"bLengthChange": false,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": false,
              "language": {
                            "lengthMenu": "Mostrar _MENU_ registros",
                            "zeroRecords": "No se encontraron resultados",
                            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sSearch": "Buscar:",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast":"Último",
                                "sNext":"Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "sProcessing":"Procesando...",
              }


        });
       
      });

      $("#ubo_filtro").change(function (){
        //console.log("Codigo de departamento:" + $("#iddpto").val());
        $("#ubo_filtro option:selected").each( function () {
          console.log( $(this).val());
          $.post("avance_pnc/listar_intervencion_filtrado",
            {
              cod_ubo: $("#ubo_filtro").val(),
              anio: $("#anio_filtro").val(),
              estado: $("#estado_filtro").val()
            },
            function (data){
              $("#id_lista_inter_asigna").html(data);
              
              $('#example1').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
              });

            }

          );
          
        });

      });      
      
      anula.busqueda = function(){
                       
                $("#loadMe").modal({
                  backdrop: "static", //remove ability to close modal with click
                  keyboard: false, //remove option to close with keyboard
                  show: true //Display loader!
                });
                
                $.post('asistencia/busqueda', {
                  dni: $("#dni").val(),
                  txtNombres : $("#txtNombres").val(),
                  txtFecFin : $("#txtFecFin").val(),
                  txtFecInicio : $("#txtFecInicio").val(),
                },function(data){
                    
                    $("#id_lista_inter_asigna").html(data);
                    $("#example1").dataTable({
                      
                          "bFilter": true,
                          "bSort": true,
                          "bInfo": true,
                          "bAutoWidth": false,
                          "language": {
                                        "lengthMenu": "Mostrar _MENU_ registros",
                                        "zeroRecords": "No se encontraron resultados",
                                        "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                        "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                        "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                                        "sSearch": "Buscar:",
                                        "oPaginate": {
                                            "sFirst": "Primero",
                                            "sLast":"Último",
                                            "sNext":"Siguiente",
                                            "sPrevious": "Anterior"
                                        },
                                        "sProcessing":"Procesando...",
                          }

                    });
                    $("#loadMe").modal("hide");                  

              });
         

      }

      anula.preanula_atencion = function(hc, codcons, fecha, ciudadano){

          $.confirm({
              icon: 'fa fa-question-circle-o',
              //closeIcon: true,
              animation: 'scale',
              type: 'blue',
              title: 'Confirmar!',
              content: 'Esta seguro de anular la consulta  ?',
              buttons: {
                    si: {
                        text: 'Si, estoy seguro',
                        btnClass: 'btn-blue',
                        keys: ['enter', 'shift'],
                        action: function(){
                          
                          $.ajax({
                              type: 'POST',
                              url: 'anulafua/pre_anulacion',
                              data: {
                                hc: hc,
                                codcons : codcons,
                                fecha : fecha,
                                ciudadano : ciudadano
                              },
                              success: function(data){

                                data = JSON.parse(data);
                                if(data.success == true){
                                  //fue satifactorio
                                  show_stack_modal('success',data.msj);
                                  anula.busqueda();
                                } else{
                                  show_stack_modal('error',data.msj);
                                }

                              }

                          });
                          //.alert(data.msg);
                          

                        }
                    },
                    no: function () {
                        //$.alert('Cancelado!');
                    }
              }

          });                           

        
      }
      
      function show_stack_modal(type, msg) {
                  var opts = {
                      title: "Resultado",
                      text: "",
                      icon: 'glyphicon glyphicon-ok',
                      addclass: "stack-modal",
                      stack: stack_modal,
                      styling: 'bootstrap3'
                  };
                  switch (type) {
                  case 'error':
                      opts.title = "Resultado";
                      opts.text = msg;
                      opts.type = "error";
                      break;
                  case 'info':
                      opts.title = "Resultado";
                      opts.text = msg;
                      opts.type = "info";
                      break;
                  case 'success':
                      opts.title = "Confirmación de Envío";
                      opts.text = msg;
                      opts.type = "success";
                      break;
                  }
                  new PNotify(opts);
            }

    </script>

