<?php
class Area{
  
    // database connection and table name
    private $conn;
    private $table_name = "area";
  
    // object properties
    public $id;
    public $name;

    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // READ admin_executives
    function read(){
  
    // select all query
    $query = "SELECT * FROM " . $this->table_name;
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
    }

    // CREATE admin_executive
    function create(){
  
   
                $query = "INSERT INTO `area` (
                    `name`
                     )
                    VALUES
                    (?)"; 
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
//    $this->stop_id=htmlspecialchars(strip_tags($this->stop_id));
  
    // bind values
    
    $stmt->bindParam(1, $this->name);
  //  $stmt->bindParam(2, $this->stop_id);
   
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
    }

    // used when reading one record
    function readOne(){
  
    // query to read single record
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->name = $row['name'];
  //  $this->stop_id = $row['stop_id'];  
    }

    // update the product
    function update(){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name
                WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
   // $this->stop_id=htmlspecialchars(strip_tags($this->stop_id));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind new values
    $stmt->bindParam(':name', $this->name);
   // $stmt->bindParam(':stop_id', $this->stop_id);
    $stmt->bindParam(':id', $this->id);
  
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

  function fetch_area_via_stop(){
      $query="SELECT 
            stops.id AS stop_id,
             stops.area_id AS area_id,
             area.name AS `area_name`
           from `stops`  join `area` ON stops.area_id=area.id
              WHERE stops.id=?";
        // prepare query
       $stmt = $this->conn->prepare($query);
       // sanitize
    $this->stop_id=htmlspecialchars(strip_tags($this->stop_id));
       $stmt->bindParam(1,$this->stop_id);
  
       // execute query
       if($stmt->execute()){
           return $stmt;
       }
     
       return false;
       }
   
  
}
?>