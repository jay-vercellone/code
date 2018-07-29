<?php
Class Offers_model extends CI_Model
{
	
	function __construct()
	{
			parent::__construct();
	}
	
	function offers($query=false){
		//if employee is passed then filter by employee
		if(!empty($query['OfferID'])){ $this->db->where('OfferID', $query['OfferID']); $query['return'] = 'row'; }
		if(!empty($query['AdvertiserID'])){ $this->db->where('AdvertiserID', $query['AdvertiserID']); }
		
		//if limit is passed then set limit
		if(!empty($query['limit'])) $this->db->limit($query['limit'], $query['offset']);
		//if fields
		if(!empty($query['fields'])) $this->db->select($query['fields']);
		//where condition exists
		
		//groupby exists
		if(!empty($query['groupBy']))	$this->db->group_by($query['groupBy']); 
		if(!empty($query['where']))	$this->db->where($query['where']);
		//if order by is set asc
		if(!empty($query['orderASC']))$this->db->order_by($query['orderASC'], "ASC"); 
		//if order by is set to desc
		if(!empty($query['orderDESC']))$this->db->order_by($query['orderDESC'], "DESC");  
		
		$result=  $this->db->get('offers');
		$return = (!empty($query['return'])) ? $query['return'] : 'result' ;
		//return data in array on in row
		$response =($return=='result') ? $result->result() : $result->row();
		
		//send data back
		$count = $result->num_rows();
		$response = $count >=0 ?  $response :  false; 
		if(!empty($query['count'])) $response->total = $count;
		return $response;
		
	}
	
	function save_offers($save){
		$this->db->insert_batch('offers', $save); 	
		
	}
	
	function update_offers($save){
		$this->db->update_batch('offers', $save, 'ID'); 		
	}
	
	
	function ho_offers($query=false){
		//if employee is passed then filter by employee
		if(!empty($query['OfferID'])){ $this->db->where('OfferID', $query['OfferID']); $query['return'] = 'row'; }
		if(!empty($query['AdvertiserID'])){ $this->db->where('AdvertiserID', $query['AdvertiserID']); }
		
		//if limit is passed then set limit
		if(!empty($query['limit'])) $this->db->limit($query['limit'], $query['offset']);
		//if fields
		if(!empty($query['fields'])) $this->db->select($query['fields']);
		//where condition exists
		
		//groupby exists
		if(!empty($query['groupBy']))	$this->db->group_by($query['groupBy']); 
		if(!empty($query['where']))	$this->db->where($query['where']);
		//if order by is set asc
		if(!empty($query['orderASC']))$this->db->order_by($query['orderASC'], "ASC"); 
		//if order by is set to desc
		if(!empty($query['orderDESC']))$this->db->order_by($query['orderDESC'], "DESC");  
		
		$result=  $this->db->get('hooffers');
		$return = (!empty($query['return'])) ? $query['return'] : 'result' ;
		//return data in array on in row
		$response =($return=='result') ? $result->result() : $result->row();
		
		//send data back
		$count = $result->num_rows();
		$response = $count >=0 ?  $response :  false; 
		if(!empty($query['count'])) $response->total = $count;
		return $response;
		
	}
	
	function save_hooffers($save){
		$this->db->insert_batch('hooffers', $save); 		
	}
	
	function update_hooffers($save){
		$this->db->update_batch('hooffers', $save, 'OfferID'); 		
	}
	
		

}

