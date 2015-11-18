<?php
include('templates/top.php');
include('functions.php');
global $current_user;
?>

<div data-role='page' id='mainpage'>

	<div data-role='header'>
		<h1>Bookmarks</h1>
		<?php
		if($current_user){
			echo "<a onclick='logout();' class='ui-btn ui-btn-right'>Log out</a>";
		}else{
			echo "<a href='#login_dialog' class='ui-btn ui-btn-right'>Log in</a>";
		}
		?>
	</div>

	<div data-role='main' class='ui-content'>
		
	</div>

</div>

<div data-role='page' data-dialog='true' id='login_dialog'>
	<div data-role='header'>
		<h1>Log In</h1>
	</div>
	<div data-role='content'>
		<form id='login_form' action='users/index.php' method='POST'>
			<label for='username'>Username:</label>
			<input type='text' id='username' name='username' hint='username'>
			<label for='username'>Password:</label>
			<input type='password' id='password' name='password'>
			<input type='submit' class='ui-btn' value='Log in'>
		</form>
	</div>
</div>

<?php include('templates/bottom.php') ?>
