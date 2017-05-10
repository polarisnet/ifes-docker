<?php
	require DIR_LIBS.'/mysql.class.php';
	$myDB = new MySQL(MY_DB_SERVER, MY_DB_USER, MY_DB_PASS, MY_DB_PORT);
	$myDB->connect(MY_DB_DATABASE);
?>