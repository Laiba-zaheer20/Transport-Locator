<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../config/database.php';
include_once '../../config/core.php';
// instantiate drivers object
include_once '../../models/drivers.php';
include_once '../../models/passengers.php';

$database = new Database();
$db = $database->getConnection();
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->login_id) &&
    !empty($data->password)
  ){
      $initial=$data->login_id[0];
      //check if the login request is for passenger portal
      if($initial=='P'){
        $passengers = new Passengers($db);
        $passengers->login_id=$data->login_id;
          $stmt=$passengers->login($data->password);
          if($stmt){


            $url = 'http://13.251.109.45/TransportLocator/apis/passengers/read_one.php?id='.$passengers->id;
            $passenger_info =json_decode(curl_get_contents($url));

           // set response code - 201 logged in
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Successful login.","passenger_info"=>$passenger_info,"portal"=>"passenger"));
    }
    else{
        // set response code - 400 incorrect data
     http_response_code(400);
     // tell the user
     echo json_encode(array("message" => "Incorrect data or service issue"));
    }

          }

          //check if the login request is for driver portal
            else  if($initial=='D'){
                $drivers = new Drivers($db);
                $drivers->login_id=$data->login_id;
                  $stmt=$drivers->login($data->password);
                  if($stmt){

                    $url = 'http://13.251.109.45/TransportLocator/apis/drivers/read_one.php?id='.$drivers->id;
                    $driver_info =json_decode(curl_get_contents($url));

                    $url = 'http://13.251.109.45/TransportLocator/apis/driver_routes/route_by_driver.php?driver_id='.$drivers->id;
                    $route_no =json_decode(curl_get_contents($url));
                    //echo($response);
                   // set response code - 201 logged in
                http_response_code(201);
          
                // tell the user
                echo json_encode(array("message" => "Successful login.","driver_info"=>$driver_info,"route_no"=>$route_no->route_no,"portal"=>"driver"));
            }
            else{
                // set response code - 400 incorrect data
             http_response_code(400);
             // tell the user
             echo json_encode(array("message" => "Incorrect data or service issue"));
            }
        
                  }
                  else{
                       // set response code - 400 incorrect data
             http_response_code(400);
             // tell the user
     echo json_encode(array("message" => "Incorrect data or service issue"));
            }
                  }


      
      else{
             // set response code - 204 incomplete data
             http_response_code(204);
               // tell the user
               echo json_encode(array("message" => "Empty field(s)."));
      }
   
  
?>
