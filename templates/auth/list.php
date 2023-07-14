
<div id="wrapper">
    <div id="form">
        <form action="" method="POST">
            <p><input type="text" name="login" class="form-control" placeholder="Логин"></p>
            <p><input type="password" name="password" class="form-control" placeholder="Пароль"></p>
            <p><input type="submit" name="auth" class="btn btn-info btn-block" value="Авторизоваться"></p>
        </form>
    </div>
    <?php if ($sessionGetter->getField("loginAuth") === "n"){ ?>
        <div class="info alert alert-info">
            Логин не верный
        </div>
    <?php } elseif ($sessionGetter->getField("passwordAuth") === "n") { ?>
        <div class="info alert alert-info">
           Пароль не верный
        </div>
    <?php } elseif ($sessionGetter->getField("passwordAuth") === "y") { ?>
        <div class="info alert alert-info">
            Авторизация прошла успешно
        </div>
    <?php }?>
</div>