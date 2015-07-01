<?php
	/**
	 * $Id: truncate_filename 011 18-08-2008 Naz $
	 * Truncate filename to FILE_CHAR_TO_SHOW defined in config.inc.php
	*/
function truncate_filename($str){
	if (strlen($str) > FILE_CHAR_TO_SHOW)
		{
		return substr($str,0,FILE_CHAR_TO_SHOW).'...';
		}else{
		return $str;
		}
}

	/**
	 * $Id: bytestostring 011 18-08-2008 Naz $
	 * Crude and dirty bites conversion to KB or MB
	*/
function bytestostring($size, $precision = 2) {
if ($size <= 0){
return false; //just play it safe
}elseif ($size < 1024000){//if smaller than 1MB
return number_format($size/1024, $precision)." KB";
}else{
return number_format($size/1024000, $precision)." MB";
}
}

	/**
	 * $Id: basic_hack_block 011 18-08-2008 Naz $
	 * basic security to block non existant folders and back_browsing (../../etc)
	 * Do we need $_GET['thedir']{0} check? I dont think so.
	*/
function basic_hack_block(){
if ($_GET['thedir']{0}=="/" || $_GET['thedir']{0}=="." || !is_dir(WORKING_PATH)){
echo '<a href="'.$_SERVER['PHP_SELF'].'?type='.$_GET['type'].'&amp;sa='.$_GET['sa'].'"><img src="img/folder_up.gif" width="16" height="16" alt="'.TXT_BROWSE.'" border="0" /></a>';
die(TXT_ERROR.' '.TXT_HACK);
}
}
	/**
	 * $Id: basic_upload_hack_block 011 18-08-2008 Naz $
	 * basic security to block uploading outside of working path (../../etc)
	*/
function basic_upload_hack_block(){
if ($_POST['upload_path']{0}=="." || $_POST['upload_path']{1}=="." || $_POST['upload_path']{1}=="/" ||  !is_dir($_POST['upload_path'])){
die(TXT_HACK);
}
}
	/**
	 * $Id: get_files 011 18-08-2008 Naz $
	 * Crude and dirty function
	 * Must be improved
	*/
function get_files(){
global $file,$dir_name,$dir_name_path;
	$dh = opendir(WORKING_PATH);
	$files = array();
while (($filename = readdir($dh)) !== false)
{	//$filename[0] dont show hidden files
	if($filename != "." && $filename != ".." && $filename[0] != ".")
	{//If it is directory put name and path into array		
	if (is_dir(WORKING_PATH.$filename))
	{
	$dir_name[]=$filename;
	$dir_name_path[]=str_replace(CHANGE_PATH, "",str_replace("//","/",WORKING_PATH)).$filename;
	}
	else
	{	//If it is file put name and other info into array	
		$file['name'][] = $filename;
		$file['modified'][] = filemtime(WORKING_PATH.$filename);
		$file['size'][] = filesize(WORKING_PATH.$filename);
		// If it is image get width, height and mime_type and put in array
		if($_GET['type']=='image')
			{
			$imginfo = getimagesize(WORKING_PATH.$filename);
			$file['width'][] = $imginfo[0]."px";
			$file['height'][] = "x".$imginfo[1]."px";
			$file['type'][] = $imginfo['mime'];
			}
			else // it is not img, so get mime_type only
			{
			$file['type'][] = return_mime_type($filename);
			}
	}
	}
}
closedir($dh);
}
	/**
	 * $Id: do_delete 011 18-08-2008 Naz $
	 * Delete files or reculsively delete directories (all content)
	 * Must be improved
	*/
function do_delete(){
// Delete any checked files
if(isset($_POST['file_to_del']))
	{
	foreach($_POST['file_to_del'] as $file => $val)
		{
		$file_to_delet = WORKING_PATH.$file;
		if (file_exists($file_to_delet)) 
			{
			chmod($file_to_delet, 0777); 
			@unlink($file_to_delet);
			}
	}
}
// Delete any checked directories and its content
	if(isset($_POST['dir_to_del']))
	{
	foreach($_POST['dir_to_del'] as $dir => $val)
		{
		$dir_to_delet = CHANGE_PATH.$dir;
		delete_directory($dir_to_delet);//do the delete_directory recursive del function
		}
	}
}
	/**
	 * $Id: delete_directory 011 18-08-2008 Naz $
	 * Reculsively delete directories (all content)
	*/
function delete_directory($dir) {    
if (substr($dir, strlen($dir)-1, 1) != '/')        
$dir .= '/';     
if ($handle = opendir($dir))
    {        
	while ($obj = readdir($handle))
	   {            
		if ($obj != '.' && $obj != '..')
			{
				if (is_dir($dir.$obj))                
				{                    
				if (!delete_directory($dir.$obj))                        
					return false;                
				}                
					elseif (is_file($dir.$obj))                
						{ 
						chmod($dir.$obj, 0777); //remove if you have problems
						if (!unlink($dir.$obj))
						return false;                
						}            
			}        
		}         
	closedir($handle); 
	chmod($dir, 0777);  //remove if you have problems
	if (!@rmdir($dir))   
	return false;        
	return true;    
	}else{   
		return true; 
		}
}
	/**
	 * $Id: return_mime_type_icon 011 18-08-2008 Naz $
	 * Return the icons of files
	 * TODO merge it with the below return_mime_type function 
	*/
function return_mime_type_icon($filename)
    {
	    //GET the extension $fileSuffix[1] is without dot, [0] with dot
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);
		//assign icon based on extension
        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
            case "json" :
                return "script.gif";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
               return "image.gif";

            case "css" :
                return "css.gif";

            case "xml" :
                return "xml.gf";

            case "doc" :
            case "docx" :
                return "word.gif";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "excel.gif";

            case "ppt" :
            case "pps" :
                return "powerpoint.gif";

            case "rtf" :
                return "rtf.gif";

            case "pdf" :
                return "pdf.gif";

            case "html" :
            case "htm" :
            case "php" :
                return "html.gif";

            case "txt" :
                return "txt.gif";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "movie.gig";

            case "mp3" :
           case "wav" :
            case "aiff" :
            case "aif" :
                 return "audio.gif";

            case "avi" :
            case "wmv" :
            case "mov" :
                return "movie.gig";

			case "zip" :
			case "gzip" :
			     return "zip.gif";
				
			case "tar" :
            case "gz" :
			case "rar" :
                return "archive.gif";
				
            case "swf" :
                return "swf.gif";
 
             case "exe" :
                return "exe.gif";
            default :
			//if neither of the above, return unknown icon
                return "unknown.gif";
        }
    }
	/**
	 * $Id: return_mime_type 011 18-08-2008 Naz $
	 * Return the mime type of files
	 * TODO merge it with the above return_mime_type_icon function 
	*/	
function return_mime_type($filename)
    {
        //GET the extension $file_extension[1] is without dot, [0] with dot
		preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $file_extension);
		//mime type based on extension
        switch(strtolower($file_extension[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($file_extension[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";
				
			case "rar" :
                return "application/x-rar";
				
			case "gz" :
                return "application/x-gz";		

            case "swf" :
                return "application/x-shockwave-flash";
				
			case "exe" :
                return "application/exe";

            default :
			//if other extension, try to get the mime type with PHP mime_content_type
			//otherwise return unknown type
            if(function_exists("mime_content_type"))
            {
                $file_extension = mime_content_type($filename);
            }else{
			$file_extension ="unknown";
			}

            return $file_extension." file";
        }
    }

?>
