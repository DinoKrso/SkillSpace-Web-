<?php
require_once 'BaseService.php';
require_once __DIR__ . "/../dao/UserDao.class.php";


class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new UserDao);
    }


    function getUserByFirstNameAndLastName($firstName, $lastName)
    {
        return $this->dao->getUserByFirstNameAndLastName($firstName, $lastName);
    }
    function add($user)
    {
        $user['password'] = md5($user['password']);
        return parent::add($user);
    }

    function update($id, $user)
    {
        $user['password'] = md5($user['password']);
        return parent::update($id, $user);  
    }

    public function updateBalance($user_id, $amount)
    {
        return $this->dao->updateBalance($user_id, $amount);
    }
    function delete($id)
    {
        return $this->dao->delete($id) ;
    }
}
?>
