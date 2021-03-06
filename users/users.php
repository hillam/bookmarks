<?php
require_once '../controller.php';
require_once '../functions.php';

class Users extends Controller{

	/*------------------------------------------------------------------
		INDEX (GET)
		- renders all users as JSON (admin only)
	------------------------------------------------------------------*/
	protected static function index(){
		global $current_user;
		if($current_user['is_admin']){
			$result = select('SELECT id, username from user');
			echo json_encode($result);
		}
		else {
			self::$failure = 'you are not admin';
		}
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
				WHERE username = "' . $username . '" AND
				password = "' . $password . '"');

		if(count($result) > 0){
			$_SESSION['user'] = array(
				'username' 	=> $username,
				'id' 		=> $result[0]['id'],
				'is_admin'	=> $result[0]['admin']);
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
		global $current_user;
		if($current_user['is_admin']){
			insert('DELETE FROM user WHERE id=' . $_POST['id']);
		}
		else {
			self::$failure = 'you are not admin';
		}
	}
}
?>
