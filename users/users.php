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
        insert('INSERT INTO user (username, password)
                VALUES ("' . $_POST['username'] . '", "' . $_POST['password'] . '")');
	}

    /*------------------------------------------------------------------
		LOGIN (POST)
		- log in user
	------------------------------------------------------------------*/
	protected static function login(){
        
	}

    /*------------------------------------------------------------------
		LOGOUT (POST)
		- log out user
	------------------------------------------------------------------*/
	protected static function logout(){

	}

	/*------------------------------------------------------------------
		DELETE (POST)
		- delete a conversation by id (admin only)
	------------------------------------------------------------------*/
	protected static function delete(){

	}
}
?>
