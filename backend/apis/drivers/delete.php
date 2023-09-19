<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../../config/database.php';
include_once '../../models/drivers.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare drivers object
$drivers = new Drivers($db);
  
// get drivers id
$data = json_decode(file_get_contents("php://input"));
  
// set drivers id to be deleted
$drivers->id = $data->id;
  
// read the details of drivers to be deleted
// read the details of routes to be deleted
$stmt=$drivers->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){

// delete the drivers
if($drivers->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Driver record was deleted."));
}
  
// if unable to delete the delivery slot
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete driver's record."));
}
}
else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user servie area does not exist
    echo json_encode(array("message" => "Driver's record does not exist."));
}

?>