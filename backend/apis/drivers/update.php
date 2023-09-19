<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/drivers.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare drivers object
$drivers = new Drivers($db);
  
// get id of drivers to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of drivers to be edited
$drivers->id = $data->id;

// read the details of drivers to be deleted
// read the details of routes to be deleted
$stmt=$drivers->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){


// set drivers property values
$drivers->name = $data->name;
$drivers->phone_no = $data->phone_no;
//$drivers->shift = $data->shift;

  
// update the drivers
if($drivers->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Driver's details were updated."));
}
  
// if unable to update the drivers, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update driver's details."));
}
}
else{
  // set response code - 404 Not found 
  http_response_code(404);

  // tell the user drivers does not exist
  echo json_encode(array("message" => "Driver's record does not exist."));
}

?>