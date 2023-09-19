<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../config/database.php';
  
// instantiate driver_routes object
include_once '../../models/driver_routes.php';

$database = new Database();
$db = $database->getConnection();
  
$driver_routes = new DriverRoutes($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty and valid
if(
    !empty($data->shift) &&
    !empty($data->driver_id)&&
    !empty($data->route_id) ||
    $data->route_id==0
  ){
  
    // set driver_routes property values
    $driver_routes->shift = $data->shift;
    $driver_routes->driver_id = $data->driver_id;
    $driver_routes->route_id = $data->route_id;
  
    // create the driver_routes
    if($driver_routes->create()){
  
        // set response driver_id - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Driver_route was created."));
    }
  
    // if unable to create the driver_routes, tell the user
    else{
  
        // set response driver_id - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create driver_route."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response driver_id - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create driver_route. Data is incomplete."));
}

?>