<?
	$ajax_file = true;
	require('config.php');
	require_once('general.php');
	
	/* Защита от постоянных запросов */
	if ( $cfg['time_post'] && count($_POST) ) {
		
		$path = $cfg['folder_secret'].'post.txt';
		$get_content = file_get_contents($path);
		
		if ( preg_match('/['.$_SERVER['REMOTE_ADDR'].'] => (.*)/', $get_content, $info) ) {
		
			if ( trim($info[1]) > time()-$cfg['time_post'] )
				exit('Wait '.( $cfg['time_post'] - (time()-trim($info[1])) ).' seconds...'); 
			else file_put_contents($path, $_SERVER['REMOTE_ADDR'].' => '.time()."\n".str_replace($_SERVER['REMOTE_ADDR'].' => '.$info[1], '', $get_content));
		
		} else file_put_contents($path, $_SERVER['REMOTE_ADDR'].' => '.time()."\n".$get_content);
	}
	/* Защита от постоянных запросов */
	
	/* Удаление */
	if ( $_POST_REAL['del'] ) {
		
		switch ( $_POST_REAL['del'] )
		{
			case 1: {
				if ( @unlink($cfg['path_skin'].$user['name'].'.png') )
					$message = 'Скин удален.'; else $message = 'У вас нет скина.';
				break;
			}
			
			case 2: {
				if ( @unlink($cfg['path_cloak'].$user['name'].'.png') )
					$message = 'Плащ удален.'; else $message = 'У вас нет плаща.';
				break;
			}
			
			case 3; {
				if ( $cfg['unban_price'] && $user['money'] >= $price_unban ) {
					if ( $user['ban_blocked'] ) {
						
						mysql_query("DELETE FROM `banlist` WHERE `name` = '{$user['name']}'");
						$user_info_set = 'ban_count = ban_count+1, money = money-'.$price_unban;
						$message = 'Вы успешно разблокированы.';
						$log = 'Пользователь '.$user['name'].' разблокировал себя за '.$price_unban.'р.';
						
					} else $message = 'Вы не найдены в списке заблокированных.';
				} else $message = 'Вам не хватает денег для разблокировки.';
				break;
			}
			
			case 4: {
				if ( $user['user_group_admin'] ) {
				
					$path = $cfg['folder_secret'].'status.txt';
					file_put_contents($path, preg_replace('/#('.$_POST_REAL['statusid'].')(.*)/', '', file_get_contents($path)));
					$message = 'Статус '.$status[$_POST_REAL['statusid']][0].' удален.';
					$log = 'Администратор '.$user['name'].' удалил статус '.$status[$_POST_REAL['statusid']][0];
					
				}
				break;
			}
			
			case 5: {
				if ( $user['user_group_admin'] ) {
				
					file_put_contents($cfg['folder_secret'].'log.txt', "Администратор ".$user['name']." очистил лог файл.\n");
					
					$message = 'Лог файл успешно очищен.';
					
				}
			}
		}
	}
	/* Удаление */
	
	/* Добавление/Сохранение */
	if ( $_POST_REAL['save'] ) {
		switch ( $_POST_REAL['save'] )
		{
			case 1: {
				if ( $status[$user['group_id']][8] )  {
					if ( preg_match('/^[0-9 fabcde]{3}$/', $_POST_REAL['colorprefix'].$_POST_REAL['colorname'].$_POST_REAL['colormsg']) && preg_match('/^\w{1,10}$/i', $_POST_REAL['textprefix']) ) {
						
						$prefix = '[&'.$_POST_REAL['colorprefix'].$_POST_REAL['textprefix'].'&f]&'.$_POST_REAL['colorname'];
						mysql_query("INSERT INTO `permissions_entity` VALUES (NULL, '{$user['name']}', '1', '{$prefix}', '&{$_POST_REAL['colormsg']}', '0') ON DUPLICATE KEY UPDATE `prefix` = '{$prefix}', `suffix` = '&{$_POST_REAL['colormsg']}'");
						$message = 'Новый префикс успешно установлен.';
						$log = 'Пользователь '.$user['name'].' установил новый префикс '.$prefix;
						
					}
				} else $message = 'Вашей группе нельзя изменять чат.';
				break;
			}
			
			case 2: {
				if ( $status[$_POST_REAL['statusid']][0] && $status[$_POST_REAL['statusid']][2] != -1 ) {
					if ( $user['group_id'] != $_POST_REAL['statusid'] ) {
						if ( $user['money'] >= $status[$_POST_REAL['statusid']][2] ) {
						
							mysql_query("INSERT INTO `permissions_inheritance` SET `child` = '{$user['name']}', `parent` = '{$status[$_POST_REAL['statusid']][0]}' ON DUPLICATE KEY UPDATE `parent` = '{$status[$_POST_REAL['statusid']][0]}'");
							$user_info_set = 'group_id = '.$_POST_REAL['statusid'].', money = money-'.$status[$_POST_REAL['statusid']][2].', duration = '.(time()+$cfg['status_day']*86400);
							$message = 'Вы успешно приобрели статус '.$status[$_POST_REAL['statusid']][0].'';
							$log = 'Пользователь '.$user['name'].' приобрел статус '.$status[$_POST_REAL['statusid']][0].' за '.$status[$_POST_REAL['statusid']][2].'р.';
							
						} else $message = 'Вам не хватает денег для покупки данного статуса.';
					} else {
						$price_extension = $status[$_POST_REAL['statusid']][2]-$status[$_POST_REAL['statusid']][3];
						
						if ( $user['money'] >= $price_extension ) {
						
							$user_info_set = 'money = money-'.$price_extension.', duration = '.($user['duration']+$cfg['status_day']*86400);
							$message = 'Вы успешно продлили статус '.$status[$_POST_REAL['statusid']][0].'.';
							$log = 'Пользователь '.$user['name'].' продлил статус '.$status[$_POST_REAL['statusid']][0].' за '.$price_extension.'р.';
							
						} else $message = 'Вам не хватает денег для продления данного статуса.';
					}
				}
				break;
			}
			
			case 3: {
				if ( $user['user_group_admin'] && $_POST_REAL['edit'] ) {
				
					$fp = fopen($cfg['folder_secret'].'status.txt', 'a+');
					fwrite($fp, '#'.count($status).' '.trim($_POST_REAL['edit'], '|')."\n");
					fclose($fp);
					$message = 'Статус добавлен.';
					$log = 'Администратор '.$user['name'].' добавил статус '.$status[$_POST_REAL['statusid']][0];
					
				}
				break;
			}
			
			case 4: {
				if ( $user['user_group_admin'] ) {
				
					$path = $cfg['folder_secret'].'status.txt';
					file_put_contents($path, preg_replace('/#('.$_POST_REAL['statusid'].')(.*)/', '#'.$_POST_REAL['statusid'].' '.trim($_POST_REAL['edit'], '|'), file_get_contents($path)));
					$message = 'Статус '.$status[$_POST_REAL['statusid']][0].' изменен. ';
					$log = 'Администратор '.$user['name'].' изменил статус '.$status[$_POST_REAL['statusid']][0];
					
				}
				break;
			}
			
			case 5: {
				if ( $user['user_group_admin'] ) {
					
					if ( $_POST_REAL['groupid'] ) mysql_query("INSERT INTO `permissions_inheritance` SET `child` = '{$_POST_REAL['player']}', `parent` = '{$status[$_POST_REAL['groupid']][0]}' ON DUPLICATE KEY UPDATE `parent` = '{$status[$_POST_REAL['groupid']][0]}'");
					mysql_query("UPDATE `{$cfg['table_Users']}` SET `money` = {$_POST_REAL['money']}, `group_id` = {$_POST_REAL['groupid']}, `duration` = ".strtotime($_POST_REAL['duration']).", `ban_count` = {$_POST_REAL['bancount']} WHERE `{$cfg['column_Username']}` = '{$_POST_REAL['player']}'");
					
					if ( mysql_affected_rows() ) {
						$message = 'Информация изменена.';
						$log = 'Администратор '.$user['name'].' изменил информацию игрока '.$_POST_REAL['player'];
					}
					
				}
				break;
			}
		}
	}
	/* Добавление/Сохранение */
	
	
	/* Генерация */
	if ( $_POST_REAL['generate'] ) {
		switch ( $_POST_REAL['generate'] ) {
			
			case 1: {
				if ( $user['user_group_admin'] ) {
				
					if ( $_POST_REAL['statusid'] != -1 ) 
						$message = implode('|', $status[$_POST_REAL['statusid']]);
						
				}
				break;
			}
			
			case 2: {
				if ( $user['user_group_admin'] ) {
				
					$user_query = mysql_query("SELECT * FROM `{$cfg['table_Users']}` WHERE `{$cfg['column_Username']}` = '{$_POST_REAL['player']}' LIMIT 1");
					$s_user = mysql_fetch_assoc($user_query);
							
					if ( $s_user[$cfg['column_Username']] )
						$message = $s_user[$cfg['column_Username']].' | '.$s_user['money'].' | '.$s_user['group_id'].' | '.date('d.m.Y', $s_user['duration']).' | '.$s_user['ban_count'];	
				
				}
				break;
			}
			
			case 3: {
				if ( $user['user_group_admin'] ) {
				
					$fp = fopen($cfg['folder_secret'].'log.txt', 'r');
					
					while ( !feof($fp) )
						$message .= fgets($fp, 300).'<br/>';
						
				}
				break;
			}
			
			case 4: {
				if ( $user['user_group_admin'] ) {
					
					$result = mysql_query("SELECT * FROM `lk_transaction` ORDER BY `date` LIMIT 50");
					
					while( $row = mysql_fetch_array($result) )
						$message .= '<b>'.$row['name'].'</b> произвел платеж на <b>'.$row['money'].'</b> рублей <b>'.date('d.m.Y H:i', $row['date']).'</b> со статусом <b>'.$row['ik_inv_st'].'</b><br/>';
					
				}
				break;
			}
			
		}
	}
	/* Генерация */
	
	if ( $user_info_set ) mysql_query("UPDATE `{$cfg['table_Users']}` SET {$user_info_set} WHERE `{$cfg['column_Username']}` = '{$user['name']}'");
	
	/* Запись действия пользователя в лог файл */
	if ( $log ) {
		$fp = fopen($cfg['folder_secret'].'log.txt', 'a');
		fwrite($fp, '['.date('d-m-Y H:i', time()).'] '.$log."\n");
		fclose($fp);
	}
	/* Запись действия пользователя в лог файл */
	
	echo $message;