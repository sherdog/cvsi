<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Store_model extends CI_Model {

	function getCategories()
	{
		//returns all categories
	}

	function getCategoryTitle($categoryID = null)
	{
		if($categoryID === null)
			return 'For Sale';

		$this->db->select('categories_title');
		$this->db->from('store_categories');
		$this->db->where('categories_id', $categoryID);

		$query = $this->db->get();

		$row = $query->row();

		return $row->categories_title;
	}

	function getProducts($categoryID = null, $limit, $offset)
	{
		//returns all items with pagination yay?

		$this->db->select('*');
		$this->db->from('store_products');
		$this->db->where('categories_id', $categoryID);
		$this->db->limit($limit, $offset);
		$this->db->order_by('products_date_added', 'desc');

		$query = $this->db->get();


		return $query->result();

	}

	function getProductTitle($productID = null)
	{
		if($productID === null)
			return '';

		$this->db->select('products_title');
		$this->db->from('store_products');
		$this->db->where('products_id', $productID);

		$query = $this->db->get();

		$row = $query->row();

		return ($row->products_title != '') ? $row->products_title : '';
	}

	function getProductTotalCount($categoryID = null)
	{
		if($categoryID === null)
		{
			return 0;
		}

		$this->db->where('category_id', $categoryID);
		return $this->db->count_all('gallery');

	}

	function getProduct($productID = null)
	{
		//returns a single item.

		$this->db->select('*');
		$this->db->from('store_products');
		$this->db->where('products_id', $productID);

		$query = $this->db->get();

		return $query->row();
	}

	function getProductImage($productID = null)
	{
		//returns the default image for a product.
		$this->db->select('*');
		$this->db->from('store_products_images');
		$this->db->where('products_id', $productID);
		$this->db->where('products_images_default', 1);
		$this->db->order_by('products_images_id', 'asc');
		$this->db->limit(1);

		$query = $this->db->get();
		$result = $query->row();

		if(!$result)
		{
			$this->db->select('*');
			$this->db->from('store_products_images');
			$this->db->where('products_id', $productID);
			$this->db->order_by('products_images_id', 'asc');
			$this->db->limit(1);

			$query = $this->db->get();

			$result = $query->row();
		}

		if($result->products_images_filename)
		{
			return $result->products_images_filename;
		}
		else
		{
			return '';
		}
	}

	function getProductImages($productID)
	{
		$this->db->select('*');
		$this->db->from('store_products_images');
		$this->db->where('products_id', $productID);
		$this->db->order_by('products_images_id', 'asc');

		$query = $this->db->get();

		return $query->result();
	}
}
