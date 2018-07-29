<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
var $user;	
	public function __construct()
	{
		parent::__construct();
		
		
	}

	public function index()
	{
				$data =array();
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '%s is required');
		//validate form data
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		
		//form validation runs true
		if ($this->form_validation->run()){
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			
			$params = array(
			'email' => $email
			,'password' => $password
			,'type' => 'employee'
			);
		
		$result = file_get_contents('http://api.hasoffers.com/Api/json?NetworkId='.$this->config->item('network_id').'&NetworkToken='.$this->config->item('network_token').'&Target=Authentication&Method=findUserByCredentials&'. http_build_query( $params ));
		
	
		$records =  json_decode( $result );
		if($records->response->status=='1' && $records->response->data->user_status =='active' ){
			$user = array();
				$user['expire'] = time()+7200; //session expire time
			  	$user['UserID'] = '1';//$records->response->data->user_id;
				if($user['UserID']=='1'){ $user['Admin'] ='1'; } else { $user['Admin'] = '0'; }
			  $this->authpool->save($user);	//save session
			  redirect('dash', 'refresh');
		}else{
			$data['error'] = 'Invalid Login Details';	
		}	
		}else{
		$data['error'] = validation_errors();			
		}
		$this->load->view('login', $data);
	}
	
	
	
	public function logout()
		{
			
		$this->authpool->destroy();
		redirect('login','refresh');
	}
	
	
	
	
}
