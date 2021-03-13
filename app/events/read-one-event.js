$(document).ready(function(){
 
    // handle 'read one' button click
    $(document).on('click', '.read-one-event-button', function(){

        // get event id
        var id = $(this).attr('data-id');

        // read event record based on given ID
        $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/read_one.php?id_event=" + id, function(data){

            // start html
            var read_one_event_html=`
            
                <!-- when clicked, it will show the event's list -->
                <div id='read-events' class='btn btn-primary pull-right m-b-15px read-events-button'>
                    <span class='glyphicon glyphicon-list'></span> Read Events
                </div>


                <!-- event data will be shown in this table -->
                <table class='table table-bordered table-hover'>
                
                    <!-- datatime -->
                    <tr>
                        <td class='w-30-pct'>Datatime</td>
                        <td class='w-70-pct'>` + data.datatime + `</td>
                    </tr>
                
                    <!-- temperature -->
                    <tr>
                        <td>Temperature</td>
                        <td>` + data.temp_indoor + `</td>
                    </tr>
                
                    <!-- humidity -->
                    <tr>
                        <td>Humidity</td>
                        <td>` + data.humid_indoor + `</td>
                    </tr>
                
                    <!-- id event -->
                    <tr>
                        <td>Id Event</td>
                        <td>` + data.id_event + `</td>
                    </tr>
                
                </table>`;

            // inject html to 'page-content' of our app
            $("#page-content").html(read_one_event_html);
            
            // chage page title
            changePageTitle("Read Event");

        });

    });
 
});