<?php
class Stops{
  
    // database connection and table name
    private $conn;
    private $table_name = "stops";
  
    // object properties
    public $id; 
    public $name;
    public $latitude;
    public $longitude;
    public $route_id; 
    public $area_id; 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // READ stops
    function read(){
  
    // select all query
    $query = "SELECT 
    stops.id AS id,
    stops.name AS `name`,
    stops.latitude AS latitude,
    stops.longitude AS longitude,
    stops.route_id AS route_id,
    routes.route_no AS route_no,
    stops.area_id AS area_id,
    area.name AS area
    
    FROM `stops`
    left join `area` ON stops.area_id=area.id
    left join `routes` ON stops.route_id=routes.id
    ";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
    }

    // CREATE stops
    function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
            name=:name,
            latitude=:latitude,
            longitude=:longitude,
            route_id=:route_id,
            area_id=:area_id
            ";
  
   
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->latitude=htmlspecialchars(strip_tags($this->latitude));
    $this->longitude=htmlspecialchars(strip_tags($this->longitude));
    $this->route_id=htmlspecialchars(strip_tags($this->route_id));
    $this->area_id=htmlspecialchars(strip_tags($this->area_id));
  
    // bind values
    $stmt->bindParam(":name",$this->name);
    $stmt->bindParam(":latitude",$this->latitude);
    $stmt->bindParam(":longitude",$this->longitude);
    $stmt->bindParam(":route_id",$this->route_id);
    $stmt->bindParam(":area_id",$this->area_id);
  
   
   
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
    }

    // used when filling up the update product form
    function readOne(){
  
    // query to read single record
    $query = "SELECT 
    stops.id AS id,
    stops.name AS `name`,
    stops.latitude AS latitude,
    stops.longitude AS longitude,
    stops.route_id AS route_id,
    routes.route_no AS route_no,
    stops.area_id AS area_id,
    area.name AS area
    FROM `stops`
    left join `area` ON stops.area_id=area.id
    left join `routes` ON stops.route_id=routes.id
    WHERE stops.id =? LIMIT 1";
  
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
            name=:name,
            latitude=:latitude,
            longitude=:longitude,
            route_id=:route_id,
            area_id=:area_id
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
   // sanitize
   $this->name=htmlspecialchars(strip_tags($this->name));
   $this->latitude=htmlspecialchars(strip_tags($this->latitude));
   $this->longitude=htmlspecialchars(strip_tags($this->longitude));
   $this->route_id=htmlspecialchars(strip_tags($this->route_id));
   $this->area_id=htmlspecialchars(strip_tags($this->area_id));
   $this->id=htmlspecialchars(strip_tags($this->id));

   // bind values
   $stmt->bindParam(":name", $this->name);
   $stmt->bindParam(":latitude", $this->latitude);
   $stmt->bindParam(":longitude", $this->longitude);
   $stmt->bindParam(":route_id", $this->route_id);
   $stmt->bindParam(":area_id", $this->area_id);
   $stmt->bindParam(":id", $this->id);
  
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

    function fetch_stops_via_route(){
        $query="SELECT 
        stops.id AS id,
        stops.name AS `name`,
        stops.latitude AS latitude,
        stops.longitude AS longitude,
        stops.route_id AS route_id, 
        area.name AS area_name     
                from `stops` 
                 join `area` ON stops.area_id=area.id
                WHERE stops.route_id=?";
          // prepare query
         $stmt = $this->conn->prepare($query);
         // sanitize
      $this->route_id=htmlspecialchars(strip_tags($this->route_id));
         $stmt->bindParam(1,$this->route_id);
    
         // execute query
         if($stmt->execute()){
             return $stmt;
         }
       
         return false;
         }



    //      function read_stops_via_route_id(){
    //          // delete query
    //  // select all query
    //  $query = "SELECT 
    //     id,
    //  FROM `stops`
    //  WHERE route_id=?
    //  ";
  
    // // prepare query
    // $stmt = $this->conn->prepare($query);
  
    // // sanitize
    // $this->id=htmlspecialchars(strip_tags($this->id));
  
    // // bind id of record to delete
    // $stmt->bindParam(1, $this->route_id);
  
    // // execute query
    // if($stmt->execute()){
    //     return true;
    // }
  
    // return false;
    //      }
}
?>