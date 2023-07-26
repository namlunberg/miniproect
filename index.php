<?php
header("content-type: text/html");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require $_SERVER['DOCUMENT_ROOT'] . "/src/autoload.php";

use Services\BaseConnect;
use Services\Repositories\UsersRepository;
use Services\Repositories\ReviewsRepository;
use Services\Repositories\NewsRepository;
use Services\Requests\Request;
use Services\Router;
use Services\Security;
use Services\ServiceContainer;

session_start();

BaseConnect::setBaseName("miniproect_db");
BaseConnect::setUser("root");
BaseConnect::setHostName("localhost");
BaseConnect::setPassword("");
BaseConnect::connect();
$connect = new BaseConnect();
ServiceContainer::addService('connect',$connect);

$usersRepository = new UsersRepository();
ServiceContainer::addService('usersRepository', $usersRepository);

$reviewsRepository = new ReviewsRepository();
ServiceContainer::addService('reviewsRepository', $reviewsRepository);

$newsConnect = new NewsRepository();
ServiceContainer::addService('newsConnect', $newsConnect);

$request = new Request($_GET, $_POST, $_SERVER);
ServiceContainer::addService('request',$request);

$security = new Security();
ServiceContainer::addService('security',$security);

$router = new Router();
ServiceContainer::addService('router',$router);



$router->addRouts([
    "/" => ["path" => "Controllers\ReviewsController", "action" => "list", "name"=>"review list"],
    "/auth" => ["path" => "Controllers\AuthController", "action" => "auth", "name"=>"authorization"],
    "/news" => ["path" => "Controllers\NewsController", "action" => "news", "name"=>"news list"],

    "/admin" => ["path" => "Controllers\Admin\UsersController", "action" => "users", "name"=>"admin panel"],
    "/admin/users" => ["path" => "Controllers\Admin\UsersController", "action" => "users", "name"=>"admin panel"],
    "/admin/updateUser" => ["path" => "Controllers\Admin\UsersController", "action" => "userUpdate", "name"=>"update user"],
    "/admin/createUser" => ["path" => "Controllers\Admin\UsersController", "action" => "userCreate", "name"=>"create user"],
    "/admin/deleteUser" => ["path" => "Controllers\Admin\UsersController", "action" => "userDelete", "name"=>"delete user"],

    "/admin/reviewsCrud" => ["path" => "Controllers\Admin\ReviewsCrudController", "action" => "reviews", "name"=>"review panel"],
    "/admin/updateReview" => ["path" => "Controllers\Admin\ReviewsCrudController", "action" => "reviewUpdate", "name"=>"review update"],
    "/admin/activateReview" => ["path" => "Controllers\Admin\ReviewsCrudController", "action" => "reviewActivate", "name"=>"review activate"],
    "/admin/deactivateReview" => ["path" => "Controllers\Admin\ReviewsCrudController", "action" => "reviewDeactivate", "name"=>"review deactivate"],
    "/admin/deleteReview" => ["path" => "Controllers\Admin\ReviewsCrudController", "action" => "reviewDelete", "name"=>"review delete"],
]);
$router->run();


//if ($security->isAuthTableName("users")) {
//    $security->updateAuth("users");
//}