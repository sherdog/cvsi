<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {


	function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('form_model');
	}

	function index($slug)
	{
		$this->load->model('page_model');

		$page = $this->page_model->getPageContent($slug);
		//check to make sure slug exists.
		$data['title'] = site_title($page->page_content_seo_title);
		$data['meta_keywords'] = $page->page_content_seo_keyword;
		$data['pageTitle'] =  $page->page_content_title;
		$data['meta_desc'] = $page->page_content_seo_description;
		$data['body'] = $page->page_content_text;
		$data['meta_last_modified'] = $page->page_content_last_modified_date;
		$data['pageHeadImage'] = $page->page_content_image;

		$this->load->view('page', $data);

	}

	function contact() {

		$this->form_validation->set_rules('name', 'Name', 'required|max_length[255]');			
		$this->form_validation->set_rules('question', 'Question', 'required');			
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');			
		$this->form_validation->set_rules('phone', 'Phone', '');

		$this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
	
		if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		{
			$data['title'] = site_title('Contact us');
			$data['pageTitle'] = "Contact us";
			//we are going to display the form.
			$this->load->view('contact', $data);
		}
		else // passed validation proceed to post success logic
		{
		 	// build array for the model
			
			$form_data = array(
					       	'name' => set_value('name'),
					       	'question' => set_value('question'),
					       	'email' => set_value('email'),
					       	'phone' => set_value('phone')
						);
					
			// run insert model to write data to db
		
			if ($this->form_model->SaveForm($form_data) == TRUE) // the information has therefore been successfully saved in the db
			{
				redirect('page/success');   // or whatever logic needs to occur
			}
			else
			{
			echo 'An error occurred saving your information. Please try again later';
			// Or whatever error handling is necessary
			}
		}
	}

	function success()
	{
		echo 'this form has been successfully submitted with all validation being passed. All messages or logic here. Please note sessions have not been used and would need to be added in to suit your app';
	}

}