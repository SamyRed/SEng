<?php
	require('config.php');
	
	function ik_sign($ik_post, $secret_key) {
		ksort($ik_post, SORT_STRING);
		array_push($ik_post, $secret_key);
		return base64_encode(md5(implode(':', $ik_post), true));
	}
	
	foreach($_POST as $key=>$val)
		$_POST_REAL[$key] = strip_tags(mysql_real_escape_string($val));
	
	if ( $_POST_REAL['ik_submit'] ) {
		
		$ik_post = array();
		$ik_post = $_POST_REAL;
		
		unset($ik_post['ik_submit']);
		$ik_post['ik_x_username'] = $user['name'];
		
		foreach($ik_post as $key=>$val)
			$url .= '&'.$key.'='.urlencode($val);
		
		header('Location: https://sci.interkassa.com/?ik_x_sign='.ik_sign($ik_post, $cfg['ik_key']).$url);
		
	} else {
		
		$ik_post = array();
		$ik_post = $_POST_REAL;
		
		$ik_post_sign = array(
			'ik_co_id' => $ik_post['ik_co_id'], 
			'ik_am' => $ik_post['ik_am'], 
			'ik_pm_no' => $ik_post['ik_pm_no'], 
			'ik_desc' => $ik_post['ik_desc'],
			'ik_x_username' => $ik_post['ik_x_username']
		);
		
		if ( urldecode(ik_sign($ik_post_sign, $cfg['ik_key'])) == $_POST_REAL['ik_x_sign'] && $ik_post['ik_inv_st'] == 'success' && $ik_post['ik_co_id'] == $cfg['ik_co_id']) {
			
			if ( !mysql_num_rows(mysql_query("SELECT `id` FROM `lk_transaction` WHERE `id` = '{$ik_post['ik_inv_id']}'")) ) {
				
				mysql_query("INSERT INTO `lk_transaction` VALUES ({$ik_post['ik_inv_id']}, '{$ik_post['ik_x_username']}', ".round($ik_post['ik_co_rfn']).", ".time().", '{$ik_post['ik_inv_st']}')");
				mysql_query("UPDATE `{$cfg['table_Users']}` SET `money` = money+".round($ik_post['ik_co_rfn'])." WHERE `{$cfg['column_Username']}` = '{$ik_post['ik_x_username']}'");
				
			} else echo '������ ������ ��� ��������.';
			
		} else echo '�������� ����������� �������. ������ �� ��� ��������.';
		
	}