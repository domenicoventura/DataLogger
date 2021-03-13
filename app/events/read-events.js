$(document).ready(function(){

    // Get the context of the canvas element we want to select
    var canvas = document.getElementById("myChart");
    
    // hide the canvas
    canvas.style.display="none";

    // show list of events on first load
    showEventsFirstPage();
 
    // when a 'read events' button was clicked
    $(document).on('click', '.read-events-button', function(){
        // hide the canvas
        canvas.style.display="none";
        
        // show list of events on first load
        showEventsFirstPage();
    });
 
    // when a 'page' button was clicked
    $(document).on('click', '.pagination li', function(){
        // get json url
        var json_url=$(this).find('a').attr('data-page');
 
        // show list of events
        showEvents(json_url);
    });
 
});


function showEventsFirstPage(){
    var json_url="http://matt.zapto.org/domenico/php/p0/api/event/read_paging.php";
    showEvents(json_url);
}
 
// function to show list of events
function showEvents(json_url){
 
    // get list of events from the Api
    $.getJSON(json_url, function(data){
 
        if (json_url.indexOf("s=")>0)  {

            const keywords = json_url.substring(json_url.indexOf("s=")+2, json_url.indexOf("&"));

            // html for listing events
            readEventsTemplate(data, keywords);
    
            // chage page title
            changePageTitle("Search Events");

        } else {

            // html for listing events
            readEventsTemplate(data, "");
    
            // chage page title
            changePageTitle("Read Events");

        }
        
    });
}
