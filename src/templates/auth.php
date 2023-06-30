<div id="wrapper">
    <h2 class="auth__title">
        форма авторизации
    </h2>
    <?php if (isset($_SESSION['mistake']) && $_SESSION['mistake']){ ?>
        <div class="info alert alert-info">
            Логин или пароль введены неверно!
        </div>
    <?php } ?>
    <div id="form">
        <form action="" method="POST">
            <input type="text" name="login">
            <input type="password" name="password">
            <input type="submit" name="auth_submit">
        </form>
    </div>
</div>