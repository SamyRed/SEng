<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="./styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (isset ($_POST['deleteNew'])) {
    if (!empty ($_POST['newID'])) {
        if ($newDelID = intval ($_POST['newID'])) {
            $q = $db->query ("SELECT * FROM `news` WHERE `id` = '$newDelID'");
            if ($q) {
                if ($q->num_rows) {
                    $res = $q->fetch_assoc ();
                    $q = $db->query ("DELETE FROM `news` WHERE `id` = '$newDelID'");
                    if ($q) {
                        $_SESSION['alerts'][] = '<div class="alert alertSuccess"><b>Выполнено!</b> Новость "<b>'.$res['title'].'</b>" удалена.</div>';
                        header ('Location: '.$_SERVER['HTTP_REFERER']);
                        die ();
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Новость с таким идентификатором не найдена.</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Идентификатор новости должен быть числом.</div>';
        }
    }
}
$itemsOnPage = 25;
$q = $db->query ("SELECT count(id)as count FROM `news`");
if ($q) {
    $itemsNum = $q->fetch_assoc ()['count'];
}
$pagesNum = ceil ($itemsNum / $itemsOnPage);
if ($pagesNum <= 0) {
    $pagesNum = 1;
}
if (isset ($_GET['pn'])) {
    if (!$currentPage = intval ($_GET['pn'])) {
    header ('Location: /admin/?page=news&pn='.$pagesNum);
    die ();
    }
} else {
    header ('Location: /admin/?page=news&pn='.$pagesNum);
    die ();
}
if ($currentPage > $pagesNum) {
    header ('Location: /admin/?page=news&pn='.$pagesNum);
    die ();
}

$firstOnPage = $currentPage * $itemsOnPage - $itemsOnPage;
?>
<div class="pgTitle">Новости</div>
<?php
echo alertsShow ();
?>
<div class="pgBody" id="adminNews">
<form action="/admin/?page=newsAdd" method="post">
    <input type="submit" class="button inline" value="Добавить">
</form>
<?php
$q = $db->query ("SELECT * FROM `news` ORDER BY `id` LIMIT $firstOnPage, $itemsOnPage");
if ($q) {
    if ($q->num_rows) {
?>
    <p class="topDesc inline">Всего <b><?=$q->num_rows?></b> новостей.</p>
<?php
        if ($pagesNum > 1) {
?>
    <div class="pager">
        <span>Страница: </span>
<?php
            if ($pagesNum <= 7) {
                for ($i = 1; $i <= $pagesNum; $i++) {
                    echo "<a href=\"".SITEURL."?page=users&pn=$i\">[$i]</a>";
                }
            } else {
                echo "<a href=\"".SITEURL."?page=users&pn=1\">[1]</a>";
                if ($currentPage - 2 < 1) {
                    for ($i = 2; $i <= 3; $i++) {
                        echo "<a href=\"".SITEURL."?page=users&pn=$i\">[$i]</a>";
                    }
                    echo ' ... ';
                } else if ($pagesNum - $currentPage < 2) {
                        echo '...';
                    for ($i = $pagesNum - 2; $i <= $pagesNum - 1; $i++) {
                        echo "<a href=\"".SITEURL."?page=users&pn=$i\">[$i]</a>";
                    }
                } else {
                    echo ' ... ';
                    for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
                        echo "<a href=\"".SITEURL."?page=users&pn=$i\">[$i]</a>";
                    }
                    echo ' ... ';
                }
                echo "<a href=\"".SITEURL."?page=users&pn=$pagesNum\">[$pagesNum]</a>";
            }
?>
    </div>
<?php
        }
?>
    <table width="100%" cessspacing="0">
        <tr>
            <td>ID</td>
            <td>Тема</td>
            <td>Дата добавления</td>
            <td>Автор</td>
            <td></td>
        </tr>
<?php
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
            <td><?=seDateFull ($item['date']);?></td>
            <td><a href="/admin/?page=userEdit&id=<?=users ('username', $item['author'])['id']?>"><?=$item['author']?></a></td>
            <td>
                <a href="/admin/?page=newEdit&id=<?=$item['id']?>" class="button inline">Редактировать</a>
                <form action="" method="post" class="inline">
                    <input type="hidden" value="<?=$item['id']?>" name="newID">
                    <input type="submit" value="Удалить" class="button inline" name="deleteNew">
                </form>
            </td>
        </tr>
<?php
            $i++;
        }
?>
    </table>
<?php
    } else {
?>
        <div class="alert alertSuccess">Ни одной новости пока нет.</div>
<?php
    }
} else {
?>
        <div class="alert alertDanger"><b>Ошибка!</b> Невозможно сделать выборку из базы данных.</div>
<?php
}
?>
</div>