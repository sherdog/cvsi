<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('gallery_item'))
{
    function gallery_item($gallery = '')
    {
    	$ci =& get_instance();
    	$ci->load->model('gallery_model');
    	$defaultImage = $ci->gallery_model->getGalleryDefaultImage($gallery->gallery_id);

        echo '<div class="col-md-3 col-sm-6">';
	        echo '<div class="gallery-image">';
	        	

	        	echo heading($gallery->gallery_title, 2);
		        
	        	if(isset($defaultImage->gallery_image_filename))
	        	{
	        		$imgProp = array(
	        		"src" => "files/" . $defaultImage->gallery_image_filename,
	        		"class" => "img-responsive"
		        	);
		        	echo '<a href="'.site_url('gallery/detail/' . $gallery->gallery_id).'">';
		        	echo img($imgProp);
		        	echo "</a>";
	        	}
		        
		       	if(isset($gallery->gallery_desc))
		        {
		        	echo 	'<p>' . strip_tags($gallery->gallery_desc) . '</p>';
		        }

	        echo "</div>";
        echo '</div>';
    }   
}

if( ! function_exists("gallery_image"))
{
	function gallery_image($image = '')
	{
		echo '<div class="col-md-3" col-sm-6">';
		echo '<div class="gallery-detail-image">';

		$prop = array(
			'src' => 'files/'.$image->gallery_image_filename,
			'class' => 'img-responsive'
		);
		echo '<a href="'.base_url().'files/'.$image->gallery_image_filename.'" data-toggle="lightbox" data-gallery="multiimages">';
		echo img($prop);
		echo "</a>";
		echo '</div>';
		echo '</div>';
	}
}


