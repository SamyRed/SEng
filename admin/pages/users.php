<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
$itemsOnPage = 25;
$q = $db->query ("SELECT count(id)as count FROM `users`");
if ($q) {
    $itemsNum = $q->fetch_assoc ()['count'];
}
$pagesNum = ceil ($itemsNum / $itemsOnPage);
if (isset ($_GET['pn'])) {
    if (!$currentPage = intval ($_GET['pn'])) {
    header ('Location: /admin/?page=users&pn='.$pagesNum);
    die ();
    }
} else {
    header ('Location: /admin/?page=users&pn='.$pagesNum);
    die ();
}
if ($currentPage > $pagesNum) {
    header ('Location: /admin/?page=users&pn='.$pagesNum);
    die ();
}

$firstOnPage = $currentPage * $itemsOnPage - $itemsOnPage;
?>
<div class="pgTitle">Пользователи</div>
<?php
echo alertsShow ();
?>
<div class="pgBody">
<?php
$q = $db->query ("SELECT * FROM `users` ORDER BY `id` LIMIT $firstOnPage, $itemsOnPage");
if ($q) {
    if ($q->num_rows) {
?>
    <p class="topDesc">Всего зарегистрировано <b><?=$q->num_rows?></b> пользователей.</p>
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
            <td>Login</td>
            <td>Дата регистрации</td>
            <td>последнее посещение</td>
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
            <td><?=$item['username']?></td>
            <td><?=seDateFull ($item['regDate']);?></td>
            <td><?=seDateFull ($item['lastDate'])?></td>
            <td><a href="/admin/?page=userEdit&id=<?=$item['id']?>" class="buttonInline">Редактировать</a></td>
        </tr>
<?php
            $i++;
        }
?>
    </table>
<?php
    } else {
?>
        <div class="alert alertSuccess">Ни один пользователь не зарегистрирован.</div>
<?php
    }
} else {
?>
        <div class="alert alertDanger"><b>Ошибка!</b> Невозможно сделать выборку из базы данных. Обратитесь к администратору.</div>
<?php
}
?>
</div>