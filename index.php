<?php
header("content-type: text/html");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require $_SERVER['DOCUMENT_ROOT'] . "/src/autoload.php";

use Controllers\ReviewsController;
use Controllers\NewsController;
use Controllers\AuthController;
use Controllers\AdminController;
use Services\Requests\Request;
use Services\Security;
use Services\BaseConnect;

session_start(['cookie_lifetime' => 1800]);

$connect = new BaseConnect("localhost", "root", "miniproect_db");
$connect->connect();

$request = new Request($_GET, $_POST, $_SERVER);

switch ($request->getGet()->getField("mode"))
{
    case "admin":
        $adminAction = new AdminController($connect, $request, new Security($connect, $request));
        $adminAction->actionAdmin();
        break;
    case "auth" :
        $authAction = new AuthController($connect, $request);
        $authAction->actionAuth();
        break;
    case "news" :
        $newsAction = new NewsController($connect, $request);
        $newsAction->actionNews();
        break;
    default :
        $listAction = new ReviewsController($connect, $request);
        $listAction->actionList();
    break;
}