<?php
header("content-type: text/html");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require $_SERVER['DOCUMENT_ROOT'] . "/src/autoload.php";

use Services\BaseConnect;
use Services\BaseRepository;
use Services\Requests\Request;
use Services\Security;
use Services\Router;
use Services\ServiceContainer;

session_start();

BaseConnect::setBaseName("miniproect_db");
BaseConnect::setUser("root");
BaseConnect::setHostName("localhost");
BaseConnect::setPassword("");
BaseConnect::connect();
$connect = new BaseConnect();
ServiceContainer::addService('connect',$connect);

$usersTableConnect = new BaseRepository();
$usersTableConnect->setTableName("users");
ServiceContainer::addService('usersTableConnect', $usersTableConnect);

$reviewsTableConnect = new BaseRepository();
$reviewsTableConnect->setTableName("reviews");
ServiceContainer::addService('reviewsTableConnect', $reviewsTableConnect);

$newsTableConnect = new BaseRepository();
$newsTableConnect->setTableName("news");
ServiceContainer::addService('newsTableConnect', $newsTableConnect);

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