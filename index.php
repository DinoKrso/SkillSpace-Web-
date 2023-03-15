<?php 
require 'vendor/autoload.php' ;

Flight::route('/', function(){
    echo 'Hello world!' ;
    echo " Test " ;
});

Flight::start();
?>