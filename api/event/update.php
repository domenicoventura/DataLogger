<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/event.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare event object
$event = new Event($db);
  
// get id of event to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of event to be edited
$event->id_event = $data->id_event;
  
// set event property values
$event->datatime = $data->datatime;
$event->temp_indoor = $data->temp_indoor;
$event->humid_indoor = $data->humid_indoor;
  
// update the event
if($event->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Event was updated."));
}
  
// if unable to update the event, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update event."));
}
?>