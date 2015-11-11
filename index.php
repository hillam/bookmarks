<?php
db_connect();
$result = select('SELECT * FROM bookmarks');
?>
	<ul>
<?php
foreach ($result as $row){
?>
		<li><a href='<?php $row['url'] ?>'><?php $row['name'] ?></a></li>
<?php
}
disconnect();
?>
	</ul>
