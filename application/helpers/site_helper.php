<?php

if( ! function_exists("getFormRecipients"))
{
	function getFormRecipients()
	{
		//get the contact emails in the settings area.
		$ci =& get_instance();
    	
    	$ci->db->select('settings_value');
    	$ci->db->from('settings');
    	$ci->db->where('settings_key', 'contact_email');

    	$query = $ci->db->get();

    	return $query->row();

	}
}