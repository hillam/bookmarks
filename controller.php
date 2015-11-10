<?php
require_once 'functions.php';

class Controller{
	/*------------------------------------------------------------------
		* Becuase methods are private, this one will be called first
		  when someone tries to call them from outside.
		* It connects to the database, and then calls the desired method,
		  then disconnects from the db.
	------------------------------------------------------------------*/
	public function __call($name,$args) {
		if (method_exists($this,$name)) {
		  db_connect();
		  $ret =  call_user_func_array(array($this, $name), $args);
		  disconnect();
		  return $ret;
		}
	}
}
?>
