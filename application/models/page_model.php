<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Page_model extends CI_Model {

	function getPageContent($pageSlug = '')
	{
		if($pageSlug === '')
			return false;

		$this->db->select('page_content_title, page_content_seo_title,page_content_last_modified_date, page_content_seo_description, page_content_seo_keyword, page_content_image, page_content_text, page_content_publish_date');
		$this->db->from('page_content');
		$this->db->where('page_content_url', $pageSlug);
		$this->db->where('page_content_status', 'published');
		$this->db->limit(1);

		$query = $this->db->get();

		return $query->row();
	}

}