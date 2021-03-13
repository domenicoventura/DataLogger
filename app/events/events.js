// event list html
function readEventsTemplate(data, keywords){
 
    var read_events_html=`
        <!-- search events form -->
        <form id='search-event-form' action='#' method='post'>
        <div class='input-group pull-left w-30-pct' style="margin-bottom: 20px;">
 
            <input type='text' value='` + keywords + `' name='keywords' class='form-control event-search-keywords' placeholder='Search events...' />
 
            <span class='input-group-btn'>
                <button type='submit' class='btn btn-default' type='button'>
                    <span class='glyphicon glyphicon-search'></span>
                </button>
            </span>
 
        </div>
        </form>
 
        <!-- when clicked, it will load the read events page -->
        <div id='read-events' class='btn btn-primary pull-right m-l-10px m-b-15px read-events-button'>
            <span class='glyphicon glyphicon-list'></span> Read All
        </div>

        <!-- when clicked, it will load the chart page -->
        <div id='chart-events' class='btn btn-primary pull-right m-l-10px m-b-15px chart-events-button'>
            <span class='glyphicon glyphicon-list'></span> View Chart
        </div>

        <!-- when clicked, it will load the state page -->
        <div id='state' class='btn btn-primary pull-right m-b-15px state-button'>
            <span class='glyphicon glyphicon-list'></span> Settings
        </div>
        
        
 
        <!-- start table -->
        <table class='table table-bordered table-hover'>
 
            <!-- creating our table heading -->
            <tr>
                <th class='w-25-pct'>Datatime</th>
                <th class='w-10-pct'>Temperature</th>
                <th class='w-15-pct'>Humidity</th>
                <th class='w-25-pct text-align-center'>Action</th>
            </tr>`;
 
 
    // loop through returned list of data
    $.each(data.records, function(key, val) {
 
        // creating new table row per record
        read_events_html+=`<tr>
 
            <td>` + val.datatime + `</td>
            <td>` + val.temp_indoor + `</td>
            <td>` + val.humid_indoor + `</td>
 
            <!-- 'action' buttons -->
            <td>
                <!-- read event button -->
                <button class='btn btn-primary m-r-10px read-one-event-button' data-id='` + val.id_event + `'>
                    <span class='glyphicon glyphicon-eye-open'></span> Read
                </button>
 
                <!-- edit button -->
                <button class='btn btn-info m-r-10px update-event-button' data-id='` + val.id_event + `'>
                    <span class='glyphicon glyphicon-edit'></span> Edit
                </button>
 
                <!-- delete button -->
                <button class='btn btn-danger delete-event-button' data-id='` + val.id_event + `'>
                    <span class='glyphicon glyphicon-remove'></span> Delete
                </button>
            </td>
        </tr>`;
    });
 
    // end table
    read_events_html+=`</table>`;

    // pagination
    if(data.paging){
        read_events_html+="<ul class='pagination pull-left margin-zero padding-bottom-2em'>";
    
            // first page
            if(data.paging.first!=""){
                read_events_html+="<li><a data-page='" + data.paging.first + "'>First Page</a></li>";
            }
    
            // loop through pages
            $.each(data.paging.pages, function(key, val){
                var active_page=val.current_page=="yes" ? "class='active'" : "";
                read_events_html+="<li " + active_page + "><a data-page='" + val.url + "'>" + val.page + "</a></li>";
            });
    
            // last page
            if(data.paging.last!=""){
                read_events_html+="<li><a data-page='" + data.paging.last + "'>Last Page</a></li>";
            }
        read_events_html+="</ul>";
    }


    // inject to 'page-content' of our app
    $("#page-content").html(read_events_html);
}