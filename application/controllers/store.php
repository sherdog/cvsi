<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends CI_Controller {

	function index()
	{

		$this->load->model('store_model', 'storeModel');
		$this->load->library('pagination');
		//temp category since we just have forsale.
		$category = 1;

		//$items per page
		$perPage = 20;
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		//we're going to load the for sale items for now.
		$config['base_url'] = base_url() . 'gallery/page';
		$config['total_rows'] = $this->storeModel->getProductTotalCount($category);
		$config['per_page'] = $perPage;
		$config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul><!--pagination-->';

		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&larr; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		$products = $this->storeModel->getProducts($category, $perPage, $page);


		$data['title'] = site_title('For Sale');
		$data['pagination'] = $this->pagination->create_links();
		$data['products'] = $products;
		$data['categoryTitle'] = $this->storeModel->getCategoryTitle($category);

		$this->load->view('store/products', $data);//load this bitch.


	}

	function detail($id = null)
	{
		if($id === null)
		{
			$this->uri->redirect(site_url() . 'store', 'refresh'); //redirect them back to the store page.
		}

		$this->load->model('store_model');

		$data['pageTitle'] = $this->store_model->getProductTitle($id);
		$data['title'] = site_title($data['pageTitle']);
		$data['defaultImage'] = $this->store_model->getProductImage($id); //default iamge.
		$data['productImages'] = $this->store_model->getProductImages($id);
		$data['product'] = $this->store_model->getProduct($id);

		$this->load->view('store/product-detail', $data);
	}

}