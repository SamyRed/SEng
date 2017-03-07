<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
?>
<div class="pgTitle">Темы</div>
<div class="pgBody" id="fsThemes">
<?php
echo alertsShow ();
?>
    <form action="/admin/?page=forumThemeAdd" method="post">
        <input type="submit" class="button inline" value="Добавить">
    </form>
    <table width="100%">
        <tr>
            <td width="5%">id</td>
            <td width="45%">Название</td>
            <td width="50%"></div>
        </tr>
<?php
$q = $db->query ("SELECT * FROM `forumThemes`");
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
            <td><?=$item['title']?></td>
            <td>
                <a href="/admin/?page=ForumThemeEdit&id=<?=$item['id']?>" class="button inline">Редактировать</a>
                <form action="" method="post" class="inline">
                    <input type="hidden" value="<?=$item['id']?>" name="ThemeID">
                    <input type="submit" value="Удалить" class="button inline" name="deleteTheme">
                </form>
            </td>
        </tr>
<?php
$i++;
}
?>
    </table>
</div>