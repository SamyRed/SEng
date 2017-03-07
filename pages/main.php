<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
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
        header ('Location: /?page=main&pn='.$pagesNum);
        die ();
    }
} else {
    header ('Location: /?page=main&pn='.$pagesNum);
    die ();
}
if ($currentPage > $pagesNum) {
    header ('Location: /?page=main&pn='.$pagesNum);
    die ();
}

$firstOnPage = $currentPage * $itemsOnPage - $itemsOnPage;
$q = $db->query ("SELECT * FROM `news` ORDER BY `date` DESC LIMIT $firstOnPage, $itemsOnPage");
if ($q) {
    if ($q->num_rows) {
        $news = $q;
    } else {
        $alerts[] = '<div class="alert alertSuccess">Новостей пока нет.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Невозможно выполнить запрос к базе данных. Обратитесь к администратору.</div>';
}
?>
<?php
echo alertsShow ();
if (!empty ($news)) {
?>
<div class="pgTitle">Новости</div>
<div class="pgBody">
<?php
    if ($pagesNum > 1) {
?>
    <div class="pager">
        <span>Страница: </span>
<?php
        if ($pagesNum <= 7) {
            for ($i = 1; $i <= $pagesNum; $i++) {
                echo "<a href=\"/?page=main&pn=$i\">[$i]</a>";
            }
        } else {
            echo "<a href=\"".SITEURL."?page=main&pn=1\">[1]</a>";
            if ($currentPage - 2 < 1) {
                for ($i = 2; $i <= 3; $i++) {
                    echo "<a href=\"/?page=main&pn=$i\">[$i]</a>";
                }
                echo ' ... ';
            } else if ($pagesNum - $currentPage < 2) {
                    echo '...';
                for ($i = $pagesNum - 2; $i <= $pagesNum - 1; $i++) {
                    echo "<a href=\"/?page=main&pn=$i\">[$i]</a>";
                }
            } else {
                echo ' ... ';
                for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
                    echo "<a href=\"/?page=main&pn=$i\">[$i]</a>";
                }
                echo ' ... ';
            }
            echo "<a href=\"/?page=main&pn=$pagesNum\">[$pagesNum]</a>";
        }
?>
    </div>
<?php
    }
    while ($item = $news->fetch_assoc ()) {
?>
    <div class="pgBlockTitle"><a href="/?page=fullnew&id=<?=$item['id']?>"><?=$item['title']?></a></div>
    <div class="pgBlockBody">
        <table width="100%">
            <tr>
                <td width="25%" class="newsImage">
                    <img src="/newsImages/<?=$item['id']?>.png" alt="<?=$item['title']?>">
                </td>
                <td width="75%" class="newsText">
                    <?=$item['shortNew']?>
                </td>
            </tr>
        </table>
    </div>
    <p class="bottomDesc">Добавил: <a href="/?page=user&u=<?=$item['author']?>"><?=$item['author']?></a> <?=seDateStr ($item['date'])?></p>
<?php
    }
?>
</div>
<?php
}
?>