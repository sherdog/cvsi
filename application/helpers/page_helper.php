<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('site_title'))
{
	function site_title($append = '')
	{
		return "CVSi Motorsports :: " . $append; 
	}
}