<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dash extends CI_Controller {

    var $user;

    public function __construct() {
        parent::__construct();
        $this->load->model('advertiser_model');
        $this->load->model('api_model');
        $this->load->model('offers_model');
        $this->user = $this->authpool->user();
        if (!$this->user['UserID']) {
            redirect('login', 'refresh');
        }
    }

    public function index() {

        $data = array();
        $AdvAPI = $this->advertiser_model->advertiserAPI(array('fields' => 'AdvertiserID, AdvertiserName', 'where' => "AdvertiserStatus = 'active'", 'orderASC' => 'AdvertiserName'));

        foreach ($AdvAPI as $adv) {
            $advInfo[$adv->AdvertiserID] = $adv->AdvertiserName;
        }

        $cat_result = $this->offers_model->offers(array('fields' => 'Category', 'groupBy' => 'Category', 'where' => "Category != ''"));
        $os_result = $this->offers_model->offers(array('fields' => 'OperatingSystem', 'groupBy' => 'OperatingSystem', 'where' => "OperatingSystem != ''"));


        $cat = array();
        $device = array();
        foreach ($cat_result as $res) {

            if ($res->Category != '')
                $cat[] = ucfirst(strtolower($res->Category));
        }
        foreach ($os_result as $res) {

            if ($res->OperatingSystem != '')
                $device[] = ucfirst(strtolower($res->OperatingSystem));
        }

        $data['categories'] = $cat;
        $data['devices'] = $device;
        $data['Advertisers'] = $AdvAPI;

        $this->load->view('dash', $data);
    }

    public function getoffers() {

        $this->load->library('datatables');
        $HoOffers = $this->offers_model->ho_offers(array('fields' => 'ID, OfferID, AdvertiserID, RefID, OfferStatus'));
        foreach ($HoOffers as $HO) {
            if ($HO->RefID != '') {
                $HO_Ref[$HO->AdvertiserID][$HO->RefID] = $HO;
            }
        }
        $where_compare = '';
        if ($this->input->post('sSearch_13') != '') {
            $compare_offer_id = $this->input->post('sSearch_13');
            $compare_offer = $this->offers_model->offers(array('fields' => 'PreviewURL', 'where' => array('ID' => $compare_offer_id), 'return' => 'row'));

            $google = $itunes = false;
            if (strpos($compare_offer->PreviewURL, 'play.google.com') != false) {
                $google = true;
            }
            if (strpos($compare_offer->PreviewURL, 'itunes.apple.com') != false) {
                $itunes = true;
            }
            if ($google) {
                $parts = explode('?id=', $compare_offer->PreviewURL);
                $parts1 = explode('&', $parts['1']);
                $appid = $parts1['0'];
            } elseif ($itunes) {
                $parts = explode('app/', $compare_offer->PreviewURL);
                $parts1 = explode('?', $parts['1']);
                $appid = $parts1['0'];
            } else {
                $appid = $compare_offer->PreviewURL;
            }
            if ($appid != '') {
                $where_compare = "and offers.PreviewURL like '%$appid%' and OfferStatus = 'active'";
            }
        }


        $columns = array('ID', 'OfferID', 'OfferName', 'offers.AdvertiserID', 'AdvertiserName', 'PayoutType', 'DefaultPayout', 'Incent', 'OperatingSystem', 'Category', 'ConversionCap', 'RequireApproval', 'offers.OfferStatus', 'offers.LastUpdate', 'Created', 'Country', 'offers.PreviewURL', 'offers.showinapp');
        $joins = ', advertiserapi';
        $where = 'advertiserapi.AdvertiserID = offers.AdvertiserID ' . $where_compare;
        $records = $this->datatables->generate(array('table' => 'offers', 'columns' => $columns, 'index' => 'ID', 'joins' => $joins, 'where' => $where));
        $result = json_decode($records);
        foreach ($result->aaData as $key => $res) {
            $result->aaData[$key][13] = (!empty($HO_Ref[$res[3]][$res[1]])) ? $HO_Ref[$res[3]][$res[1]]->OfferStatus : '';
        }
        echo json_encode($result);
    }

}
