<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/site.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare site object
$site = new Site($db);
  
// set ID property of site to read
$site->id_site = isset($_GET['id_site']) ? $_GET['id_site'] : 0;
  
// read the details of site to be edited
$site->read();
  
if($site->name!=null){
    // create array
    $site_arr=array(
        "id_site" => $site->id_site,
        "name" => $site->name,
        "timeout" => $site->timeout,
        "state" => $site->state
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($site_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user site does not exist
    echo json_encode(array("message" => "Site does not exist."));
}
?>