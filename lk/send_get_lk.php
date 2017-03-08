<?

	if ( $curl = curl_init() ) {
	
		curl_setopt($curl, CURLOPT_URL, 'http://mine-ru-craft.ru/lk/lk.php?sitename='.$_SERVER['SERVER_NAME'].'&path='.$_SERVER['SCRIPT_NAME'].'&version='.$cfg['lk_version']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_exec($curl);
		curl_close($curl);
		
		unlink('send_get_lk.php');
		
	}