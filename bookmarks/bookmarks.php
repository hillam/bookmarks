<?php
require_once '../controller.php';
require_once '../functions.php';

class Bookmarks extends Controller{

	/*------------------------------------------------------------------
		INDEX (GET)
		- renders all bookmarks for the current user as JSON
	------------------------------------------------------------------*/
	protected static function index(){
		$select = 'SELECT url, name, id FROM bookmarks';
		if(isset($_GET['tag'])){
			$select .= ' INNER JOIN classifications
						ON bookmarks.id = classifications.bookmark_id
						WHERE classifications.tag_id=' . $_GET['tag'];
		}
		$results = select($select);
		$bookmarks 	= array();
		foreach ($results as $row){
			$tags = select(
				'SELECT tags.name, tags.id FROM tags
					INNER JOIN classifications
					ON tags.id = classifications.tag_id
					WHERE classifications.bookmark_id = ' . $row['id']);
			$b = array(
				'url' 	=> $row['url'],
				'name' 	=>$row['name']);
			$b['tags'] = $tags;
			$bookmarks[] = $b;
		}
		echo json_encode($bookmarks);
	}

	/*------------------------------------------------------------------
		CREATE (POST)
		- create a new bookmark
	------------------------------------------------------------------*/
	protected static function create(){
		$name	= $_POST['name'];
		$url 	= $_POST['url'];
		$tags	= explode(',', $_POST['tags']);

		$id = insert('INSERT INTO bookmarks (name, url)
					VALUES ("' . $name . '", "' . $url . '")');
		foreach ($tags as $tag){
			insert('INSERT INTO classifications (bookmark_id, tag_id)
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
			insert('UPDATE bookmarks SET url="' . $url . '" WHERE id=' . $id);
		}
		if($name){
			insert('UPDATE bookmarks SET name="' . $name . '" WHERE id=' . $id);
		}
		if($tags){
			$db_tags = select('SELECT id FROM tags');
			foreach($db_tags as $tag){
				$index = array_search($tag['id'], $tags);
				if($index){
					unset($tags[$index]);
				}
				else{
					// $tag is in the db but not in tags so delete it from the db
					insert('DELETE FROM classifications
							WHERE bookmark_id=' . $id . ' AND tag_id=' . $tag['id']);
				}
			}
			foreach($tags as $tag){
				insert('INSERT INTO classifications (bookmark_id,tag_id)
						VALUES (' . $id . ',' . $tag . ')');
			}
		}
	}

	/*------------------------------------------------------------------
		DELETE (POST)
		- delete a conversation by id
	------------------------------------------------------------------*/
	protected static function delete(){
		insert('DELETE FROM bookmarks WHERE id=' . $_POST['id']);
	}
}
?>
