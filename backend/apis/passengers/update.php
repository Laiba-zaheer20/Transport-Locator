<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/passengers.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare passengers object
$passengers = new Passengers($db);
  
// get id of passengers to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of passengers to be edited
$passengers->id = $data->id;

// read the details of passenger to be updated
$stmt=$passengers->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){


// set passengers property values
$passengers->nu_id=$data->nu_id;
$passengers->name=$data->name;
$passengers->address=$data->address;
$passengers->email=$data->email;
$passengers->phone_no=$data->phone_no;
$passengers->area_id=$data->area_id;
$passengers->stop_id=$data->stop_id;
$passengers->route_id=$data->route_id;

// update the passengers
if($passengers->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Passenger's record was updated."));
}
  
// if unable to update the passengers, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update passenger's record."));
}
}
else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user passengers does not exist
    echo json_encode(array("message" => "Passenger's record does not exist."));
}

?>