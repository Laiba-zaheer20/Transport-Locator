<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../config/core.php';
include_once '../../models/drivers.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare drivers object
$drivers = new drivers($db);
  
// set ID property of record to read
$drivers->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of drivers to be edited
// read the details of routes to be deleted
$stmt=$drivers->readOne();
// get retrieved row


     // driverss array
     $driverss_arr=array();
     $driverss_arr["records"]=array();
   
     // retrieve our table contents
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
         // extract row
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         if($row){
        extract($row);
        $url = 'http://13.251.109.45/TransportLocator/apis/driver_routes/route_by_driver.php?driver_id='.$id;
        $route_no =json_decode(curl_get_contents($url));
         $drivers_item=array(
             "id" => $id,
             "name" =>$name,
             "phone_no" => $phone_no,
             "login_id"=>$login_id,
            "password"=>$password,
            "route_no"=>$route_no->route_no
             //"shift" => $shift
         );
   
         array_push($driverss_arr["records"], $drivers_item);
     
   
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($driverss_arr);
}

  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user drivers does not exist
    echo json_encode(array("message" => "Driver's record does not exist."));
}

?>