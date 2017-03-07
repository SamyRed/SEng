<?php
if (!defined ('seng')) {
    echo '<link rel="stylesheet" href="./styles.css">';
    echo '<div id="alert alertDanger"><b>Ошибка!</b> У Вас нет доступа к данному файлу. Обратитесь к администратору.</div>';
    die ();
}

/*---------------------------Отображение сообщений----------------------------*/
function alertsShow () {
    global $alerts;
    if (!empty ($alerts)) {
        $alertsStr = '';
        foreach ($alerts as $item) {
            $alertsStr .= $item;
        }
        return $alertsStr;
    } else {
        return false;
    }
}

/*----------------------------------Группы------------------------------------*/
function groups ($type = false, $var = false, $perms = false) {
    global $db;
    switch ($type) {
        case 'list':
            $q = $db->query ("SELECT * FROM `groups`");
            return $q;
        case 'id':
            if ($var === false) {
                return 'Ошибка в groups(). Ожидается идентификатор группы во втором параметре!';
            }
            $q = $db->query ("SELECT * FROM `groups` WHERE `id` = '".$var."' LIMIT 1");
            if ($perms === 1) {
                return unserialize ($q->fetch_assoc()['permissions']);
            } else {
                return $q->fetch_assoc();
            }
        case 'title':
            if ($var === false) {
                return 'Ошибка в groups(). Ожидается название группы во втором параметре!';
            }
            $q = $db->query ("SELECT * FROM `groups` WHERE `title` = '".$var."' LIMIT 1");
            if ($perms === 1) {
                return unserialize ($q->fetch_assoc()['permissions']);
            } else {
                return $q->fetch_assoc();
            }
        default:
            return 'Ошибка в groups(). Ожидается \'list\',\'id\', или \'title\' в первом параметре!';
    }
}

/*-------------------------------Пользователи---------------------------------*/
function users ($type, $var = false) {
    global $db;
    switch ($type) {
        case 'list':
            $q = $db->query ("SELECT * FROM `users`");
            return $q;
        case 'id':
            if ($var === false) {
                return 'Ошибка в users()! Ожидается идентификатор пользователя во втором параметре.';
            }
            $q = $db->query ("SELECT * FROM `users` WHERE `id` = '".$var."' LIMIT 1");
            if ($q) {
                if ($q->num_rows) {
                    return $q->fetch_assoc ();
                } else {
                    return false;
                }
            }
        case 'username':
            if ($var === false) {
                return 'Ошибка в users()! Ожидается логин пользователя во втором параметре.';
            }
            $q = $db->query ("SELECT * FROM `users` WHERE `username` = '".$var."' LIMIT 1");
            if ($q) {
                if ($q->num_rows) {
                    return $q->fetch_assoc ();
                } else {
                    return false;
                }
            }
        case 'group':
            if (intval ($var)) {
                $group = $var;
            } else {
                if (!$group = groups ('title', $var)['id']) {
                    return 'Ошика в users()! Ошибочный второй параметр. Ожидается id, или название группы.';
                }
            }
            $q = $db->query ("SELECT * FROM `users` WHERE `groupID` = '".$group."'");
            return $q;
        default:
            return 'Ошибка в users(). Ожидается \'list\', \'id\', или \'login\' в первом параметре.';
    }
}

function uuid ($var) {
    $val = md5("OfflinePlayer:".$var, true);
    $byte = array_values(unpack('C16', $val));
    $tLo = ($byte[0] << 24) | ($byte[1] << 16) | ($byte[2] << 8) | $byte[3];
    $tMi = ($byte[4] << 8) | $byte[5];
    $tHi = ($byte[6] << 8) | $byte[7];
    $csLo = $byte[9];
    $csHi = $byte[8] & 0x3f | (1 << 7);
    if (pack('L', 0x6162797A) == pack('N', 0x6162797A)) {
        $tLo = (($tLo & 0x000000ff) << 24) | (($tLo & 0x0000ff00) << 8) | (($tLo & 0x00ff0000) >> 8) | (($tLo & 0xff000000) >> 24);
        $tMi = (($tMi & 0x00ff) << 8) | (($tMi & 0xff00) >> 8);
        $tHi = (($tHi & 0x00ff) << 8) | (($tHi & 0xff00) >> 8);
    }
    $tHi &= 0x0fff;
    $tHi |= (3 << 12);
    $uuid = sprintf(
        '%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
        $tLo, $tMi, $tHi, $csHi, $csLo,
        $byte[10], $byte[11], $byte[12], $byte[13], $byte[14], $byte[15]
    );
    return $uuid;
}

