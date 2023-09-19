<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-name: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-name, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/stops.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare stops object
$stops = new Stops($db);
  
// get id of stops to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of stops to be edited
$stops->id = $data->id;

// read the details of stops to be deleted
$stops->readOne();
// read the details of routes to be deleted
$stmt=$stops->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){

// set stops property values
$stops->name=$data->name;
$stops->latitude=$data->latitude;
$stops->longitude=$data->longitude;
$stops->route_id=$data->route_id;
$stops->area_id=$data->area_id;
// update the stops
if($stops->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Stop details were updated."));
}
  
// if unable to update the stops, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update stop details."));
}
}
else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user stops does not exist
    echo json_encode(array("message" => "Stop record does not exist."));
}

?>