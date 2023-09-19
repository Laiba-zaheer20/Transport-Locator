<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/routes.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare routes object
$routes = new Routes($db);
  
// set ID property of record to read
$routes->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of routes to be edited
$stmt=$routes->readOne();
   // get retrieved row
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   // set values to object properties
   $routes->id = $row['id'];
   $routes->route_no = $row['route_no'];  
if($row){
    // create array
    $routes_arr = array(
        "id" =>  $routes->id,
        "route_no" => $routes->route_no
  );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($routes_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user routes does not exist
    echo json_encode(array("message" => "Route does not exist."));
}

?>