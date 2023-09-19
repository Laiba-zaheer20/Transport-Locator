<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../config/database.php';
include_once '../../config/core.php';
include_once '../../models/drivers.php';

// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$drivers = new Drivers($db);
  
$stmt = $drivers->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // driverss array
    $driverss_arr=array();
    $driverss_arr["records"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
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
        );

  
        array_push($driverss_arr["records"], $drivers_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show driverss data in json format
    echo json_encode($driverss_arr);
}
else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no driverss found
    echo json_encode(
        array("message" => "No drivers found.")
    );
}

// no driverss found will be here