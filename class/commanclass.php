<?php 
class commonUseImage
{
public function uploadImagedri($imgsize,$upfolder,$tempfol,$imgname,$imgsizeheight)
{

    $uploadimage=$tempfol;
	for($i=0;$i<count($imgsize);$i++)
	{
		  $folderName = $upfolder.$imgsize[$i];

		if ( !file_exists($folderName) ) {
			mkdir($folderName,777);
		}
		 
		 if($i>0)
			{
			$uploadimage=$upfolder.$imgsize[$i-1]."/".$imgname;
			}
			
			$uploadDir=$upfolder.$imgsize[$i]."/";
			$hh=0;
			 if($imgsizeheight[$i]>0)
			 {
			  $hh=$imgsizeheight[$i];
			 }
			 
			 $result1    = $this->createThumbnail($uploadimage, $uploadDir . $imgname, $imgsize[$i],$hh);
	}
	
	return $result1;
}
public function createThumbnail($srcFile, $destFile, $width,$height=0, $quality = 100)
{

	$thumbnail = '';
	 

		if (file_exists($srcFile)  && isset($destFile))
		{
			$size        = getimagesize($srcFile);
			
			if($size[0]>=$width)
			{
			$w           = number_format($width, 0, ',', '');
			 $h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');
			
			
	
				if($h>$height && $height>0){
				
				
					$heighth=$h;
					$h           = number_format($height, 0, ',', '');
					$w           = number_format(($size[0] / $size[1]) * $heighth, 0, ',', '');	
				}
		
				$thumbnail =  $this->copyImage($srcFile, $destFile, $w, $h, $quality);
			}else if($size[1]>$height && $height>0)
			{
					$h           = number_format($height, 0, ',', '');
					$w           = number_format(($size[0] / $size[1]) * $height, 0, ',', '');	
	
			$thumbnail =  copyImage($srcFile, $destFile, $w, $h, $quality);
			}else{
	 			$thumbnail = copy($srcFile, $destFile); 
					//move_uploaded_file($srcFile, $destFile);
			}
		}
	
	// return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}

private  function copyImage($srcFile, $destFile, $w, $h, $quality = 100)
{

    $tmpSrc     = pathinfo(strtolower($srcFile));
    $tmpDest    = pathinfo(strtolower($destFile));
    $size       = getimagesize($srcFile);

    if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg")
    {
       $destFile  = substr_replace($destFile, 'jpg', -3);
       $dest      = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    } elseif ($tmpDest['extension'] == "png") {
       $dest = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    } else {
      return false;
    }
    switch($size[2])
    {
       case 1:       //GIF
           $src = imagecreatefromgif($srcFile);
           break;
       case 2:       //JPEG
           $src = imagecreatefromjpeg($srcFile);
           break;
       case 3:       //PNG
           $src = imagecreatefrompng($srcFile);
           break;
       default:
           return false;
           break;
    }

    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

    switch($size[2])
    {
       case 1:
       case 2:
           imagejpeg($dest,$destFile, $quality);
           break;
       case 3:
           imagepng($dest,$destFile);
    }
    return $destFile;

}
public function imagenameChange($imagename,$txturl)
{
  $largePath1='';
    $ext = substr(strrchr($imagename, "."), 1);
    $extname = explode(".",$imagename);
		$counnvar=count($extname);
		 for($i=0;$i<$counnvar-1;$i++)
		 {
		 $largePath12.=$extname[$i];
		 }
		$largePath12 = str_replace('(','',$largePath12);
		$largePath12 = str_replace(')','',$largePath12);
		$largePath12 = str_replace('#','',$largePath12);
		$largePath12 = str_replace(';','',$largePath12);
		$largePath12 = str_replace(',','',$largePath12);
		$largePath1 = str_replace(' ','_',$largePath12)."_".$txturl. ".$ext";
		  $largePath   = str_replace(" ","_",$largePath1);
		  
		  return  $largePath;
}

public function uploadImage($uploadPath,$imageName,$tempImageName)
{
	$imageRename=$this->renameFunc($imageName,1); 
	move_uploaded_file($tempImageName,$uploadPath.$imageRename);
    return $imageRename;
}

private function renameFunc($file_name, $count)
{        
	$token=md5((rand()* time())+$count);
	$name_array=explode('.',$file_name);
	$real_name=$token.".".$name_array[1];
	return $real_name;
}

}
?>