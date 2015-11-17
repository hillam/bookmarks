<?php
require_once '../controller.php';
require_once '../functions.php';

class Tags extends Controller{

	/*------------------------------------------------------------------
		INDEX (GET)
		- renders all tags for the current user as JSON
	------------------------------------------------------------------*/
	public static function index(){
		global $current_user;
		$tags = select('SELECT * FROM tag WHERE user_id = ' . $current_user['id']);
		echo json_encode($tags);
	}

	/*------------------------------------------------------------------
		CREATE (POST)
		- create a new tag
	------------------------------------------------------------------*/
	public static function create(){
		global $current_user;
		$name 	= $_POST['name'];
		insert('INSERT INTO tag (name, user_id)
				VALUES ("' . $name . '", ' . $current_user['id'] . ')');
	}

	/*------------------------------------------------------------------
		UPDATE (POST)
		- update a tag by id
	------------------------------------------------------------------*/
	public static function update(){
		global $current_user;
		$id		= $_POST['id'];
		$name 	= isset($_POST['name']) ? $_POST['name'] : null;

		if($name){
			insert('UPDATE bookmark
					SET name="' . $name . '"
					WHERE id=' . $id .
					'AND user_id=' . $current_user['id']);
		}
	}

	/*------------------------------------------------------------------
		DELETE (POST)
		- delete a tag by id
	------------------------------------------------------------------*/
	public static function delete(){
		global $current_user;
		insert('DELETE FROM tag WHERE id=' . $_POST['id'] .
				'AND user_id=' . $current_user['id']);
	}
}
?>
