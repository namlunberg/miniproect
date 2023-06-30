<div id="wrapper">
    <h1>Гостевая книга</h1>
    <a href="/?mode=auth" class="auth__link">авторизация</a>
    <div>
        <nav>
            <ul class="pagination">
                <li class="<?= ($request->getGetField('page') === null) ? "disabled" : '';?>">
                    <?php if ($request->getGetField('page') === null) {?>
                        <div class="nav__arrow nav__arrow--prev" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </div>
                    <?php } else {?>
                        <a href="<?=($request->getGetField('page')>2)? '?page=' . $request->getGetField('page')-1 : '/'?>"  aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    <?php }?>
                </li>
                <?php foreach ($navArray as $navLink) { ?>
                    <?=$navLink?>
                <?php } ?>
                <li class="<?= ($request->getGetField('page') !== null && $request->getGetField('page') === "$numRows") ? "disabled" : '';?>">
                    <?php if ($request->getGetField('page') !== null && $request->getGetField('page') === "$numRows") {?>
                        <div class="nav__arrow nav__arrow--next" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </div>
                    <?php } else {?>
                        <a href="?page=<?=($request->getGetField('page') !== null) ? $request->getGetField('page')+1 : '2'?>"  aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    <?php }?>
                </li>
            </ul>
        </nav>
    </div>
    <?php foreach ($reviewsRows as $row) {?>
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