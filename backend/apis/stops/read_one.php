<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/stops.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare stops object
$stops = new Stops($db);
  
// set ID property of record to read
$stops->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of stops to be read
$stmt=$stops->readOne();
    // create array
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
  
   
  if($stopss_arr["records"]!=null){
       // set response code - 200 OK
    http_response_code(200);
    // make it json format
    echo json_encode($stopss_arr);
}

  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user stops does not exist
    echo json_encode(array("message" => "Stop does not exist."));
}

?>