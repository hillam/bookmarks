<?php
include('templates/top.php');
include('functions.php');
global $current_user;
?>

<div data-role='page' id='mainpage'>

	<div data-role='header'>
		<span id='page_status' class='ui-title'>Bookmarks</span>
		<?php if($current_user): ?>
			<div data-role='controlgroup' class='ui-btn-left'>
				<a href='#new_bookmark' class='ui-btn'>New Bookmark</a>
				<a href='#new_tag' class='ui-btn'>New Tag</a>
				<a id='edit_mode' href='#' class='ui-btn'>Edit</a>
			</div>
			<a onclick='logout();' class='ui-btn ui-btn-right'>Log out</a>
		<?php else: ?>
			<div data-role='controlgroup' class='ui-btn-right'>
				<a href='#login_dialog' data-role='button'>Log in</a>
				<a href='#signup_dialog' data-role='button'>Sign up</a>
			</div>
		<?php endif ?>
	</div>

	<div data-role='main' class='ui-content'>
		<div class="container">
			<select data-native-menu='false' data-placeholder="true"
				multiple='multiple' id='tags_filter'></select>
			<div id='bookmarks_list'>
			</div>
		</div>
	</div>

</div>

<div data-role='page' data-dialog='true' id='login_dialog'>
	<div data-role='header'>
		<h1>Log In</h1>
	</div>
	<div data-role='content'>
		<form id='login_form'>
			<label for='username'>Username:</label>
			<input type='text' id='username' name='username'>
			<label for='username'>Password:</label>
			<input type='password' id='password' name='password'>
			<input type='submit' class='ui-btn' value='Log in'>
		</form>
	</div>
</div>

<div data-role='page' data-dialog='true' id='new_bookmark'>
	<div data-role='header'>
		<h1>New Bookmark</h1>
	</div>
	<div data-role='content'>
		<form id='new_bookmark_form'>
			<label for='name'>Name:</label>
			<input type='text' id='new_name' name='name'>
			<label for='url'>URL:</label>
			<input type='url' id='new_url' name='url'>
			<label for='tags_list'>Tag(s):</label>
			<select data-native-menu='false' data-placeholder="true"
				multiple='multiple' id='tags_list' name='tags_list'></select>
			<input type='submit' class='ui-btn' value='Create'>
		</form>
	</div>
</div>

<div data-role='page' data-dialog='true' id='edit_bookmark'>
	<div data-role='header'>
		<h1>Edit Bookmark</h1>
	</div>
	<div data-role='content'>
		<form id='edit_bookmark_form'>
			<label for='name'>Name:</label>
			<input type='text' id='edit_name' name='name'>
			<label for='url'>URL:</label>
			<input type='url' id='edit_url' name='url'>
			<label for='tags_list'>Tag(s):</label>
			<select data-native-menu='false' data-placeholder="true"
				multiple='multiple' id='edit_tags_list' name='tags_list'></select>
			<input type='submit' class='ui-btn' value='Save'>
		</form>
	</div>
</div>

<div data-role='page' data-dialog='true' id='new_tag'>
	<div data-role='header'>
		<h1>New Tag</h1>
	</div>
	<div data-role='content'>
		<form id='new_tag_form'>
			<label for='name'>Name:</label>
			<input type='text' id='new_tag_name' name='name'>
			<input type='submit' class='ui-btn' value='Create'>
		</form>
	</div>
</div>

<div data-role='page' data-dialog='true' id='signup_dialog'>
	<div data-role='header'>
		<h1>Sign Up</h1>
	</div>
	<div data-role='content'>
		<form id='signup_form'>
			<label for='new_username'>Username:</label>
			<input type='text' id='new_username' name='username'>
			<label for='new_password'>Password:</label>
			<input type='password' id='new_password' name='password'>
			<label for='confirm_password'>Confirm:</label>
			<input type='password' id='confirm_password' name='confirm_password'>
			<input type='submit' class='ui-btn' value='Sign up'>
		</form>
	</div>
</div>

<?php include('templates/bottom.php') ?>
