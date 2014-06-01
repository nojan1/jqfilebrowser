<?php

if(isset($_GET["p"])){
	$path = urldecode($_GET["p"]);
}else{
	//Default path
	$path = "/";
}

$fileList = [];
foreach(scandir($path) as $file){
	if($file == ".")
	  continue;
	elseif($file == ".." && $path == "/")
	  continue;
	
	$fullPath = $path . "/" . $file;
	if(strpos($fullPath, "..") !== false)
		$fullPath = realpath($fullPath);
	
	//$fullPath = str_replace($fullPath, "//", "/");

	$path_parts = pathinfo($fullPath);
	if(!array_key_exists("extension", $path_parts))
	  $path_parts["extension"] = "file";

	$fileList[] = array("path" => urlencode($fullPath),
			    "isdir" => is_dir($fullPath),
			    "thumbnail" => "api/thumbnail.php?p=" . urlencode($fullPath),
			    "ext" => is_dir($fullPath) ? "folder" : strtolower($path_parts["extension"]),
			    "filename" => $file);
}

echo json_encode($fileList);