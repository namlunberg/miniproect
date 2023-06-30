<div id="wrapper">
    <h2>Административная панель</h2>
    <p style="padding-bottom: 5px; border-bottom: solid 1px #000000">Модерация постов</p>
<?php if ($adminNumRows > 1) {?>
<div>
    <nav>
        <ul class="pagination">
            <li class="<?= ($request->getGetField('page') === null) ? "disabled" : '';?>">
                <?php if ($request->getGetField('page') === null) {?>
                    <div class="nav__arrow nav__arrow--prev" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </div>
                <?php } else {?>
                    <a href="<?=($request->getGetField('page')>2) ? $url . '&page=' . $request->getGetField('page')-1 : '?mode=admin'?>"  aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                <?php }?>
            </li>
            <?php foreach ($adminNavArray as $navLink) { ?>
                <?=$navLink?>
            <?php } ?>
            <li class="<?= ($request->getGetField('page') !== null && $request->getGetField('page') === "$adminNumRows") ? "disabled" : '';?>">
                <?php if ($request->getGetField('page') !== null && $request->getGetField('page') === "$adminNumRows") {?>
                    <div class="nav__arrow nav__arrow--next" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </div>
                <?php } else {?>
                    <a href="<?=$url?>&page=<?=($request->getGetField('page') !== null) ? $request->getGetField('page')+1 : '2'?>"  aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                <?php }?>
            </li>
        </ul>
    </nav>
</div>
<?php } ?>
<?php foreach ($adminReviewsRows as $row) {?>
    <div id="form">
        <form action="" method="POST">
            <p><input type="hidden" name="id" class="form-control" value="<?=$row['id']?>"</p>
            <p><input name="date_time" class="form-control" value="<?=$row['date_time']?>"</p>
            <p><input name="name" class="form-control" value="<?=$row['name']?>"</p>
            <p><textarea name="review" class="form-control"><?=$row['review']?></textarea></p>
            <div class="radio-box" style="display:flex; align-items: center; justify-content: space-between">
                <div class="radio-box__item" style="display:flex; align-items: center;">
                    <p style="margin-right: 10px;"><input id="radio-<?=$row['id']?>_approved" type="radio" name="status" value="approved" required></p>
                    <lable for="radio-<?=$row['id']?>_approved">одобряю</lable>
                </div>
                <div class="radio-box__item" style="display:flex; align-items: center;">
                    <p style="margin-right: 10px;"><input id="radio-<?=$row['id']?>_denied" type="radio" name="status" value="denied" required></p>
                    <lable for="radio-<?=$row['id']?>_denied">осуждаю</lable>
                </div>
            </div>
            <p><input type="submit" name="replace" class="btn btn-info btn-block" value="Вынести вердикт"></p>
        </form>
    </div>
<?php }?>
</div>
