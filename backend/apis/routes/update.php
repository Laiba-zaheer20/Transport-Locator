<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/routes.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare routes object
$routes = new Routes($db);
  
// get id of routes to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of routes to be edited
$routes->id = $data->id;

// read the details of routes to be deleted
$stmt=$routes->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){
// set routes property values
$routes->route_no = $data->route_no;

  
// update the routes
if($routes->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Route details were updated."));
}
  
// if unable to update the routes, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update Route details."));
}
}
else{
  // set response code - 404 Not found 
  http_response_code(404);

  // tell the user routes does not exist
  echo json_encode(array("message" => "Route does not exist."));
}

?>