<!------------------------------------------->
<!-------#####--######--##--##---#####------->
<!------##------##------###-##--##----------->
<!-------####---#####---##-###--##-###------->
<!----------##--##------##--##--##--##------->
<!------#####---#####---##--##---####-------->
<!------------------------------------------->
<!--------------Автор: SamyRed--------------->
<!-----------http://seng.kl.com.ua----------->
<!------------------------------------------->
<!--Распространение и копирование разрешено-->
<!----при условии сохраненияя копирайтов----->
<!------------------------------------------->
<?php
define ('seng', true);

require_once './engine/configDB.php';
require_once './engine/config.php';
require_once './engine/functions.php';
require_once './engine/crone.php';

if (isset ($_GET['page']) && !empty ($_GET['page'])) {
    $page = $_GET['page'];
} else {
    header ('Location: /?page=main');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="./styles/main.css">
        <title><?=$config['siteTitle'].' - '.$config['siteDesc']?></title>
<?php
if ($config['animateTitle'] == 1) {
?>
        <script>
            (function titleMarquee() {
                document.title = document.title.substring(1) + document.title.substring(0,1) + " ";
                orgn_ttl = setTimeout (titleMarquee,600);
            })();
        </script>
<?php
}
?>
    </head>
    <body onload="scroller ()">
        <div class="background"></div>
<?php include_once './pages/blocks/header.php';?>
        <div id="wrapper">
<?php include_once './pages/blocks/topMenu.php';?>
            <main>
<?php
try {
    $file = "./pages/$page.php";
    if (!file_exists($file)) {
        throw new Exception ("<b>Ошибка!</b> Страница \"<b>$page</b>\" не найдена");
    }
    include $file;

} catch(Exception $e) {
    echo '<div class="alert alertDanger">'.$e->getMessage().'</div>';
}
?>
            </main>
<?php include_once './pages/blocks/sideBlock.php';?>
        </div>
    </body>
</html>