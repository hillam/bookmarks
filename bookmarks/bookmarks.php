<?php
require_once '../controller.php';
require_once '../functions.php';

class Bookmarks extends Controller{

	/*------------------------------------------------------------------
		INDEX (GET)
		- renders all bookmarks for the current user as JSON
	------------------------------------------------------------------*/
	protected static function index(){
		global $current_user;
		$select = 'SELECT url, name, id FROM bookmark';
		if(isset($_GET['tag'])){
			$select .= ' INNER JOIN classification
						ON bookmark.id = classification.bookmark_id
						WHERE classification.tag_id=' . $_GET['tag'] .
						' AND bookmark.user_id = ' . $current_user['id'];
		}else{
			$select .= ' WHERE bookmark.user_id = ' . $current_user['id'];
		}
		$results = select($select);
		$bookmarks 	= array();
		foreach ($results as $row){
			$tags = select(
				'SELECT tag.id, tag.name FROM tag
					INNER JOIN classification
					ON tag.id = classification.tag_id
					WHERE classification.bookmark_id = ' . $row['id']);
			$b = array(
				'id' 	=> $row['id'],
				'name' 	=> $row['name'],
				'url' 	=> $row['url']);

			// convert $tags from associative to array
			$tags_array = array();
			foreach($tags as $tag){
				$tags_array[] = $tag['id'];
			}

			$b['tags'] = $tags_array;
			$bookmarks[] = $b;
		}
		echo json_encode($bookmarks);
	}

	/*------------------------------------------------------------------
		CREATE (POST)
		- create a new bookmark
	------------------------------------------------------------------*/
	protected static function create(){
		global $current_user;
		$name	= $_POST['name'];
		$url 	= $_POST['url'];
		$tags	= explode(',', $_POST['tags']);

		$id = insert('INSERT INTO bookmark (name, url, user_id)
					VALUES ("' . $name . '", "' . $url . '", ' . $current_user['id'] . ')');
		foreach ($tags as $tag){
			insert('INSERT INTO classification (bookmark_id, tag_id)
					VALUES (' . $id . ', ' . intval($tag) . ')');
		}
	}

	/*------------------------------------------------------------------
		UPDATE (POST)
		- update a conversation by id
	------------------------------------------------------------------*/
	protected static function update(){
		$id 	= $_POST['id'];
		$url 	= isset($_POST['url'])  ? $_POST['url'] : null;
		$name 	= isset($_POST['name']) ? $_POST['name'] : null;
		$tags 	= isset($_POST['tags']) ? explode(',', $_POST['tags']) : null;

		if($url){
			insert('UPDATE bookmark SET url="' . $url . '" WHERE id=' . $id);
		}
		if($name){
			insert('UPDATE bookmark SET name="' . $name . '" WHERE id=' . $id);
		}
		if($tags){
			$db_tags = select('SELECT id FROM tag');
			foreach($db_tags as $tag){
				$index = array_search($tag['id'], $tags);
				if($index){
					unset($tags[$index]);
				}
				else{
					// $tag is in the db but not in tags so delete it from the db
					insert('DELETE FROM classification
							WHERE bookmark_id=' . $id . ' AND tag_id=' . $tag['id']);
				}
			}
			foreach($tags as $tag){
				insert('INSERT INTO classification (bookmark_id,tag_id)
						VALUES (' . $id . ',' . $tag . ')');
			}
		}
	}

	/*------------------------------------------------------------------
		DELETE (POST)
		- delete a conversation by id
	------------------------------------------------------------------*/
	protected static function delete(){
		insert('DELETE FROM bookmark WHERE id=' . $_POST['id']);
	}
}
?>
