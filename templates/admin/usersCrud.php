<a href="/?mode=admin&subModeAdmin=createUser" class="create-user__link">
    добавить нового админа
</a>
<table style="border: solid 1px #000000">
    <tr style="border: solid 1px #000000">
        <th style="border: solid 1px #000000">
            id
        </th>
        <th style="border: solid 1px #000000">
            login
        </th>
        <th style="border: solid 1px #000000">
            email
        </th>
        <th style="border: solid 1px #000000">
            update
        </th>
        <th style="border: solid 1px #000000">
            delete
        </th>
    </tr>
    <?php foreach ($usersRows as $userRow) {?>
    <tr style="border: solid 1px #000000">
        <td style="border: solid 1px #000000"><?=$userRow["id"]?></td>
        <td style="border: solid 1px #000000"><?=$userRow["login"]?></td>
        <td style="border: solid 1px #000000"><?=$userRow["email"]?></td>
        <td style="border: solid 1px #000000"><a href="/?mode=admin&subModeAdmin=updateUser&id=<?=$userRow["id"]?>">изменить</a></td>
        <td style="border: solid 1px #000000"><a href="/?mode=admin&subModeAdmin=deleteUser&id=<?=$userRow["id"]?>">удалить</a></td>
    </tr>
    <?php }?>
</table>