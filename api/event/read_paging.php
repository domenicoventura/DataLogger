<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/event.php';
  
// utilities
$utilities = new Utilities();
  
// instantiate database and event object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$event = new Event($db);
  
// query events
$stmt = $event->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // events array
    $events_arr=array();
    $events_arr["records"]=array();
    $events_arr["paging"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $event_item=array(
            "id_event" => $id_event,
            "id_site" => $id_site,
            "datatime" => $datatime,
            "temp_indoor" => $temp_indoor,
            "temp_outdoor" => $temp_outdoor,
            "humid_indoor" => $humid_indoor,
            "humid_outdoor" => $humid_outdoor
        );
        
        array_push($events_arr["records"], $event_item);
    }
  
  
    // include paging
    $total_rows=$event->count();
    $page_url="{$home_url}event/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $events_arr["paging"]=$paging;
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($events_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user events do not exist
    echo json_encode(
        array("message" => "No events found.")
    );
}
?>