<?php

$validImages = array("jpg", "jpeg", "png", "tiff", "gif");
$appThumbs = array("pdf" => "pdf.png", 
		   "txt" => "txt.png",);

function thumbnail($inputFileName, $key, $maxSize = 100)
{
	$info = @getimagesize($inputFileName);
	if($info === false)
	  throw new Exception();

	$type = isset($info['type']) ? $info['type'] : $info[2];

	// Check support of file type
	if ( !(imagetypes() & $type) )
	{
		// Server does not support file type
		return false;
	}

	$width  = isset($info['width'])  ? $info['width']  : $info[0];
	$height = isset($info['height']) ? $info['height'] : $info[1];

	// Calculate aspect ratio
	$wRatio = $maxSize / $width;
	$hRatio = $maxSize / $height;

	// Using imagecreatefromstring will automatically detect the file type
	$sourceImage = imagecreatefromstring(file_get_contents($inputFileName));

	// Calculate a proportional width and height no larger than the max size.
	if ( ($width > $maxSize) || ($height > $maxSize) )
	{    
		if ( ($wRatio * $height) < $maxSize )
        {
            // Image is horizontal
            $tHeight = ceil($wRatio * $height);
            $tWidth  = $maxSize;
        }
        else
        {
            // Image is vertical
            $tWidth  = ceil($hRatio * $width);
            $tHeight = $maxSize;
        }

        $thumb = imagecreatetruecolor($tWidth, $tHeight);

        if ( $sourceImage === false )
        {
            // Could not load image
            return false;
        }

        // Copy resampled makes a smooth thumbnail
        imagecopyresampled($thumb, $sourceImage, 0, 0, 0, 0, $tWidth, $tHeight, $width, $height);
        imagedestroy($sourceImage);

		$fileName = "../thumbnails/$key";
        imagepng($thumb, $fileName);
	}else{
		copy($inputFileName, "../thumbnails/$key");
	}
}

if(isset($_GET["p"])){
	$path = urldecode($_GET["p"]);
}else{
	die("NO PATH");
}

$thumbnailPath = "";
$path_parts = pathinfo($path);
if(!array_key_exists("extension", $path_parts))
  $path_parts["extension"] = "";

if(is_dir($path)){
  $thumbnailPath = "../icons/folder.png";
}else{
  if(in_array(strtolower($path_parts["extension"]), $validImages)){
    try{
	//Find or generate thumbnail
	$key = md5($path);
	if(!file_exists("../thumbnails/$key")){
	  //Go GD
	  thumbnail($path, $key, 256);
	}
        $thumbnailPath = "../thumbnails/$key";
    }catch(Exception $e){
      $thumbnailPath = "../icons/generic_image.png";
    }
  }else{
	//Send application specific thumbnail or default
    if(array_key_exists($path_parts["extension"], $appThumbs)){
	  $thumbnailPath = "../icons/" . $appThumbs[strtolower($path_parts["extension"])];
	}else{
	  $thumbnailPath = "../icons/generic_file.png";
	}
  }
}

header("Content-type: image/png");
readfile($thumbnailPath);