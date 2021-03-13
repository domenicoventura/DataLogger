<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/event.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare event object
$event = new Event($db);
  
// set ID property of record to read
$event->id_event = isset($_GET['id_event']) ? $_GET['id_event'] : NULL;
  
// read the details of event to be edited
$event->readOne();
  
if($event->datatime!=null){
    // create array
    $event_arr=array(
        "id_event" => $event->id_event,
        "id_site" => $event->id_site,
        "datatime" => $event->datatime,
        "temp_indoor" => $event->temp_indoor,
        "temp_outdoor" => $event->temp_outdoor,
        "humid_indoor" => $event->humid_indoor,
        "humid_outdoor" => $event->humid_outdoor
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($event_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user event does not exist
    echo json_encode(array("message" => "Event does not exist."));
}
?>