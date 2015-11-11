<?php
require_once '../controller.php';
require_once '../functions.php';

class Bookmarks extends Controller{
	protected static function index($params = null){
		$results 	= select('SELECT * FROM bookmarks');
		$bookmarks 	= array();
		foreach ($results as $row){
			$tags = select(
				'SELECT * FROM tags
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

	protected static function create($params){
		var_dump($_GET['tags']);
	}

	protected static function update($params){

	}

	protected static function delete($params){

	}
}

Bookmarks::action('index');
?>
