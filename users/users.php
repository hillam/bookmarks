<?php
require_once '../controller.php';
require_once '../functions.php';

class Users extends Controller{

	/*------------------------------------------------------------------
		INDEX (GET)
		- renders all users as JSON (admin only)
	------------------------------------------------------------------*/
	protected static function index(){

	}

	/*------------------------------------------------------------------
		CREATE (POST)
		- create a new user (sign up)
	------------------------------------------------------------------*/
	protected static function create(){
		$username = $_POST['username'];
		$password = pw_encode($_POST['password']);
        insert('INSERT INTO user (username, password)
                VALUES ("' . $username . '", "' . $password . '")');
	}

    /*------------------------------------------------------------------
		LOGIN (POST)
		- log in user
	------------------------------------------------------------------*/
	protected static function login(){
		$username = $_POST['username'];
		$password = pw_encode($_POST['password']);

		$result = select('SELECT * from user
				username = "' . $username . '" AND
				password = "' . $password . '"');

		if(count($result) > 0){
			$_SESSION['username'] = $username;
		}
	}

    /*------------------------------------------------------------------
		LOGOUT (POST)
		- log out user
	------------------------------------------------------------------*/
	protected static function logout(){
		session_unset();
		session_destroy();
	}

	/*------------------------------------------------------------------
		DELETE (POST)
		- delete a conversation by id (admin only)
	------------------------------------------------------------------*/
	protected static function delete(){

	}
}
?>
