<?php 

class ProjectDao{
        private $conn; 
        /*
        Class constructor used to establish connection to db
        */
    public function __construct() {
        try {
            $servername = "127.0.0.1" ;
            $username = "root" ;
            $password = "80Sarajevo" ;
            $schema = "ProjectSchema" ;
            $this->conn = new PDO("mysql:host=$servername;dbname=$schema",$username,$password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully" ;
            
        }catch(PDOException $e){
            echo "Connection failed : " . $e->getMessage();
        }
        
    }

    /*
    Metod used to get all students from database
    */
    public function get_all(){
        $stmt = $this->conn->prepare("SELECT * FROM Users") ;
        $stmt->execute();
        return $result = $stmt->fetchAll(PDO::FETCH_ASSOC) ;
     
    }

     /*
    Metod used to add students to database
    */
    public function add($first_name, $last_name){
        $stmt = $this->conn->prepare("INSERT INTO Users (first_name , last_name) VALUES ('$first_name', '$last_name')");
        $result = $stmt->execute() ; 
        
     
    }

      /*
    Metod used to update students from database
    */
    public function update($first_name, $last_name,$id){
        $stmt = $this->conn->prepare("UPDATE Users SET first_name= '$first_name',last_name ='$last_name' WHERE idUsers = $id");
        $stmt->execute() ;
        
     
    }
        /*
    Metod used to update students from database
    */
    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM Users WHERE idUsers =:id" ) ;
        $stmt->bindParam(':id',$id) ;
        $stmt->execute() ; 
        
     
    }
}

?>