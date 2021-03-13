$(document).ready(function(){
 
    // show html form when 'update event' button was clicked
    $(document).on('click', '.update-event-button', function(){
        
        // get event id
        var id = $(this).attr('data-id');

        // read one record based on given event id
        $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/read_one.php?id_event=" + id, function(data){
        
            // values will be used to fill out our form
            var datatime = data.datatime;
            var temp_indoor = data.temp_indoor;
            var humid_indoor = data.humid_indoor;
            
            // store 'update event' html to this variable
            var update_event_html=`
            <div id='read-events' class='btn btn-primary pull-right m-b-15px read-events-button'>
                <span class='glyphicon glyphicon-list'></span> Read Events
            </div>

            <!-- build 'update event' html form -->
            <!-- I used the 'required' html5 property to prevent empty fields -->
            <form id='update-event-form' action='#' method='post' border='0'>
                <table class='table table-hover table-responsive table-bordered'>
            
                    <!-- datatime field -->
                    <tr>
                        <td>Datatime</td>
                        <td><input value=\"` + datatime + `\" type='text' name='datatime' class='form-control' readonly /></td>
                    </tr>

                    <!-- temp field -->
                    <tr>
                        <td>Temperature</td>
                        <td><input value=\"` + temp_indoor + `\" type='number' name='temp_indoor' class='form-control' required /></td>
                    </tr>
            
                    <!-- humid field -->
                    <tr>
                        <td>Humidity</td>
                        <td><input value=\"` + humid_indoor + `\" type='number' min='1' name='humid_indoor' class='form-control' required /></td>
                    </tr>
            
                    <tr>
            
                        <!-- hidden 'event id' to identify which record to delete -->
                        <td><input value=\"` + id + `\" name='id_event' type='hidden' /></td>
            
                        <!-- button to submit form -->
                        <td>
                            <button type='submit' class='btn btn-info'>
                                <span class='glyphicon glyphicon-edit'></span> Update Event
                            </button>
                        </td>
            
                    </tr>
            
                </table>
            </form>`;

            // inject to 'page-content' of our app
            $("#page-content").html(update_event_html);
            
            // chage page title
            changePageTitle("Update Event");


        });

    });
     
    // 'update event form' submit handle will be here
    $(document).on('submit', '#update-event-form', function(){
        
        // get form data
        var form_data=JSON.stringify($(this).serializeObject());

        // submit form data to api
        $.ajax({
            url: "http://matt.zapto.org/domenico/php/p0/api/event/update.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // event was updated, go back to events list
                showEventsFirstPage();
            },
            error: function(xhr, resp, text) {
                // show error to console
                console.log(xhr, resp, text);
            }
        });

        return false;
    });

});