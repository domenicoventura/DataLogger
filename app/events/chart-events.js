$(document).ready(function(){
     
    // when a 'read events' button was clicked
    $(document).on('click', '.chart-events-button', function(){
        

        $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/read.php", function(data){

            // start html
            var read_one_event_html=`
            
                <!-- when clicked, it will show the event's list -->
                <div id='read-events' class='btn btn-primary pull-right m-b-15px read-events-button'>
                    <span class='glyphicon glyphicon-list'></span> Read Events
                </div>
                
                `;
            

            const datatime = $.map(data.records, function (i) {
                return i.datatime;
            });

            const temp = $.map(data.records, function (i) {
                return i.temp_indoor;
            });

            const humid = $.map(data.records, function (i) {
                return i.humid_indoor;
            });


            // Get the context of the canvas element we want to select
            var canvas = document.getElementById("myChart");
            var ctx = canvas.getContext("2d");
            
            canvas.style.display="block";
            
            // Instantiate a new chart using 'data' (defined below)
            
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: datatime,
                    datasets: [{
                        label: 'Temperature [°C] ',
                        data: temp,
                    },
                    {
                        label: 'Humidity [%] ',
                        data: humid,
                    }]
                },
            });
            

            // inject html to 'page-content' of our app
            $("#page-content").html(read_one_event_html);
            
            // chage page title
            changePageTitle("Chart Data");

        });

    });
    
 
});
