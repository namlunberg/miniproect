<?php
header("content-type: text/html");

if (isset($_GET['page'])) {
    $_GET['page'] = (int) $_GET['page'];
}

//if ($_GET['page'] && $_GET['page']===1) {
//    header("location:/");
//}

session_start();

$conn = mysqli_connect("localhost", "root", "", "miniproject_db");
if (!$conn) {
    die("Ошибка: " . mysqli_connect_error());
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

include 'src/functions.php';

// постраничная навигация

$sql = "SELECT * FROM reviews";
$question = mysqli_query($conn, $sql);
$rowsOnPage = 5;
$numRows = ceil(mysqli_num_rows($question) / $rowsOnPage);
$navArray = [];
if (isset($_GET['page'])) {
    $nav = $_GET['page'];
    $str = $_GET['page'] * $rowsOnPage - $rowsOnPage;
} else {
    $nav = 0;
    $str = 0;
}
for ($i = 1; $i <= $numRows; $i++) {
    if ($i === 1) {
        $navArray[] = '<li' . (($str === 0) ? ' class="active"' : "") . '><a href="/">' . $i . '</a></li>';
    } elseif ($i !== $nav) {
        $navArray[] = '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
    } else {
        $navArray[] = '<li class="active"><a href="?page=' . $i . '">' . $i . '</a></li>';
    }
}
// пострапницная навигация

// отправка формы
if (!empty($_POST) && !empty($_POST["submit"])) {
    $sql = "INSERT INTO reviews (name, review) VALUES ('" . $_POST['name'] . "', '" . $_POST['review'] . "')";
    mysqli_query($conn, $sql);

    $_SESSION['success'] = true;

//    $urlGet = "?page=" . $numRows;
    $url = explode('?', $url);
    $url = $url[0];
    $redirect = $url; // . $urlGet;
    header("location:" . $redirect);
    exit;
}
// отправка формы
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Гостевая книга</h1>
			<div>
                <nav>
                    <ul class="pagination">
                        <li class="<?= (!isset($_GET['page'])) ? "disabled" : '';?>">
                            <?php if (!isset($_GET['page'])) {?>
                                <div class="nav__arrow nav__arrow--prev" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </div>
                            <?php } else {?>
                                <a href="<?=($_GET['page']>2)? '?page=' . $_GET['page']-1 : '/'?>"  aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            <?php }?>
                        </li>
                        <?php foreach ($navArray as $navLink) { ?>
                            <?=$navLink?>
                        <?php } ?>
                        <li class="<?= (isset($_GET['page']) && $_GET['page']=="$numRows") ? "disabled" : '';?>">
                            <?php if (isset($_GET['page']) && $_GET['page']=="$numRows") {?>
                                <div class="nav__arrow nav__arrow--next" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </div>
                            <?php } else {?>
                                <a href="?page=<?=(isset($_GET['page'])) ? $_GET['page']+1 : '2'?>"  aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            <?php }?>
                        </li>
                    </ul>
                </nav>
			</div>
            <?php
            $sql = "SELECT * from reviews ORDER by date_time desc limit $str, $rowsOnPage";
            $reviewsRows = getFieldsFromDb($sql, $conn, ['date_time', 'name', 'review']);
            foreach ($reviewsRows as $row) {
            ?>
                <div class="note">
                    <p>
                        <span class="date">
                            <?=$row["date_time"]?>
                        </span>
                        <span class="name">
                            <?=$row["name"]?>
                        </span>
                    </p>
                    <p>
                        <?=$row["review"]?>
                    </p>
                </div>
            <?php }?>
            <?php if (isset($_SESSION['success']) && $_SESSION['success']){ ?>
			<div class="info alert alert-info">
				Запись успешно сохранена!
			</div>
            <?php } ?>
			<div id="form">
				<form action="" method="POST">
					<p><input name="name" class="form-control" placeholder="Ваше имя"></p>
					<p><textarea name="review" class="form-control" placeholder="Ваш отзыв"></textarea></p>
					<p><input type="submit" name="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>
	</body>
</html>
<?php
if (isset($_SESSION['success'])) {
    unset($_SESSION['success']);
}

