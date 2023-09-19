<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/driver_routes.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare driver_routes object
$driver_routes = new DriverRoutes($db);
  
// get id of driver_routes to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of driver_routes to be edited
$driver_routes->id = $data->id;

// read the details of driver_routes to be updated
$stmt=$driver_routes->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){

// set driver_routes property values
$driver_routes->shift = $data->shift;
$driver_routes->driver_id = $data->driver_id;
$driver_routes->route_id =$route_id;

  
// update the driver_routes
if($driver_routes->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Driver route was updated."));
}
  
// if unable to update the driver_routes, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update driver route."));
}
}
else{
  // set response code - 404 Not found 
  http_response_code(404);

  // tell the user driver_routes does not exist
  echo json_encode(array("message" => "Driver route does not exist."));
}

?>