<?php

use Controllers\Controller;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$loader = new FilesystemLoader(dirname(__DIR__) . "/templates");


$log = new Logger('user');




$userHandler = new StreamHandler('mess.log', Logger::INFO);
$log->pushHandler($userHandler);
$twig = new Environment($loader);
$controller = new Controller($twig,$log);
$login_successful = false;

date_default_timezone_set('Asia/Vladivostok');

$controller-> show_messages();
if (isset($_GET['logs'])) {
    echo("Список логов: ");
    $file = file_get_contents('mess.log');
    echo $file;
}

if (isset($_GET['login'])&&isset($_GET['password']) || (isset($_GET['logs'])) ) {
    setcookie('login', $_GET['login']);
    $usr = $_GET['login'];
    $pwd = $_GET['password'];
 //список: логины и пароли   
     if ($usr == 'admin' && $pwd == '000' )
         {
        $login_successful = true; 
        echo ("Авторизирован как   ");
        echo($_GET['login']);
        $log->info('User name is ', ['who' => $usr]);
    }
    else if (!(isset($_GET['logs']))){
        echo "<p>";
        echo("Неверный логин или пароль!");
        $log->error('no login or password!');

        
    }
}

if ($login_successful){
$controller-> mesform();
}

 if (isset($_GET['message'])){
    $controller->add_message_to_file($_GET['message'], $_COOKIE['login']) ; 
    header('Refresh: 0; url=index.php'); 
    
 if (isset($_GET['clear'])) {
    file_put_contents('mes.json', '{"messages":[]}');
    $log->info('Chat is cleared');
}
   
}
