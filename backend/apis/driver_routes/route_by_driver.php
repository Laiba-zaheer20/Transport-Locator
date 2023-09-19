<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/driver_routes.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare driver_routes object
$driver_routes = new DriverRoutes($db);
  
// set ID property of record to read
$driver_routes->driver_id = isset($_GET['driver_id']) ? $_GET['driver_id'] : die();
  
// read the details of driver_routes 
$stmt=$driver_routes->get_route_by_driver();
if($stmt!=false){
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$route_no=$row['route_no'];
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode(array("route_no"=>$route_no));
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user driver_routes does not exist
    echo json_encode(array("message" => "driver or route does not exist."));
}

?>