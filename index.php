<?php
header("content-type: text/html");
session_start(['cookie_lifetime' => 1800]);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require $_SERVER['DOCUMENT_ROOT'] . "/autoload.php";

use src\classes\Navigation;
use src\classes\Connect;
use src\classes\Requests;

$request = new Requests($_GET, $_POST, $_SERVER);

// коннект к базе данных
$connect = new Connect('localhost', 'root', 'miniproject_db');
$connectResult = $connect->connect();
// коннект к базе данных

$url = ((!empty($request->getServerField('HTTPS'))) ? 'https' : 'http') . '://' . $request->getServerField('HTTP_HOST') . $request->getServerField('REQUEST_URI');

var_dump($_SESSION);

// отправка формы
if (!empty($request->getPost()) && !empty($request->getPostField('submit'))) {
    $connect->query("INSERT INTO reviews (name, review) VALUES ('" . $request->getPostField('name') . "', '" . $request->getPostField('review') . "')");

    $_SESSION['success'] = true;

    $url = explode('?', $url);
    $url = $url[0];
    $redirect = $url;
    header("location:" . $redirect);
    exit;
}
// отправка формы

//обработка формы авторизации

if (!empty($request->getPost()) && !empty($request->getPostField('auth_submit'))) {
    $stmt = $connectResult->prepare("SELECT password, email FROM users WHERE login=?");
    $password = $request->getPostField('password');
    $stmt->bind_param('s', $password);
    if (!($stmt->execute())) {
        $_SESSION['mistake'] = true;
        $url = explode('?', $url);
        $url = $url[0];
        $redirect = $url . "?mode=auth";
        header("location:" . $redirect);
        exit;
    } else {
        $stmt->bind_result($truePassword, $email);
        while ($stmt->fetch()) {
            $truePasswordEncrypt = $truePassword;
            $trueEmail = $email;
        }
        $truePassword = password_verify($request->getPostField('password'), $truePasswordEncrypt);
        if (!$truePassword) {
            $_SESSION['mistake'] = true;
            $url = explode('?', $url);
            $url = $url[0];
            $redirect = $url . "?mode=auth";
            header("location:" . $redirect);
            exit;
        } else {
            $_SESSION['auth_yes'] = true;
            $_SESSION['login'] = $request->getPostField('login');
            $_SESSION['email'] = $email;

            $url = explode('?', $url);
            $url = $url[0];
            $redirect = $url . "?mode=admin";
            header("location:" . $redirect);
            exit;
        }
    }

}

//обработка формы авторизации

//одобрение формы

if (!empty($request->getPost()) && !empty($request->getPostField('replace'))) {
    if ($request->getPostField('status')==='approved') {
        $connect->query("UPDATE reviews SET allowed = 1 WHERE id = " . $request->getPostField('id'));
    } else {
        $connect->query("UPDATE reviews SET allowed = 0 WHERE id = " . $request->getPostField('id'));
    }

    $url = explode('?', $url);
    $url = $url[0];
    $redirect = $url;
    header("location:" . $redirect . "?mode=admin");
    exit;
}

//одобрение формы
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="/css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="/css/styles.css">
	</head>
	<body>
        <?php
        if ($request->getGetField('mode') && $request->getGetField('mode') === 'admin') {

            if (!isset($_SESSION['auth_yes']) && $_SESSION['auth_yes'] !== true) {
                $url = explode('?', $url);
                $url = $url[0];
                $redirect = $url;
                header("location:" . $redirect);
                exit;
            }

            // входные данные для админки

            $adminNumRows = $connect->query('SELECT * FROM reviews WHERE allowed is null')->num_rows;
            $adminNavigation = new Navigation($adminNumRows, $request->getGetField('page') ?? NULL);
            $adminNavArray = $adminNavigation->navigationBuild();
            $str = $adminNavigation->getStr();
            $rowsOnPage = $adminNavigation->getRows();
            $adminNumRows = $adminNavigation->navigationParamCalculate();

            $adminReviewsRows = $connect->getRows("SELECT * FROM reviews WHERE allowed is null ORDER by date_time desc limit $str, $rowsOnPage");
            // входные данные для админки

            include $request->getServerField('DOCUMENT_ROOT') . "/src/templates/admin.php";
        } elseif ($request->getGetField('mode') && $request->getGetField('mode') === 'auth'){
            include $request->getServerField('DOCUMENT_ROOT') . "/src/templates/auth.php";
        } else {
            // постраницная навигация
            $numRows = $connect->query('SELECT * FROM reviews WHERE allowed = 1')->num_rows;
            $navigation = new Navigation($numRows, $request->getGetField('page') ?? NULL);
            $navArray = $navigation->navigationBuild();
            $str = $navigation->getStr();
            $rowsOnPage = $navigation->getRows();
            $numRows = $navigation->navigationParamCalculate();
            // постраницная навигация

            $reviewsRows = $connect->getRows("SELECT date_time, name, review from reviews WHERE allowed = 1 ORDER by date_time desc limit $str, $rowsOnPage");

            include $request->getServerField('DOCUMENT_ROOT') . "/src/templates/list.php";
        }
        ?>
	</body>
</html>
<?php
if (isset($_SESSION['success'])) {
    unset($_SESSION['success']);
}
if (isset($_SESSION['mistake'])) {
    unset($_SESSION['mistake']);
}

