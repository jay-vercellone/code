<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {


   protected $CI;
 
	function __construct()
	{
		parent::__construct();
 
		$this->CI =& get_instance();
	}

    function valid_url_format($str){
		
        if (!preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i',$str)) {
            $this->set_message('valid_url_format', 'Please Enter a valid Website URL');
            return FALSE;
        }
 
        return TRUE;
    }      
 
    
    function url_exists($url){                                  
        $url_data = parse_url($url); // scheme, host, port, path, query
        if(!fsockopen($url_data['host'], isset($url_data['port']) ? $url_data['port'] : 80)){
            $this->set_message('url_exists', 'The URL you entered is not accessible.');
            return FALSE;
        }              
         
        return TRUE;
    } 
}