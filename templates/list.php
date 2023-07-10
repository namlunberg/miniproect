
<div id="wrapper">
    <h1>Гостевая книга</h1>
    <div>
        <nav>
            <ul class="pagination">
                <li class="disabled">
                    <a href="?page=1"  aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="active"><a href="?page=1">1</a></li>
                <li><a href="?page=2">2</a></li>
                <li><a href="?page=3">3</a></li>
                <li><a href="?page=4">4</a></li>
                <li><a href="?page=5">5</a></li>
                <li>
                    <a href="?page=5" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php foreach ($reviewsRows as $row) {?>
        <div class="note">
            <p>
                <span class="date">
                    <?=$row["create_time"]?>
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
    <?php if ($sessionGetter->getField("success")){ ?>
        <div class="info alert alert-info">
            Запись успешно сохранена!
        </div>
    <?php } ?>
    <div id="form">
        <form action="" method="POST">
            <p><input name="name" class="form-control" placeholder="Ваше имя"></p>
            <p><textarea name="review" class="form-control" placeholder="Ваш отзыв"></textarea></p>
            <p><input type="submit" name="insert" class="btn btn-info btn-block" value="Сохранить"></p>
        </form>
    </div>
</div>