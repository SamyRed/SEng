<?
	
	if ( isset($_POST_REAL['login']) ) 
	{
		$info_query = mysql_query("SELECT * FROM `{$cfg['table_Users']}` WHERE `{$cfg['column_Username']}` = '{$_POST_REAL['login']}'");
		$info = mysql_fetch_assoc($info_query);
		
		//Тут хеш пароля вашего движка. Например это хеш паролей DLE.
		$password = md5(md5($_POST_REAL['password']));
		
		if ( $info[$cfg['column_Userpass']] == $password )
			$_SESSION['lk_user_id'] = $info[$cfg['column_Id']]; else $error = 'Неверный Логин или Пароль.';
		
	}
	
	echo '
		<div>
			<h3>Пожалуйста, войдите в Личный Кабинет.</h3>
			<form method="POST">
				Логин: <br/>
				<input type="text" name="login" required/> <br/><br/>
				
				Пароль: <br/>
				<input type="password" name="password" required/> <br/><br/>
				
				<input type="submit" value="Вход"/> '.$error.'
			</form>
		</div>
		
		<style>
			input {
				width: 200;
				height: 35;
				border: 1px solid #C3C3C3;
				padding: 5;
				outline: 0;
			}
		</style>
	';