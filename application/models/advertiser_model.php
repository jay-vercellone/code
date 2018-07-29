<?php
Class Advertiser_model extends CI_Model
{
	
	function __construct()
	{
			parent::__construct();
	}
	
		function advertiserAPI($query=false){

		//if employee is passed then filter by employee
		if(!empty($query['AdvertiserID'])){ $this->db->where('AdvertiserID', $query['AdvertiserID']); $query['return'] = 'row'; }
		
		//if limit is passed then set limit
		if(!empty($query['limit'])) $this->db->limit($query['limit'], $query['offset']);
		//if fields
		if(!empty($query['fields'])) $this->db->select($query['fields']);
		//where condition exists
		if(!empty($query['where']))	$this->db->where($query['where']);
		//if order by is set asc
		if(!empty($query['orderASC']))$this->db->order_by($query['orderASC'], "ASC"); 
		//if order by is set to desc
		if(!empty($query['orderDESC']))$this->db->order_by($query['orderDESC'], "DESC");  
		
		$result=  $this->db->get('advertiserapi'); 
               
		$return = (!empty($query['return'])) ? $query['return'] : 'result' ;
		//return data in array on in row
		$response =($return=='result') ? $result->result() : $result->row();
		
		//send data back
		$count = $result->num_rows();
		$response = $count >=0 ?  $response :  false; 
		if(!empty($query['count'])) $response->total = $count;
		return $response;
		
	}

	
	function update_adv($save){
		$this->db->where('AdvertiserID', $save['AdvertiserID']);
		$this->db->update('advertiserapi', $save);  		
	}
		

}