/*------------------------------Данные пользователя---------------------------*/
function user ($var = false) {
    global $db;
    if ($var === false) {
        return 'Ошибка в user(). Не указан параметр функции.';
    } else {
        $q = $db->query ("SELECT * FROM `users` WHERE `id` = '".$_SESSION['id']."' LIMIT 1");
        $user = $q->fetch_assoc ();
        $params = ['id', 'username', 'email', 'regDate', 'regIP', 'lastDate', 'lastIP', 'groupID'];
        $q = $db->query ("SELECT * FROM `users` WHERE `id` = '".$_SESSION['id']."' LIMIT 1");
        if ($q) {
            if (!$q->num_rows) {
            return false;
            }
        } else {
            return 'Ошибка в user (). Не удалось выполнить заброс к базе данных.';
        }
        $user = $q->fetch_assoc ();
        if (in_array($var, $params)) {
            return $user[$var];
        } else if ($var == 'groupName') {
            return 'user (). Не реализовано';
        } else if ($var == 'groupNameStyle') {
            return 'user (). Не реализовано';
        } else {
            return 'Ошибка в user(). Указан ошибочный параметр функции.';
        }
    }
}

/*----------------------------------Деньги------------------------------------*/
function money ($type = false, $var = false) {
    global $db;
    switch ($type) {
        case 'list':
            $q = $db->query ("SELECT * FROM `money`");
            return $q;
        case 'id':
            if ($var === false) {
                return 'Ошибка в money()! Ожидается идентификатор пользователя во втором параметре.';
            }
            $q = $db->query ("SELECT * FROM `money` WHERE `id` = '".$var."' LIMIT 1");
            return $q->fetch_assoc();
        case 'username':
            if ($var === false) {
                return 'Ошибка в money()! Ожидается лоигн пользователя во втором параметре.';
            }
            $q = $db->query ("SELECT * FROM `money` WHERE `username` = '".$var."' LIMIT 1");
            return $q->fetch_assoc();
        default:
            return 'Ошибка в money(). Ожидается \'list\', \'id\', или \'username\' в первом параметре.';
    }
}

/*----------------------------------Монеты------------------------------------*/
function coins ($type, $var, $server = false) {
    global $db;
    switch ($type) {
        case "list":
            if ($server === false) {
                $q = $db->query ("SELECT * FROM `coins`");
                return $q;
            } else {
                $q = $db->query ("SELECT * FROM `".$server."coins`");
                return $q;
            }
        break;
        case "username":
            if ($server === false) {
                $q = $db->query ("SELECT * FROM `coins` WHERE `username` = '$var' LIMIT 1");
                return $q->fetch_assoc();
            } else {
                $q = $db->query ("SELECT * FROM `".$server."coins` WHERE `username` = '$var' LIMIT 1");
                return $q->fetch_assoc();
            }
        break;
        default:
            return "Ошибка в coins()! Ожидается 'list', 'id', или 'login' в первом параметре.";
    }
}

function servers ($type = false) {
    global $db;
    if ($type === false) {
        $q = $db->query ("SELECT * FROM `servers`");
        return $q;
    }
}

