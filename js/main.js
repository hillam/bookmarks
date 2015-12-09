var model = {
	selected: {
		index: -1,
		bookmark: {}
	},
	filters: [],
	edit_mode: false,
	bookmarks: [],
	tags: [],
	users: []
};

/**-------------------------------------
 * Wire up event handlers
-------------------------------------*/
$(document).ready(function(){
	$('form').submit(function(event){
		event.preventDefault();
	});

	$('#login_form').submit(login);

	$('#new_bookmark_form').submit(function(){
		create_bookmark();
		$.mobile.changePage('#mainpage');
	});

	$('#edit_bookmark_form').submit(function(){
		update_bookmark();
		$.mobile.changePage('#mainpage');
	});

	$('#delete_bookmark').click(function(){
		delete_bookmark();
		$.mobile.changePage('#mainpage');
	});

	$('#new_tag_form').submit(function(){
		create_tag();
		$.mobile.changePage('#mainpage');
	});

	$('#signup_form').submit(function(){
		signup();
	});

	$('#tags_filter').change(function(){
		model.filters = [];
		($('#tags_filter').val() || []).forEach(function(id){
			model.filters.push(id);
		});
		update_bookmarks_list();
	});

	$('#edit_mode').click(function(){
		model.edit_mode = !model.edit_mode;
		var message = model.edit_mode ? 'Edit Mode' : 'Bookmarks';
		$('#page_status').text(message);
	});

	$(document).on('click', '.user_listing', function(){
		console.log()
		if(confirm('Are you sure you want to permanently delete this user?')){
			delete_user($(this).attr('id'));
		}
	});

	get_bookmarks();
	get_tags();
	get_users();
});

/**-------------------------------------
 * Load home page with a full reload
-------------------------------------*/
function go_home(){
	window.location.replace('#mainpage');
	window.location.reload(true);
}

/**-------------------------------------
 * Load model data into the view
-------------------------------------*/
function update_view(){
	$('input').empty();

	update_bookmarks_list();

	$('#users_list').empty();
	model.users.forEach(function(user){
		$('#users_list').append($('<a>', {
			text: user.username,
			class:	'ui-btn user_listing'
		}).attr('id', user.id));
	});

	$('#tags_list').empty().selectmenu();
	$('#edit_tags_list').empty().selectmenu();
	$('#tags_filter').empty().selectmenu();

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

function update_bookmarks_list(){
	$('#bookmarks_list').empty();
	model.bookmarks.forEach(function(bookmark, index){
		var matching = true;

		model.filters.forEach(function(tag){
			if(bookmark.tags.indexOf(tag) < 0){
				matching = false;
			}
		});

		if(matching){
			$('#bookmarks_list').append($('<a>', {
				href: 	bookmark.url,
				text:	bookmark.name,
				target:	'_blank',
				class:	'ui-btn',

				// don't redirect unless not in editing mode
				onclick: 'return !model.edit_mode;'
			}).click(edit_bookmark)
				.attr('index', index));
		}
	});
}

/**-------------------------------------
 * Render edit bookmark form
 * - Click handler for bookmark
-------------------------------------*/
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

/**-------------------------------------
 * Submit update bookmark form
-------------------------------------*/
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

/**-------------------------------------
 * Delete a user from the edit dialog
-------------------------------------*/
function delete_bookmark(){
	var obj = {
		action: 'delete',
		id:		model.selected.bookmark.id
	};
	var jqxhr = $.post('bookmarks/index.php', obj);
	jqxhr.done(function(data){
		get_bookmarks();
	});
}

/**-------------------------------------
 * Get all bookmarks for this user
-------------------------------------*/
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

/**-------------------------------------
 * Get all tags for this user
-------------------------------------*/
function get_tags(){
	$.mobile.loading('show');
	var jqxhr = $.getJSON('tags/index.php', {action:'index'});
	jqxhr.done(function(data){
		if(!data.status){
			model.tags = data;
		}
		update_view();
	});
}

/**-------------------------------------
 * Submit create bookmark form
-------------------------------------*/
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

/**-------------------------------------
 * Submit create tag form
-------------------------------------*/
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

/**-------------------------------------
 * Submit login form
-------------------------------------*/
function login(callback){
	$.mobile.loading('show');
	var obj = {
		action: 'login',
		username: $('#username').val(),
		password: $('#password').val()
	};
	var jqxhr = $.post('users/index.php', obj);

	jqxhr.done(function(data){
		go_home();
	});

	jqxhr.always(function(data){
		$.mobile.loading('hide');
	});
}

/**-------------------------------------
 * Submit sign up form
-------------------------------------*/
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
		var jqxhr = $.post('users/index.php', obj);
		jqxhr.done(function(data){
			$.mobile.loading('hide');
			$.mobile.changePage('#mainpage');
		});
	}
}

/**-------------------------------------
 * Send logout signal to server
-------------------------------------*/
function logout(){
	$.mobile.loading('show');
	var jhxr = $.post('users/index.php', {action:'logout'});

	jhxr.always(function(){
		$.mobile.loading('hide');
		go_home();
	});
}

/**-------------------------------------
 * Get all users from the database
-------------------------------------*/
function get_users(){
	$.mobile.loading('show');
	var jqxhr = $.getJSON('users/index.php', {action:'index'});
	jqxhr.done(function(data){
		if(!data.status){
			model.users = data;
			update_view();
		}
	});
}

/**-------------------------------------
 * Delete a user by id
-------------------------------------*/
function delete_user(id){
	var obj = {
		action: 'delete',
		id:		id
	};
	var jqxhr = $.post('users/index.php', obj);
	jqxhr.done(function(data){
		get_users();
	});
}
