<?php
require_once '../controller.php';
require_once '../functions.php';

class Bookmarks extends Controller{
	public function __call($method, $variables){
        return parent::__call($method, $variables);
    }
	
	private static function index($params = null){
		$results = select('SELECT * FROM bookmarks');
		foreach ($results as $row){
			$tags = select(
					'SELECT * FROM tags
						INNER JOIN categorizations
						ON tags.id = categorizations.tag_id
						WHERE categorizations.bookmark_id = ' . $row['id']);
			$row['tags'] = $tags;
		}
		echo json_encode($row);
	}

	private static function create($params){

	}

	private static function update($params){

	}

	private static function delete($params){

	}
}

Bookmarks::index();
?>
