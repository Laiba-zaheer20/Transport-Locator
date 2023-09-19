<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/area.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare area object
$area = new Area($db);
  
// set ID property of record to read
$area->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of area to be edited
$area->readOne();
  
if($area->name!=null){
    // create array
    $area_arr = array(
        "id" =>  $area->id,
        "name" => $area->name,
        "stop_id" => $area->stop_id
       
  
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($area_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user area does not exist
    echo json_encode(array("message" => "Area does not exist."));
}


?>