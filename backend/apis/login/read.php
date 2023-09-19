<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../config/database.php';
include_once '../../models/login.php';

// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// login_idize object
$login = new Login($db);
  
$stmt = $login->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // logins array
    $logins_arr=array();
    $logins_arr["records"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
  
        $login_item=array(
            "id" =>  $id,
            "password" => $password,
            "login_id" => $login_id
            
        );
  
        array_push($logins_arr["records"], $login_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show logins data in json format
    echo json_encode($logins_arr);
}
else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no logins found
    echo json_encode(
        array("message" => "No login credentials found.")
    );
}

  
// no logins found will be here