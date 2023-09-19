<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../../config/database.php';
include_once '../../models/passengers.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare passengers object
$passengers = new Passengers($db);
  
// get passengers id
$data = json_decode(file_get_contents("php://input"));
  
// set passengers id to be deleted
$passengers->id = $data->id;

// read the details of passenger to be deleted
$stmt=$passengers->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){

    // delete the passengers
    if($passengers->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Passenger's record was deleted."));
}
  
// if unable to delete the passengers
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to delete passenger's record."));
    }
}
else{
      // set response code - 404 Not found
      http_response_code(404);
  
      // tell the user passengers does not exist
      echo json_encode(array("message" => "Passenger's record does not exist."));
}

?>