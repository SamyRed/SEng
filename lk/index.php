<?
	
	$tpl_set = array();
	
	require_once('config.php');
	require_once('general.php');
	
	if ( file_exists('send_get_lk.php') ) include('send_get_lk.php');
	
	if ( $user['user_group_admin'] && $cfg['lk_new_version_info'] ) {
	
		$arr = @explode(' | ', file_get_contents($cfg['lk_new_version_info']));
		if ( count($arr) > 2 && $arr[0] != $cfg['lk_version'] ) $message = '����� ����� ������ ������� �������� v'.$arr[0].'. <a href="'.$arr[1].'">�������</a> '.$arr[2];
		
	}
	
	/* ������ ����������� � �� */
	if ( !$user['name']  ) {
		if ( !$cfg['id_session'] ) include('login.php');
		exit();
	}
	
	$path_skin = $cfg['path_skin'].$user['name'].'.png';
	$path_cloak = $cfg['path_cloak'].$user['name'].'.png';
	
	if ( file_exists($path_skin) ) $tpl_set['skin_name'] = $user['name']; else $tpl_set['skin_name'] = 'default';
	
	/* �������� ������/������ � HD ������/ HD ������ */
	if ( is_uploaded_file($_FILES['file']['tmp_name']) ) {

		$imgsize = getimagesize($_FILES['file']['tmp_name']);
		if ( $_FILES['file']['size'] < $cfg['file_maxsize']*1024*1024 && $_FILES ['file']['type'] == 'image/png' ) {
			if ( $_POST_REAL['upload-skin'] )
			
				if ( $imgsize['0'] == 64 && $imgsize['1'] == 32) {
				
					if ( $status[$user['group_id']][5] && move_uploaded_file($_FILES['file']['tmp_name'], $path_skin) )
						$message = '��� ���� ������� ��������.';
					
				} else if ( $imgsize['0'] == 256 && $imgsize['1'] == 128 || $imgsize['0'] == 1024 && $imgsize['1'] == 512 ) {
					
					if ( $status[$user['group_id']][7] && move_uploaded_file($_FILES['file']['tmp_name'], $path_skin) )
						$message = '��� HD ���� ������� ��������.';
					
				} else $message = '�������� ������� �����.';
				
			else if ( $_POST_REAL['upload-cloak'] )
			
				if ( $imgsize['0'] == 64 && $imgsize['1'] == 32 || $imgsize['0'] == 22 && $imgsize['1'] == 17) {
				
					if ( $status[$user['group_id']][6] && move_uploaded_file($_FILES['file']['tmp_name'], $path_cloak) )
						$message = '��� ���� ������� ��������.';
						
				} else if ( $imgsize['0'] == 512 && $imgsize['1'] == 256 ) {
					
					if ( $status[$user['group_id']][7] && move_uploaded_file($_FILES['file']['tmp_name'], $path_cloak) )
						$message = '��� HD ���� ������� ��������.';
					
				} else $message = '�������� ������� �����.';
		} else $message = '���� ������ ���� ������� .png � ������ �� ����� '.$cfg['file_maxsize'].'Mb.';
		
	}
	/* �������� ������/������ � HD ������/ HD ������ */
	
	if ( $message ) $tpl_set['message'] = '<div class="alert">'.$message.'</div>';
	
	include('templates/'.$cfg['tpl'].'/lk.tpl');
	
	mysql_close();