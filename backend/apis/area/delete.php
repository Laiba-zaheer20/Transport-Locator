<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../../config/database.php';
include_once '../../models/area.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare area object
$area = new Area($db);
  
// get area id
$data = json_decode(file_get_contents("php://input"));
  
// set area id to be deleted
$area->id = $data->id;

// read the details of area to be deleted
$area->readOne();

//if record exists
if($area->name!=null){
    // delete the area
    if($area->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Area was deleted."));
}
  
// if unable to delete the area
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to delete area."));
    }
}
else{
      // set response code - 404 Not found
      http_response_code(404);
  
      // tell the user area does not exist
      echo json_encode(array("message" => "Area does not exist."));
}


?>