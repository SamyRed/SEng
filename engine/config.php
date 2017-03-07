<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo "<div id='alert alertDanger'><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>";
    die ();
}

$q = $db->query ("SELECT * FROM `config`");
if ($q) {
    if ($q->num_rows) {
        $config = [];
        while ($item = $q->fetch_assoc ()) {
            $config[$item['title']] = $item['value'];
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Отсутствуют настройки системы в таблице.</div>';
    }
} else {
    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных.</div>';
}

session_start ();

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

mysqli_report(MYSQLI_REPORT_STRICT);

if (isset ($_SESSION['alerts']) && !empty ($_SESSION['alerts'])) {
    $alerts = $_SESSION["alerts"];
    unset ($_SESSION['alerts']);
} else {
    $alerts = [];
}

date_default_timezone_set($config['timeZone']);