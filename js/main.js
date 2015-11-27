var model = {
	bookmarks: [],
	tags: []
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

	$('#new_tag_form').submit(function(event){
		create_tag();
		$.mobile.changePage('#mainpage');
		event.preventDefault();
	});

	$('#signup_form').submit(function(event){
		signup();
		$.mobile.changePage('#mainpage');
		event.preventDefault();
	});

	$('#tags_filter').change(function(){
		$('#tags_filter').val().forEach(function(id){
			model.tags.forEach(function(tag){
				if(tag.id == id)
					tag.selected = true;
				else
					tag.selected = false;
			});
		});
	});

	get_bookmarks();
	get_tags();
});

function update_view(){
	$('input').empty();

	$('#bookmarks_list').empty();
	model.bookmarks.forEach(function(bookmark){
		var matching = false;

		// TODO: only append if all if a bookmark's tags are selected

		$('#bookmarks_list').append($('<a>', {
			href: 	decodeURIComponent(bookmark.url),
			text:	bookmark.name,
			target:	'_blank',
			class:	'ui-btn'
		}));
	});

	$('#tags_list').empty();
	$('#tags_filter').empty();

	$('#tags_list').append('<option>Select tags...</option>');
	$('#tags_filter').append('<option>Filter by tags...</option>');

	model.tags.forEach(function(tag){
		$('#tags_list').append($('<option>', {
			value: 	tag.id,
			text: 	tag.name
		}));
		$('#tags_filter').append($('<option>', {
			value: 	tag.id,
			text: 	tag.name
		}));
	});
	$('#tags_filter').selectmenu('refresh');

	$.mobile.loading('hide');
}

function get_bookmarks(){
	$.mobile.loading('show');
	var jqxhr = $.getJSON('bookmarks/index.php', {action:'index'});
	jqxhr.done(function(data){
		if(!data.status){
			model.bookmarks = data;
		}
		update_view();
	});
}

function get_tags(){
	// $.mobile.loading('show');
	var jqxhr = $.getJSON('tags/index.php', {action:'index'});
	jqxhr.done(function(data){
		if(!data.status){
			model.tags = data;
		}
		update_view();
	});
}

function create_bookmark(){
	$.mobile.loading('show');
	var obj = {
		action:	'create',
		name:	$('#new_name').val(),
		url:	$('#new_url').val(),
		tags:	$('#tags_list').val().join(',')
	};
	var jqxhr = $.post('bookmarks/index.php', obj);
	jqxhr.done(function(data){
		get_bookmarks();
	});
	console.log(obj);
}

function create_tag(){
	$.mobile.loading('show');
	var obj = {
		action:	'create',
		name:	$('#new_tag_name').val()
	};
	var jqxhr = $.post('tags/index.php', obj);
	jqxhr.done(function(data){
		get_tags();
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
		console.log(data);
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
