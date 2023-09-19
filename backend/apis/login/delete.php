<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../../config/database.php';
include_once '../../models/login.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare login object
$login = new Login($db);
  
// get login id
$data = json_decode(file_get_contents("php://input"));
  
// set login id to be deleted
$login->id = $data->id;
// read the details of login to be deleted
$stmt=$login->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){

// delete the login
if($login->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Login credentials were deleted."));
}
  
// if unable to delete the login
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete login credentials."));
}
}
else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user servie area does not exist
    echo json_encode(array("message" => "Login credentials do not exist."));
}

?>