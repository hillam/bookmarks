<?php
require_once 'functions.php';

class Controller{
	/*------------------------------------------------------------------
		* Becuase methods are private, this one will be called first
		  when someone tries to call them from outside.
		* It connects to the database, and then calls the desired method,
		  then disconnects from the db.
	------------------------------------------------------------------*/
	public static function __callStatic($name,$args) {
		if (method_exists(get_class(), $name)) {
		  db_connect();
		  $ret =  forward_static_call_array(array(get_class(), $name), $args);
		  disconnect();
		  return $ret;
		}
	}
}
?>
