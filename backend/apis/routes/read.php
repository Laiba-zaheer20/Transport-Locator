<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../config/database.php';
include_once '../../models/routes.php';


// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$routes = new Routes($db);
  
$stmt = $routes->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // routess array
    $routess_arr=array();
    $routess_arr["records"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
  
        $routes_item=array(
            "id" => $id,
            "route_no" => $route_no
        );
  
        array_push($routess_arr["records"], $routes_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show routess data in json format
    echo json_encode($routess_arr);
}
else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no routes found
    echo json_encode(
        array("message" => "No routes found.")
    );
}

  
