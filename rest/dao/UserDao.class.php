<?php 
require_once __DIR__ . '/BaseDao.class.php' ;
class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("Users");
    }


    // custom function, which is not present in BaseDao
    // query_unique -> returns only 1 result if multiple are present
    function getUserByFirstNameAndLastName($firstName, $lastName)
    {
        return $this->query_unique("SELECT * FROM Users WHERE first_name = :firstName AND last_name = :lastName", ["first_name" => $firstName, "last_name" => $lastName]);
    }
    public function get_user_by_email($email){
        return $this->query("SELECT * FROM Users WHERE email = :email", ['email' => $email]);
      }

      public function get_user_students($user_id){
        return $this->query('SELECT * FROM Users WHERE idUsers = :user_id', ['user_id' => $user_id]);
    }
    public function updateBalance($user_id, $balance){
        return $this->query('UPDATE Users SET balance = :balance WHERE idUsers = :user_id', ['user_id' => $user_id, 'balance' => $balance]) ;
    }
 
    }


?>