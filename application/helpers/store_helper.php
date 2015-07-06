<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( ! function_exists("product_listing"))
{
	function product_listing_single($product)
	{	
		$ci =& get_instance();
    	$ci->load->model('store_model');
    	$defaultImage = $ci->store_model->getProductImage($product->products_id);

		echo '<div class="col-md-3 col-sm-';
		echo '<div class="product-listing-image">';
		echo '<a href="' . base_url() . 'product/detail/' . $product->products_id . '">';
		$prop = array(
			'class' => 'img-responsive',
			'src' => 'files/' . $defaultImage
		);
		echo img($prop);
		echo "</a>";
		if($product->products_title != '')
		{
			echo '<h2 class="product-listing-title">';
			echo '<a href="' . base_url() . 'product/detail/' . $product->products_id . '">';
			echo $product->products_title;
			echo '</a>';
			echo "</h2>";
		}
		if($ci->config->item('show_price') && $product->products_price)
		{
			echo '<div class="product-listing-price">';
			echo '$' . number_format($product->products_price,2,'.',',');
			echo '</div>';
		}

		if($product->products_desc != '')
		{
			echo "<p>";
			echo $product->products_desc;
			echo "</p>";
		}
		echo "<p>";
		echo '<a class="btn btn-primary btn-outline btn-block" href="' . base_url() . 'product/detail/' . $product->products_id . '">View</a>';
		echo "</p>";
		echo '</div>';
		echo '</div>';
	}
}