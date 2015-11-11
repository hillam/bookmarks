<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Bookmarker</title>

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
			<div class='jumbotron'>
				<h1>Bookmarks</h1>
			</div>
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
		</div>
	</body>
</html>
