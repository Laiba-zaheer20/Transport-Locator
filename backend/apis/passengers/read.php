<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../config/database.php';
include_once '../../models/passengers.php';

// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$passengers = new Passengers($db);
  
$stmt = $passengers->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // passengerss array
    $passengerss_arr=array();
    $passengerss_arr["records"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
  
        $passengers_item=array(
            "id" =>  $id,
        "nu_id" =>$nu_id,
        "name" => $name,
        "address" => $address,
        "email" => $email,
        "phone_no" => $phone_no,
        "area_id" =>$area_id,
        "area" =>$area,
        "stop_id" =>$stop_id,
        "stop" =>$stop,
        "route_id" =>$route_id,
        "route_no" =>$route_no,
        "login_id"=>$login_id,
        "password"=>$password

        );
  
        array_push($passengerss_arr["records"], $passengers_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show passengerss data in json format
    echo json_encode($passengerss_arr);
}
else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no passengerss found
    echo json_encode(
        array("message" => "No passengers found.")
    );
}

