<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Nav_model extends CI_Model {

	function subpages($id = 0)
	{
		$this->db->select('page_content_url, page_content_title');
		$this->db->from('page_content');
		if($id)
		{
			$this->db->where('parent', $id);
		}
		$this->db->where('page_content_status', 'published');

		$this->db->order_by('page_content_sort_order');

		$query = $this->db->get();

		return $query->result();

	}

}