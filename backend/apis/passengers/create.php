<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../config/database.php';
  
// instantiate passengers object
include_once '../../models/passengers.php';


$database = new Database();
$db = $database->getConnection();
  
$passengers = new Passengers($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty and valid
if(
    !empty($data->nu_id) &&
     !empty($data->name) &&
     !empty($data->address) && 
     !empty($data->email) &&
     !empty($data->phone_no) &&
     //!empty($data->stop_id) &&
     filter_var($data->email, FILTER_VALIDATE_EMAIL)

){
    $pl=$passengers->get_login_id();
    if(!$pl){
        echo("error");
    }
    $passengers->login_id="P-".$pl;
   // echo($passengers->login_id);
    
    $dict = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz*&%$';
    $chars=8;
    $passengers->password=substr(str_shuffle($dict), 0, $chars);
    // set passengers property values
   $passengers->nu_id=$data->nu_id;
   $passengers->name=$data->name;
   $passengers->address=$data->address;
   $passengers->email=$data->email;
   $passengers->phone_no=$data->phone_no;
   $passengers->stop_id=$data->stop_id;
 
  
  
    // create the passengers
    if($passengers->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "New passenger was added."));
    }
  
    // if unable to create the passengers, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to add new passengers."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to add new passenger. Data is incomplete."));
}

?>