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

require_once '../engine/configDB.php';
require_once '../engine/config.php';
require_once '../engine/functions.php';
require_once '../engine/crone.php';

if (isset ($_GET['page']) && !empty ($_GET['page'])) {
    $page = $_GET['page'];
} else {
    header ('Location: /admin/?page=system');
    die ();
}
if (adminAccess () !== true) {
    require './pages/blocks/login.php';
    die ();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$config['siteTitle']?> - админпанель</title>
        <link rel="stylesheet" href="../styles/main.css">
        <meta charset="utf8">
    </head>
    <body>
        <div class="background"></div>
<?php include_once './pages/blocks/header.php';?>
        <div id="wrapper">
<?php include_once './pages/blocks/topMenu.php';?>
            <main id="mainBold">
<?php
try {
    $file = "./pages/$page.php";
    if (!file_exists($file)) {
        throw new Exception ("<div class=\"alert alertDanger\"><b>Ошибка!</b> Страница $page не найдена</div>");
    }
    include $file;

} catch(Exception $e) {
    echo $e->getMessage();
}
?>
            </main>
        </div>
    </body>
</html>
