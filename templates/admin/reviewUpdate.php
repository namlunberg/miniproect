<div id="form">
    <form action="" method="POST">
        <p><input type="text" name="name" class="form-control" placeholder="Имя" value="<?=$reviewRow["name"]?>"></p>
        <p><textarea name="review" class="form-control" placeholder="отзыв"><?=$reviewRow["review"]?></textarea></p>
        <p><input type="radio" name="status" class="form-control" value="<?="1"?>" <?=$reviewRow["status"] === "1" ? "checked" : ""?>>Активировать</p>
        <p><input type="radio" name="status" class="form-control" value="<?="0"?>" <?=$reviewRow["status"] === "0" ? "checked" : ""?>>Деактивировать</p>
        <p><input type="submit" name="update" class="btn btn-info btn-block" value="обновить"></p>
    </form>
</div>