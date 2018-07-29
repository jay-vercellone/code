<?php
Class Whitelist_model extends CI_Model
{
	
	function __construct()
	{
			parent::__construct();
	}
	
	
	public function advertisers()
	{
		
		$params = array(
			'fields' => array(
				'id'
				,'company'
				,'status'
				
			)
			,'filters' => array(
					array('status' => 'pending')
					,array('status' => 'active')
			)
			,'limit' => 4000
			,'page'=> 1
		);
		
		$result = file_get_contents('http://api.hasoffers.com/Api/json?NetworkId='.$this->config->item('network_id').'&NetworkToken='.$this->config->item('network_token').'&Target=Advertiser&Method=findAll&'. http_build_query( $params ));
		
		$records = json_decode( $result );
		$adv = array();
		foreach($records->response->data->data as $key => $record){
			$adv[$key] = $record->Advertiser;  	
		}
	 return $adv;
			
	}
	
	
	public function offers($adv = false)
	{
		
		$params = array(
			'fields' => array(
				'id'
				,'name'
				,'status'
				
			)
			,'filters' => array(
				'status' => array(
					'active',
					'pending'
				),
            'advertiser_id' => $adv
        )
			,'limit' => 4000
			,'page'=> 1
		);
		
		$result = file_get_contents('http://api.hasoffers.com/Api/json?NetworkId='.$this->config->item('network_id').'&NetworkToken='.$this->config->item('network_token').'&Target=Offer&Method=findAll&'. http_build_query( $params ));
		
		$records = json_decode( $result );
		$offer = array();
		foreach($records->response->data->data as $key => $record){
			$offer[$key] = $record->Offer;  	
		}
	 return $offer;
			
	}
	
	
	
	public function offerswhitelist_finall($offers = false)
	{
		$params = array(
			'filters' => array(
				'offer_id' => $offers
        )
			,'limit' => 4000
			,'page'=> 1
		);
		
		$result = file_get_contents('http://api.hasoffers.com/Api/json?NetworkId='.$this->config->item('network_id').'&NetworkToken='.$this->config->item('network_token').'&Target=OfferWhitelist&Method=findAll&'. http_build_query( $params ));
		
		$records = json_decode( $result );
		
		$ips = array();
		foreach($records->response->data->data as $key => $record){
			$ips[$key] = $record->OfferWhitelist;  	
		}
	 return $ips;
	 
			
	}
	
	
	public function delete_ip($ids = false)
	{
		$params = array(
			'id' => $ids
		);
		
		$result = file_get_contents('http://api.hasoffers.com/Api/json?NetworkId='.$this->config->item('network_id').'&NetworkToken='.$this->config->item('network_token').'&Target=OfferWhitelist&Method=delete&'. http_build_query( $params ));
		
		$records = json_decode( $result );
			
	}
	
	function enableOfferWhitelist($offer_id= false){
		$params = array(
			'id'=>$offer_id,
			'data' => array(
				'enable_offer_whitelist' => 1
			)
		);
		
		$result = file_get_contents('http://api.hasoffers.com/Api/json?NetworkId='.$this->config->item('network_id').'&NetworkToken='.$this->config->item('network_token').'&Target=Offer&Method=update&'. http_build_query( $params ));
		
		$records = json_decode( $result );
	}
	
	function addWhitelistIP($offerID, $ipAddress){
		$params = array(
			'data' => array(
				'offer_id' => $offerID
				,'type' => 'postback'
				,'content_type' =>'ip_address'
				,'content' => $ipAddress
			)
		);
		
		$result = file_get_contents('http://api.hasoffers.com/Api/json?NetworkId='.$this->config->item('network_id').'&NetworkToken='.$this->config->item('network_token').'&Target=OfferWhitelist&Method=create&'. http_build_query( $params ));
		
		$records = json_decode( $result );
	}
	
	
	
}

