<?php
require_once '../controller.php';
require_once '../functions.php';

class Bookmarks extends Controller{

	protected static function index(){
		$results 	= select('SELECT * FROM bookmarks');
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

	protected static function create(){
		$name	= $_GET['name'];
		$url 	= $_GET['url'];
		$tags	= explode(',', $_GET['tags']);

		$id = insert('INSERT INTO bookmarks (name, url)
				VALUES ("' . $name . '", "' . $url . '")');
		foreach ($tags as $tag){
			insert('INSERT INTO classifications (bookmark_id, tag_id)
					VALUES (' . $id . ', ' . intval($tag) . ')');
		}
	}

	protected static function update(){
		$id 	= $_GET['id'];
		$url 	= isset($_GET['url'])  ? $_GET['url'] : null;
		$name 	= isset($_GET['name']) ? $_GET['name'] : null;
		$tags 	= isset($_GET['tags']) ? explode(',', $_GET['tags']) : null;

		if($url){
			insert('UPDATE bookmarks SET url=' . $url . ' WHERE id=' . $id);
		}
		if($name){
			insert('UPDATE bookmarks SET name=' . $name . ' WHERE id=' . $id);
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

	protected static function delete(){
		insert('DELETE FROM bookmarks WHERE id=' . $params['id']);
	}
}

Bookmarks::action($_GET['action']);
?>
