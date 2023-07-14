
<div id="wrapper">
    <h1>Гостевая книга</h1>
    <div>
        <nav>
            <ul class="pagination">
                <li class="<?=($pageNumber === 1) ? 'disabled' : ''?>">
                    <?php if ($pageNumber === 1) { ?>
                        <span aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </span>
                    <?php } else { ?>
                        <a href="<?=($pageNumber > 2 ? "?page=" . $pageNumber-1 : "/")?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    <?php }?>
                </li>
                <?php for ($i=1; $i<=$sumPages; $i++) {?>
                    <?php if (($i === $pageNumber) || ($i === 1 && $pageNumber === 1)){?>
                        <li class="active">
                            <span><?=$i?></span>
                        </li>
                    <?php } else {?>
                    <li>
                        <a href="<?=($i === 1) ? "/" : "?page=" . $i?>"><?=$i?></a>
                    </li>
                    <?php }?>
                <?php }?>
                <li class="<?=($pageNumber === $sumPages) ? 'disabled' : ''?>">
                    <?php if ($pageNumber === $sumPages) { ?>
                        <span aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </span>
                    <?php } else { ?>
                        <a href="?page=<?=($pageNumber !== 1) ? $pageNumber+1 : 2?>" aria-label="Next">
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