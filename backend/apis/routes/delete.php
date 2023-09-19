<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../../config/database.php';
include_once '../../models/routes.php';

use \Firebase\JWT\JWT;

// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare routes object
$routes = new Routes($db);
  
// get routes id
$data = json_decode(file_get_contents("php://input"));
  
// set routes id to be deleted
$routes->id = $data->id;
// read the details of routes to be deleted
$stmt=$routes->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){
// delete the routes
if($routes->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Route was deleted."));
}
  
// if unable to delete the Route 
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete Route."));
}
}
else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user route  does not exist
    echo json_encode(array("message" => "Route does not exist."));
}

?>