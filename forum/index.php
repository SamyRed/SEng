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
    header ('Location: /forum/?page=main');
    die ();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$config['siteTitle']?> - форум</title>
        <link rel="stylesheet" href="../styles/main.css">
        <meta charset="utf8">
    </head>
    <body>
        <div class="background"></div>
<?php include_once '../pages/blocks/header.php';?>
        <div id="wrapper">
<?php include_once '../pages/blocks/topMenu.php';?>
            <main id="mainBold">
<?php
if (isset ($alerts) && !empty ($alerts)) {
    foreach ($alerts as $item) {
        echo $item;
    }
} else {
    try {
        $file = "./pages/$page.php";
        if (!file_exists($file)) {
            throw new Exception ("<b>Ошибка!</b> Страница $page не найдена");
        }
        include $file;

    } catch(Exception $e) {
        echo '<div class="alert alertDanger">'.$e->getMessage().'</div>';
    }
}
?>
            </main>
        </div>
    </body>
</html>