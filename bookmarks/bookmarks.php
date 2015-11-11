<?php
require_once '../controller.php';
require_once '../functions.php';

class Bookmarks extends Controller{
	protected static function index($params = null){
		$results = select('SELECT * FROM bookmarks');
		foreach ($results as $row){
			$tags = select(
					'SELECT * FROM tags
						INNER JOIN classifications
						ON tags.id = classifications.tag_id
						WHERE classifications.bookmark_id = ' . $row['id']);
			$row['tags'] = $tags;
		}

		echo json_encode($results);
	}

	protected static function create($params){

	}

	protected static function update($params){

	}

	protected static function delete($params){

	}
}

Bookmarks::action('index');
?>
