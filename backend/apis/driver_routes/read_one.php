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
$driver_routes->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of driver_routes 
$stmt=$driver_routes->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){
    

    // create array
    $driver_routes_arr = array(
        "id" =>  $row['id'],
        "shift" => $row['shift'],
        "driver_id" => $row['driver_id'],
        "route_id" => $row['route_id']
       );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($driver_routes_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user driver_routes does not exist
    echo json_encode(array("message" => "driver_route does not exist."));
}

?>