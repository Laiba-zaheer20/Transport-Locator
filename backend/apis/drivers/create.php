<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../config/database.php';
  
// instantiate drivers object
include_once '../../models/drivers.php';


$database = new Database();
$db = $database->getConnection();
  
$drivers = new Drivers($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->phone_no)//&&
    //!empty($data->shift)
  

){
    $dl=$drivers->get_login_id();
    if(!$dl){
        echo("error");
    }
    $drivers->login_id="D-".$dl;
   // echo($passengers->login_id);
    
    $dict = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz*&%$';
    $chars=8;
    $drivers->password=substr(str_shuffle($dict), 0, $chars);
    // set drivers property values
    $drivers->name = $data->name;
    $drivers->phone_no = $data->phone_no;
   // $drivers->shift = $data->shift;
  
    // create the drivers
    if($drivers->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "New driver was added."));
    }
  
    // if unable to create the drivers, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to add new driver."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to add new driver. Data is incomplete."));
}

?>