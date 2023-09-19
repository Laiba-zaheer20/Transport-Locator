<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../config/database.php';
include_once '../../models/driver_routes.php';


// instantiate database and object
$database = new Database();
$db = $database->getConnection();

// initialize object
$driver_routes = new DriverRoutes($db);
 //session_start();
$stmt = $driver_routes->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // driver_routess array
    $driver_routess_arr=array();
    $driver_routess_arr["records"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
  
        $driver_routes_item=array(
            "id"=>$id,
        "shift"=> $shift,
        "driver_id"=>$driver_id,
        "route_id"=>$route_id
           );
  
        array_push($driver_routess_arr["records"], $driver_routes_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show driver_routess data in json format
    echo json_encode($driver_routess_arr);
}
else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no driver_routess found
    echo json_encode(
        array("message" => "No driver_routes found.")
    );
}
 

// no driver_routess found will be here