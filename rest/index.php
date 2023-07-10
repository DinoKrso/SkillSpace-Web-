<?php
require '../vendor/autoload.php';

// import and register all business logic files (services) to FlightPHP
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/CourseService.php';
require_once "dao/UserDao.class.php" ;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


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

 // middleware method for login
Flight::route('/*', function(){
    //perform JWT decode
    $path = Flight::request()->url;
    if ($path == '/login' || $path == '/docs.json') return TRUE; // exclude login route from middleware
  
    $headers = getallheaders();
    if (!$headers['Authorization']){
      Flight::json(["message" => "Authorization is missing"], 403);
      return FALSE;
    }else{
      try {
        $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
        $current_time = time() ; 
        if(isset($decoded['exp']) && $decoded['exp'] < $current_time){
          Flight::json(["message" => "Auth token has expired"],403) ;
          return false;
        }
        Flight::set('user', $decoded);
        return TRUE;
      } catch (\Exception $e) {
        Flight::json(["message" => "Authorization token is not valid"], 403);
        return FALSE;
      }
    }
  });

  /* REST API documentation endpoint */
    Flight::route('GET /docs.json', function(){
    $openapi = \OpenApi\scan('routes');
    header('Content-Type: application/json');
    echo $openapi->toJson();
  });

Flight::start();

?>
