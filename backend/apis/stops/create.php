<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-name: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-name, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../config/database.php';
  
// instantiate stops object
include_once '../../models/stops.php';



$database = new Database();
$db = $database->getConnection();
  
$stops = new Stops($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty and valid
if(
    !empty($data->name)&&
    !empty($data->latitude)&&
    !empty($data->longitude)&&
    !empty($data->area_id)&&
    !empty($data->route_id)||
    $data->route_id==0
  

){
  
    // set stops property values
    $stops->name=$data->name;
    $stops->latitude=$data->latitude;
   $stops->longitude=$data->longitude;
   $stops->route_id=$data->route_id;
   $stops->area_id=$data->area_id;
  

  
    // create the stops
    if($stops->create()){
        
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "New stop was added."));
    }
  
    // if unable to create the stops, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to add new stop."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to add new stop. Data is incomplete."));
   
    
}

?>