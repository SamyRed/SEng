<?
	/* ������ �� XSS, SQL �������� */
		foreach($_POST as $key=>$val)
			if(!is_array($val)) $_POST_REAL[$key] = strip_tags(mysql_real_escape_string($val));
	/* ������ �� XSS, SQL �������� */
	
	if ( $cfg['unban_price'] ) {
	
		$user['ban_blocked'] = mysql_num_rows(mysql_query("SELECT `name` FROM `banlist` WHERE `name` = '{$user['name']}'"));
		$price_unban = $cfg['unban_price'] + $user['ban_count'] * $cfg['unban_next_price'];
		
	}
	
	/* ��������� �������� �� ����� � �� ����� */
	$file_status = file_get_contents($cfg['folder_secret'].'status.txt');
	
	if ( preg_match_all('/#(\d+)(.*)/', $file_status, $status_info) ) { 
		foreach($status_info[2] as $key=>$val) {
			$status_id = $status_info[1][$key];
			$status[$status_id] = explode('|', trim($status_info[2][$key]));
			
			if ( !$ajax_file && $status[$status_id][2] != -1 || $user['user_group_admin'] )	
				$tpl_set['statuses'] .= '
					<div class="lk-miniblock">
						<table>
							<td width="100"><p class="lk-h3">'.$status[$status_id][0].'</p></td>
							<td><button class="button" onclick="buyStatusId('.$status_id.')">'.($user['group_id'] != $status_id ? '������ �� '.$status[$status_id][2] : '�������� �� '.($status[$status_id][2]-$status[$status_id][3])).'�</button></td>
							'.($user['user_group_admin'] ? '<td><button title="�������������" class="button" onclick="formStatusId('.$status_id.')">*</button></td>' : false).'
						</table>
					</div>
				';
		}
	}	
	/* ��������� �������� �� ����� � �� ����� */	