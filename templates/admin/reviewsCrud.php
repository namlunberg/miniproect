<div>
    <nav>
        <ul class="pagination">
            <li class="<?=($pageNumber === 1) ? 'disabled' : ''?>">
                <?php if ($pageNumber === 1) { ?>
                    <span aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </span>
                <?php } else { ?>
                    <a href="<?=($pageNumber > 2 ? $currentUrl . "&page=" . $pageNumber-1 : $currentUrl)?>" aria-label="Previous">
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
                        <a href="<?=($i === 1) ? $currentUrl : $currentUrl . "&page=" . $i?>"><?=$i?></a>
                    </li>
                <?php }?>
            <?php }?>
            <li class="<?=($pageNumber === $sumPages) ? 'disabled' : ''?>">
                <?php if ($pageNumber === $sumPages) { ?>
                    <span aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </span>
                <?php } else { ?>
                    <a href="<?=$currentUrl?>&page=<?=($pageNumber !== 1) ? $pageNumber+1 : 2?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                <?php }?>
            </li>
        </ul>
    </nav>
</div>
<?php foreach ($reviewsRows as $row) { ?>
    <div>
        <div><?=$row["create_time"]?></div>
        <div><?=$row["name"]?></div>
        <div><?=$row["review"]?></div>
        <div><?=$row["update_time"]?></div>
        <div><?php
            foreach ($adminActivity as $curAdmin) {
                if ($row["adminId"] === $curAdmin["adminId"]) {
                    echo $curAdmin["login"];
                    break;
                }
            }
        ?></div>
        <div><a href="/admin/<?=$row["status"] === "0" ? "activateReview" : "deactivateReview"?>?id=<?=$row["id"]?>"><?=$row["status"] === "0" ? "активировать" : "деактивировать"?></a></div>
        <div><a href="/admin/updateReview?id=<?=$row["id"]?>">изменить</a></div>
        <div><a href="/admin/deleteReview?id=<?=$row["id"]?>">удалить</a></div>
    </div>
<?php } ?>