<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../config/database.php';
include_once '../../models/area.php';


// instantiate database and object
$database = new Database();
$db = $database->getConnection();

// initialize object
$area = new Area($db);
 //session_start();
$stmt = $area->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // areas array
    $areas_arr=array();
    $areas_arr["records"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
  
        $area_item=array(
            "id" => $id,
            "name" => $name,
            "stop_id" => $stop_id
          
        );
  
        array_push($areas_arr["records"], $area_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show areas data in json format
    echo json_encode($areas_arr);
}
else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no areas found
    echo json_encode(
        array("message" => "No areas found.")
    );
}
 
// no areas found will be here