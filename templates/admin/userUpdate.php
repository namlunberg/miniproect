<div id="form">
    <form action="" method="POST">
        <p><input type="text" name="login" class="form-control" placeholder="Логин" value="<?=$userRow["login"]?>"></p>
        <p><input type="password" name="password" class="form-control" placeholder="Пароль"></p>
        <p><input type="text" name="email" class="form-control" placeholder="почта" value="<?=$userRow["email"]?>"></p>
        <p><input type="submit" name="update" class="btn btn-info btn-block" value="обновить"></p>
    </form>
</div>