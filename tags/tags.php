<?php
require_once '../controller.php';
require_once '../functions.php';

class Tags extends Controller{

	/*------------------------------------------------------------------
		INDEX (GET)
		- renders all tags for the current user as JSON
	------------------------------------------------------------------*/
	public static function index(){
		$tags = select('SELECT * FROM tags');
		echo json_encode($tags);
	}

	/*------------------------------------------------------------------
		CREATE (POST)
		- create a new tag
	------------------------------------------------------------------*/
	public static function create(){
		$name 	= $_POST['name'];
		insert('INSERT INTO tags (name) VALUES ("' . $name . '")');
	}

	/*------------------------------------------------------------------
		UPDATE (POST)
		- update a tag by id
	------------------------------------------------------------------*/
	public static function update(){
		$id		= $_POST['id'];
		$name 	= isset($_POST['name']) ? $_POST['name'] : null;

		if($name){
			insert('UPDATE bookmarks
					SET name="' . $name . '"
					WHERE id=' . $id);
		}
	}

	/*------------------------------------------------------------------
		DELETE (POST)
		- delete a tag by id
	------------------------------------------------------------------*/
	public static function delete(){
		insert('DELETE FROM tags WHERE id=' . $_POST['id']);
	}
}
?>
