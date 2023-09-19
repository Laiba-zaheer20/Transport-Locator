<?php
class Passengers{
  
    // database connection and table name
    private $conn;
    private $table_name = "passengers";
  
    // object properties
    public $id;
    public $nu_id;
    public $name;
    public $address; 
    public $email;
    public $phone_no;
    public $stop_id;
    public $login_id;
    public $password;
    public $route_no;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // READ passengerss
    function read(){
  
    // select all query
    $query = "SELECT passengers.id AS id,
    passengers.nu_id AS nu_id,
    passengers.name AS `name`,
    passengers.email AS email,
    passengers.address AS `address`,
    passengers.phone_no AS phone_no,
    passengers.stop_id AS stop_id,
    passengers.login_id AS login_id,
    passengers.password AS `password`,
    stops.name AS `stop`,
    stops.area_id AS area_id,
    area.name AS area,
    stops.route_id AS route_id,
    routes.route_no AS route_no
        FROM 
        passengers
      left join stops ON passengers.stop_id=stops.id
      left join area ON stops.area_id=area.id
      left join routes ON stops.route_id=routes.id";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
    }

    // CREATE passengers
    function create(){
  
    // query to insert record
    $query = "INSERT INTO " . $this->table_name . "(
                `nu_id`,
                `name`,
                `address`,
                `email`,
               `phone_no`,
               login_id,
               password,
                `stop_id`)
                VALUES
                (?,?,?,?,?,?,?,?)"; 
            
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->nu_id=htmlspecialchars(strip_tags($this->nu_id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    $this->login_id=htmlspecialchars(strip_tags($this->login_id));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->stop_id=htmlspecialchars(strip_tags($this->stop_id));
  
  

    $stmt->bindParam("1", $this->nu_id);
    $stmt->bindParam("2", $this->name);
    $stmt->bindParam("3", $this->email);
    $stmt->bindParam("4", $this->address);
    $stmt->bindParam("5", $this->phone_no);
    $stmt->bindParam("6", $this->login_id);
    $stmt->bindParam("7", $this->password);
    $stmt->bindParam("8", $this->stop_id);

  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
    }

    // used when reading one record
    function readOne(){
  
    // query to read single record
    $query = "SELECT passengers.id AS id,
    passengers.nu_id AS nu_id,
    passengers.name AS `name`,
    passengers.email AS email,
    passengers.address AS `address`,
    passengers.phone_no AS phone_no,
    passengers.login_id AS login_id,
    passengers.password AS `password`,
    passengers.stop_id AS stop_id,
    stops.name AS `stop`,
    stops.area_id AS area_id,
    area.name AS area,
    stops.route_id AS route_id,
    routes.route_no AS route_no
        FROM 
        passengers
        left join stops ON passengers.stop_id=stops.id
        left join area ON stops.area_id=area.id
        left join routes ON stops.route_id=routes.id
         WHERE passengers.id =?";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
            // execute query
        $stmt->execute();
        return $stmt;
   
    }

  
  
  
  
    // update the product
    function update(){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
            nu_id=?,
            name=?,
            address=?,
            email=?,
            phone_no=?,
            stop_id=?
            WHERE
                id =?";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    echo "Prepare done";
    // sanitize
    $this->nu_id=htmlspecialchars(strip_tags($this->nu_id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    $this->stop_id=htmlspecialchars(strip_tags($this->stop_id));
    $this->id=htmlspecialchars(strip_tags($this->id));
    
    
    // bind values
    $stmt->bindParam("1", $this->nu_id);
    $stmt->bindParam("2", $this->name);
    $stmt->bindParam("3", $this->email);
    $stmt->bindParam("4", $this->address);
    $stmt->bindParam("5", $this->phone_no);
    $stmt->bindParam("6", $this->stop_id);
    $stmt->bindParam("9", $this->id);
    
    // execute the query
    if($stmt->execute()){    
        return true;
    }

    return false;
    }

    // delete the product
    function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
    }


    function no_of_passengers_in_route(){
        // delete query
    $query = "SELECT count(passengers.id) AS total_passengers,
    route_id,
    routes.route_no
    FROM `passengers`
     left join `stops` ON passengers.stop_id=stops.id
     left join routes ON stops.route_id=routes.id
     Where route_id IN (SELECT id from routes ) group by route_id";

  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // execute query
    if($stmt->execute()){
        return $stmt;
    }
  
    return false;
    }
    

function get_login_id(){
    $query="SELECT SUBSTRING(login_id, 3, length(login_id)) AS login_id FROM ".$this->table_name;
    // prepare query
    $stmt = $this->conn->prepare($query);
    $i=0;
    if(!$stmt->execute())
        return false;
    $num = $stmt->rowCount();
    if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($i<$row['login_id']){
            $i=$row['login_id'];
            //echo($i);
        }
    }
   return $i+1;
}

    return  $i;


}



function login($password){
    $query="SELECT id from passengers where login_id=? AND `password`=?";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // bind id of record to delete
    $stmt->bindParam(1, $this->login_id);
    $stmt->bindParam(2, $password);
    if(!$stmt->execute())
        return false;
    $num = $stmt->rowCount();
    if($num!=1)
        return false;
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id=$row['id'];
    return true;
}
    



// function stop_of_a_passenger(){
//     $query="SELECT stops.stop_no from passengers left join stops on passengers.stop_id where passengers.stop_id=stops.id and passengers.id=?";
//     // prepare query
//     $stmt = $this->conn->prepare($query);
//     // bind id of record to delete
//     $stmt->bindParam(1, $this->id);
// }


// function route_of_a_passenger(){
//     $query="SELECT routes.route_no AS route_no from passengers left join stops on passengers.stop_id
//     left join routes on stops.route_id where passengers.stop_id=stops.id AND stops.route_id=routes.id 
//      AND passengers.id=?";
//     // prepare query
//     $stmt = $this->conn->prepare($query);
//     // bind id of record to delete
//     $stmt->bindParam(1, $this->id);
//     $stmt->execute();
//     $row = $stmt->fetch(PDO::FETCH_ASSOC);
//     $this->route_no=$row['route_no'];
// }
}
?>