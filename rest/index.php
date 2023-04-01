<?php 
require "../vendor/autoload.php" ;
require "dao/ProjectDao.class.php" ; 

Flight::register("project_dao","ProjectDao") ;
 
Flight::route("/", function(){
    echo "Hello from / route";
});

Flight::route("GET /users", function(){
    //echo "Hello from /project route";
    //$project_dao = new ProjectDao() ;
    //$results = Flight::project_dao()->get_all();
    Flight::json(Flight::project_dao()->get_all()) ;
});

Flight::route("DELETE /users/@id", function($id){
    //echo "Hello from /Project route";
    //$project_dao = new ProjectDao() ;
    Flight::project_dao()->delete($id);
    Flight::json(['message' => "User deleted successfully"]) ;
});

Flight::route("POST /users", function(){
    $project_dao = new ProjectDao() ;
    $request = Flight::request()->data->getData();
    //$response =  $project_dao->add($request);
    Flight::json(['message' => "User added successfully",'Data' =>  Flight::project_dao()->add($request)]) ;
});

Flight::route("PUT /users/@id", function($id){
    $project_dao = new ProjectDao() ;
    $request = Flight::request()->data->getData();
    //$response =  $project_dao->update($request,$id);
    Flight::json(['message' => "User edited successfully",'Data' => Flight::project_dao()->update($request,$id)]) ;
});
Flight::route("GET /users_by_id", function(){
    $id = Flight::request()->query['id'];
    Flight::json(Flight::project_dao()->get_by_id($id));
});

Flight::route("GET /users/@name", function($name) {
    echo "Hello from /users route with name = ".$name;
});

Flight::start();

?>