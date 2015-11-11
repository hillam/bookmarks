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

	}

	protected static function delete(){
		insert('DELETE FROM bookmarks WHERE id=' . $params['id']);
	}
}

Bookmarks::action('create');
?>
