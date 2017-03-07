<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="../styles/main.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}
if (!empty ($_POST)) {
    var_dump ($_POST);
}
if (isset ($_POST['pcDonateSubm'])) {
    
}
if (isset ($_POST['pcExchangeSubm'])) {
    if ($money = intval ($_POST['pcExchangeCount'])) {
        $coins = $money * $config['exchangeRate'];
        if (user ('id') !== false) {
            $q = $db->query ("SELECT * FROM `money` WHERE `id` = {$_SESSION['id']}");
            if ($q) {
                if ($q->num_rows) {
                    if ($q->fetch_assoc ()['balance'] >= $coins) {
                        $q = $db->query ("UPDATE `money` SET `balance` = `balance` - $money WHERE `id` = {$_SESSION['id']}");
                        if ($q) {
                            $q = $db->query ("UPDATE `coins` SET `balance` = `balance` + $coins WHERE `id` = {$_SESSION['id']}");
                            if ($q) {
                                header ('Location: '.$_SERVER['HTTP_REFERER']);
                                die ();
                            } else {
                                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных. Обратитесь к администратору.</div>';
                            }
                        } else {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных. Обратитесь к администратору.</div>';
                        }
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> У Вас недостаточно средств.</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Запись денег этого пользователя отсутствует. Обратитесь к администратору</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных. Обратитесь к администратору.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пользователь с таким идентификатором не найден.</div>';
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Количество обмениваемых денег должно быть целым числом.</div>';
    }
}
if (isset ($_POST['pcEnrollSubm'])) {
    if ($coins = intval ($_POST['pcEnrollCount'])) {
        if ($server = intval ($_POST['pcEnrollSrv'])) {
            if ($username = user ('username')) {
                $q = $db->query ("SELECT * FROM `coins` WHERE `username` = '$username'");
                if ($q) {
                    if ($q->num_rows) {
                        if ($q->fetch_assoc ()['balance'] >= $coins) {
                            $q = $db->query ("UPDATE `coins` SET `balance` = `balance` - $coins WHERE `username` = '$username'");
                            if ($q) {
                                $q = $db->query ("UPDATE `".$server."coins` SET `balance` = `balance` + $coins WHERE `username` = '$username'");
                                if ($q) {
                                    header ('Location: '.$_SERVER['HTTP_REFERER']);
                                    die ();
                                } else {
                                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных. Обратитесь к администратору.</div>';
                                }
                            } else {
                                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных. Обратитесь к администратору.</div>';
                            }
                        } else {
                            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> У Вас недостаточно средств.</div>';
                        }
                    } else {
                        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Запись денег этого пользователя отсутствует. Обратитесь к администратору</div>';
                    }
                } else {
                    $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Не удалось выполнить запрос к базе данных. Обратитесь к администратору.</div>';
                }
            } else {
                $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Пользователь с таким идентификатором не найден.</div>';
            }
        } else {
            $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Выберите сервер.</div>';
        }
    } else {
        $alerts[] = '<div class="alert alertDanger"><b>Ошибка!</b> Количество обмениваемых денег должно быть целым числом.</div>';
    }
}
echo alertsShow ();
if (autorized ()) {
?>
<div class="pgTitle">Баланс</div>
<div class="pgBlockBody">
    <center>
        <h4><?=money ('id', $_SESSION['id'])['balance']?> руб. / <?=coins ('username', user ('username'))['balance']?> м.</h4>
        <form action="" method="post">
            <select name="pcDonateSystem" class="inputSelect inline">
                <option value="interkassa">Interkassa</option>
            </select>
            <input type="number" min="0" max="9999999" value="100" name="pcDonateCount" class="inputText inline">
            <input type="submit" name="pcDonateSubm" value="Пополнить" class="button inline">
        </form>
    </center>
</div>
<div class="pgTitle">Обменник</div>
<div class="pgBlockBody">
    <center>
        <i>Здесь вы можете обменять рубли на монеты по курсу: <br><br><center><b>1 руб. = <?=$config['exchangeRate']?> м.</b></center><br>Сколько рублей вы хотите обменять?</i><br>
        <form action="" method="post">
            <input type="number" min="0" max="999999999" value="0" name="pcExchangeCount" class="inputText inline">
            <input type="submit" value="Обменять" name="pcExchangeSubm" class="button inline">
        </form>
        <br>
        <form action="" method="post">
            <select name="pcEnrollSrv" class="inputSelect inline">
                <option value="0">Выберите сервер</option>
<?php
$servers = servers ();
while ($item = $servers->fetch_assoc ()) {
?>
                <option value="<?=$item['id']?>"><?=$item['title']?></option>
<?php
}
?>
            </select>
            <input type="number" min="0" max="999999" value="0" name="pcEnrollCount" class="inputText inline">
            <input type="submit" name="pcEnrollSubm" value="Зачислить" class="button inline">
        </form>
    </center>
</div>
<?php
}
?>