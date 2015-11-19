var model = {
	bookmarks: []
};

$(document).ready(function(){
	$('#login_form').submit(function(event){
		login(go_home);
		event.preventDefault();
	});

	$('#new_bookmark_form').submit(function(event){
		create_bookmark();
		$.mobile.changePage('#mainpage');
		event.preventDefault();
	});

	$('#signup_form').submit(function(event){
		signup();
		$.mobile.changePage('#mainpage');
		event.preventDefault();
	});

	get_bookmarks();
	update_view();
});

function update_view(){
	$('#bookmarks_list').empty();
	model.bookmarks.forEach(function(bookmark){
		$('#bookmarks_list').append($('<a>', {
			href: 	decodeURIComponent(bookmark['url']),
			text:	bookmark['name'],
			target:	'_blank',
			class:	'ui-btn'
		}));
	});
}

function get_bookmarks(){
	var jqxhr = $.getJSON('bookmarks/index.php', {action:'index'});
	jqxhr.done(function(data){
		model.bookmarks = [];
		if(data.length){
			data.forEach(function(bookmark){
				$('#bookmarks_list').append($('<a>', {
					href: 	decodeURIComponent(bookmark['url']),
					text:	bookmark['name'],
					target:	'_blank',
					class:	'ui-btn'
				}));
			});
		}
	});
}

function create_bookmark(){
	var obj = {
		action:	'create',
		name:	$('#new_name').val(),
		url:	$('#new_url').val()
	};
	var jqxhr = $.post('bookmarks/index.php', obj);
	jqxhr.done(function(data){
		get_bookmarks();
		update_view();
	});
}

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
	var jqxhr = $.post('users/index.php', obj);

	jqxhr.done(function(data){
		callback();
	});

	jqxhr.always(function(data){
		$.mobile.loading('hide');
	});
}

function signup(){
	var username = $('#new_username').val(),
		password = $('#new_password').val(),
		confirm = $('#confirm_password').val();

	if(password != confirm){
		alert('Passwords do not match!');
	}
	else{
		var obj = {
			action:		'create',
			username: 	username,
			password: 	password
		}
		$.mobile.loading('show');
		var jqxhr = $.post('/users/index.php', obj);
		jqxhr.done(function(data){
			$.mobile.loading('hide');
		});
	}
}

function logout(){
	$.mobile.loading('show');
	var jhxr = $.post('users/index.php', {action:'logout'});

	jhxr.always(function(){
		$.mobile.loading('hide');
		go_home();
	});
}
