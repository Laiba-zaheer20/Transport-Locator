<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../config/database.php';
include_once '../../models/stops.php';


// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$stops = new Stops($db);
  
$stmt = $stops->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // stopss array
    $stopss_arr=array();
    $stopss_arr["records"]=array();
 
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
  
      
        $stops_item=array(
            "id" =>  $id,
            "name"=>$name,
            "location_latitude"=>$latitude,
            "location_longitude"=>$longitude,
            "route_id" =>$route_id,
            "route_no" =>$route_no,
            "area_id" => $area_id,
            "area" => $area
           
        );
  
        array_push($stopss_arr["records"], $stops_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show stopss data in json format
    echo json_encode($stopss_arr);
}
else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no stopss found
    echo json_encode(
        array("message" => "No stops found.")
    );
}

// no stopss found will be here