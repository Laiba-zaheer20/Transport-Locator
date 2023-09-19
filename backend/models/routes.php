<?php
class Routes{
  
    // database connection and table name
    private $conn;
    private $table_name = "routes";
  
    // object properties
    public $id; 
    public $route_no;
 
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // READ route_plan
    function read(){
  
    // select all query
    $query = "SELECT 
    `id`,
     route_no
      FROM " . $this->table_name;
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
    }

    // CREATE route_plan
    function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
            route_no=:route_no";
   
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->route_no=htmlspecialchars(strip_tags($this->route_no));
   
    // bind values
    $stmt->bindParam(":route_no",$this->route_no);
    
  
  
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
     `id`,
    route_no
   
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
            route_no=:route_no
           
            WHERE
                id =:id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
   // sanitize
   $this->route_no=htmlspecialchars(strip_tags($this->route_no));
  
   $this->id=htmlspecialchars(strip_tags($this->id));

   // bind values
   $stmt->bindParam(":route_no", $this->route_no);
   
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
}
?>