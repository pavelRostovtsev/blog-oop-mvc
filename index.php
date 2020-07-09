<?php 
session_start();

include_once 'public_html/application/lib/Dev.php';



use public_html\application\lib\DB;
use public_html\application\core\Router;
use public_html\application\controllers\AccountController;
spl_autoload_register(function ($className) {
    if (file_exists(__DIR__ .DIRECTORY_SEPARATOR . str_replace('\\', '/' , $className ) .'.php')) {
    	include_once __DIR__ .DIRECTORY_SEPARATOR . str_replace('\\', '/' , $className ) .'.php' ;
    } 
});






 $router = new Router;
 $router -> run();
 

 