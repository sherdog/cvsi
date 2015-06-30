<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if( ! function_exists("getImage"))
{
	function getImage($imageName = "", $postfix = "")
	{
		//this will return the respective image
		if($postfix === "")
			return $imageName;

		return substr($imageName, 0, strrpos($imageName, ".")).".thumb$postfix.jpg";
	}
}