$(document).ready(function(){
 
    // when a 'search events' button was clicked
    $(document).on('submit', '#search-event-form', function(){
 
        // get search keywords
        var keywords = $(this).find(":input[name='keywords']").val();
 
        // get data from the api based on search keywords
        $.getJSON("http://matt.zapto.org/domenico/php/p0/api/event/search_paging.php?s=" + keywords, function(data){
 
            // template in events.js
            readEventsTemplate(data, keywords);
 
            // chage page title
            changePageTitle("Search Events: " + keywords);
 
        })
            .fail(function() {
                //console.log( "error" );
                changePageTitle("Search Events: no records found");
                readEventsTemplate("", "");
            });
 
        // prevent whole page reload
        return false;
    });
 
});
