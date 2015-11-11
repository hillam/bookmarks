<?php
require_once 'functions.php';

abstract class Controller{
	public static function action($action){
		db_connect();
		forward_static_call(array(get_called_class(), $action));
		disconnect();
	}
}
?>
