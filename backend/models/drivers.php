<?php
class drivers{
  
    // database connection and table name
    private $conn;
    private $table_name = "drivers";

    // object properties
    public $id;
    public $name;
    public $phone_no;
    public  $login_id;
    public $password;


    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // READ admin_executives
    function read(){
  
    // select all query
    $query = "SELECT *
      FROM  `drivers`";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
    }

    // CREATE admin_executive
    function create(){
  
    // query to insert record
    $query = "INSERT INTO
                `drivers`
            SET
                name=:name,
                phone_no=:phone_no,
                login_id=:login_id,
               password=:password
                  ";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
    $this->login_id=htmlspecialchars(strip_tags($this->login_id));
    $this->password=htmlspecialchars(strip_tags($this->password));
    //$this->shift=htmlspecialchars(strip_tags($this->shift));

  
    // bind values
    $stmt->bindParam(":name",$this->name);
    $stmt->bindParam(":phone_no",$this->phone_no);
    $stmt->bindParam(":login_id",$this->login_id);
    $stmt->bindParam(":password",$this->password);
   // $stmt->bindParam(":shift",$this->shift);
    

  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
    }

    // used when reading one record
    function readOne(){
  
    // query to read single record
    $query = "SELECT *
      FROM  `drivers`
      
      WHERE drivers.id = ? LIMIT 1";
  
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
              drivers
              SET
            name=:name,
            phone_no=:phone_no  
            WHERE
            id=:id";
               
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
   // sanitize
   $this->name=htmlspecialchars(strip_tags($this->name));
   $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
  // $this->shift=htmlspecialchars(strip_tags($this->shift));
   $this->id=htmlspecialchars(strip_tags($this->id));


 
   // bind values
   $stmt->bindParam(":name",$this->name);
   $stmt->bindParam(":phone_no",$this->phone_no);
  // $stmt->bindParam(":shift",$this->shift);
   $stmt->bindParam(":id",$this->id);

  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
    }

    // delete the product
    function delete(){
      
    // delete query
    $query = " DELETE FROM `drivers` WHERE id = ?";
  
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
  else{
    return false;
    
    }
}





function get_login_id(){
  $query="SELECT SUBSTRING(login_id, 3, length(login_id)) AS login_id FROM `drivers`";
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
  $query="SELECT id from drivers where login_id=? AND `password`=?";
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
  

}
?>