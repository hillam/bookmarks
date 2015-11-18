$(document).ready(function(){
	$('#login_form').submit(function(event){
		login(function(){
			go_home();
		});
		event.preventDefault();
	});
});

function go_home(){
	window.location.replace('#mainpage');
	window.location.reload(true);
}

function login(callback){
	$.mobile.loading('show');
	var obj = {
		action: 'login',
		username: $('#username').val(),
		password: $('#password').val()
	};
	var jxhr = $.post('users/index.php', obj);

	jxhr.done(function(data){
		callback();
	});

	jxhr.always(function(data){
		$.mobile.loading('hide');
	});
}

function logout(){
	$.mobile.loading('show');
	var jhxr = $.post('users/index.php', {action:'logout'});

	jhxr.always(function(){
		$.mobile.loading('hide');
		go_home();
	});
}
