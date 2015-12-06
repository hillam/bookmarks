var model = {
	selected: {
		index: -1,
		bookmark: {}
	},
	edit_mode: false,
	bookmarks: [],
	tags: []
};

/**
 * Wire up event handlers
 */
$(document).ready(function(){
	$('form').submit(function(event){
		event.preventDefault();
	});

	$('#login_form').submit(function(event){
		login(go_home);
	});

	$('#new_bookmark_form').submit(function(){
		create_bookmark();
		$.mobile.changePage('#mainpage');
	});

	$('#edit_bookmark_form').submit(function(){
		update_bookmark();
		$.mobile.changePage('#mainpage');
	});

	$('#new_tag_form').submit(function(){
		create_tag();
		$.mobile.changePage('#mainpage');
	});

	$('#signup_form').submit(function(){
		signup();
		$.mobile.changePage('#mainpage');
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

	$('#edit_mode').click(function(){
		model.edit_mode = !model.edit_mode;
		var message = model.edit_mode ? 'Edit Mode' : 'Bookmarks';
		$('#page_status').text(message);
	});

	get_bookmarks();
	get_tags();
});

/**
 * Load model data into the view
 */
function update_view(){
	$('input').empty();

	$('#bookmarks_list').empty();
	model.bookmarks.forEach(function(bookmark, index){
		var matching = false;

		// TODO: only append if all if a bookmark's tags are selected

		$('#bookmarks_list').append($('<a>', {
			href: 	bookmark.url,
			text:	bookmark.name,
			target:	'_blank',
			class:	'ui-btn',

			// don't redirect unless not in editing mode
			onclick: 'return !model.edit_mode;'
		}).click(edit_bookmark)
			.attr('index', index));
	});

	$('#tags_list').empty();
	$('#edit_tags_list').empty();
	$('#tags_filter').empty();

	$('#tags_list').append('<option>Select tags...</option>');
	$('#tags_filter').append('<option>Filter by tags...</option>');

	model.tags.forEach(function(tag){
		$('#tags_list').append($('<option>', {
			value: 	tag.id,
			text: 	tag.name
		}));
		$('#edit_tags_list').append($('<option>', {
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

/**
 * Render edit bookmark form
 * - Click handler for bookmark
 */
function edit_bookmark(){
	if(model.edit_mode){
		$.mobile.changePage('#edit_bookmark');
		model.selected.index = $(this).attr('index');
		model.selected.bookmark = model.bookmarks[model.selected.index];

		$('#edit_name').val(model.selected.bookmark.name);
		$('#edit_url').val(model.selected.bookmark.url);
		$('#edit_tags_list').val(model.selected.bookmark.tags);
		$('#edit_tags_list').selectmenu('refresh');
	}
}

/**
 * Submit update bookmark form
 */
function update_bookmark(){
	$.mobile.loading('show');
	var tags = $('#edit_tags_list').val();
	tags = tags ? tags.join(',') : '';
	var obj = {
		action:	'update',
		id: 	model.selected.bookmark.id,
		name:	$('#edit_name').val(),
		url:	$('#edit_url').val(),
		tags:	tags
	};
	console.log(obj);
	var jqxhr = $.post('bookmarks/index.php', obj);
	jqxhr.done(function(data){
		get_bookmarks();
	});
}

/**
 * Get all bookmarks for this user
 */
function get_bookmarks(){
	$.mobile.loading('show');
	var jqxhr = $.getJSON('bookmarks/index.php', {action:'index'});
	jqxhr.done(function(data){
		if(!data.status){
			model.bookmarks = data;
			model.bookmarks.forEach(function(bookmark){
				bookmark.url = decodeURIComponent(bookmark.url);
			});
		}
		update_view();
	});
}

/**
 * Get all tags for this user
 */
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

/**
 * Submit create bookmark form
 */
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
}

/**
 * Submit create tag form
 */
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

/**
 * Load home page with a full reload
 */
function go_home(){
	window.location.replace('#mainpage');
	window.location.reload(true);
}

/**
 * Submit login form
 */
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

/**
 * Submit sign up form
 */
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

/**
 * Send logout signal to server
 */
function logout(){
	$.mobile.loading('show');
	var jhxr = $.post('users/index.php', {action:'logout'});

	jhxr.always(function(){
		$.mobile.loading('hide');
		go_home();
	});
}
