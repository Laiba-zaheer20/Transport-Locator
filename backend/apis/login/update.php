<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/login.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare login object
$login = new Login($db);
  
// get id of login to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of login to be edited
$login->id = $data->id;

// read the details of login to be updated
$stmt=$login->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){


// set login property values
$login->password = $data->password;
$login->login_id = $data->login_id;


  
// update the login
if($login->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Login credentials were updated."));
}
  
// if unable to update the login, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update login credentials."));
}
}
else{
  // set response code - 404 Not found 
  http_response_code(404);

  // tell the user login does not exist
  echo json_encode(array("message" => "Login credetials do not exist."));
}

?>