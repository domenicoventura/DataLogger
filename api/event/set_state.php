<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/site.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare site object
$site = new Site($db);
  
// get id of site to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of site to be edited
$site->id_site = $data->id_site;
  
// set site property values
//$site->name = $data->name;
$site->timeout = $data->timeout;
if (isset($data->state))  {
    $site->state = ($data->state === "on") ? 1 : ($data->state === 1) ? 1 : 0 ;
}   else    {
    $site->state = 0;
}

//$site->state = $data->state;
  
// update the site
if($site->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Site was updated."));
}
  
// if unable to update the site, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update site."));
}
?>