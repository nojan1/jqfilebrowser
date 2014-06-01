<?php

if(isset($_GET["p"])){
	$path = urldecode($_GET["p"]);
}else{
	die("NO PATH");
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $path);
finfo_close($finfo);

header("Content-type: $mime");
readfile($path);