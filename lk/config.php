<?
	/*
		Настройки ЛК.
	*/
	
	$cfg = array(
		
		/* ------ ( MYSQL настройки ) ------*/
		'db_host' 				=> '', //Хост вашей базы данных. *
		
		'db_name' 				=> '', //Имя вашей базы данных. *
		
		'db_user' 				=> '', //Имя пользователя вашей базы данных. *
		
		'db_pass' 				=> '', //Пароль вашей базы данных. *
		
		'db_charset' 			=> 'cp1251', //Кодировка вашей базы данных. *
		/* ------ ( MYSQL настройки ) ------*/
		
		
		/* ------ ( Интеграция с CMS ) ------ */
		
		//Настроено по умолчанию на движок DLE. Если у вас DLE, то здесь ниче не трогать!
		
		'id_session' 			=> 'dle_user_id', //Id сессии CMS. false - сделать авторизацию в ЛК. *
		
		'column_Userpass' 		=> 'password', //Колонка с Паролями пользователей. Поле необязательное, если параметр id_session не имеет значения false.
		
		'table_Users' 			=> 'dle_users', //Таблица с пользователями. *
		
		'column_Id' 			=> 'user_id', //Колонка с Id пользователей. *
		
		'column_Username' 		=> 'name', //Колонка с Логинами пользователей. *
		
		'column_Usergroup' 		=> 'user_group', //Колонка с Группами пользователей. *
		
		'column_Usergroup_val' 	=> 1, //Укажите ID группы администраторов. *
		/* ------ ( Интеграция с CMS  ) ------*/
		
		
		/* ------ ( SYSTEM ) ------*/
		
		'folder_secret' 		=> 'secret/', //Секретная папка с файлами(Лог, Статусы, ...). *
		
		'tpl' 					=> 'default', //Шаблон ЛК. *
		
		'file_maxsize' 			=> 5, //Укажите макс. размер загружаемого скина/плаща в Мб. *
		
		'path_skin' 			=> 'upload/skins/', //Укажите путь до папки со скинами. *
		
		'path_cloak' 			=> 'upload/cloaks/', //Укажите путь до папки с плащами. *
		
		'status_day' 			=> 30, //На сколько дней осуществляется покупка статуса. *
		
		'unban_price' 			=> 100, //Первоначальная цена разбана. //false - отключить разбан. *
		
		'unban_next_price'		=> 50, //На сколько руб. будет дороже цена последующих разбанов. *
		
		'time_post' 			=> 2, //Время ожидания после каждого запроса в сек. false - отключить. *
		
		
		/* Информация о новой версии. НЕ МЕНЯТЬ!!! 'lk_new_version_info' = false - отключить. */
		
		'lk_new_version_info'	=> 'http://mine-ru-craft.ru/lk/lk_new_version.txt', //Ссылка на файл новой версии.
		
		'lk_version'			=> '0.8.0', //Текущая версия
		/* ------ ( SYSTEM ) ------*/
		
		
		/* ------ ( InterKassa ) ------*/
		'ik_co_id' 				=> '', //ID кассы
		
		'ik_key' 				=> '' //Секретный ключ
		/* ------ ( InterKassa ) ------*/
		
	);
	
	session_start();
	
	$mysql_connect = mysql_connect($cfg['db_host'], $cfg['db_user'], $cfg['db_pass']) or die(mysql_error()); 
	mysql_select_db($cfg['db_name'], $mysql_connect) or die(mysql_error()); 
	mysql_query("SET NAMES '".$cfg['db_charset']."'");
	
	if ( $cfg['id_session'] ) 
		$session = $_SESSION[$cfg['id_session']]; else $session = $_SESSION['lk_user_id'];
	
	$user_query = mysql_query("SELECT * FROM `{$cfg['table_Users']}` WHERE `{$cfg['column_Id']}` = '{$session}' LIMIT 1");
	$user = mysql_fetch_assoc($user_query);
	
	$user['name'] = $user[$cfg['column_Username']];
	$user['user_group'] = $user[$cfg['column_Usergroup']];
	if ( $user['user_group'] == $cfg['column_Usergroup_val'] ) $user['user_group_admin'] = true;