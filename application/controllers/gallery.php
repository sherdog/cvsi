<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {

	public function index($page = 0)
	{	
		
		$this->load->model('gallery_model', 'gallery');
		$this->load->library('pagination');

		//Number of records per page.
		$data = array();
		$perPage = 24;
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$currentPage = $page;

		//setup pagination.
		$config['base_url'] = base_url() . 'gallery/page';
		$config['total_rows'] = $this->gallery->getTotalGalleryCount();
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

		$data['title'] = site_title('Photo Gallery');
		$data['pagination'] = $this->pagination->create_links();

		$data['galleryItems'] = $this->gallery->getGalleries($perPage, $page);

		$this->load->view('gallery/gallery', $data);
	}

	public function detail($id = null)
	{
		if($id === null)
		{
			redirect('/gallery', 'refresh');
		}

		$this->load->model('gallery_model', 'gallery');

		$data['gallery'] = $this->gallery->getSingleGallery($id);
		$data['images'] = $this->gallery->getGalleryImages($id);

		$data['title'] = site_title($data['gallery']->gallery_title);
		$this->load->view('gallery/gallery-detail', $data);

	}

}