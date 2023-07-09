<?php



require_once __DIR__ . '/../config.class.php' ;
class BaseDao{
    private $conn ;
    
    private $table_name ; 


    //coustructor of dao class

    public function __construct($table_name)
    {
        $this->table_name = $table_name ;
        $host = Config::DB_HOST() ;
        $username = Config::DB_USERNAME() ; 
        $password = Config::DB_PASSWORD() ;
        $schema = Config::DB_SCHEME() ; 
        $port = Config::DB_PORT() ;
        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$schema",$username , $password) ;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    public function get_all(){
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->table_name) ;
        $stmt->execute();
        return  $stmt->fetchAll(PDO::FETCH_ASSOC) ;
        
    }
    public function get_by_id($id){
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    //DELETE FROM DATABASE
    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name. " WHERE idUsers = :id");
        $stmt->bindParam(':id',$id); //prevent sql injection
        $stmt->execute() ;
    }
    public function add($entity)
    {
        $query = "INSERT INTO " . $this->table_name . " (";
        foreach ($entity as $column => $value) {
            $query .= $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":" . $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= ")";


        $stmt = $this->conn->prepare($query);
        $stmt->execute($entity); // sql injection prevention
        $entity['id'] = $this->conn->lastInsertId();
        return $entity;
    }


    public function update($id,$entity,$id_column ="idUsers"){
        $query = "UPDATE " . $this->table_name . " SET ";
        foreach ($entity as $name => $value) {
            $query .= $name . "= :" . $name . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE $id_column = :id";


        $stmt = $this->conn->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);
    }
    protected function query($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    protected function query_unique($query, $params)
    {
        $results = $this->query($query, $params);
        return reset($results);
    }
   /* public function updateBalance($user_id, $amount) {
        // Prepare the SQL statement
        $stmt = $this->conn->prepare("UPDATE Users SET balance = balance + :amount WHERE idUsers = :user_id", ['user_id'=> $user_id,'amount'=> $amount]);
        $stmt->bindParam(":amount", $amount, PDO::PARAM_INT); 
    
        // Execute the prepared statement
        if ($stmt->execute()) {
          return true; // Update successful
        } else {
          return false; // Update failed
        }
      }*/
}
?>