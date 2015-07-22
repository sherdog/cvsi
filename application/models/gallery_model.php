<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Gallery_model extends CI_Model {

    var $title   = '';
    var $thumbnail = '';
    var $images    = '';
    var $desc = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getTotalGalleryCount()
    {
        return $this->db->count_all('gallery');
    }

    function getGalleries($limit = 0, $offset = 10)
    {  
        $this->db->select("*");
        $this->db->from('gallery');
        $this->db->order_by('gallery_date_added', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        error_log('last query: ' . $this->db->last_query());

        return $query->result();    
    }

    function getGalleryDefaultImage($galleryID=null)
    {


        $this->db->select('*');
        $this->db->from('gallery_images');
        $this->db->where('gallery_id', $galleryID);
        $this->db->where('gallery_image_featured', 1);
        $this->db->order_by('gallery_image_id', 'asc');
        $this->db->limit(1);

        $query = $this->db->get();

        $result = $query->row();

        if($result)
        {
            return $result;
        }

        $this->db->select('*');
        $this->db->from('gallery_images');
        $this->db->where('gallery_id', $galleryID);
        $this->db->order_by('gallery_image_id', 'asc');
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row();

    }

    function getSingleGallery($id)
    {
        $this->db->select('*');
        $this->db->from('gallery');
        $this->db->where('gallery_id', $id);

        $query = $this->db->get();
        return $query->row();
    }

    function getGalleryImages($id)
    {
        $this->db->select('*');
        $this->db->from('gallery_images');
        $this->db->where('gallery_id', $id);

        $query = $this->db->get();
        
        return $query->result();
    }
}