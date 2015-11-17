<?php
require_once 'functions.php';

abstract class Controller{
	public static function action($action){
		global $current_user;
		if(get_called_class() == 'Users' || $current_user){
			db_connect();
			forward_static_call(array(get_called_class(), $action));
			disconnect();
			if($action != 'index'){
				render_success();
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
