<div id="form">
    <form action="" method="POST">
        <p><input type="text" name="login" class="form-control" placeholder="Логин" required"></p>
        <p><input type="password" name="password" class="form-control" placeholder="Пароль" required></p>
        <p><input type="text" name="email" class="form-control" placeholder="почта""></p>
        <p><input type="submit" name="create" class="btn btn-info btn-block" value="обновить"></p>
    </form>
</div>
<?php if ($sessionGetter->getField("loginCreate") === "n"){ ?>
    <div class="info alert alert-info">
        Логин уже занят
    </div>
<?php } elseif ($sessionGetter->getField("emailCreate") === "n") { ?>
    <div class="info alert alert-info">
        Почта уже занята
    </div>
<?php }?>