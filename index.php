<?php
/*
 *      index.php
 *
 *
 */
include 'functions.php';

if(isset($_POST['pool']) && !empty($_POST['pool'])){
	$keywords=extractCommonWords(trim($_POST['pool']));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>untitled</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.20" />
</head>

<body>
	<form action="index.php" method="post">
<p><input type="text" name="pool" /></p>
<p><input type="submit" name="search" /></p>
	</form>
<p><?php

if(isset($keywords) && count($keywords)!=0){
	foreach($keywords as $keywrd=>$count){
		echo "<li>{$keywrd} - {$count} time(s)</li>";
	}
}
?>
</p>
</body>

</html>
