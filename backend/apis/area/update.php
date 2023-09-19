<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/area.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare area object
$area = new Area($db);
  
// get id of area to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of area to be edited
$area->id = $data->id;

// read the details of area to be deleted
$area->readOne();

//if record exists
if($area->name!=null){
  
// set area property values
$area->name = $data->name;
$area->stop_id = $data->stop_id;
 
// update the area
if($area->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "area details were updated."));
}
  
// if unable to update the area, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update area details."));
}
}
else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user area does not exist
    echo json_encode(array("message" => "area does not exist."));
}

?>