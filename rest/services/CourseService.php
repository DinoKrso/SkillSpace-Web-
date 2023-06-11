<?php
require_once 'BaseService.php';
require_once __DIR__ . "/../dao/CourseDao.class.php";


class CourseService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new CourseDao);
    }


    function getUserByFirstNameAndLastName($firstName, $lastName)
    {
        return $this->dao->getUserByFirstNameAndLastName($firstName, $lastName);
    }
}
?>
