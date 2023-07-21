<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Гостевая книга</title>
    <link rel="stylesheet" href="../../css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="admin-link__wrap nav-item<?= isset($get["subModeAdmin"]) ? (($get["subModeAdmin"] === "users") ? " active" : "") : " active"?>>">
                    <a class="nav-link" href="/admin/users">Администраторы</a>
                </li>
                <li class="admin-link__wrap nav-item<?= isset($get["subModeAdmin"]) ? (($get["subModeAdmin"] === "reviewsCrud") ? " active" : "") : ""?>">
                    <a class="nav-link" href="/admin/reviewsCrud">Отзывы</a>
                </li>
            </ul>
        </div>
    </nav>