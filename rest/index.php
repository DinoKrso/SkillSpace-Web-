<?php
require '../vendor/autoload.php';

// import and register all business logic files (services) to FlightPHP
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/CourseService.php';
require_once "dao/UserDao.class.php" ;


Flight::register('userService', "UserService");
Flight::register('courseService', "CourseService");
Flight::register('userDao', "UserDao");



// import all routes
require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/CourseRoutes.php';



// it is still possible to add custom routes after the imports
Flight::route('GET /', function () {
    echo "Hello";
});

Flight::start();

?>
