<?php
class DriverRoutes{
  
    // database connection and table name
    private $conn;
    private $table_name = "driver_routes";
  
    // object properties
    public $id; 
    public $shift;
    public $driver_id;
    public $route_id;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // READ driver_routes
    function read(){
  
    // select all query
    $query = "SELECT `id`,
     `shift`,
     driver_id,
     route_id
     FROM " . $this->table_name;
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
    }

    // CREATE driver_routes
    function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
            shift=:shift,
            driver_id=:driver_id,
            route_id=:route_id";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->shift=htmlspecialchars(strip_tags($this->shift));
    $this->driver_id=htmlspecialchars(strip_tags($this->driver_id));
    $this->route_id=htmlspecialchars(strip_tags($this->route_id));
  
    // bind values
    $stmt->bindParam(":shift", $this->shift);
    $stmt->bindParam(":driver_id", $this->driver_id);
    $stmt->bindParam(":route_id", $this->route_id);
  
  
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
    }

    // used when filling up the update product form
    function readOne(){
  
    // query to read single record
    $query = "SELECT `id`,
     `shift`,
      driver_id,
      route_id
      FROM " . $this->table_name . "
      WHERE id = ? LIMIT 1";
  
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
            shift=:shift,
            driver_id=:driver_id,
            route_id=:route_id
           
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
   // sanitize
   $this->shift=htmlspecialchars(strip_tags($this->shift));
   $this->driver_id=htmlspecialchars(strip_tags($this->driver_id));
   $this->route_id=htmlspecialchars(strip_tags($this->route_id));
   $this->id=htmlspecialchars(strip_tags($this->id));

   // bind values
   $stmt->bindParam(":shift", $this->shift);
   $stmt->bindParam(":driver_id", $this->driver_id);
   $stmt->bindParam(":route_id", $this->route_id);
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







function get_route_by_driver(){
    $query="SELECT routes.`route_no` AS route_no from driver_routes left join routes on driver_routes.route_id where 
    driver_routes.driver_id=? AND driver_routes.route_id=routes.id";
     // prepare query
     $stmt = $this->conn->prepare($query);
   
     // bind id of record to delete
     $stmt->bindParam(1, $this->driver_id);
   
     // execute query
     if($stmt->execute()){
         return $stmt;
     }
   
     return false;
     }
    }
?>