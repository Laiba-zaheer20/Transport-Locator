<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/login.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare login object
$login = new login($db);
  
// set ID property of record to read
$login->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of login 
$stmt=$login->readOne();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row){
    extract($row);
    // create array
    $login_arr = array(
        "id" =>  $id,
        "password" => $password,
        "login_id" => $login_id
        
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($login_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user login does not exist
    echo json_encode(array("message" => "Login credentials do not exist."));
}

?>