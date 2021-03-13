$(document).ready(function(){

    let active = 0;
    let interval = null;
 
    // show html form when 'update state' button was clicked
    $(document).on('click', '.state-button', function(){

        active = 1;
        
        // get site id
        //var id = $(this).attr('data-id');
        var id = 0;

        // read the only record
        $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/get_state.php", function(data){
        
            // values will be used to fill out our form
            var timeout = data.timeout;
            var state = data.state;
            var checked = (state == 0) ? false : true;
            
            // store 'update state' html to this variable
            var update_state_html=`
            <div id='read-events' class='btn btn-primary pull-right m-b-15px read-events-button'>
                <span class='glyphicon glyphicon-list'></span> Read Events
            </div>

            <!-- build 'update state' html form -->
            <!-- I used the 'required' html5 property to prevent empty fields -->
            <form id='update-state-form' action='#' method='post' border='0'>
                <table class='table table-hover table-responsive table-bordered'>
            
                    <!-- timeout field -->
                    <tr>
                        <td>Sample Time</td>
                        <td><input value=\"` + timeout + `\" type='number' min='5' max='300' name='timeout' class='form-control' required /></td>
                    </tr>
            
                    <!-- state field -->
                    <tr>
                        <td>State</td>
                        <td><input type='checkbox' name='state'` + (checked ? " checked " : " ") + `/></td>
                        <!-- <td><input value=\"` + state + `\" type='checkbox' name='state' class='form-control' required /></td> -->
                    </tr>
            
                    <tr>
            
                        <!-- hidden 'site id' to identify which record to update -->
                        <td><input value=\"` + id + `\" name='id_site' type='hidden' /></td>
            
                        <!-- button to submit form -->
                        <td>
                            <button type='submit' class='btn btn-info'>
                                <span class='glyphicon glyphicon-edit'></span> Update State
                            </button>
                        </td>
            
                    </tr>
            
                </table>
            </form>`;


            $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/read_one.php", function(data){

                update_state_html+=`

                    <!-- event data will be shown in this table -->
                    <table class='table table-bordered table-hover'>
                    
                        <!-- datatime -->
                        <tr>
                            <td class='w-30-pct'>Datatime</td>
                            <td name='datatime' class='w-70-pct'>` + data.datatime + `</td>
                        </tr>
                    
                        <!-- temperature -->
                        <tr>
                            <td>Temperature</td>
                            <td name='temp_indoor'>` + data.temp_indoor + `</td>
                        </tr>
                    
                        <!-- humidity -->
                        <tr>
                            <td>Humidity</td>
                            <td name='humid_indoor'>` + data.humid_indoor + `</td>
                        </tr>
                    
                        <!-- id event 
                        <tr>
                            <td>Id Event</td>
                            <td>` + data.id_event + `</td>
                        </tr> -->
                    
                    </table>`;

                // inject to 'page-content' of our app
                $("#page-content").html(update_state_html);
                
                // chage page title
                changePageTitle("Update State");

                // set Timeout to update the fields periodically
                interval = setTimeout(function() {

                    refresh_field();
                    
                }, 1000);

            });

        });

    });
     
    // 'update state form' submit handle will be here
    $(document).on('submit', '#update-state-form', function(){
        
        // get form data
        var form_data=JSON.stringify($(this).serializeObject());

        // submit form data to api
        $.ajax({
            url: "http://matt.zapto.org/domenico/php/p0/api/event/set_state.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // state was updated, go back to events list
                //showEventsFirstPage();
                //active = 1;
            },
            error: function(xhr, resp, text) {
                // show error to console
                console.log(xhr, resp, text);
            }
        });

        return false;
    });

    function refresh_field() {

        $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/get_state.php", function(data){

            // values will be used to fill out our form
            var timeout = data.timeout;
            var state = data.state;
            var checked = (state == 0) ? false : true;

            document.getElementsByName("state")[0].checked = checked;

            $("[name='timeout']").val(timeout);

            // read the last event
            $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/read_one.php", function(data){

                // values will be used to fill out our form
                var datatime = data.datatime;
                var temp_indoor = data.temp_indoor;
                var humid_indoor = data.humid_indoor;

                $("[name='datatime']").text(datatime);
                $("[name='temp_indoor']").text(temp_indoor);
                $("[name='humid_indoor']").text(humid_indoor);
                
                if (active)   {
                    interval = setTimeout(refresh_field, 1000);
                } else {
                    clearTimeout(interval);
                }

            });

        });

    }

    // when a 'read events' button was clicked
    $(document).on('click', '.read-events-button', function(){
        
        if (active)   {
            clearTimeout(interval);
            active = 0;
        }
    });


    $(document).on("click", "[name='state']", function(){
        
        $("#update-state-form").submit();
    });

    
    $(document).on("focus", "[name='timeout']", function(){
        
        if (active)   {
            clearTimeout(interval);
            active = 0;
        }
        
    });


    $(document).on("blur", "[name='timeout']", function(){
        
        if (!active)   {
            interval = setTimeout(refresh_field, 1000);
            active = 1;
        }
        
    });
      
});