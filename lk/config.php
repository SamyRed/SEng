<?
	/*
		��������� ��.
	*/
	
	$cfg = array(
		
		/* ------ ( MYSQL ��������� ) ------*/
		'db_host' 				=> '', //���� ����� ���� ������. *
		
		'db_name' 				=> '', //��� ����� ���� ������. *
		
		'db_user' 				=> '', //��� ������������ ����� ���� ������. *
		
		'db_pass' 				=> '', //������ ����� ���� ������. *
		
		'db_charset' 			=> 'cp1251', //��������� ����� ���� ������. *
		/* ------ ( MYSQL ��������� ) ------*/
		
		
		/* ------ ( ���������� � CMS ) ------ */
		
		//��������� �� ��������� �� ������ DLE. ���� � ��� DLE, �� ����� ���� �� �������!
		
		'id_session' 			=> 'dle_user_id', //Id ������ CMS. false - ������� ����������� � ��. *
		
		'column_Userpass' 		=> 'password', //������� � �������� �������������. ���� ��������������, ���� �������� id_session �� ����� �������� false.
		
		'table_Users' 			=> 'dle_users', //������� � ��������������. *
		
		'column_Id' 			=> 'user_id', //������� � Id �������������. *
		
		'column_Username' 		=> 'name', //������� � �������� �������������. *
		
		'column_Usergroup' 		=> 'user_group', //������� � �������� �������������. *
		
		'column_Usergroup_val' 	=> 1, //������� ID ������ ���������������. *
		/* ------ ( ���������� � CMS  ) ------*/
		
		
		/* ------ ( SYSTEM ) ------*/
		
		'folder_secret' 		=> 'secret/', //��������� ����� � �������(���, �������, ...). *
		
		'tpl' 					=> 'default', //������ ��. *
		
		'file_maxsize' 			=> 5, //������� ����. ������ ������������ �����/����� � ��. *
		
		'path_skin' 			=> 'upload/skins/', //������� ���� �� ����� �� �������. *
		
		'path_cloak' 			=> 'upload/cloaks/', //������� ���� �� ����� � �������. *
		
		'status_day' 			=> 30, //�� ������� ���� �������������� ������� �������. *
		
		'unban_price' 			=> 100, //�������������� ���� �������. //false - ��������� ������. *
		
		'unban_next_price'		=> 50, //�� ������� ���. ����� ������ ���� ����������� ��������. *
		
		'time_post' 			=> 2, //����� �������� ����� ������� ������� � ���. false - ���������. *
		
		
		/* ���������� � ����� ������. �� ������!!! 'lk_new_version_info' = false - ���������. */
		
		'lk_new_version_info'	=> 'http://mine-ru-craft.ru/lk/lk_new_version.txt', //������ �� ���� ����� ������.
		
		'lk_version'			=> '0.8.0', //������� ������
		/* ------ ( SYSTEM ) ------*/
		
		
		/* ------ ( InterKassa ) ------*/
		'ik_co_id' 				=> '', //ID �����
		
		'ik_key' 				=> '' //��������� ����
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