<?php
class Login{
  
    // database connection and table name
    private $conn;
    private $table_name = "login";
  
    // object properties
    public $id;
    public $name;
    public $password;
    public $login_id;
   
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // READ logins
    function read(){
  
    // select all query
    $query = "SELECT
      `id`,
      `password`,
       login_id
       FROM " . $this->table_name;
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
    }

    // CREATE login
    function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
             password=:password, login_id=:login_id";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->login_id=htmlspecialchars(strip_tags($this->login_id));
  
    // bind values
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":login_id", $this->login_id);
  
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
    `password`,
     login_id
    FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
  
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
                password = :password,
                login_id = :login_id
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->login_id=htmlspecialchars(strip_tags($this->login_id));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind new values
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':login_id', $this->login_id);
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
}
?>
