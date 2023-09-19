<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../../config/database.php';
include_once '../../models/driver_routes.php';
//include_once '../../apis/admin_executive/validate_token.php';
use \Firebase\JWT\JWT;
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare driver_routes object
$driver_routes = new DriverRoutes($db);
  
// get driver_routes id
$data = json_decode(file_get_contents("php://input"));
  
// set driver_routes id to be deleted
$driver_routes->id = $data->id;

// read the details of driver_routes to be deleted
$stmt=$driver_routes->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){

    // delete the driver_routes
    if($driver_routes->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "driver_route was deleted."));
}
  
// if unable to delete the driver_routes
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to delete driver_route."));
    }
}
else{
      // set response code - 404 Not found
      http_response_code(404);
  
      // tell the user driver_routes does not exist
      echo json_encode(array("message" => "Driver_route does not exist."));
}

?>