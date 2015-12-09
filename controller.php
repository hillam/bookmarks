<?php
require_once 'functions.php';

abstract class Controller{
	public static $failure;

	public static function action($action){
		global $current_user;
		if((get_called_class() == 'Users' && ($action == 'create' || $action == 'login'))
				|| $current_user){
			db_connect();
			forward_static_call(array(get_called_class(), $action));
			disconnect();


			// RENDER FAILURE if failure message is set
			if(self::$failure){
				$failure = array(
					'status' 	=> 'failure',
					'failure' 	=> self::$failure);
				echo json_encode($failure);
			}
			// RENDER SUCCESS otherwise
			else if($action != 'index'){
				$success = array('status' => 'success');
				echo json_encode($success);
			}
		}
		else{
			$error = array(
				'status' => 'failure',
				'failure' => 'not logged in');
			echo json_encode($error);
		}
	}
}
?>
