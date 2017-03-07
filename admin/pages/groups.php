<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['deleteGroup'])) {
    if (!empty ($_POST['groupID'])) {
        if ($groupDelID = intval ($_POST['groupID'])) {
            $q = $db->query ("SELECT * FROM `groups` WHERE `id` = '$groupDelID'");
            if ($q) {
                if ($q->num_rows) {
                    $res = $q->fetch_assoc ();
                    $q = $db->query ("DELETE FROM `groups` WHERE `id` = '$groupDelID'");
                    if ($q) {
                        $alerts[] = '<div class="alert alertSuccess"><b>Выполнено!</b> Группа "<b>'.$res['title'].'</b>" удалена.</div>';
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Группа с таким идентификатором не найдена.</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Группа с таким идентификатором не найдена.</div>';
        }
    }
}
?>
<div class="pgTitle">Группы</div>
<?php
echo alertsShow ();
?>
<div class="pgBody">
    <table width="100%">
        <tr>
            <td width="5%">id</td>
            <td width="45%">Название</td>
            <td width="50%"></div>
        </tr>
<?php
$q = $db->query ("SELECT * FROM `groups` ORDER BY `id`");
$i = 0;
while ($item = $q->fetch_assoc ()) {
    if ($i%2 == 0) {
        $color = ' class="tableLight"';
    } else {
        $color = ' class="tableDark"';
    }
?>
        <tr<?=$color?>>
            <td><?=$item['id']?></td>
            <td><?=$item['tBefore'].$item['title'].$item['tAfter']?></td>
            <td>
                <a href="/admin/?page=groupEdit&id=<?=$item['id']?>" class="button inline">Редактировать</a>
                <form action="" method="post" class="inline">
                    <input type="hidden" value="<?=$item['id']?>" name="groupID">
                    <input type="submit" value="Удалить" class="button inline" name="deleteGroup">
                </form>
            </td>
        </tr>
<?php
$i++;
}
?>
    </table>
</div>
