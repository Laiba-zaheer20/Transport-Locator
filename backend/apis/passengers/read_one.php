<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/passengers.php';

$database = new Database();
$db = $database->getConnection();
  
// prepare passengers object
$passengers = new Passengers($db);
  
// set ID property of record to read
$passengers->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of passengers to be edited
$stmt=$passengers->readOne();
  $passengers_item=array();
  $passengerss_arr["records"]=array();

 while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // extract row
    extract($row);
    // create array
    $passengers_item = array(
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
if($passengerss_arr["records"]!=null){
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($passengerss_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user passengers does not exist
    echo json_encode(array("message" => "Passenger's record do not exist."));
}

?>