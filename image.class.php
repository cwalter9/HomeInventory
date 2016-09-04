<?
//*****************************************************************************************
//Simple Inventory System 
//is a small inventory system which is designed for fashion 
//and clothing industry. It is a browser based applications which utilized AJAX to let 
//user input color/size on a two dimension table. Currenty, it contains purchase, sales, 
//inventory inquiry and inventory reports.
//
//Copyright (C) 2008  New Associates Consultant Ltd. http://www.nacl.hk
//
//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//along with this program.  If not, see <http://www.gnu.org/licenses/>.
//*****************************************************************************************

class ImageUtils
{
	var $ImageType;
	var $Image;
	var $FileName;
	
	function GetImageType($filename)
	{
		$info = pathinfo($filename);
		$ext = strtolower($info["extension"]);
		
		switch($ext)
		{
			case "jpg":
				return IMAGETYPE_JPEG;
				break;
			case "jpeg":
				return IMAGETYPE_JPEG;
				break;
			case "gif":
				return IMAGETYPE_GIF;
				break;
			case "png":
				return IMAGETYPE_PNG;
				break;
		}
		
		//return exif_imagetype($filename);		
	}
	
	function LoadImage($filename)
	{	
		$this->FileName = $filename;
		$this->ImageType = $this->GetImageType($filename);
		
		switch($this->ImageType)
		{
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($filename);
				break;
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($filename);
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($filename);
				break;
		}
		
		$this->Image = $image;
	}
	
	function OutputImage($res="")
	{
		if (empty($res)) $res = $this->Image;

		switch($this->ImageType)
		{
			case IMAGETYPE_JPEG:
				$image = imagejpeg($res);
				break;
			case IMAGETYPE_GIF:
				$image = imagegif($res);
				break;
			case IMAGETYPE_PNG:
				$image = imagepng($res);
				break;
		}
		
		return $image;
	}
	
	function SaveImage($filename="", $res="")
	{
		if (empty($filename)) $filename = $this->FileName;
		if (empty($res)) $res = $this->Image;
		
		switch($this->ImageType)
		{
			case IMAGETYPE_JPEG:
				$image = imagejpeg($res, $filename);
				break;
			case IMAGETYPE_GIF:
				$image = imagegif($res, $filename);
				break;
			case IMAGETYPE_PNG:
				$image = imagepng($res, $filename);
				break;
		}
		
		return $image;
	}
	
	function Resize($filename, $width="", $height="", $outfile="")
	{
		list($orgWidth, $orgHeight) = getimagesize($filename);
		
		if ($width != "" && $height != "") {
			$ratio1 = $width / $orgWidth;
			$ratio2 = $height / $orgHeight;
			
			$ratio = ($ratio1 < $ratio2)? $ratio1 : $ratio2;
			$width = $orgWidth * $ratio;
			$height = $orgHeight * $ratio;
		} else if ($width != "") {
			$ratio = $width / $orgWidth;
			$height = $orgHeight * $ratio;
		} else if ($height != "") {
			$ratio = $height / $orgHeight;
			$width = $orgWidth * $ratio;
		} else {
			return false;
		}
		
		$this->LoadImage($filename);
		$image_p = imagecreatetruecolor($width, $height);
		imagecopyresampled($image_p, $this->Image, 0, 0, 0, 0, $width, $height, $orgWidth, $orgHeight);
		$this->SaveImage($outfile, $image_p);
		imagedestroy($image_p);
		imagedestroy($this->Image);
	}
}
?>