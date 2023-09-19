<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../config/database.php';
include_once '../../models/area.php';


// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare area object
$area = new Area($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
// set ID property of record to read
$area->stop_id =$data->stop_id;
  
// read the details of area to be edited
$stmt=$area->fetch_area_via_stop();
  
if($stmt){
     // get retrieved row
     $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    $area_arr = array(
        "area_id" => $row['area_id'],
        "area_name" => $row['area_name'],
        "stop_id" => $row['stop_id']
        );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($area_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user area does not exist
    echo json_encode(array("message" => "Stop does not exist."));
}


?>