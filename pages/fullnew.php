<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_GET['id'])) {
    if ($id = intval ($_GET['id'])) {
        if (news ('id', $id) === false) {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Новость с таким идентификатором не найдена.</div>';
        } else {
            $new = news ('id', $id);
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор новости должен быть числом.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор новости не найден.</div>';
}
?>
<?php
echo alertsShow();
if (alertsShow() === false) {
?>
<div class="pgTitle"><?=$new['title']?></div>
<div class="pgBody">
    <table width="100%">
        <tr>
            <td width="25%" class="newsImage">
                <img src="/newsImages/<?=$item['id']?>.png" alt="<?=$new['title']?>">
            </td>
            <td width="75%" class="newsText">
                <?=$new['fullNew']?>
            </td>
        </tr>
    </table>
    <p class="newsTags">Теги: <?=implode (', ', unserialize ($new['tags']))?></p>
    <p class="bottomDesc">Добавил: <a href="/?page=user&u=<?=$new['author']?>"><?=$new['author']?></a> <?=seDateStr ($new['date'])?></p>
</div>
<?php
}
?>