<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Offers extends CI_Controller {

    var $user;

    public function __construct() {
        parent::__construct();
        $this->load->model('api_model');
        $this->load->model('offers_model');
        $this->load->model('advertiser_model');
        $this->user = $this->authpool->user();
        if (!$this->user['UserID']) {
            redirect('login', 'refresh');
        }
    }

    public function index() {
        redirect('dash', 'refresh');
    }

    public function compare() {
        $ho_offers = $this->api_model->offers_compare();
        $adv = $this->api_model->advertisers();
        $offers = $this->offers_model->offers(array('fields' => 'ID,OfferID,AdvertiserID, OfferStatus, PreviewURL, DefaultPayout ', 'where' => "OfferStatus = 'active'"));
        $ho_records = array();
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
            $ho_records[$appid][$ho['OfferID']] = $ho;
        }

        $offer_records = array();
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
            $offer_records[$appid][$off->OfferID] = $off;
        }



        foreach ($ho_records as $hokey => $hor) {
            foreach ($hor as $horkey => $horoffer) {
                $max = array();
                if (array_key_exists($hokey, $offer_records)) {
                    if ($offer_records[$hokey]) {
                        foreach ($offer_records[$hokey] as $ofkey => $ofr) {
                            if (round($ofr->DefaultPayout, 2) > round($horoffer['DefaultPayout'], 2)) {
                                $max[$ofkey] = $ofr->DefaultPayout;
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

        $data['records'] = $records;
        $this->load->view('compare', $data);
    }

    public function create($offerID = false) {
        $data = array();

        $this->load->library('form_validation');
        $this->form_validation->set_message('required', '%s is required');
        //validate form data
        $this->form_validation->set_rules('name', 'Offer Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('advertiser_id', 'Advertiser ID', 'required|numeric|xss_clean');
        $this->form_validation->set_rules('description', 'Offer Description', 'xss_clean');
        $this->form_validation->set_rules('preview_url', 'Preview URL', 'xss_clean');
        $this->form_validation->set_rules('offer_url', 'Offer URL', 'required');
        $this->form_validation->set_rules('protocol', 'Protocol', 'xss_clean');
        $this->form_validation->set_rules('status', 'Offer Status', 'xss_clean');
        $this->form_validation->set_rules('payout_type', 'Payout Type', 'xss_clean');
        $this->form_validation->set_rules('default_payout', 'Default Payout', 'xss_clean');
        $this->form_validation->set_rules('expiration_date', 'Expiry Date', 'xss_clean');
        $this->form_validation->set_rules('ref_id', 'Ref ID', 'xss_clean');

        //form validation runs true
        if ($this->form_validation->run()) {
            $posts = $this->input->post();

            $fields = array();
            foreach ($posts as $key => $post) {
                if ($post != '' && $key != 'category_ids')
                    $fields[$key] = $post;
            }

            $params = array(
                'data' => $fields,
            );

            $result = file_get_contents(
                    'https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&Target=Offer&Method=create&NetworkToken=' . $this->config->item('network_token') . '&' . http_build_query($params));
            $records = json_decode($result);

            if ($records->response->status == '-1') {
                $data['error'] = $records->response->errors[0]->err_msg;
            } else {
                $data['success'] = 'success';
                $newOfferID = $records->response->data->Offer->id;
                if (!empty($posts['category_ids'])) {
                    $params1 = array(
                        'id' => $newOfferID,
                        'category_ids' => $posts['category_ids'],
                    );

                    $result1 = file_get_contents(
                            'https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&Target=Offer&Method=setCategories&NetworkToken=' . $this->config->item('network_token') . '&' . http_build_query($params1));
                    $records1 = json_decode($result1);
                }
            }

            $this->load->view('create_submit', $data);
        } else {
            $data['error'] = validation_errors();


            if (!$offerID)
                redirect('dash', 'refresh');
            $result = $this->offers_model->offers(array('where' => "ID = '$offerID'", 'return' => 'row'));
            if (empty($result))
                die('No such offer found');



            $offerURL = '';
            $offerURL = $result->OfferURL;
            if ($offerURL != '') {
                switch ($result->AdvertiserID) {
                    case '43':
                        $offerURL = str_replace('{your_clickid_here}', '{transaction_id}', $offerURL);
                        $offerURL = str_replace('{your_subid_here}', '{affiliate_id}', $offerURL);
                        break;
                    case '39':
                        $offerURL = $offerURL . '&subid={transaction_id}&mb_subid={affiliate_id}-{source}';
                        break;
                    case '7':
                        $offerURL = $offerURL . '&affid={affiliate_id}&subid2={transaction_id}';
                        break;
                    case '3':
                        $offerURL = $offerURL . '&pd={transaction_id}';
                        break;
                    case '193':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '550':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub5={affiliate_id}-{source}';
                        break;
                    case '57':
                        $offerURL = $offerURL . '&ref={transaction_id}&_scr={affiliate_id}-{source}';
                        break;
                    case '173':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '169':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '386':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub5={affiliate_id}-{source}';
                        break;
                    case '15':
                        $offerURL = $offerURL . '{transaction_id}&placement={affiliate_id}-{source}';
                        break;
                    case '312':
                        $offerURL = $offerURL . '?uc={transaction_id}';
                        break;
                    case '211':
                        $offerURL = $offerURL . '&subid={transaction_id}&subid2={affiliate_id}-{source}';
                        break;

                    case '73':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '410':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '412':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '294':
                        $offerURL = $offerURL . '{transaction_id}/s2/{affiliate_id}';
                        break;
                    case '19':
                        $offerURL = $offerURL . '&subid={affiliate_id}-{source}&tid2={aff_sub}&gaid={google_aid}&tid1={transaction_id}&idfa={ios_ifa}';
                        break;
                    case '440':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub5={affiliate_id}-{source}';
                        break;
                    case '408':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '209':
                        $offerURL = $offerURL . '&tags={transaction_id}';
                        $offerURL = str_replace('&tags=&', '&', $offerURL);
                        break;
                    case '444':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '446':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;

                    case '372':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '470':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '484':
                        $offerURL = $offerURL . '&s1={affiliate_id}&s3={source}&s2={transaction_id}&s4={ios_ifa}';
                        break;
                    case '474':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '472':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '376':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '568':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '564':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '574':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '510':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;

                    case '599':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '554':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '191':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '488':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '229':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '430':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '743':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}';
                        break;
                    case '384':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '655':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '23':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '350':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '155':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '259':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '661':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '486':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                    case '528':
                        $offerURL = $offerURL . '&s1={affiliate_id}-{source}&s2={transaction_id}';
                        break;
                    case '861':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '115':
                        $offerURL = $offerURL . '&sid={transaction_id}&sid2={affiliate_id}-{source}';
                        break;
                    case '1023':
                        $offerURL = $offerURL . '&s1={transaction_id}&s2={affiliate_id}&s3={source}';
                        break;
                    case '390':
                        if ($offerURL != '') {
                            $offerURL = str_replace('&subid=&subid2=&subid3=&subid4=&subid5=', $offerURL);
                            $offerURL = $offerURL . '&subid={transaction_id}&subid2={affiliate_id}-{source}';
                        }
                        break;
                    case '781':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '691':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                        break;
                    case '991':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '526':
                        $offerURL = $offerURL . '&subid={affiliate_id}-{source}';
                    case '336':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}&aff_sub3={affiliate_id}';
                        break;
                    case '699':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub5={affiliate_id}-{source}';
                        break;
                    case '699':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub5={affiliate_id}-{source}';
                        break;
                    case '927':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}';
                        break;
                    case '1147':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&source={affiliate_id}-{source}&ios_ifa={ios_ifa}&google_aid={google_aid}';
                        break;
                    case '1327':
                        $offerURL = $offerURL . '&sub_1={transaction_id}&publisher_slot={affiliate_id}-{source}';
                        break;
                    case '1361':
                        $offerURL = $offerURL . '&aff_sub1={affiliate_id}-{source}&p1={transaction_id}';
                        break;
                     case '1363':
                        $offerURL = $offerURL . '&aff_sub1={affiliate_id}-{source}&p1={transaction_id}';
                        break;
                     case '1341':
                        $offerURL = $offerURL . '&aff_sub={transaction_id}&sub_channel={affiliate_id}&gaid={google_aid}&idfa={ios_ifa}';
                        break;
                     case '1141':
                        $offerURL = $offerURL . '?sub_param1={transaction_id}&source_id={affiliate_id}-{source}';
                        break;
                    default:

                        $offerURL = $offerURL . '&aff_sub={transaction_id}&aff_sub2={affiliate_id}-{source}';
                }
                $result->OfferURL = $offerURL;
            }
            $app_icon = '';
            $this->load->helper('simple_html_dom');
            if ($result->PreviewURL != '') {
                $google = $itunes = false;
                if (strpos($result->PreviewURL, 'play.google.com') != false) {
                    $google = true;
                }
                if (strpos($result->PreviewURL, 'itunes.apple.com') != false) {
                    $itunes = true;
                }

                if ($google) {
                    $html = file_get_html($result->PreviewURL);
                    foreach ($html->find('div[class=cover-container]') as $article) {
                        $app_icon = $article->find('img[class=cover-image]', 0)->src;
                    }
                } elseif ($itunes) {
                    $html = file_get_html($result->PreviewURL);
                    foreach ($html->find('meta') as $article) {
                        if ($article->property == 'og:image') {
                            $app_icon = $article->content;
                        }
                    }
                } else {
                    $app_icon = '';
                }
            }
            $result->App_Icon = $app_icon;

            $data['offer'] = $result;

            $this->load->view('create_offer', $data);
        }
    }

}
