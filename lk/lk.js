
var prefix = {'f':'fff', 0:'000', 1:'1451A7', 2:'00bf00', 3:'00bfbf', 4:'bf0000', 5:'bf00bf', 6:'bfbf00', 7:'bfbfbf', 8:'404040', 9:'4040ff', 'a':'40ff40', 'b':'40ffff', 'c':'ff4040', 'd':'ff40ff', 'e':'ffff40'},
	posteditstatus = ['name', 'namepe', 'price', 'discount'],
	chekbox = [];

	
/* Статусы */

function buyStatusId(id) {
	post({ save:2, statusid:id }, 'Вы действительно хотите купить статус? С вашего счета будут списаны средства.');
}

function editStatusId(id, type) {

	if ( !confirm('Вы действительно хотите это сделать?') ) return false;
	
	var edit = '';
	
	for ( i = 0, count = posteditstatus.length+6; i < count; i++ )
		if ( i < 4 )
			edit += $('.lk-form-addstatus-'+posteditstatus[i]).val()+'|'; else edit += (checkbox['want'+i] ? checkbox['want'+i] : 0)+'|'; 
	
	if ( type )
		if ( id == -1 )
			post({ 
				save:3,
				statusid:id,
				edit:edit
			});
		else {
			post({ 
				save:4,
				statusid:id,
				edit:edit
			});
		}
	else 
		post({ del:4, statusid:id });
}

function formStatusId(id) {

	$('.lk-form-status').show();
	
	if ( id != -1 )
		$.post('ajax.php', { generate:1, statusid:id }, function(data) { 
			var status = data.split('|');
		
			for ( i = 0, count = posteditstatus.length+6; i < count; i++ )
				if ( i < 4 ) {
					$('.lk-form-addstatus-'+posteditstatus[i]).val(status[i]); 
				} else {
					checkbox['want'+i] = status[i];
					checkbox('want'+i, true);
				}
				
			if ( $('.lk-form-status-button').lenght != 0 ) $('.lk-form-status-button').html('<button class="button" onclick="editStatusId('+id+', 0)">Удалить</button> <button class="button" onclick="editStatusId('+id+', 1)">Изменить</button>');			
		});
	else 
		$('.lk-form-status-button').html('<button class="button" onclick="editStatusId(-1, 1)">Добавить</button>');
}

/* Статусы */


function checkbox(id, type) {

	if ( checkbox[id] != (type ? 0 : 1) ) {
		$('.checkbox-id-'+id).addClass('checkbox-active');
		checkbox[id] = 1;
	} else {
		$('.checkbox-id-'+id).removeClass('checkbox-active');
		checkbox[id] = 0;
	}	
	
}

function show_element(id) {
	$(id).toggleClass('display_none');
}

$(document).ready(function() { 
             
	$('.lk-ajax-delskin').click(function() {
		post({ del:1 });
	});
	
	$('.lk-ajax-delcloak').click(function() {
		post({ del:2 });
	});
	
	$('.lk-ajax-delban').click(function() {
		post({ del:3 }, 'Вы действительно хотите снять бан? С вашего счета будут списаны средства.');
	});
	
	$('.lk-ajax-savechat').click(function() {
		post({ 
			save:1, 
			colorprefix:$('.input-select-prefix').val(), 
			textprefix:$('.input-text-prefix-text').val(), 
			colorname:$('.input-select-prefix-name').val(), 
			colormsg:$('.input-text-prefix-msg').val() 
		}, 'Вы действительно хотите установить новый префикс? Существующий будет удален.');
	});
	
	$('.prefix').bind('click keyup', function() { 
		$('.chat-prefix').text($('.input-text-prefix-text').val()).css({color:prefix[$('.input-select-prefix').val()]});
		$('.chat-name').css({color:prefix[$('.input-select-prefix-name').val()]});
		$('.chat-text').css({color:prefix[$('.input-text-prefix-msg').val()]});
	});
	
	$('.search_player').click(function() {
		$.post('ajax.php', {generate:2, player:$('.search_player-text').val()}, function(data) {
			var arr = data.split(' | ');
			
			if ( arr[0] && $('.info_user_input-name').val() != arr[0] ) {
				$('.search_player-info').removeClass('display_none');
				
				$('.info_user_input-name').val(arr[0]);
				$('.info_user_input-money').val(arr[1]);
				$('.info_user_input-groupid [value=\''+arr[2]+'\']').attr('selected', '');
				$('.info_user_input-duration').val(arr[3]);
				$('.info_user_input-bancount').val(arr[4]);
			}

		});
	});
	
	$('.save_info_user').click(function() {
		post({ 
			save:5,
			player:$('.info_user_input-name').val(),
			money:$('.info_user_input-money').val(),
			groupid:$('.info_user_input-groupid').val(),
			duration:$('.info_user_input-duration').val(),
			bancount:$('.info_user_input-bancount').val()
		}, false, function(data) { 
			$('.save_info_user-output').text(data).show().delay(2000).slideUp(500); 
		});
	});
	
	$('.log').click(function() {
		post({ generate:3 }, false, function(data) { 
			$('.admin-block').html('<h3>Лог файл</h3><div class="log-bg">'+data+'</div><button class="button" onclick="post({ del:5 })">Очистить лог</button>'); 
		});
	});
	
	$('.payments').click(function() {
		post({ generate:4 }, false, function(data) { 
			$('.admin-block').html('<h3>Платежи</h3><div class="log-bg">'+data+'</div>'); 
		});
	});
	
});

function post(params, msg, funct) {
	if ( msg && !confirm(msg) ) return false;
	
	if ( !funct ) ajax_alert('Подождите...');
	
	$.post('ajax.php', params, function(data) {
		if ( data ) 
		{
		
			if ( funct ) 
				funct(data); else ajax_alert(data);
				
		} else 
			ajax_alert('Ошибка.'); 
	});
}

function ajax_alert(text) {
	$('.alerts').html('<div class="alert">'+text+'</div>');
	$('.alert').delay(5000).slideUp(500);
}