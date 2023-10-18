<section class="content-header">
          <h1>
            TELECONSULTA PROGRAMADAS            
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>main"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Teleconsulta programadas</li>
          </ol>   
        </section>
        <!-- Main content -->
        <section class="content">          
          <!-- Your Page Content Here -->          
          <div class="row">          
            <div class="col-xs-12">
              <!-- /.box -->
              <div class="box box-primary">
                <div class="box-body" >                                  
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>

                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
      <div class="row" style="text-align:center">
                        
      </div>

      </section><!-- /.content -->

    <script type = "text/javascript">
        $(function () {

            /* initialize the external events
            -----------------------------------------------------------------*/
            function init_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                    zIndex        : 1070,
                    revert        : true, // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                    })

                })
            }

            init_events($('#external-events div.external-event'))

            /* initialize the calendar
            -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear()
            
            $('#calendar').fullCalendar({
                locale: 'es',
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'month,agendaWeek,agendaDay'
                },                
                /*buttonText: {
                    today: 'today',
                    month: 'month',
                    week : 'week',
                    day  : 'day'
                },*/
                //Random default events
                events    : <?php echo $events ?>,
                editable  : true,
                droppable : true, // this allows things to be dropped onto the calendar !!!
                
                //defaultDate: '2019-07-09',
                //buttonIcons: true,
                //weekNumbers: false,
                //editable: true,
                //eventLimit: true,
                dayRender: function (date, cell) {
                    var today = $.fullCalendar.moment();
                    //cell.css("background-color", "red");
                    if (date.isSame(today, "day")) {
                        cell.css("background", "#ffec80");
                    }
                    //https://html-color.codes/s
                    //cell.css("background", "#e8e8e8");
                    //#fffa70
                    /*if (date.get('date') == today.get('date')) {
                        cell.css("background", "#e8e8e8");
                    }*/
                },            
                /*dayRender: function (date, cell) {
                    var today = moment('2018-06-22T00:00Z');
                    if (date.isSame(today, "day")) {
                        cell.css("background-color", "blue");
                    }
                },*/

                dayClick: function (date, jsEvent, view) {
                    alert( 'Has hecho click en: '+ date.format() );
                }, 
                
                eventClick: function (calEvent, jsEvent, view) {
                    $('#event-title').text(calEvent.title);
                    $('#event-description').html(calEvent.description);
                    $('#modal-event').modal();
                    alert(calEvent.title);
                }, 

                
                drop      : function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject')

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject)

                    // assign it the date that was reported
                    copiedEventObject.start           = date
                    copiedEventObject.allDay          = allDay
                    copiedEventObject.backgroundColor = $(this).css('background-color')
                    copiedEventObject.borderColor     = $(this).css('border-color')

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove()
                    }

                }
            });

            /* ADDING EVENTS */
            var currColor = '#3c8dbc' //Red by default
            //Color chooser button
            var colorChooser = $('#color-chooser-btn')
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                //Save color
                currColor = $(this).css('color')
                //Add color effect to button
                $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
            })
            $('#add-new-event').click(function (e) {
                e.preventDefault()
                //Get value and make sure it is not null
                var val = $('#new-event').val()
                if (val.length == 0) {
                    return
                }

                //Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color'    : currColor,
                    'color'           : '#fff'
                }).addClass('external-event')
                event.html(val)
                $('#external-events').prepend(event)

                //Add draggable funtionality
                init_events(event)

                //Remove event from text input
                $('#new-event').val('')
            })
        })
    </script>

