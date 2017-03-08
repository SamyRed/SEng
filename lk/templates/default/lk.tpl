
<link rel="stylesheet" type="text/css" href="templates/default/lk.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="lk.js"></script>

<div class="alerts"><?=$tpl_set['message']?></div>

<div id="lk">
	
	<? if ( $user['user_group_admin'] ) { ?>
		
		<div class="lk-block-long">
			<p class="lk-block-header">АдминЦентр</p>
			
			<div class="f_right">
				<div class="search_player-info display_none">
					<table>
						<tr><td width="150">Баланс:</td><td><input type="text" class="input-text info_user_input-money"/></td></tr>
						<tr><td>Статус:</td><td><select style="font-size: 16;" class="info_user_input-groupid">
						<?for($i = 0, $max = count($status); $i < $max; ++ $i )echo '<option value='.$i.'>'.$status[$i][0].'</option>';?></select></td></tr>		
						<tr><td>Статус до:</td><td><input type="text" class="input-text info_user_input-duration"/></td></tr>
						<tr><td>Кол-во банов:</td><td><input type="text" class="input-text info_user_input-bancount"/></td></tr>
						<tr><td><input type="hidden" class="info_user_input-name"/><button class="button save_info_user">Сохранить</button></td><td class="save_info_user-output"></td></tr>
					</table>
				</div>	
			</div>
			
			<div>
				<input type="text" class="input-text search_player-text" placeholder="Имя игрока"/>
				<button class="button search_player">Найти</button>
			</div>
			<br clear="both"/>
			
			<button class="button log">Лог файл</button> <button class="button payments">Платежи</button>
			<div class="admin-block"></div>
			
		</div>
		
	<? } ?>
	
	<div class="f_right">
		<div align="center" class="lk-block">
			<p class="lk-block-header">Пополнение счета</p>
			
			<form name="payment" method="post" action="http://mine-ru-craft.ru/mc-scripts/lk/interkassa.php" accept-charset="UTF-8"> 
				<input type="hidden" name="ik_co_id" value="<?=$cfg['ik_co_id']?>"/>
				<input type="number" class="input-text input-number" name="ik_am" value="1.00"/>
				<input type="hidden" name="ik_pm_no" value="0"/>
				<input type="hidden" name="ik_desc" value="Payment Description"/>
				<input type="hidden" name="ik_submit" value="1"/>
				<input type="submit" class="button" value="Пополнить"> 
			</form> 
			
			<div class="lk-miniblock">На вашем счету: <b><?=$user['money']?></b> руб.</div>
		</div>
		
		<div align="center" class="lk-block">
			<p class="lk-block-header">Статусы</p>
			<div class="lk-miniblock">Текущий статус: <b><?=($user['group_id'] ? $status[$user['group_id']][0].'<br/> до '.date('d-m-Y H:i', $user['duration']) : 'Игрок')?></b></div>
			<?=$tpl_set['statuses']?>
			
			<? if ( $user['user_group_admin'] ) { ?>
				<button class="button" onclick="formStatusId(-1)">+ добавить статус</button>
				
				<div class="lk-miniblock lk-form-status display_none">
					<input type="text" class="input-text lk-form-addstatus-name" placeholder="Имя статуса"/><br/>
					<input type="text" class="input-text lk-form-addstatus-namepe" placeholder="Имя в permissions"/><br/>
					<input type="text" class="input-text lk-form-addstatus-price" placeholder="Цена статуса"/><br/>
					<input type="text" class="input-text lk-form-addstatus-discount" placeholder="Скидка"/><br/>
					<a onclick="show_element('.lk-form-status-want')">Возможности</a><br/>
					<table class="lk-form-status-want display_none">
						<tr> <td width="150">Функции админа</td><td><div class="checkbox checkbox-id-want4" onclick="checkbox('want4')"></div></td> </tr>
						<tr> <td>Загрузка скинов</td><td><div class="checkbox checkbox-id-want5" onclick="checkbox('want5')"></div></td> </tr>
						<tr> <td>Загрузка плащей</td><td><div class="checkbox checkbox-id-want6" onclick="checkbox('want6')"></div></td> </tr>
						<tr> <td>Загрузка HD</td><td><div class="checkbox checkbox-id-want7" onclick="checkbox('want7')"></div></td> </tr>
						<tr> <td>Чат</td><td><div class="checkbox checkbox-id-want8" onclick="checkbox('want8')"></div></td> </tr>
						<tr> <td>Разблокировка</td><td><div class="checkbox checkbox-id-want9" onclick="checkbox('want9')"></div></td> </tr>
					</table>
					<div class="lk-form-status-button"></div>
				</div>
			<? } ?>
			<p>Возможности статусов можно <a href="/index.php?action=page&name=statuses" target="_blank">посмотреть</a>.</p>
		</div>
		
		<? if ( $cfg['unban_price'] ) { ?>
			<div align="center" class="lk-block">
				<p class="lk-block-header">Разбан</p>
				<div class="lk-miniblock">Вы <?=($user['ban_blocked'] ? false : '<b>не</b>')?> заблокированы</div>
				<table>
					<td width="190"><p class="lk-h3">Цена снятия бана: <?=$price_unban?>р</p></td>
					<td><button class="button lk-ajax-delban">Снять</button></td>
				</table>
				<p class="lk-block-text">- Снятие каждого последующего бана дороже на <?=$cfg['unban_next_price']?>р.</p>
			</div>
		<? } ?>
	</div>
	
	<div>
	
		<div align="center" class="lk-block">
			<p class="lk-block-header">Скин</p>
			<img src="skin2d.php?username=<?=$tpl_set['skin_name']?>" />
			<img src="skin2d.php?username=<?=$tpl_set['skin_name']?>&mode=2" />
		</div>
		
		<div class="lk-block">
			<p align="center" class="lk-block-header">Зарузка скина</p>
			<form method="post" enctype="multipart/form-data">
				<input type="file" name="file"><br/>
				<input type="submit" class="button" name="upload-skin" value="Загрузить скин">
				<button form="" class="button lk-ajax-delskin">Удалить скин</button>
			</form>
		</div>
		
		<div class="lk-block">
			<p align="center" class="lk-block-header">Зарузка плащ</p>
			<form method="post" enctype="multipart/form-data">
				<input type="file" name="file"><br/>
				<input type="submit" class="button" name="upload-cloak" value="Загрузить плащ">
				<button form="" class="button lk-ajax-delcloak">Удалить плащ</button>
			</form>
		</div>
		
		<div align="center" class="lk-block prefix">
			<p align="center" class="lk-block-header">Чат</p>
			<table>
				<tr>
					<td>Цвет префикса</td><td>Текст префикса</td><td>Цвет ника</td><td>Цвет сообщения</td>
				</tr>
				<tr>
				
					<td>
						<select class="select-prefix input-select-prefix">
							<option style="background:#ffffff;" value="f">#f</option>
							<option style="background:#000000;" value="0">#0</option>
							<option style="background:#0000bf;" value="1">#1</option>
							<option style="background:#00bf00;" value="2">#2</option>
							<option style="background:#00bfbf;" value="3">#3</option>
							<option style="background:#bf0000;" value="4">#4</option>
							<option style="background:#bf00bf;" value="5">#5</option>
							<option style="background:#bfbf00;" value="6">#6</option>
							<option style="background:#bfbfbf;" value="7">#7</option>
							<option style="background:#404040;" value="8">#8</option>
							<option style="background:#4040ff;" value="9">#9</option>
							<option style="background:#40ff40;" value="a">#a</option>
							<option style="background:#40ffff;" value="b">#b</option>
							<option style="background:#ff4040;" value="c">#c</option>
							<option style="background:#ff40ff;" value="d">#d</option>
							<option style="background:#ffff40;" value="e">#e</option>
						</select>
					</td>
					
					<td>
						<input type="text" class="input-text input-text-prefix-text" placeholder="Текст префикса"/>
					</td>
					
					<td>
						<select name="name_color" class="select-prefix input-select-prefix-name">
							<option style="background:#ffffff;" value="f">#f</option>
							<option style="background:#000000;" value="0">#0</option>
							<option style="background:#0000bf;" value="1">#1</option>
							<option style="background:#00bf00;" value="2">#2</option>
							<option style="background:#00bfbf;" value="3">#3</option>
							<option style="background:#bf0000;" value="4">#4</option>
							<option style="background:#bf00bf;" value="5">#5</option>
							<option style="background:#bfbf00;" value="6">#6</option>
							<option style="background:#bfbfbf;" value="7">#7</option>
							<option style="background:#404040;" value="8">#8</option>
							<option style="background:#4040ff;" value="9">#9</option>
							<option style="background:#40ff40;" value="a">#a</option>
							<option style="background:#40ffff;" value="b">#b</option>
							<option style="background:#ff4040;" value="c">#c</option>
							<option style="background:#ff40ff;" value="d">#d</option>
							<option style="background:#ffff40;" value="e">#e</option>
						</select>
					</td>
					
					<td>
						<select class="select-prefix input-text-prefix-msg">
							<option style="background:#ffffff;" value="f">#f</option>
							<option style="background:#000000;" value="0">#0</option>
							<option style="background:#0000bf;" value="1">#1</option>
							<option style="background:#00bf00;" value="2">#2</option>
							<option style="background:#00bfbf;" value="3">#3</option>
							<option style="background:#bf0000;" value="4">#4</option>
							<option style="background:#bf00bf;" value="5">#5</option>
							<option style="background:#bfbf00;" value="6">#6</option>
							<option style="background:#bfbfbf;" value="7">#7</option>
							<option style="background:#404040;" value="8">#8</option>
							<option style="background:#4040ff;" value="9">#9</option>
							<option style="background:#40ff40;" value="a">#a</option>
							<option style="background:#40ffff;" value="b">#b</option>
							<option style="background:#ff4040;" value="c">#c</option>
							<option style="background:#ff40ff;" value="d">#d</option>
							<option style="background:#ffff40;" value="e">#e</option>
						</select>
					</td>
					
				</tr>
			</table>
			
			<div align="right">
				<button class="button lk-ajax-savechat">Сохранить</button>
				<div class="output-prefix">[<span class="chat-prefix">Prefix</span>] <span class="chat-name"><?=$user['name']?></span> <span class="chat-text"> привет всем!</span></div>
			</div>
			
		</div>
		
	</div>
	<br class="f_clear"/>
</div>