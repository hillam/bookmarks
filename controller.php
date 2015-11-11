<?php
require_once 'functions.php';

abstract class Controller{
	public static function action($action, $params = array()){
		db_connect();
		forward_static_call_array(array(get_called_class(), $action), $params);
		disconnect();
	}
}
?>