/*-------------------Проверка, авторизован ли пользователь--------------------*/
function autorized () {
    global $db;
    if (isset ($_SESSION['id'])) {
        $userId = $db->real_escape_string ($_SESSION['id']);
        if (!empty ($userId) && $userId != 0) {
            $q = $db->query ("SELECT * FROM `users` WHERE `id` = '$userId'");
            if ($q) {
                if ($q->num_rows) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/*----------------------------------------------------------------------------*/
/*---------------------Функции привелегий пользователей-----------------------*/
/*----------------------------------------------------------------------------*/

/*---------------------------Доступ к админпанели-----------------------------*/
function adminAccess ($type = false, $var = false) {
    global $db;
    $q = $db->query ("SELECT * FROM `groups`");
    $groupsID = [];
    while ($item = $q->fetch_assoc ()) {
        $perms = unserialize($item['permissions']);
        if ($perms['adminAccess'] == 1) {
            array_push($groupsID, $item['id']);
        }
    }
    if ($type === false) {
        $type = 'id';
    }
    switch ($type) {
        case 'list':
            $q = $db->query ("SELECT * FROM `users` WHERE `groupID` IN ('".implode (',', $groupsID)."')");
            if ($q) {
                if ($q->num_rows) {
                    return $q;
                } else {
                    return 'Ошибка в adminAccess()! Пользователь не авторизован. Необходимо ввести второй параметр.';
                }
            } else {
                return false;
            }
        case 'id':
            if ($var === false) {
                if (isset ($_SESSION['id'])) {
                    $var = $_SESSION['id'];
                } else {
                    return 'Ошибка в adminAccess()! Пользователь не авторизован. Необходимо ввести второй параметр.';
                }
            }
            $q = $db->query ("SELECT * FROM `users` WHERE `groupID` IN ('".implode (',', $groupsID)."') AND `id` = '$var' LIMIT 1");
            if ($q) {
                if ($q->num_rows) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return 'dbError';
            }
        case 'username':
            if ($var === false) {
                if (isset ($_SESSION['id'])) {
                    $var = user ('username');
                } else {
                    return 'Ошибка в adminAccess()! Пользователь не авторизован. Необходимо ввести второй параметр.';
                }
            }
            $q = $db->query ("SELECT * FROM `users` WHERE `groupID` IN ('".implode (',', $groupsID)."') AND `username` = '$var' LIMIT 1");
            if ($q) {
                if ($q->num_rows) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return 'dbError';
            }
        case 'ip':
            if ($var === false) {
                if (isset ($_SESSION['id'])) {
                    $var = user ('regIP');
                } else {
                    return 'Ошибка в adminAccess()! Пользователь не авторизован. Необходимо ввести второй параметр.';
                }
            }
            $q = $db->query ("SELECT * FROM `users` WHERE `groupID` IN ('".implode (',', $groupsID)."') AND `regIP` = '$var' LIMIT 1");
            if ($q) {
                if ($q->num_rows) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return 'dbError';
            }
    }
}

function news ($type = false, $var = false) {
    global $db;
    switch ($type) {
        case 'list':
            $q = $db->query ("SELECT * FROM `news` ORDER BY `date` DESC");
            if ($q) {
                if ($q->num_rows) {
                    return $q;
                } else {
                    return false;
                }
            } else {
                return 'Ошибка в news ()! Невозможно выполнить запрос к базе данных.';
            }
        case 'id':
            if ($var === false) {
                return 'Ошибка в news ()! Ожидается идентификатор группы во втором параметре в первом параметре';
            } else {
                $q = $db->query ("SELECT * FROM `news` WHERE `id` = '$var' ORDER BY `date` DESC");
                if ($q) {
                    if ($q->num_rows) {
                        return $q->fetch_assoc ();
                    } else {
                        return 'Ошибка в news ()! Новость с таким идентификатором не найдена.';
                    }
                } else {
                    return 'Ошибка в news ()! Невозможно выполнить запрос к базе данных.';
                }
            }
        default:
            return 'Ошибка в news ()! Ожидается \'list\', или \'id\' в первом параметре';
    }
}

/*----------------------------------------------------------------------------*/
/*---------------------////////////////////////////////-----------------------*/
/*----------------------------------------------------------------------------*/

/*--------------------------Возвращает checked если всё ок--------------------*/
function checked($var = false) {
    if ($var === false) {
        return false;
    } else if ($var == 1 || $var === true) {
        return 'checked';
    } else {
        return 'Ошибка в checked()! Передан неизвестный параметр.';
    }
}

/*----------------------------------------------------------------------------*/
/*------------------------------Работа с датой--------------------------------*/
/*----------------------------------------------------------------------------*/

/*-----------------------Полная дата 12.03.1997, 12:43------------------------*/
function seDateFull ($var = false) {
    if ($var === false) {
        $var = time ();
    }
    return date ("d.m.Y, H:i", $var);
}

/*-----------------------Полная дата Вчера, в 12:43---------------------------*/
function seDateStr ($var = false) {
    $date_str = new DateTime(date ('Y-m-d H:i:s', $var));
    $date = $date_str->Format('d.m.Y');
    $date_month = $date_str->Format('d.m');
    $date_year = $date_str->Format('Y');

    $date_time = $date_str->Format('H:i');

    $ndate = date('d.m.Y');
    $ndate_time = date('H:i');
    $ndate_time_m = date('i');
    $ndate_exp = explode('.', $date);
    $nmonth = array(
        1 => 'янв',
        2 => 'фев',
        3 => 'мар',
        4 => 'апр',
        5 => 'мая',
        6 => 'июн',
        7 => 'июл',
        8 => 'авг',
        9 => 'сен',
        10 => 'окт',
        11 => 'ноя',
        12 => 'дек'
    );

    foreach ($nmonth as $key => $value) {
        if($key == intval($ndate_exp[1])) $nmonth_name = $value;
    }

    if ($date == date('d.m.Y')) {
        return 'Cегодня в ' .$date_time;
    }

    else if ($date == date('d.m.Y', strtotime('-1 day'))) {
        return 'Вчера в ' .$date_time;
    }

    else if ($date != date('d.m.Y') && $date_year != date('Y')) {
        return $ndate_exp[0].' '.$nmonth_name.' '.$ndate_exp[2]. ' в '.$date_time;
    }

    else {
        return $ndate_exp[0].' '.$nmonth_name.' в '.$date_time;
    }
}
/*--------------------------Только дата 12.03.1997----------------------------*/
function seDateDate ($var = false) {
    if ($var === false) {
        $var = time ();
    }
    return date ("d.m.Y", $var);
}

/*-----------------------------Только время 12:43-----------------------------*/
function seDateTime ($var = false) {
    if ($var === false) {
        $var = time ();
    }
    return date ("H:i", $var);
}