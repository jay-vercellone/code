<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
error_reporting(-1);
        ini_set('display_errors', 1);
        $this->load->model('api_model');
        $this->load->model('offers_model');
        $this->load->model('advertiser_model');
        $this->load->model('email_model');
        $this->load->model('whitelist_model');
        
    }

    public function index() {
        
    }

    function run_cron() {
        $AdvAPI = $this->advertiser_model->advertiserAPI(array('fields' => 'AdvertiserID', 'where' => "AdvertiserStatus = 'active' and LastUpdate <= '" . date('Y-m-d H:i:s', strtotime('-5 hours')) . "'", 'return' => 'row'));

        if (!empty($AdvAPI)) {
            self::adv_offers($AdvAPI->AdvertiserID);
        }
    }
    
    function run_status() {
        $AdvAPI = $this->advertiser_model->advertiserAPI(array('fields' => 'AdvertiserID', 'where' => "AdvertiserStatus = 'active' and OfferStatusUpdate <= '" . date('Y-m-d H:i:s', strtotime('-4 hours')) . "'", 'return' => 'row'));

        if (!empty($AdvAPI)) {
            self::status($AdvAPI->AdvertiserID);
        }
    }

    public function adv_offers($AdvID = false) {
        if (!$AdvID)
            die('No Advertiser found');
        $data = array();

        $offer_id = $update = $save = $inactive_offers = array();
        $AdvAPI = $this->advertiser_model->advertiserAPI(array('AdvertiserID' => $AdvID, 'fields' => 'AdvertiserID, AdvertiserName', 'where' => "AdvertiserStatus = 'active'", 'return' => 'row'));
        if (empty($AdvAPI))
            die('Advertiser API Not Available.');

        $adv_update = array();
        $adv_update['AdvertiserID'] = $AdvID;
        $adv_update['LastUpdate'] = date('Y-m-d H:i:s');
        $this->advertiser_model->update_adv($adv_update);

        $function_name = 'offers_' . $AdvID;
        $AdvOffers = $this->api_model->$function_name();
        if (!empty($AdvOffers)) {
            $existings = $this->offers_model->offers(array('fields' => 'ID, OfferID, AdvertiserID', 'AdvertiserID' => $AdvID));

            if (!empty($existings)) {
                foreach ($existings as $exist) {
                    $offer_id[$exist->OfferID] = $exist->ID;
                }
            }

            foreach ($AdvOffers as $n) {
                $n['LastUpdate'] = date('Y-m-d H:i:s');
                if (array_key_exists($n['OfferID'], $offer_id)) {
                    $n['ID'] = $offer_id[$n['OfferID']];
                    $update[] = $n;
                } else {
                    $n['Created'] = date('Y-m-d');
                    $save[] = $n;
                }

                $active_offers[] = $n['OfferID'];
            }

            foreach ($existings as $exist) {
                if (!in_array($exist->OfferID, $active_offers)) {
                    $inactive = array();
                    $inactive['ID'] = $exist->ID;
                    $inactive['OfferStatus'] = 'paused';
                    $inactive['LastUpdate'] = date('Y-m-d H:i:s');
                    $inactive_offers[] = $inactive;
                }
            }

            if (!empty($save))
                $this->offers_model->save_offers($save);
            if (!empty($update))
                $this->offers_model->update_offers($update);
            if (!empty($inactive_offers))
                $this->offers_model->update_offers($inactive_offers);
            //update advertiser
        }
        echo 'Records Updated!!';
    }

    public function ho_offers() {
        $data = array();

        $offer_id = $update = $save = array();

        $HoOffers = $this->api_model->offers();

        $existings = $this->offers_model->ho_offers(array('fields' => 'ID, OfferID, AdvertiserID'));

        if (!empty($existings)) {
            foreach ($existings as $exist) {
                $offer_id[$exist->OfferID] = $exist->OfferID;
            }
        }

        foreach ($HoOffers as $n) {
            $n['LastUpdate'] = date('Y-m-d H:i:s');
            if (array_key_exists($n['OfferID'], $offer_id)) {
                $n['OfferID'] = $offer_id[$n['OfferID']];
                $update[] = $n;
            } else {
                $n['HOCreated'] = date('Y-m-d H:i:s');
                $save[] = $n;
            }
        }

        if (!empty($save))
            $this->offers_model->save_hooffers($save);
        if (!empty($update))
            $this->offers_model->update_hooffers($update);
    }

    public function status($advertiserID = false) {
        $data = array();

        $offer_id = $pause = $update = $save = array();
        
       
        $ho_offers = $this->offers_model->ho_offers(array('fields' => 'ID, OfferID, AdvertiserID, RefID, OfferName', 'where' => "OfferStatus = 'active' and AdvertiserID = '".$advertiserID."'"));

        $offers = $this->offers_model->offers(array('fields' => 'ID, OfferID, AdvertiserID, OfferStatus', 'where' => "AdvertiserID = '".$advertiserID."'"));


        if (!empty($offers)) {
            foreach ($offers as $offer) {
                $offer_id[$offer->AdvertiserID][$offer->OfferID] = $offer->OfferStatus;
            }
        }


        if (!empty($ho_offers)) {
            foreach ($ho_offers as $ho_offer) {
                if (array_key_exists($ho_offer->AdvertiserID, $offer_id) && array_key_exists($ho_offer->RefID, $offer_id[$ho_offer->AdvertiserID]) && $offer_id[$ho_offer->AdvertiserID][$ho_offer->RefID] != 'active') {
                    $pause[$ho_offer->OfferID] = $ho_offer->OfferID;
                }
            }
        }

        foreach ($pause as $p) {
            $n['LastUpdate'] = date('Y-m-d H:i:s');
            $n['OfferPause'] = '1';
            $n['OfferID'] = $p;
            $update[] = $n;

            $this->api_model->update_status($p);
        }

        if (!empty($update))
            $this->offers_model->update_hooffers($update);
        
        $upd_adv = array();
        $upd_adv['AdvertiserID'] = $advertiserID;
        $upd_adv['OfferStatusUpdate'] = date('Y-m-d H:i:s');;
        
            $this->advertiser_model->update_adv($upd_adv);
    }

    public function send_emails() {
        $data = array();

        $pause_offer = $offer_name = $offer_affiliate = $offer_id = $pause = $update = array();
        $affiliates = $this->api_model->affiliates();

        $ho_offers = $this->offers_model->ho_offers(array('fields' => 'ID, OfferID, AdvertiserID, RefID, OfferName', 'where' => "OfferPause = '1'", 'limit' => '5', 'offset' => '0', 'return' => 'result'));


        if (!empty($ho_offers)) {
            foreach ($ho_offers as $ho_offer) {
                $pause_offer[$ho_offer->OfferID] = $ho_offer->OfferID;
                $offer_name[$ho_offer->OfferID] = $ho_offer->OfferName;
                $adv_id[$ho_offer->OfferID] = $ho_offer->AdvertiserID;
            }
            foreach ($pause_offer as $key => $po) {
                $function_name = 'offers_' . $adv_id[$po];
                $AdvOffers = $this->api_model->$function_name();
                foreach ($AdvOffers as $k) {
                    $active_offers[$k['OfferID']] = $k['OfferStatus'];
                }
                $n = array();
                if (array_key_exists($po, $active_offers) && $active_offers[$po] == 'active') {
                    $n['LastUpdate'] = date('Y-m-d H:i:s');
                    $n['OfferPause'] = '0';
                    $n['OfferID'] = $po;
                    $update[] = $n;
                    if (!empty($update))
                        $this->offers_model->update_hooffers($update);
                }
            }
            $working = $this->api_model->get_working_affiliates($pause_offer);

            foreach ($pause_offer as $key => $po) {

                $offer_affiliate = array();
                foreach ($working[$key] as $key1 => $wo) {
                    $offer_affiliate[$wo] = $affiliates[$wo];
                }

                if (!empty($offer_affiliate)) {
                    $this->email_model->send_pause_email(array(
                        'OfferID' => $key,
                        'OfferName' => $offer_name[$key],
                        'Emails' => $offer_affiliate
                    ));
                }

                $pause[] = $key;
            }
        }

        foreach ($pause as $p) {
            $n['LastUpdate'] = date('Y-m-d H:i:s');
            $n['OfferPause'] = '2';
            $n['OfferID'] = $p;
            $update[] = $n;
        }

        if (!empty($update))
            $this->offers_model->update_hooffers($update);
    }

    public function send_active_emails() {
        $data = array();

        $pause_offer = $offer_name = $offer_affiliate = $offer_id = $pause = $update = array();
        $affiliates = $this->api_model->affiliates();

        $ho_offers = $this->offers_model->ho_offers(array('fields' => 'ID, OfferID, AdvertiserID, RefID, OfferName', 'where' => "TempCol = '1' and OfferPause ='2'", 'limit' => '5', 'offset' => '0'));


        if (!empty($ho_offers)) {
            foreach ($ho_offers as $ho_offer) {
                $pause_offer[$ho_offer->OfferID] = $ho_offer->OfferID;
                $offer_name[$ho_offer->OfferID] = $ho_offer->OfferName;
            }
            $working = $this->api_model->get_working_affiliates_temp($pause_offer);

            foreach ($pause_offer as $key => $po) {
                $offer_affiliate = array();
                foreach ($working[$key] as $key1 => $wo) {
                    $offer_affiliate[$wo] = $affiliates[$wo];
                }

                if (!empty($offer_affiliate)) {
                    $this->api_model->change_status($key);
                    $this->email_model->send_active_email(array(
                        'OfferID' => $key,
                        'OfferName' => $offer_name[$key],
                        'Emails' => $offer_affiliate
                    ));
                }

                $pause[] = $key;
            }
        }

        foreach ($pause as $p) {
            $n['LastUpdate'] = date('Y-m-d H:i:s');
            $n['TempCol'] = '2';
            $n['OfferID'] = $p;
            $update[] = $n;
        }

        if (!empty($update))
            $this->offers_model->update_hooffers($update);
    }

    public function update_ip() {
        $data = array();

        $ho_offers = $this->offers_model->ho_offers(array('fields' => 'ID, OfferID, AdvertiserID, RefID, OfferName', 'where' => "HoCreated  >= '" . date('Y-m-d H:i:s', strtotime('-2 hours')) . "'", 'limit' => '3', 'offset' => '0'));
        if (!empty($ho_offers)) {
            foreach ($ho_offers as $ho) {

                $update['offers'] = $this->whitelist_model->offers($ho->AdvertiserID);
                if (!empty($update['offers'])) {
                    foreach ($update['offers'] as $okey => $of) {
                        $adv_offer[] = $okey;
                    }
                    $ips = $this->whitelist_model->offerswhitelist_finall($adv_offer);
                    $ip1 = array();
                    foreach ($ips as $ip) {
                        $ip1[$ip->content] = $ip->content;
                    }
                    $update['ips'] = $ip1;
                    foreach ($update['offers'] as $key => $off) {
                        $this->whitelist_model->enableOfferWhitelist($key);
                        foreach ($update['ips'] as $ipp) {
                            $this->whitelist_model->addWhitelistIP($key, $ipp);
                        }
                    }
                }
            }
        }
        echo 'records updated';
    }

    public function compare() {

        $ho_offers = $this->api_model->offers_compare();
        $adv = $this->api_model->advertisers();
        
        $advertisers_to_compare = array('554', '27', '472', '961', '191', '430', '312', '440', '350', '949', '939', '211', '376',  '550', '488', '817', '384', '470', '568', '474', '779', '372', '743', '410', '655', '101', '408', '414', '921', '412', '728', '155', '173', '562','261','975','564','446','661');
        
       
        $offers = $this->offers_model->offers(array('fields' => 'ID,OfferID, OfferName,AdvertiserID, OfferStatus, PreviewURL, DefaultPayout, Created, ConversionCap, ExpiryDate, Country ', 'where' => "OfferStatus = 'active' and LastUpdate > '".date('Y-m-d H:i:s', strtotime('-2 days'))."'  and AdvertiserID not in (" . implode(',', $advertisers_to_compare) . ")"));
        $ho_records = $adv_offers = array();
        
        

        foreach ($ho_offers as $ho) {

            $google = $itunes = false;
            if (!empty($ho['PreviewURL'])) {
                if (strpos($ho['PreviewURL'], 'play.google.com') != false) {
                    $google = true;
                }
                if (strpos($ho['PreviewURL'], 'itunes.apple.com') != false) {
                    $itunes = true;
                }
            }
            if ($google) {
                $parts = explode('?id=', $ho['PreviewURL']);
                if ($parts[0] == $ho['PreviewURL']) {
                    $parts = explode('&id=', $ho['PreviewURL']);
                }
                $parts1 = explode('&', $parts['1']);
                $appid = $parts1['0'];
            } elseif ($itunes) {
                $parts = explode('/id', $ho['PreviewURL']);
                $parts1 = explode('?', 'id' . $parts['1']);
                $appid = $parts1['0'];
            } else {
                $appid = $ho['PreviewURL'];
            }
            $ho['AdvertiserName'] = $adv[$ho['AdvertiserID']]['company'];
            $ho['AdvertiserManager'] = $adv[$ho['AdvertiserID']]['manager'];
            $adv_offers[$appid][$ho['RefID']] = $ho;
            $ho_records[$appid][$ho['OfferID']] = $ho;
        }
        
        

        $offer_records = $offers_adv = $of_records = array();

        foreach ($offers as $off) {
            $google = $itunes = false;
            if (!empty($off->PreviewURL)) {
                if (strpos($off->PreviewURL, 'play.google.com') != false) {
                    $google = true;
                }
                if (strpos($off->PreviewURL, 'itunes.apple.com') != false) {
                    $itunes = true;
                }
            }

            if ($google) {
                $parts = explode('?id=', $off->PreviewURL);
                if ($parts[0] == $off->PreviewURL) {
                    $parts = explode('&id=', $off->PreviewURL);
                }
                $parts1 = explode('&', $parts['1']);
                $appid = $parts1['0'];
            } elseif ($itunes) {
                $parts = explode('/id', $off->PreviewURL);
                $parts1 = explode('?', 'id' . $parts['1']);
                $appid = $parts1['0'];
            } else {
                $appid = $off->PreviewURL;
            }
            $off->AdvertiserName = $adv[$off->AdvertiserID]['company'];
            $off->AdvertiserManager = $adv[$off->AdvertiserID]['manager'];
            if ($off->Created >= date('Y-m-d', strtotime('-1 days')) && ($google || $itunes)) {
                $of_records[$appid][$off->OfferID] = $off;
            }
            $offer_records[$appid][$off->OfferID] = $off;
        }


        $new_offers = array();
        foreach ($of_records as $ofkey => $of) {
            if (!array_key_exists($ofkey, $adv_offers)) {
                foreach ($of as $o) {
                    $new_offers[$ofkey][$o->OfferID] = $o;
                }
            }
        }

        foreach ($ho_records as $hokey => $hor) {
            foreach ($hor as $horkey => $horoffer) {
                $max = $ho_country = array();
                $ho_country = explode(',', $horoffer['Country']);

                if (array_key_exists($hokey, $offer_records)) {
                    if ($offer_records[$hokey]) {
                        foreach ($offer_records[$hokey] as $ofkey => $ofr) {
                            $ofer_country = explode(',', $ofr->Country);


                            foreach ($ofer_country as $con) {
                                if ($con != '' && in_array($con, $ho_country)) {
                                    if (round($ofr->DefaultPayout, 2) > round($horoffer['DefaultPayout'], 2)) {
                                        $max[$ofkey] = $ofr->DefaultPayout;
                                    }
                                }
                            }
                        }
                    }
                }




                if (!empty($max)) {
                    $maxpayout = array_search(max($max), $max);
                    $record = array();
                    $record['ho'] = $hor[$horkey];
                    $record['offer'] = $offer_records[$hokey][$maxpayout];
                    $records[] = $record;
                }
            }
        }

        $found_item = array();
        foreach ($new_offers as $new_appy => $new) {

            $maxvalue = -9999999; //will hold max val
            //will hold item with max val;

            foreach ($new as $k => $v) {

                if ($v->DefaultPayout > $maxvalue) {
                    $maxvalue = $v->DefaultPayout;
                    $found_item_new = $v;
                }
            }
            $found_item[] = $found_item_new;
        }

        $data['newoffers'] = $found_item;
        $data['ho_records'] = $records;
       
        
        $this->email_model->send_morning_email(array(
            'newoffers' => $data['newoffers'],
            'ho_records' => $data['ho_records']
        ));
        echo 'sent';
    }

    public function suggestion() {
        $s_t_l = $s_t_r = array(); // suggestion to load, suggestion to replace
        $advertisers_to_compare = array('7',  '23', '390',  '861', '59', '376', '384', '927', '851',  '43', '209', '31', '691', '15', '211',  '781',  '510', '19', '39', '103', '73', '294', '57', '386', '3', '175', '229', '193','488','115','145','386','1147','243','51','41','1043','767','1185','869', '376','101');


        $adv = $this->api_model->advertisers();
        //get all the live offers from these advertiser 
        $offers = $this->offers_model->offers(array('fields' => 'ID,OfferID, OfferName,AdvertiserID, OfferStatus, PreviewURL, DefaultPayout, Created, ConversionCap, ExpiryDate, Country ', 'where' => "Created  >= '" . date('Y-m-d H:i:s', strtotime('-25 hours')) . "' and OfferStatus = 'active' and AdvertiserID in (" . implode(',', $advertisers_to_compare) . ")"));
        
        
        //get all offers from hasoffers
        $ho_offers = $this->api_model->offers_compare();
        
        $ho_records = $adv_offers = array();
        foreach ($ho_offers as $ho) {

            $google = $itunes = false;
            if (!empty($ho['PreviewURL'])) {
                if (strpos($ho['PreviewURL'], 'play.google.com') != false) {
                    $google = true;
                }
                if (strpos($ho['PreviewURL'], 'itunes.apple.com') != false) {
                    $itunes = true;
                }
            }
            if ($google) {
                $parts = explode('?id=', $ho['PreviewURL']);
                if ($parts[0] == $ho['PreviewURL']) {
                    $parts = explode('&id=', $ho['PreviewURL']);
                }
                $parts1 = explode('&', $parts['1']);
                $appid = $parts1['0'];
            } elseif ($itunes) {
                $parts = explode('/id', $ho['PreviewURL']);
                $parts1 = explode('?', 'id' . $parts['1']);
                $appid = $parts1['0'];
            }
            if ($google || $itunes) {
                $ho['AdvertiserName'] = $adv[$ho['AdvertiserID']]['company'];
                $ho['AdvertiserManager'] = $adv[$ho['AdvertiserID']]['manager'];
                $adv_offers[$ho['AdvertiserID']][$ho['RefID']] = $ho;
                $ho_records[$appid][$ho['OfferID']] = $ho;
            }
        }
        
        $offer_records = $offers_adv = $of_records = array();
        // compare based on payout.
        foreach ($offers as $off) {
            $google = $itunes = false;
            if (!empty($off->PreviewURL)) {
                if (strpos($off->PreviewURL, 'play.google.com') != false) {
                    $google = true;
                }
                if (strpos($off->PreviewURL, 'itunes.apple.com') != false) {
                    $itunes = true;
                }
            }

            if ($google) {
                $parts = explode('?id=', $off->PreviewURL);
                if ($parts[0] == $off->PreviewURL) {
                    $parts = explode('&id=', $off->PreviewURL);
                }
                $parts1 = explode('&', $parts['1']);
                $appid = $parts1['0'];
            } elseif ($itunes) {
                $parts = explode('/id', $off->PreviewURL);
                $parts1 = explode('?', 'id' . $parts['1']);
                $appid = $parts1['0'];
            }
            if ($google || $itunes) {
                $off->AdvertiserName = $adv[$off->AdvertiserID]['company'];
                $off->AdvertiserManager = $adv[$off->AdvertiserID]['manager'];

                $offer_records[$appid][$off->OfferID] = $off;
            }
        }

        //get the list of offers which are single and we don;t have live them
        if (!empty($offer_records)) {
            foreach ($offer_records as $ofr_key => $ofr) {
                if (count($ofr) == '1') { // check if offer is live only on one advertiser
                    if (!array_key_exists($ofr_key, $ho_records)) { //this app url is not already live on our platform
                        foreach ($ofr as $ofr_single) {
                            if (is_numeric($ofr_single->DefaultPayout) && $ofr_single->DefaultPayout > '2') {
                                $s_t_l['single'][] = $ofr_single; // suggest to load as single advertiser
                            }
                        }
                    }
                } else { // offer is live at mroe than one advertiser
                    if (!array_key_exists($ofr_key, $ho_records)) { //this app url is not already live on our platform
                        $highest_revenue = $highest_revenue_key = '0';
                        $highest_revenue_offer = false;
                        foreach ($ofr as $offr_key => $ofr_single) {
                            $our_payout = '0';
                            if ($ofr_single->DefaultPayout > $highest_revenue) {
                                $highest_revenue = $ofr_single->DefaultPayout;
                                $highest_revenue_key = $offr_key;
                                $highest_revenue_offer = $ofr_single;
                            }
                        }

                        $our_payout = '0';
                        // our payout will be 25% less
                        $our_payout = $highest_revenue - ($highest_revenue * 25 / 100);


                        foreach ($ofr as $offrrr_key => $ofrrr_single) {
                            
                            if ($offrrr_key != $highest_revenue_key) {
                                if ( $ofrrr_single->DefaultPayout > $our_payout) {
 
                                    $highest_revenue_offer = false;
                                }
                            }
                        }
                        if ($highest_revenue_offer)
                            $s_t_l['payout'][] = $highest_revenue_offer;
                    }
                }
                
                
//                if (array_key_exists($ofr_key, $ho_records)) { // offer already exists
//                    foreach($ho_records[$ofr_key] as $ho_off){
//                        $load_offer = false;
//                        $hasoffers_payout = $ho_off->max_payout;
//                        foreach ($ofr as $ofr_single) {
//                            if ($ofr_single->DefaultPayout > $hasoffers_payout) {
//                                $ofr_single->CurrentOffer = $ho_off->id;
//                                $load_offer = $ofr_single; // suggest to load as single advertiser
//                            }
//                        }
//                        
//                    }
//                }
            }
        }
        
       
        
        $this->email_model->send_suggestion_email($s_t_l);
       
    }

}
