<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom extends CI_Controller {
var $user;	
	public function __construct()
	{
		parent::__construct();
		$this->user = $this->authpool->user();
		if(!$this->user['UserID']){
			redirect('login','refresh');	
		}
		
	}

	public function index()
	{
		redirect('dash','refresh');
	}
	
	
	
	
}
