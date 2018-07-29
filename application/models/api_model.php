<?php

Class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function offers($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'advertiser_id'
                , 'status'
                , 'ref_id'
                , 'offer_url'
            )
            , 'page' => 1
            , 'limit' => '20000'
        );

        if (!empty($query['AdvertiserID'])) {
            $params['filters']['advertiser_id'] = $query['AdvertiserID'];
        }
        if (!empty($query['OfferID'])) {
            $params['filters']['id'] = $query['OfferID'];
        }
        if (!empty($query['Status'])) {
            $params['filters']['status'] = $query['Status'];
        }

        $result = file_get_contents(
                'https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&Target=Offer&Method=findAll&NetworkToken=' . $this->config->item('network_token') . '&' . http_build_query($params));

        $records = json_decode($result);


        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $offer['OfferID'] = $off->Offer->id;
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['AdvertiserID'] = $off->Offer->advertiser_id;
            $offer['RefID'] = $off->Offer->ref_id;
            $offer['OfferURL'] = $off->Offer->offer_url;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_compare($status = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'advertiser_id'
                , 'status'
                , 'ref_id'
                , 'preview_url'
                , 'default_payout',
                'max_payout'
            )
            , 'contain' => array('Country')
            , 'filters' => array(
                'status' => 'active'
            )
            , 'page' => 1
            , 'limit' => '10000'
        );

        if (!$status) {
            $params['filters'] = array(
                'status' => 'active'
            );
        } elseif ($status != 'all') {
            $params['filters'] = array(
                'status' => $stat
            );
        }

        $result = file_get_contents(
                'https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&Target=Offer&Method=findAll&NetworkToken=' . $this->config->item('network_token') . '&' . http_build_query($params));

        $records = json_decode($result);
        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['AdvertiserID'] = $off->Offer->advertiser_id;
            $offer['RefID'] = $off->Offer->ref_id;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['DefaultPayout'] = $off->Offer->max_payout;

            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;

            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_43() {

        $result = file_get_contents('http://api.artofclick.com/web/Api/v2.2/offer.json?api_key=' . $this->config->item('advertiser_43_key'));
        $records = json_decode($result);

        $offers = array();
        foreach ($records->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '43';
            $offer['OfferName'] = $off->name;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = $off->status;
            $offer['PreviewURL'] = $off->previewUrl;
            $offer['OfferURL'] = $off->trackingUrl;
            $offer['DefaultPayout'] = $off->payout;
            $offer['PayoutType'] = $off->payoutType;
            $offer['ConversionCap'] = $off->dailyCap;
            $offer['Incent'] = ($off->incent == '1') ? '1' : '0';
            $offer['OperatingSystem'] = strtoupper($off->os[0]);
            $offer['Country'] = implode($off->countries);
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_1079() {

        $result = file_get_contents('http://api.iwoop.com/affiliate/offer/findAll/?token=' . $this->config->item('advertiser_1079_key'));
        $records = json_decode($result);

        $offers = array();
        foreach ($records->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->ID;
            $offer['AdvertiserID'] = '1079';
            $offer['OfferName'] = $off->Name;
            $offer['OfferDescription'] = $off->Description;
            $offer['OfferStatus'] = $off->Status;
            $offer['PreviewURL'] = $off->Preview_url;
            $offer['OfferURL'] = $off->Tracking_url;
            $offer['DefaultPayout'] = $off->Payout;
            //$offer['PayoutType'] = $off->payoutType;
            $offer['ConversionCap'] = $off->Daily_cap;
            $offer['Incent'] = ($off->Type == 'Incent') ? '1' : '0';
            //$offer['OperatingSystem'] = strtoupper($off->os[0]);
            $offer['Country'] = $off->Countries;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_927() {

        $result = file_get_contents('http://api.jungletap.com/affiliate/offer/findAll/?token=' . $this->config->item('advertiser_927_key'));
        $records = json_decode($result);

        $offers = array();
        foreach ($records->offers as $off) {
            $incent = '0';
            $offer = array();
            $offer['OfferID'] = $off->ID;
            $offer['AdvertiserID'] = '927';
            $offer['OfferName'] = $off->Name;
            $offer['OfferDescription'] = $off->Description;
            $offer['OfferStatus'] = $off->Status;
            $offer['PreviewURL'] = $off->Preview_url;
            $offer['OfferURL'] = $off->Tracking_url;
            $offer['DefaultPayout'] = $off->Payout;
            $offer['ConversionCap'] = $off->Daily_cap;
            if ($off->Type == 'Incent') {
                $incent = '1';
            }
            $offer['Incent'] = $incent;
            $offer['Country'] = $off->Countries;
            $offer['ExpiryDate'] = $off->Expiration_date;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_39() {

        $result = file_get_contents('http://query.mobvista.com/channel/api.php?aff=' . $this->config->item('advertiser_39_key'));
        $records = json_decode($result);

        $offers = array();
        foreach ($records as $off) {
            $offer = array();
            $expiry_date = $part = '';
            $offer['OfferID'] = $off->campid;
            $offer['AdvertiserID'] = '39';
            $offer['OfferName'] = $off->offer_name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_link;
            $offer['OfferURL'] = $off->tracking_link;
            $offer['DefaultPayout'] = $off->price;
            $offer['PayoutType'] = $off->price_model;
            $offer['ConversionCap'] = $off->daily_cap;
            $offer['OperatingSystem'] = strtoupper($off->platform);
            $offer['Incent'] = ($off->exclude_traffic == 'incent') ? '0' : '1';
            $offer['Country'] = $off->geo;
            $expiry_date = $off->end_date;
            $part = explode(' ', $expiry_date);
            $expiry_date = $part[0];
            $offer['ExpiryDate'] = $expiry_date;
            $offer['Category'] = $off->app_category;
            $offers[$offer['OfferID']] = $offer;
        }
        return $offers;
    }

    function offers_7() {

        $result = file_get_contents('https://traffic.ad4game.com/www/admin/offers-api.php?apiKey=' . $this->config->item('advertiser_7_key') . '&zoneId=' . $this->config->item('advertiser_7_zone') . '&affiliateId=' . $this->config->item('advertiser_7_affiliate') . '&method=findAll&format=json');
        $records = json_decode($result);

        $offers = array();
        foreach ($records->Offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->OfferId;
            $offer['AdvertiserID'] = '7';
            $offer['OfferName'] = $off->OfferName;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->PreviewUrl;
            $offer['OfferURL'] = $off->TrackingUrl;
            $offer['OfferDescription'] = $off->Description;
            $offer['Thumbnail'] = $off->PreviewImage;
            $offer['ConversionCap'] = $off->DailyCap;
            $country = $payout = '';
            foreach ($off->countries as $con) {
                $country .= $con->CountryName . ',';
                $payout .= $con->CountryName . ' = ' . $con->Rate . ' ,';
            }
            $offer['DefaultPayout'] = $payout;
            $offer['Country'] = $country;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_3() {

        $device_array = array('iPhone', 'iPad', 'iPod', 'Android');

        foreach ($device_array as $device) {
            $result = file_get_contents('https://api.taptica.com/v2/bulk?token=' . $this->config->item('advertiser_3_key') . '&version=2&platforms=' . $device . '&format=xml');

            $result = new SimpleXMLElement($result);
            $result = json_encode($result);
            $records = json_decode($result);

            $offers = array();
            foreach ($records->Offer as $off) {
                $offer = array();
                $offer['OfferID'] = $off->OfferId;
                $offer['AdvertiserID'] = '3';
                $offer['OfferName'] = $off->OfferName;
                $offer['OfferDescription'] = $off->OfferDescription;
                $offer['OfferStatus'] = 'active';
                $offer['PreviewURL'] = $off->PreviewLink;
                $offer['OfferURL'] = $off->TrackingLink;
                $offer['Thumbnail'] = $off->AppIconURL;
                $offer['DefaultPayout'] = $off->Payout;
                $offer['PayoutType'] = $off->PayoutType;
                $offer['OperatingSystem'] = (is_array($off->SupportedPlatform)) ? implode(',', $off->SupportedPlatform) : $off->SupportedPlatform;
                //$offer['Country'] = (is_array($off->SupportedCountry)) ? implode(',', $off->SupportedCountry) : $off->SupportedCountry;
                $offer['Country'] = $off->SupportedCountriesV2->Country;
                $offer['Category'] = (is_array($off->Category)) ? implode(',', $off->Category) : $off->Category;
                $offers[$offer['OfferID']] = $offer;
            }
        }


        return $offers;
    }

    function offers_193() {

        $result = file_get_contents('http://sync.yeahmobi.com/sync/offer/get?api_id=' . $this->config->item('advertiser_193_id') . '&api_token=' . md5($this->config->item('advertiser_193_token')));
        $records = json_decode($result);

        $offers = array();
        foreach ($records->data->data as $key => $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $key;
            $offer['AdvertiserID'] = '193';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_url;
            $offer['OfferURL'] = $off->tracklink;
            $offer['DefaultPayout'] = $off->payout;
            $offer['OfferDescription'] = $off->offer_description;
            foreach ($off->countries as $con) {
                $country .= $con . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->category as $cat) {
                $category .= $cat . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }
        return $offers;
    }

    function offers_767() {

        $result = file_get_contents('http://dashboard.minimob.com/api/v1.1/myoffers?apikey=' . $this->config->item('advertiser_767_key'));
        $records = json_decode($result);

        echo '<pre>';
        print_r($records);
        exit;
        $offers = array();
        foreach ($records->data->data as $key => $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $key;
            $offer['AdvertiserID'] = '767';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_url;
            $offer['OfferURL'] = $off->tracklink;
            $offer['DefaultPayout'] = $off->payout;
            $offer['OfferDescription'] = $off->offer_description;
            foreach ($off->countries as $con) {
                $country .= $con . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->category as $cat) {
                $category .= $cat . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }
        return $offers;
    }

    function offers_173() {

        $result = file_get_contents('http://leadads.hasoffers.com/offers/offers.json?api_key=' . $this->config->item('advertiser_173_key') . '&limit=10000&page=1');
        $records = json_decode($result);

        $offers = array();
        foreach ($records->data->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '173';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_url;
            $offer['OfferURL'] = $off->tracking_url;
            $offer['DefaultPayout'] = $off->payout;
            $offer['PayoutType'] = $off->payout_type;
            $offer['Country'] = $off->countries_short;
            $offer['ExpiryDate'] = $off->expiration_date;
            $offer['Category'] = $off->categories;
            $offer['OfferDescription'] = $off->description;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_155() {

        $result = file_get_contents('http://bmg.hasoffers.com/offers/offers.json?api_key=' . $this->config->item('advertiser_155_key') . '&limit=10000&page=1');

        $records = json_decode($result);

        $offers = array();
        foreach ($records->data->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '155';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_url;
            $offer['OfferURL'] = $off->tracking_url;
            $offer['DefaultPayout'] = $off->payout;
            $offer['PayoutType'] = $off->payout_type;
            $offer['Country'] = $off->countries_short;
            $offer['ExpiryDate'] = $off->expiration_date;
            $offer['Category'] = $off->categories;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_57() {

        $result = file_get_contents('http://api.woobi.com/TAS/v2/offers/proxyLinks/?key=' . $this->config->item('advertiser_57_key'));
        $records = json_decode($result);
        $offers = array();

        foreach ($records->offer as $off) {
            $offer = array();
            $offer['OfferID'] = $off->cpnId;
            $offer['AdvertiserID'] = '57';
            $offer['OfferName'] = $off->supportedLanguages[0]->title;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->appDomain;
            $offer['OfferURL'] = $off->clickURL;
            $offer['DefaultPayout'] = $off->geos[0]->geoRate;
            $offer['PayoutType'] = $off->payoutType;

            $offer['Country'] = implode(',', $off->geos[0]->geoCode);
            $offer['Incent'] = ($off->incent == 'yes') ? '1' : '0';
//            $offer['OfferDescription'] = $off->description;
//            $offer['OperatingSystem'] = $off->deviceType;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_85() {
        $pages = 10;
        $offers = array();
        for ($i = 1; $i <= $pages; $i++) {
            $result = file_get_contents('http://bt.io/api/?key=' . $this->config->item('advertiser_85_key') . '&action=offers&format=json&limit=200&page=' . $i);
            $records = json_decode($result);
            $total_offers = $records->total_offers;
            $count_pages = $total_offers / 200;
            $pages = round($count_pages + 1);


            foreach ($records->offers as $off) {
                $offer = array();
                $approval = '';
                $offer['OfferID'] = $off->offer_id;
                $offer['AdvertiserID'] = '85';
                $offer['OfferName'] = $off->offer_name;
                $offer['OfferStatus'] = 'active';
                $offer['PreviewURL'] = $off->preview_link;
                $offer['OfferURL'] = $off->offer_creative_banners[0]->banner_tracking_link;
                if ($offer['OfferURL'] == 'NOT APPROVED')
                    $offer['OfferURL'] = '';
                $offer['DefaultPayout'] = $off->offer_commission;
                $offer['PayoutType'] = $off->offer_program_type;
                $offer['Country'] = $off->offer_countries_allowed;
                $offer['Category'] = $off->offer_category;
                $offer['OfferDescription'] = $off->offer_requirements;
                $offer['RequireApproval'] = ($off->offer_status == 'Approval Required / Must Apply') ? '1' : '0';
                $offers[$offer['OfferID']] = $offer;
            }
        }

        return $offers;
    }

    function offers_169($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_169_key') . '&NetworkId=' . $this->config->item('advertiser_169_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '169';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.blindferretmedia.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2043';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_967($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_967_key') . '&NetworkId=' . $this->config->item('advertiser_967_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '967';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://leadzin.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1197';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_901($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_901_key') . '&NetworkId=' . $this->config->item('advertiser_901_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '901';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.vmobads.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1255';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_492($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_492_key') . '&NetworkId=' . $this->config->item('advertiser_492_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '492';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://campaigns.pmntrack.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2196';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_851($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_851_key') . '&NetworkId=' . $this->config->item('advertiser_851_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '851';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://picajio.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1275';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_845($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_845_key') . '&NetworkId=' . $this->config->item('advertiser_845_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '845';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tapigy.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=107';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

    function offers_921($query = false) {

        $url = 'http://allitapp.api.offerslook.com/v1/offers';
        $key = $this->config->item('advertiser_921_key');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "allitapp:$key");

        $output = curl_exec($ch);

        curl_close($ch);
        $result = $output;

        $records = json_decode($result);

        echo '<pre>';
        print_r($records);
        exit;
        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '921';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tr.allitapp.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=443';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_939($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_939_key') . '&NetworkId=' . $this->config->item('advertiser_939_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '939';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://rockhotleads.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=77';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_430($query = false) {


        $result = file_get_contents('http://track.go-rilla.mobi/apiv2/?key=' . $this->config->item('advertiser_430_key') . '&action=offers&format=json');

        $records = json_decode($result);


        $offers = array();

        foreach ($records->offers as $off) {
            $offer = array();
            $incent = $tracking_link = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '430';
            $offer['OfferName'] = $off->offer_name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_link;
            $tracking_link = $off->offer_creative_banners[0]->banner_creative_url;
            $tracking_link = str_replace('banner', 'click', $tracking_link);


            $offer['OfferURL'] = $tracking_link;
            $offer['DefaultPayout'] = $off->offer_commission;
            if ($off->offer_allows_incent == 'YES') {
                $incent = '1';
            } else {
                $incent = '0';
            }
            $offer['Incent'] = $incent;
            $offer['ConversionCap'] = $off->offer_daily_cap;
            $offer['Country'] = $off->offer_countries_allowed;
            if ($off->offer_daily_cap == 'Approved/Available') {
                $offer['RequireApproval'] = '0';
            } else {
                $offer['RequireApproval'] = '1';
            }
            $offer['PayoutType'] = $off->offer_program_type;
            $offer['OfferDescription'] = $off->offer_requirements;

            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_414($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_414_key') . '&NetworkId=' . $this->config->item('advertiser_414_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '414';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.adfishmedia.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=5039';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_691($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_691_key') . '&NetworkId=' . $this->config->item('advertiser_691_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '691';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://track.sharkgames.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2476';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_779($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_779_key') . '&NetworkId=' . $this->config->item('advertiser_779_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '779';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://track.clariad.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1167';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_350($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_350_key') . '&NetworkId=' . $this->config->item('advertiser_350_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '350';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://track.adxmi.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1298';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_376($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_376_key') . '&NetworkId=' . $this->config->item('advertiser_376_id') . '&' . http_build_query($params));

        $records = json_decode($result);


        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '376';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.mobtraff.de/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1562';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_1027($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_1027_key') . '&NetworkId=' . $this->config->item('advertiser_1027_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '1027';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://cphmedia.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1217';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

    function offers_241($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_241_key') . '&NetworkId=' . $this->config->item('advertiser_241_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '241';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://click2commission.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=6';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_259($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_259_key') . '&NetworkId=' . $this->config->item('advertiser_259_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '259';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://x.prjmp.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=7385';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_59($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_59_key') . '&NetworkId=' . $this->config->item('advertiser_59_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '59';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://ads.iconpeak.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=7169';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_991($query = false) {

        $url = 'http://pimpmyclicks.api.offerslook.com/aff/v1/offers?type=all';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, 'CURLAUTH_BASIC');
        //curl_setopt($ch, CURLOPT_USERPWD, "pimpmyclicks:" . $this->config->item('advertiser_991_key'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: pimpmyclicks:" . $this->config->item('advertiser_991_key')));
        $output = curl_exec($ch);
        echo '<pre>';
        print_r($output);
        exit;
        curl_close($ch);
        $records = json_decode($result);


        $offers = array();
        foreach ($records->response->data->offers as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '991';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_url;
            $offer['OfferURL'] = $off->offer_url;
            $offer['DefaultPayout'] = $off->payout;
            $offer['OfferDescription'] = $off->description;
            foreach ($off->countries as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_486($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_486_key') . '&NetworkId=' . $this->config->item('advertiser_486_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '486';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.w2mobile.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2034';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_661($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
                , 'currency'
            ),
            'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_661_key') . '&NetworkId=' . $this->config->item('advertiser_661_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();
        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '661';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.vcommission.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=11830';
            if ($off->Offer->currency == 'USD') {
                $offer['DefaultPayout'] = $off->Offer->default_payout;
            } else {
                $offer['DefaultPayout'] = round($off->Offer->default_payout / 61, 2);
            }

            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_386($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_386_key') . '&NetworkId=' . $this->config->item('advertiser_386_id') . '&' . http_build_query($params));

        $records = json_decode($result);
        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '386';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://startapp.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=685';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }
    
    
    function offers_1195($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_1195_key') . '&NetworkId=' . $this->config->item('advertiser_1195_id') . '&' . http_build_query($params));

        $records = json_decode($result);
        $offers = array();
        

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '1195';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://whitebluemedia.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=162';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_1103($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_1103_key') . '&NetworkId=' . $this->config->item('advertiser_1103_id') . '&' . http_build_query($params));

        $records = json_decode($result);
        $offers = array();


        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '1103';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://spykemedia.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1875';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_312() {

        $total_pages = 1;

        for ($i = 1; $i <= $total_pages; $i++) {

            $result = file_get_contents('http://api.mappstreet.com/?target=offers&method=findAll&limit=1000&page=' . $i . '&token=' . $this->config->item('advertiser_312_key'));

            $records = json_decode($result);

            $offers = array();

            $total_pages = $records->respones->total_pages;

            foreach ($records->respones->data as $off) {
                $offer = array();
                $expiry_date = $part = '';
                $payout = $payout_type = $status = '';
                $offer['OfferID'] = $off->campaign_id;
                $offer['AdvertiserID'] = '312';
                $offer['OfferName'] = $off->title;
                $status = ($off->status == '1') ? 'active' : 'paused';
                $offer['OfferStatus'] = $status;
                $offer['OfferURL'] = $off->url;
                $offer['ExpiryDate'] = '';
                $offer['OfferDescription'] = "'" . $off->description . "'";
                $offer['PreviewURL'] = $off->preview_url;

                foreach ($off->payout as $key => $pay) {
                    $payout = $pay->usd_value;
                    $payout_type = $key;
                }
                $offer['DefaultPayout'] = $payout;
                $offer['PayoutType'] = $payout_type;
                $offer['OperatingSystem'] = $off->OS;
                $offer['Country'] = $off->country_iso;
                $offer['Category'] = $off->campaign_types_str;


                $offers[$offer['OfferID']] = $offer;
            }
        }



        return $offers;
    }

    function offers_961() {

        for ($i = 0; $i <= 6; $i++) {
            $start = $i * 1000;
            $result = file_get_contents('https://networkapi.geenapp.com/offers.json?apikey=' . $this->config->item('advertiser_961_key') . '&start=' . $start . '&limit=1000&myoffers=1');

            $records = json_decode($result);

            $offers = array();

            foreach ($records as $off) {
                $offer = array();
                $offer['OfferID'] = $off->offer;
                $offer['AdvertiserID'] = '961';
                $offer['OfferName'] = $off->app . ' ' . $off->idcountry;
                $offer['OfferStatus'] = 'active';
                $offer['OfferURL'] = $off->url_hasoffers;
                $offer['PreviewURL'] = $off->previewurl_androidphone;

                $offer['DefaultPayout'] = $off->cpi;
                $offer['PayoutType'] = 'CPI';
                $offer['Country'] = $off->idcountry;
                $offer['Thumbnail'] = $off->image;


                $offers[$offer['OfferID']] = $offer;
            }
        }



        return $offers;
    }

    function offers_869() {
        $username = $this->config->item('advertiser_869_id');
        $password = $this->config->item('advertiser_869_key');
        $timestamp = round(microtime(true) * 1000);
        $hash = md5($password . $timestamp);
        $result = file_get_contents("https://epomaffiliate.com/rest-api/affiliate/offers/list.do?username=" . $username . "&timestamp=" . $timestamp . "&hash=" . $hash);
        $records = json_decode($result);

        foreach ($records->offers as $off) {
            $offer = array();

            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '869';

            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';

            $offer['OfferURL'] = $off->trackingUrl;

            $offer['PreviewURL'] = $off->previewUrl;
            $offer['OfferDescription'] = $off->description;

            $offer['DefaultPayout'] = $off->pricing->price;
            $offer['PayoutType'] = $off->pricing->type;

            $offer['Country'] = implode(',', $off->targeting->geo);


            $offers[$offer['OfferID']] = $offer;
        }
        return $offers;
    }

    function offers_15() {


        $result = file_get_contents('https://www.glispainteractive.com/API/campaigns.php?token=' . $this->config->item('advertiser_15_key') . '&cid=' . $this->config->item('advertiser_15_cd'));

        $result = new SimpleXMLElement($result);
        $result = json_encode($result);
        $records = json_decode($result);
        $offers = array();

        foreach ($records->campaign as $off) {
            $offer = array();
            $payout = $url = '';
            $key = '@attributes';
            $offer['OfferID'] = $off->$key->glispaID;
            $offer['AdvertiserID'] = '15';

            $offer['OfferName'] = $off->$key->name;
            $offer['OfferStatus'] = 'active';
            $url = $off->creatives->creative->link;
            if ($url == '') {
                $url = $off->creatives->creative[0]->link;
            }
            $offer['OfferURL'] = $url;
            //$offer['OfferDescription'] = $off->summary;
            $payout = str_replace('USD', '', $off->payout);
            $payout = str_replace('EUR', '', $payout);
            $payout = str_replace('GBP', '', $payout);
            $payout = str_replace(' ', '', $payout);
            $offer['DefaultPayout'] = $payout;
            $offer['PayoutType'] = $off->acquisition;
            $offer['PayoutType'] = str_replace('(', '', $off->acquisition);
            $offer['PayoutType'] = str_replace(')', '', $offer['PayoutType']);
            $offer['Country'] = str_replace(' ', ',', $off->countries);
            $offer['Category'] = $off->category;
            $offer['OfferDescription'] = $off->summary;


            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_115() {


        $result = file_get_contents('http://www.bluetrackmedia.com/api/campaigns/?publisher_id=' . $this->config->item('advertiser_115_id') . '&apikey=' . $this->config->item('advertiser_115_key'));

        $result = new SimpleXMLElement($result);
        $result = json_encode($result);
        $records = json_decode($result);
        $offers = array();



        foreach ($records->campaign as $off) {
            $offer = array();
            $payout = $url = '';

            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '115';

            $offer['OfferName'] = $off->name;
            $offer['OfferDescription'] = $off->description;
            $offer['PayoutType'] = $off->payout_type;
            $offer['Country'] = implode(',', $off->countries->country);
            $offer['OfferStatus'] = 'active';
            $offer['Incent'] = ($off->incentive_allowed == 'true') ? '1' : '0';
            $offer['OfferURL'] = $off->campaign_url;
            $offer['PreviewURL'] = $off->preview_url;
            $offer['DefaultPayout'] = $off->rate;


            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

    function offers_229($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_229_key') . '&NetworkId=' . $this->config->item('advertiser_229_id') . '&' . http_build_query($params));

        $records = json_decode($result);
        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '229';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.positivemobile.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=3537';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_211($query = false) {
        $url = 'http://api.hangmytracking.com/api.php';

        $params = array(
            'method' => 'getOffers'
            , 'apiToken' => $this->config->item('advertiser_211_key')
            , 'apiID' => $this->config->item('advertiser_211_id')
        );

        $postData = http_build_query($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);

        curl_close($ch);
        $result = $output;

        $records = json_decode($result);

        $offers = array();

        foreach ($records->data->offers as $off) {
            $offer = array();
            $expiry_date = '';
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '211';
            $offer['OfferName'] = $off->name;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = $off->status;
            $offer['PreviewURL'] = $off->preview_URL;
            $offer['OfferURL'] = 'http://tracking.hangmytracking.com/click.php?oid=' . $off->id . '&aid=53';
            $offer['DefaultPayout'] = $off->payout_cents / 100;
            $offer['PayoutType'] = $off->pay_type;
            $offer['RequireApproval'] = $off->require_approval;
            $expiry_date = $off->expiration_date;
            $part = explode(' ', $expiry_date);
            $expiry_date = $part[0];
            $offer['ExpiryDate'] = $expiry_date;

            $offer['Country'] = $off->countries;

            $offer['Category'] = $off->categories;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_27() {
        $username = $this->config->item('advertiser_27_id');
        $password = $this->config->item('advertiser_27_key');
        $host = "https://api.adscendmedia.com/v1/publisher/" . $username . "/offers.json";

        $process = curl_init($host);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);
        curl_close($process);
        $result = $return;

        $records = json_decode($result);

        foreach ($records->offers as $off) {
            $offer = array();


            $country = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '27';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_url;
            $offer['OfferURL'] = $off->click_url;
            $offer['DefaultPayout'] = $off->payout;
            $offer['OfferDescription'] = $off->description;
            foreach ($off->countries as $con) {
                $country .= $con . ',';
            }
            $offer['Country'] = $country;

            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_103($query = false) {
        $url = 'http://api.leadzuaf.com/offersPub';

        $params = array(
            'function' => 'affOffers'
            , 'api_key' => $this->config->item('advertiser_103_key')
            , 'user_id' => $this->config->item('advertiser_103_id')
        );

        $postData = $params;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $output = curl_exec($ch);

        curl_close($ch);
        $result = $output;

        $records = json_decode($result);
        $offers = array();

        foreach ($records->answer->offers as $off) {

            $country = '';
            foreach ($off as $k => $o) {
                $offer = array();
                $offer['OfferID'] = $o->key;
                $offer['AdvertiserID'] = '103';
                $offer['OfferName'] = $o->title;
                $offer['OfferDescription'] = $o->description;
                $offer['OfferStatus'] = $o->status;
                //$offer['PreviewURL'] = $off->preview_URL;
                $offer['OfferURL'] = $o->url;
                $offer['DefaultPayout'] = $o->payout * 1.09;
                $offer['Incent'] = $o->incent;
                if ($country != '') {
                    $country .= ', ' . $k;
                } else {
                    $country = $k;
                }
                $offer['Country'] = $country;
            }
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_101($query = false) {
        $result = file_get_contents("http://api.tapgerine.net/affiliate/offer/findAll/?token=" . $this->config->item('advertiser_101_key'));

        $records = json_decode($result);

        foreach ($records->offers as $off) {
            $offer = array();
            $incent = '';
            $offer['OfferID'] = $off->ID;
            $offer['AdvertiserID'] = '101';
            $offer['OfferName'] = $off->Name;
            $offer['OfferStatus'] = $off->Status;
            $offer['PreviewURL'] = $off->Preview_url;
            $offer['OfferURL'] = $off->Tracking_url;
            $offer['Thumbnail'] = $off->Icon_url;
            $offer['DefaultPayout'] = $off->Payout;
            $offer['Category'] = $off->Tags;
            $offer['OperatingSystem'] = $off->Platforms;
            $offer['Country'] = $off->Countries;
            $offer['ExpiryDate'] = $off->Expiration_date;
            $offer['ConversionCap'] = $off->Daily_cap;
            $offer['RequireApproval'] = ($off->Approved == '0') ? '0' : '1';

            if ($off->Type == 'Incent') {
                $incent = '1';
            } else {
                $incent = '0';
            }
            $offer['Incent'] = $incent;

            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;

//		$params = array(
//		
//			'fields' => array(
//				 'id'
//				,'name'
//				,'status'
//				,'preview_url'
//				,'offer_url'
//				,'default_payout'
//				,'payout_type'
//				,'require_approval'
//				,'expiration_date'
//				,'description'
//				
//			)
//			,'contain' => array('Country','OfferCategory')
//			,'page' => 1
//			,'limit' => '10000'
//		);
//		
//		
//		$result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key='.$this->config->item('advertiser_101_key').'&NetworkId='.$this->config->item('advertiser_101_id').'&'. http_build_query( $params ));
//		
//		$records = json_decode( $result );
//		
//		$offers = array();
//		
//		foreach($records->response->data->data as $off){
//			$offer = array();
//			$country = $category=  '';
//			$offer['OfferID'] = $off->Offer->id;
//			$offer['AdvertiserID'] = '101';
//			$offer['OfferName'] = $off->Offer->name;
//			$offer['OfferStatus'] = $off->Offer->status;
//			$offer['PreviewURL'] = $off->Offer->preview_url;
//			$offer['OfferURL'] = 'http://tracking.tapge.com/aff_c?offer_id='.$off->Offer->id.'&aff_id=2625';
//			$offer['DefaultPayout'] = $off->Offer->default_payout;
//			$offer['PayoutType'] = $off->Offer->payout_type;
//			$offer['RequireApproval'] = $off->Offer->require_approval;
//			$offer['ExpiryDate'] = $off->Offer->expiration_date;
//			$offer['OfferDescription'] = $off->Offer->description;
//			foreach($off->Country as $con){
//				$country .= $con->code.',';	
//			}
//			$offer['Country'] = $country;
//			foreach($off->OfferCategory as $cat){
//				$category .= $cat->name.',';	
//			}
//			$offer['Category'] = $category;
//			$offers[$offer['OfferID']] = $offer; 
//		}
//		
//		return $offers;	
    }

    function offers_191($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_191_key') . '&NetworkId=' . $this->config->item('advertiser_191_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '191';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://upps.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1328';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_488($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_488_key') . '&NetworkId=' . $this->config->item('advertiser_488_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '488';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://track.comboapp.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1230';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_73($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_73_key') . '&NetworkId=' . $this->config->item('advertiser_73_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '73';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tm.trackmobi.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=42837';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_410($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_410_key') . '&NetworkId=' . $this->config->item('advertiser_410_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '410';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.adatha.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1162';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_412($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_412_key') . '&NetworkId=' . $this->config->item('advertiser_412_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '412';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://jump.gooffers.net/aff_c?offer_id=' . $off->Offer->id . '&aff_id=19722';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_440($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_440_key') . '&NetworkId=' . $this->config->item('advertiser_440_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '440';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://wadogo.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1374';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_655($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_655_key') . '&NetworkId=' . $this->config->item('advertiser_655_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '655';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://performance.affiliaxe.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=62219';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_444($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_444_key') . '&NetworkId=' . $this->config->item('advertiser_444_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '444';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://plus.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1132';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_446($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_446_key') . '&NetworkId=' . $this->config->item('advertiser_446_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '446';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.toroadvertising.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=3520';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_408($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_408_key') . '&NetworkId=' . $this->config->item('advertiser_408_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '408';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://track.12trackway.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2178';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_470($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_470_key') . '&NetworkId=' . $this->config->item('advertiser_470_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '470';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.adattract.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2018';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_484($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_484_key') . '&NetworkId=' . $this->config->item('advertiser_484_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '484';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://trackmobave.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1402';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_474($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_474_key') . '&NetworkId=' . $this->config->item('advertiser_474_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '474';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://judoads.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1106';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_472($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_472_key') . '&NetworkId=' . $this->config->item('advertiser_472_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '472';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.pushpullads.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1038';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_372($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_372_key') . '&NetworkId=' . $this->config->item('advertiser_372_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '372';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.adzonesocial.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1296';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_568($query = false) {

        $result = file_get_contents('http://dispply.com/publishers/api/v1/campaigns?access_token=' . $this->config->item('advertiser_568_key'));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->data as $off) {
            $offer = array();
            $country = $category = '';
            $require_approval = '0';
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '568';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = $off->status;
            //$offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = $off->tracking_link;
            $offer['DefaultPayout'] = $off->payout;
            if ($off->require_approval == '1')
                $require_approval = '1';
            $offer['RequireApproval'] = $require_approval;
            $offer['OfferDescription'] = $off->description;
            $offer['Country'] = implode(',', $off->geo);
            $offer['OperatingSystem'] = $off->platform;
            $offer['Thumbnail'] = $off->app->icon;
            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

    function offers_564($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_564_key') . '&NetworkId=' . $this->config->item('advertiser_564_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '564';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.taigamobile.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1504';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_574($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_574_key') . '&NetworkId=' . $this->config->item('advertiser_574_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '574';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tapaudience.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1000';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_781() {
//
//		$result =file_get_contents('https://api.mobilecore.com:8080/v1/getAds?siteid=29167&token=64dca92689a84a9549aec67df6a8f5f2&country=US');	
//		$records = json_decode( $result );
        $ch = curl_init();
        $url = 'https://api.mobilecore.com:8080/v1/getAds?siteid=29167&token=64dca92689a84a9549aec67df6a8f5f2&country=IN';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $output = curl_exec($ch);

        curl_close($ch);
        $result = $output;
        $records = json_decode($result);
        echo '<pre>';
        print_r($records);
        exit;
    }

    function offers_554($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_554_key') . '&NetworkId=' . $this->config->item('advertiser_554_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '554';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://blackfox.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=5112';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_510() {


        $result = file_get_contents('http://partners.mobligo.com/api/api_offers/get_all?aff_id=' . $this->config->item('advertiser_510_id') . '&api_key=' . $this->config->item('advertiser_510_key'));

        $records = json_decode($result);
        $offers = array();


        foreach ($records->data->Offers as $off) {
            $offer = array();
            $preview = '';
            $country = '';
            $rates = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '510';

            $offer['OfferName'] = $off->name;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = 'active';
            $offer['Incent'] = ($off->allow_incentivized_traffic == '1') ? '1' : '0';

            $offer['PayoutType'] = $off->engagement_type;
            $offer['OfferURL'] = $off->tracking_code;
            $offer['Thumbnail'] = $off->image;


            $offer['OfferStatus'] = 'active';

            $preview = is_array($off->preview_links) ? '' : (array) $off->preview_links;
            if (!empty($preview)) {
                $preview = array_values($preview);
                $preview = $preview[0];
            }
            $offer['PreviewURL'] = $preview;

            $countries = (array) $off->rates;
            if (!empty($countries)) {
                $country = array_keys($countries);
                $country = implode(', ', $country);

                $rates = array_values($countries);
                $rates[0] = (array) $rates[0];
                $rates = array_values($rates[0]);
                $rates = $rates[0];
            }


            $offer['Country'] = $country;


            $offer['DefaultPayout'] = $rates;


            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_599($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_599_key') . '&NetworkId=' . $this->config->item('advertiser_599_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '599';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.ybrantmobile.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1660';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_384($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_384_key') . '&NetworkId=' . $this->config->item('advertiser_384_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '384';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.surikate.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1114';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_23($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_23_key') . '&NetworkId=' . $this->config->item('advertiser_23_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '23';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://www.pxlvlt2.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2328';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_743($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_743_key') . '&NetworkId=' . $this->config->item('advertiser_743_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '743';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://mobaloo.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=2498';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_550() {

        $result = file_get_contents('http://leadhug.hasoffers.com/offers/offers.json?api_key=' . $this->config->item('advertiser_550_key') . '&limit=10000&page=1');


        $records = json_decode($result);

        $offers = array();
        foreach ($records->data->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '550';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_url;
            $offer['OfferURL'] = $off->tracking_url;
            $offer['DefaultPayout'] = $off->payout;
            $offer['PayoutType'] = $off->payout_type;
            $offer['Country'] = $off->countries_short;
            $offer['ExpiryDate'] = $off->expiration_date;
            $offer['Category'] = $off->categories;
            $offer['OfferDescription'] = $off->Offer->description;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_390($query = false) {


        $result = file_get_contents('http://login.aptitude-media.com/api/?key=' . $this->config->item('advertiser_390_key') . '&action=offers&format=json');

        $records = json_decode($result);

        $offers = array();

        foreach ($records->offers as $off) {
            $offer = array();
            $incent = $tracking_link = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '390';
            $offer['OfferName'] = $off->offer_name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_link;
            if ($off->offer_creative_banners[0]->banner_tracking_link != 'NOT APPROVED') {
                $tracking_link = $off->offer_creative_banners[0]->banner_tracking_link;
            } else {
                $tracking_link = '';
            }
            $offer['OfferURL'] = $tracking_link;
            $offer['DefaultPayout'] = $off->offer_commission;
            if ($off->offer_allows_incent == 'YES') {
                $incent = '1';
            } else {
                $incent = '0';
            }
            $offer['Incent'] = $incent;
            $offer['ConversionCap'] = $off->offer_daily_cap;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_1023($query = false) {


        $result = file_get_contents('http://sabiamedia.afftrack.com/api/?key=' . $this->config->item('advertiser_1023_key') . '&action=offers&format=json');

        $records = json_decode($result);


        $offers = array();

        foreach ($records->offers as $off) {
            $offer = array();
            $incent = $tracking_link = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '1023';
            $offer['OfferName'] = $off->offer_name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_link;
            $tracking_link = $off->offer_creative_banners[0]->banner_creative_url;
            $tracking_link = str_replace('banner', 'click', $tracking_link);


            $offer['OfferURL'] = $tracking_link;
            $offer['DefaultPayout'] = $off->offer_commission;
            if ($off->offer_allows_incent == 'YES') {
                $incent = '1';
            } else {
                $incent = '0';
            }
            $offer['Incent'] = $incent;
            $offer['ConversionCap'] = $off->offer_daily_cap;
            $offer['Country'] = $off->offer_countries_allowed;
            if ($off->offer_daily_cap == 'Approved/Available') {
                $offer['RequireApproval'] = '0';
            } else {
                $offer['RequireApproval'] = '1';
            }
            $offer['PayoutType'] = $off->offer_program_type;
            $offer['OfferDescription'] = $off->offer_requirements;

            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_261($query = false) {

        $result = file_get_contents('http://mymonetise.co.uk/affiliates/api/4/offers.asmx/OfferFeed?api_key=' . $this->config->item('advertiser_261_key') . '&affiliate_id=' . $this->config->item('advertiser_261_id') . '&campaign_name=&media_type_category_id=0&vertical_category_id=0&vertical_id=0&offer_status_id=0&tag_id=0&start_at_row=1&row_limit=0');

        $result = new SimpleXMLElement($result);
        $result = json_encode($result);
        $records = json_decode($result);

        $offers = array();

        foreach ($records->offers->offer as $off) {
            $offer = array();
            $country = $category = $statu = $tags = $payout = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '261';
            $offer['OfferName'] = $off->offer_name;
            $offer['OfferStatus'] = 'active';
            //$offer['PreviewURL'] = $off->preview_link == '' ? '' : $off->preview_link;
            //$offer['OfferURL'] = 'http://tm.trackmobi.com/aff_c?offer_id='.$off->Offer->id.'&aff_id=42837';
            $payout = $off->payout;
            $payout = str_replace('Ã‚Â£', '', $payout);
            $payout = str_replace('Â£', '', $payout);
            $offer['DefaultPayout'] = $payout * 1.50;
            $offer['PayoutType'] = $off->price_format;
            $statu = $off->offer_status->status_id;
            if ($statu == '1' || $statu == '2') {
                $offer['RequireApproval'] = '0';
            } else {
                $offer['RequireApproval'] = '1';
            }
            //$offer['ExpiryDate'] = $off->Offer->expiration_date;
            foreach ($off->allowed_countries->country as $con) {
                $country .= $con->country_code;
                if ($country != '')
                    $country = $country . ', ';
            }
            $offer['Country'] = $country;

            $offer['Category'] = $off->vertical_name;

            foreach ($off->tags->tag as $tag) {
                $tags .= $tag->tag_name;
                if ($tags != '')
                    $tags = $tags . ', ';
            }
            $offer['OperatingSystem'] = $tags;

            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

    function offers_203($query = false) {

        $result = file_get_contents('http://clickwork7network.com/affiliates/api/4/offers.asmx/OfferFeed?api_key=' . $this->config->item('advertiser_203_key') . '&affiliate_id=' . $this->config->item('advertiser_203_id') . '&campaign_name=&media_type_category_id=0&vertical_category_id=0&vertical_id=0&offer_status_id=0&tag_id=0&start_at_row=1&row_limit=0');

        $result = new SimpleXMLElement($result);
        $result = json_encode($result);
        $records = json_decode($result);


        $offers = array();

        foreach ($records->offers->offer as $off) {
            $offer = array();
            $country = $category = $statu = $tags = $payout = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '203';
            $offer['OfferName'] = $off->offer_name;
            $offer['OfferStatus'] = 'active';
            $payout = $off->payout;
            $payout = str_replace('$', '', $payout);
            $offer['DefaultPayout'] = $payout;
            $offer['PayoutType'] = $off->price_format;
            $statu = $off->offer_status->status_id;
            if ($statu == '1' || $statu == '2') {
                $offer['RequireApproval'] = '0';
            } else {
                $offer['RequireApproval'] = '1';
            }
            //$offer['ExpiryDate'] = $off->Offer->expiration_date;
            foreach ($off->allowed_countries->country as $con) {
                $country .= $con->country_code;
                if ($country != '')
                    $country = $country . ', ';
            }
            $offer['Country'] = $country;

            $offer['Category'] = $off->vertical_name;

            foreach ($off->tags->tag as $tag) {
                $tags .= $tag->tag_name;
                if ($tags != '')
                    $tags = $tags . ', ';
            }
            $offer['OperatingSystem'] = $tags;

            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

    function offers_31($query = false) {

        $result = file_get_contents('https://login.cpadna.com/affiliates/api/4/offers.asmx/OfferFeed?api_key=' . $this->config->item('advertiser_31_key') . '&affiliate_id=' . $this->config->item('advertiser_31_id') . '&campaign_name=&media_type_category_id=0&vertical_category_id=0&vertical_id=0&offer_status_id=0&tag_id=0&start_at_row=1&row_limit=0');

        //$result =  file_get_contents('https://login.cpadna.com/affiliates/api/2/offers.asmx/CreativeFeed?api_key='.$this->config->item('advertiser_31_key').'&affiliate_id='.$this->config->item('advertiser_31_id').'&export_feed_id=0&updates_since=0'); 
        /* echo 'https://login.cpadna.com/affiliates/api/2/offers.asmx/CreativeFeed?api_key='.$this->config->item('advertiser_31_key').'&affiliate_id='.$this->config->item('advertiser_31_id').'&export_feed_id=0&updates_since=0';
          exit; */
        $result = new SimpleXMLElement($result);
        $result = json_encode($result);
        $records = json_decode($result);

        $offers = array();

        foreach ($records->offers->offer as $off) {
            $offer = array();
            $country = $category = $statu = $tags = $payout = '';
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '31';
            $offer['OfferName'] = $off->offer_name;
            $offer['OfferStatus'] = 'active';
            //$offer['PreviewURL'] = $off->preview_link == '' ? '' : $off->preview_link;
            //$offer['OfferURL'] = 'http://tm.trackmobi.com/aff_c?offer_id='.$off->Offer->id.'&aff_id=42837';
            $payout = $off->payout;
            $payout = str_replace('Ã‚Â£', '', $payout);
            $payout = str_replace('Â£', '', $payout);
            $payout = str_replace('$', '', $payout);
            $offer['DefaultPayout'] = $payout;
            $offer['PayoutType'] = $off->price_format;
            $statu = $off->offer_status->status_id;

            if ($statu == '1' || $statu == '2') {
                $offer['RequireApproval'] = '0';
            } else {
                $offer['RequireApproval'] = '1';
            }
            //$offer['ExpiryDate'] = $off->Offer->expiration_date;
            foreach ($off->allowed_countries->country as $con) {
                $country .= $con->country_code;
                if ($country != '')
                    $country = $country . ', ';
            }
            $offer['Country'] = $country;

            $offer['Category'] = $off->vertical_name;
            foreach ($off->tags->tag as $tag) {
                $tags .= $tag->tag_name;
                if ($tags != '')
                    $tags = $tags . ', ';
            }
            $offer['OperatingSystem'] = $tags;

            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

    function offers_294() {


        $result = file_get_contents('http://www.pointclicktrack.com/web-services/campaigns/format/xml/username/' . $this->config->item('advertiser_294_email') . '/key/' . $this->config->item('advertiser_294_key'));


        $result = new SimpleXMLElement($result);
        $result = json_encode($result);
        $records = json_decode($result);

        $offers = array();

        foreach ($records->campaign as $off) {
            $offer = array();
            $incent = $category = $cat = '';
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '294';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            if ($off->incentivized == 'yes') {
                $incent = '1';
            } else {
                $incent = '0';
            }
            $offer['Incent'] = $incent;
            $offer['OfferURL'] = $off->creatives->publisher_url[0];
            $offer['ExpiryDate'] = '';
            $offer['OfferDescription'] = $off->description;
            $offer['DefaultPayout'] = $off->commission;
            $category = $off->categories->category;
            if (is_array($category)) {
                foreach ($off->categories->category as $cat) {
                    $category .= $cat . ',';
                }
            }
            $offer['Category'] = $category;


            $offers[$offer['OfferID']] = $offer;
        }



        return $offers;
    }

    function offers_19($query = false) {
        $source = array('63628', '105234');
        $offers = array();
        foreach ($source as $so) {
            $result = file_get_contents('http://dashboard.mobpartner.com/en/admin/service/' . $so . '/feed?key=' . $this->config->item('advertiser_19_key') . '&output=json&displayPayout=1&displayIncent=1');

            $records = json_decode($result);



            foreach ($records->service->campaigns->campaign as $off) {
                $offer = array();
                $offer_url = $payout = $country = '';
                $expiry_date = $part = '';
                $offer['OfferID'] = $off->id;
                $offer['AdvertiserID'] = '19';
                $offer['OfferName'] = $off->name;
                $offer['OfferStatus'] = 'active';
                $offer['PreviewURL'] = $off->click;
                $part = explode('&source=', $off->click);
                $offer['OfferURL'] = $part[0];
                foreach ($off->actions->action[0]->targets->target as $tar) {
                    $payout .= $tar->country . ' = ' . $tar->payout->value . ', ';
                    $country .= $tar->country . ', ';
                }
                $offer['DefaultPayout'] = $payout;
                $offer['PayoutType'] = $off->actions->action[0]->type;


                $offer['Country'] = $country;
                $offers[$offer['OfferID']] = $offer;
            }
        }

        return $offers;
    }

    function offers_209($query = false) {

        $result = file_get_contents('https://api.clicksmob.com/api/v2/services/offers.json?&utoken=' . $this->config->item('advertiser_209_token') . '&uid=2291');
        $records = json_decode($result);


        foreach ($records->offer as $off) {
            $offer = array();
            $cat = $category = $country = $platform = '';
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '209';
            $offer['OfferName'] = $off->offerName;
            $offer['OfferStatus'] = 'active';
            //$offer['PreviewURL'] = $off->targetURL;
            $offer['OfferURL'] = $off->targetURL;

            foreach ($off->categories->category as $cat) {
                $category .= $cat . ', ';
            }
            $offer['Category'] = $category;
            $offer['Incent'] = $off->incentiveAllowed;
            $offer['RequireApproval'] = $off->approvalRequired;
            $offer['OfferDescription'] = $off->description;
            $offer['DefaultPayout'] = $off->offerPayouts->offerPayout[0]->payout;

            foreach ($off->offerPayouts->offerPayout[0]->countries->country as $coun) {
                $country .= $coun . ', ';
            }


            $offer['Country'] = $country;

            foreach ($off->offerPayouts->offerPayout[0]->platforms->platform as $plat) {
                $platform .= $plat . ', ';
            }

            $offer['OperatingSystem'] = strtoupper($platform);
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function update_status($query = false) {
        $result = file_get_contents(
                'https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&Target=Offer&Method=updateField&NetworkToken=' . $this->config->item('network_token') . '&id=' . $query . '&field=status&value=paused');

        $records = json_decode($result);
    }

    function get_working_affiliates($offers = false) {
        $start_date = date('Y-m-d', strtotime('-1 month'));
        $end_date = date('Y-m-d');
        $params = array(
            'fields' => array(
                'Stat.affiliate_id'
            )
            , 'groups' => array(
                'Stat.affiliate_id',
                'Stat.offer_id',
            )
            , 'filters' => array(
                'Stat.date' => array(
                    'conditional' => 'BETWEEN'
                    , 'values' => array(
                        $start_date
                        , $end_date
                    )
                ),
                'Stat.offer_id' => array(
                    'conditional' => 'EQUAL_TO'
                    , 'values' => $offers
                )
            )
            , 'limit' => 4000
            , 'page' => 1
            , 'totals' => true
        );

        $result = file_get_contents('https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&NetworkToken=' . $this->config->item('network_token') . '&Target=Report&Method=getStats&' . http_build_query($params));

        $records = json_decode($result);

        $new = array();
        foreach ($records->response->data->data as $data) {
            $row = array();
            $row['AffiliateID'] = $data->Stat->affiliate_id;
            $row['OfferID'] = $data->Stat->offer_id;
            $new[$row['OfferID']][$row['AffiliateID']] = $row['AffiliateID'];
        }
        return $new;
    }

    function get_working_affiliates_temp($offers = false) {
        $start_date = date('Y-m-d', strtotime('-10 days'));
        $end_date = date('Y-m-d');
        $params = array(
            'fields' => array(
                'Stat.affiliate_id'
            )
            , 'groups' => array(
                'Stat.affiliate_id',
                'Stat.offer_id',
            )
            , 'filters' => array(
                'Stat.date' => array(
                    'conditional' => 'BETWEEN'
                    , 'values' => array(
                        $start_date
                        , $end_date
                    )
                ),
                'Stat.offer_id' => array(
                    'conditional' => 'EQUAL_TO'
                    , 'values' => $offers
                )
            )
            , 'limit' => 4000
            , 'page' => 1
            , 'totals' => true
        );

        $result = file_get_contents('https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&NetworkToken=' . $this->config->item('network_token') . '&Target=Report&Method=getStats&' . http_build_query($params));

        $records = json_decode($result);

        $new = array();
        foreach ($records->response->data->data as $data) {
            $row = array();
            $row['AffiliateID'] = $data->Stat->affiliate_id;
            $row['OfferID'] = $data->Stat->offer_id;
            $new[$row['OfferID']][$row['AffiliateID']] = $row['AffiliateID'];
        }
        return $new;
    }

    function affiliates($query = false) {
        $params = array(
            'fields' => array(
                'affiliate_id'
                , 'email'
            )
            , 'filters' => array(
                array('status' => 'pending')
                , array('status' => 'active')
            )
            , 'limit' => 4000
            , 'page' => 1
        );
        $new = array();
        $result = file_get_contents('https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&NetworkToken=' . $this->config->item('network_token') . '&Target=AffiliateUser&Method=findAll&' . http_build_query($params));

        $records = json_decode($result);

        foreach ($records->response->data->data as $data) {
            $row = array();
            $row['AffiliateID'] = $data->AffiliateUser->affiliate_id;
            $row['AffiliateEmail'] = $data->AffiliateUser->email;

            $new[$row['AffiliateID']] = $row['AffiliateEmail'];
        }

        return $new;
    }

    function change_status($offerID = false) {
        file_get_contents('https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&Target=Offer&Method=updateField&NetworkToken=' . $this->config->item('network_token') . '&id=' . $offerID . '&field=status&value=active');
    }

    function advertisers($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'company'
            ),
            'contain' => array(
                'AccountManager'
            )
            , 'limit' => 4000
            , 'page' => 1
        );
        $result = file_get_contents('https://api.hasoffers.com/Apiv3/json?NetworkId=' . $this->config->item('network_id') . '&NetworkToken=' . $this->config->item('network_token') . '&Target=Advertiser&Method=findAll&' . http_build_query($params));

        $records = json_decode($result);


        $adv = array();
        foreach ($records->response->data->data as $data) {
            $row = array();
            $row['id'] = $data->Advertiser->id;
            $row['company'] = $data->Advertiser->company;
            $row['manager'] = $data->AccountManager->first_name . ' ' . $data->AccountManager->last_name;
            $adv[$row['id']] = $row;
        }



        return $adv;
    }

//added by sahil
    function offers_562($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_562_key') . '&NetworkId=' . $this->config->item('advertiser_562_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '562';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://la.luxeads.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1278';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

//added by sahil
    function offers_817($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_817_key') . '&NetworkId=' . $this->config->item('advertiser_817_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '817';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.appxem.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1234';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    //added by sahil
    function offers_175($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_175_key') . '&NetworkId=' . $this->config->item('advertiser_175_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '175';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://wmadv.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1819';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    //added by sahil
    function offers_528($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_528_key') . '&NetworkId=' . $this->config->item('advertiser_528_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '528';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://blackfox.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1804';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    //added by sahil
    function offers_699($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_699_key') . '&NetworkId=' . $this->config->item('advertiser_699_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '699';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://hasoffers.mobisummer.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1130';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_728() {


        $result = file_get_contents('http://api.apprevolve.com/v2/getAds?siteid=' . $this->config->item('advertiser_728_id') . '&token=' . $this->config->item('advertiser_728_key'));

        //$result = new SimpleXMLElement($result);
        $records = json_decode($result);

        $offers = array();

        foreach ($records->ads as $off) {
            $offer = array();
            $payout = $url = '';

            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '728';

            $offer['OfferName'] = $off->title;
            $offer['OfferStatus'] = 'active';
            $offer['OfferURL'] = $off->clickURL;

            $offer['DefaultPayout'] = $off->bid;
            $offer['OperatingSystem'] = $off->platform;
            $offer['Country'] = implode(',', $off->geoTargeting);
            $offer['Category'] = $off->category;
            $offer['OfferDescription'] = $off->description;


            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_526() {


        $result = file_get_contents('http://www.mybestclick.mobi/api.php?method=getOffers&aid=' . $this->config->item('advertiser_526_id') . '&key=' . $this->config->item('advertiser_526_key') . '&require=true');

        //$result = new SimpleXMLElement($result);
        $records = json_decode($result);
        $offers = array();


        foreach ($records->offers as $off) {
            $offer = array();

            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '526';

            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview;
            $offer['OfferURL'] = $off->tracking_link;

            $offer['DefaultPayout'] = $off->payout;
            $offer['ExpiryDate'] = $off->expire;
            $offer['ConversionCap'] = $off->caps;
            $offer['OperatingSystem'] = implode(',', $off->devices);
            $offer['Country'] = implode(',', $off->geo);


            $offers[$offer['OfferID']] = $offer;
        }



        return $offers;
    }

    function offers_775() {

        $result = file_get_contents('https://api.crunchiemedia.com/api/index.cfm?token=' . $this->config->item('advertiser_775_key') . '&format=json');

        //$result = new SimpleXMLElement($result);
        $records = json_decode($result);

        $offers = array();



        foreach ($records->Offer as $off) {
            $offer = array();

            $offer['OfferID'] = $off->ID;
            $offer['AdvertiserID'] = '775';

            $offer['OfferName'] = $off->OfferName;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->PreviewLink;
            $offer['OfferURL'] = $off->TrackingLink;

            $offer['DefaultPayout'] = $off->Payout;
            $offer['Thumbnail'] = $off->AppIconURL;
            $offer['PayoutType'] = $off->PayoutType;
            $offer['Incent'] = ($off->IncentAllowed == '1') ? '1' : '0';

            $offer['OperatingSystem'] = implode(',', $off->SupportedPlatforms);
            $offer['Country'] = implode(',', $off->SupportedCountries);


            $offers[$offer['OfferID']] = $offer;
        }




        return $offers;
    }

    function offers_949() {

        $result = file_get_contents('http://api.rulead.com/affiliate/offer/findAll/?token=' . $this->config->item('advertiser_949_key'));

        $records = json_decode($result);

        $offers = array();



        foreach ($records->offers as $off) {
            $offer = array();

            $offer['OfferID'] = $off->ID;
            $offer['AdvertiserID'] = '949';

            $offer['OfferName'] = $off->Name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->Preview_url;
            $offer['OfferURL'] = $off->Tracking_url;
            $offer['DefaultPayout'] = $off->Payout;
            $offer['Thumbnail'] = $off->Icon_url;
            $offer['Incent'] = ($off->Type == 'Incent') ? '1' : '0';

            $offer['OperatingSystem'] = $off->Platforms;
            $offer['Country'] = $off->Countries;
            $offer['ExpiryDate'] = $off->Expiration_date;
            $offer['ConversionCap'] = $off->Monthly_cap;
            $offer['RequireApproval'] = ($off->Approved == '0') ? '1' : '0';
            $offer['Category'] = $off->Tags;
            $offer['OfferDescription'] = $off->Description;


            $offers[$offer['OfferID']] = $offer;
        }



        return $offers;
    }

    //added by sahil
    function offers_861($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_861_key') . '&NetworkId=' . $this->config->item('advertiser_861_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '861';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://track.hitcell.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=9';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }
    
//added by sahil
    function offers_1399() {

        $result = file_get_contents('http://admin.tabatoo.com/offers/list?appid='.$this->config->item('advertiser_1399_appid').'&n=10&t=json&all=1&incent=0');
        $records = json_decode($result);

        $offers = array();
        foreach ($records->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->externalOfferId;
            $offer['AdvertiserID'] = '1399';
            $offer['OfferName'] = $off->name;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->creatives_link;
            $offer['OfferURL'] = $off->shortenURL;
            $offer['Thumbnail'] = $off->iconLink;
            $offer['DefaultPayout'] = $off->bid;
            $unlimitedDailyBudget = 1;
            if ($off->unlimitedDailyBudget) {
                $unlimitedDailyBudget = '& unlimitedDailyBudget : ' . $off->unlimitedDailyBudget;
            } else {
                $unlimitedDailyBudget = '& dailyBudget : ' . $off->dailyBudget;
            }

            $offer['ConversionCap'] = 'daily_capping : ' . $off->daily_capping . $unlimitedDailyBudget;
            $PayoutType = '';
            if ($off->offerType == 0) {
                $PayoutType = 'CPI';
            }
            $offer['PayoutType'] = $PayoutType;
            $offer['Incent'] = 0;
            $offer['Category'] = $off->category;
            $offer['Country'] = $off->geo;
            $platform = 'Android';
            if ($off->platform == '0') {
                $platform = 'Android';
            }
            if ($off->platform == '1') {
                $platform = 'iPhone';
            }
            if ($off->platform == '2') {
                $platform = 'iPad';
            }
            if ($off->platform == '3') {
                $platform = 'Mobileweb';
            }
            if ($off->platform == '4') {
                $platform = 'iPod';
            }
            if ($off->platform == '5') {
                $platform = 'Windows Phone';
            }
            if ($off->platform == '9') {
                $platform = 'Others';
            }
            if ($off->platform == '12') {
                $platform = 'iPhone / iPad';
            }

            $offer['OperatingSystem'] = $platform;

            $offers[$offer['OfferID']] = $offer;
        }
        return $offers;
    }

//added by sahil
    function offers_1463() {

        $result = file_get_contents('http://admin.tabatoo.com/offers/list?appid='.$this->config->item('advertiser_1463_appid').'&n=10&t=json&all=1&incent=1');
        $records = json_decode($result);

        $offers = array();
        foreach ($records->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->externalOfferId;
            $offer['AdvertiserID'] = '1463';
            $offer['OfferName'] = $off->name;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->creatives_link;
            $offer['OfferURL'] = $off->shortenURL;
            $offer['Thumbnail'] = $off->iconLink;
            $offer['DefaultPayout'] = $off->bid;
            $unlimitedDailyBudget = 1;
            if ($off->unlimitedDailyBudget) {
                $unlimitedDailyBudget = '& unlimitedDailyBudget : ' . $off->unlimitedDailyBudget;
            } else {
                $unlimitedDailyBudget = '& dailyBudget : ' . $off->dailyBudget;
            }

            $offer['ConversionCap'] = 'daily_capping : ' . $off->daily_capping . $unlimitedDailyBudget;
            $PayoutType = '';
            if ($off->offerType == 0) {
                $PayoutType = 'CPI';
            }
            $offer['PayoutType'] = $PayoutType;
            $offer['Incent'] = 1;
            $offer['Category'] = $off->category;
            $offer['Country'] = $off->geo;
            $platform = 'Android';
            if ($off->platform == '0') {
                $platform = 'Android';
            }
            if ($off->platform == '1') {
                $platform = 'iPhone';
            }
            if ($off->platform == '2') {
                $platform = 'iPad';
            }
            if ($off->platform == '3') {
                $platform = 'Mobileweb';
            }
            if ($off->platform == '4') {
                $platform = 'iPod';
            }
            if ($off->platform == '5') {
                $platform = 'Windows Phone';
            }
            if ($off->platform == '9') {
                $platform = 'Others';
            }
            if ($off->platform == '12') {
                $platform = 'iPhone / iPad';
            }

            $offer['OperatingSystem'] = $platform;

            $offers[$offer['OfferID']] = $offer;
        }
        return $offers;
    }
    
    function offers_51($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_51_key') . '&NetworkId=' . $this->config->item('advertiser_51_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '51';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://tracking.performancerevenues.com/aff_c?offer_id=' . $off->Offer->id . '&aff_id=7262';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }
    
    
    function offers_643($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_643_key') . '&NetworkId=' . $this->config->item('advertiser_643_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '643';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://airpush.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=1062';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

    function offers_975($query = false) {

        $result = file_get_contents('http://api.offerseven.com/affiliate/offer/findAll/?token=' . $this->config->item('advertiser_975_key'));

        $records = json_decode($result);



        $offers = array();

        foreach ($records->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->ID;
            $offer['AdvertiserID'] = '975';
            $offer['OfferName'] = $off->Name;
            $offer['OfferStatus'] = $off->Status;
            $offer['PreviewURL'] = $off->Preview_url;
            $offer['OfferURL'] = $off->Tracking_url;
            $offer['DefaultPayout'] = $off->Payout;
            if ($off->Approved == '1')
                $approval = '0';
            else
                $approval = '1';
            $offer['RequireApproval'] = $approval;
            $offer['ExpiryDate'] = $off->Expiration_date;
            $offer['OfferDescription'] = $off->Description;
            $offer['Thumbnail'] = $off->Icon_url;
            $offer['Category'] = $off->Tags;
            $offer['Country'] = $off->Countries;
            $offer['ConversionCap'] = $off->Daily_cap;
            if ($off->Type == 'Incent')
                $incent = '1';
            else
                $incent = '0';
            $offer['Incent'] = $incent;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }
    
    
    function offers_1255($query = false) {

        $result = file_get_contents('http://appwalls.mobi/api/v1?pub_id=256&api_key='.$this->config->item('advertiser_1255_key').'&incent=3&os=Android&country=ALL&c=507');

        $records = json_decode($result);
        
        $offers = array();

        foreach ($records->ads as $off) {
            $offer = array();
            $offer['OfferID'] = $off->offerid;
            $offer['AdvertiserID'] = '1255';
            $offer['OfferName'] = $off->title;
            $offer['OfferStatus'] = 'active';
            //$offer['PreviewURL'] = $off->Preview_url;
            $offer['OfferURL'] = $off->clickurl;
            $offer['DefaultPayout'] = $off->payout;
            if ($off->incent == 'no')
                $incent = '0';
            else
                $incent = '1';
            $offer['Incent'] = $incent;
            
            $offer['OfferDescription'] = $off->description;
            $offer['Thumbnail'] = $off->icon;
            $offer['Category'] = $off->appcategory;
            $offer['Country'] = $off->countries;
            
            $offers[$offer['OfferID']] = $offer;
        }
        
        
        $result1 = file_get_contents('http://appwalls.mobi/api/v1?pub_id=256&api_key='.$this->config->item('advertiser_1255_key').'&incent=3&os=IOS&country=ALL&c=507');

        $records1 = json_decode($result1);
        
        foreach ($records1->ads as $off) {
            $offer = array();
            $offer['OfferID'] = $off->offerid;
            $offer['AdvertiserID'] = '1255';
            $offer['OfferName'] = $off->title;
            $offer['OfferStatus'] = 'active';
            //$offer['PreviewURL'] = $off->Preview_url;
            $offer['OfferURL'] = $off->clickurl;
            $offer['DefaultPayout'] = $off->payout;
            if ($off->incent == 'no')
                $incent = '0';
            else
                $incent = '1';
            $offer['Incent'] = $incent;
            
            $offer['OfferDescription'] = $off->description;
            $offer['Thumbnail'] = $off->icon;
            $offer['Category'] = $off->appcategory;
            $offer['Country'] = $off->countries;
            
            $offers[$offer['OfferID']] = $offer;
        }
        
        return $offers;
    }

    function offers_1043($query = false) {

        $result = file_get_contents('http://login.cpiapi.com/getOffers/v1?apiKey=' . $this->config->item('advertiser_1043_key'));

        $records = json_decode($result);

        $offers = array();



        foreach ($records->offers as $off) {
            $offer = array();
            $offer['OfferID'] = $off->offerID;
            $offer['AdvertiserID'] = '1043';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->previewURL;
            $offer['OfferURL'] = $off->clickUrl;
            $offer['DefaultPayout'] = $off->cpi;
            $offer['Incent'] = $off->incent;
            $offer['Thumbnail'] = $off->icon;
            $offer['Country'] = implode(', ', $off->countryISO);


            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }


//added by sahil
    function offers_1147($query = false) {
        $params = array(
            'fields' => array(
                'id'
                , 'name'
                , 'status'
                , 'preview_url'
                , 'offer_url'
                , 'default_payout'
                , 'payout_type'
                , 'require_approval'
                , 'expiration_date'
                , 'description'
            )
            , 'contain' => array('Country', 'OfferCategory')
            , 'page' => 1
            , 'limit' => '10000'
        );


        $result = file_get_contents('http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=findAll&api_key=' . $this->config->item('advertiser_1147_key') . '&NetworkId=' . $this->config->item('advertiser_1147_id') . '&' . http_build_query($params));

        $records = json_decode($result);

        $offers = array();

        foreach ($records->response->data->data as $off) {
            $offer = array();
            $country = $category = '';
            $offer['OfferID'] = $off->Offer->id;
            $offer['AdvertiserID'] = '1147';
            $offer['OfferName'] = $off->Offer->name;
            $offer['OfferStatus'] = $off->Offer->status;
            $offer['PreviewURL'] = $off->Offer->preview_url;
            $offer['OfferURL'] = 'http://nativex.go2cloud.org/aff_c?offer_id=' . $off->Offer->id . '&aff_id=490';
            $offer['DefaultPayout'] = $off->Offer->default_payout;
            $offer['PayoutType'] = $off->Offer->payout_type;
            $offer['RequireApproval'] = $off->Offer->require_approval;
            $offer['ExpiryDate'] = $off->Offer->expiration_date;
            $offer['OfferDescription'] = $off->Offer->description;
            foreach ($off->Country as $con) {
                $country .= $con->code . ',';
            }
            $offer['Country'] = $country;
            foreach ($off->OfferCategory as $cat) {
                $category .= $cat->name . ',';
            }
            $offer['Category'] = $category;
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
    }

//added by sahil
function offers_1341() {

        $result = file_get_contents('http://api.appsnt.com/res.php?cid='.$this->config->item('advertiser_1341_id').'&token='.$this->config->item('advertiser_1341_token'));
        $records = json_decode($result);

        $offers = array();
        foreach ($records->ads as $off) {
            $offer = array();
            $offer['OfferID'] = $off->camp_id;
            $offer['AdvertiserID'] = '1341';
            $offer['OfferName'] = $off->title;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = '';
            $offer['OfferURL'] = $off->click_url;
            $offer['Thumbnail'] = $off->icon;

            $offer['DefaultPayout'] = $off->payout;
            $offer['PayoutType'] = $off->pricing_model;
            $incent = 0;
            if($off->kpi == 'None Incent Traffic' || $off->kpi == 'none incent'){
                $incent = 1;
            }
            $offer['Incent'] = $incent;
            $offer['Category'] = $off->category;
            $offer['OperatingSystem'] = $off->platform;
            $offer['Country'] =  $off->geo;

            $offers[$offer['OfferID']] = $offer;
        }

         return $offers;

    }
//added by sahil
  function offers_1327() {

        $result = file_get_contents('http://pspm.pingstart.com/api/campaigns?token='.$this->config->item('advertiser_1327_token').'&publisher_id='.$this->config->item('advertiser_1327_id'));
        $records = json_decode($result);
        
        $offers = array();
        foreach ($records->campaigns as $off) {
            $offer = array();
            $offer['OfferID'] = $off->_id;
            $offer['AdvertiserID'] = '1327';
            $offer['OfferName'] = $off->name;
            $offer['OfferDescription'] = $off->native_one_sentence_description;
            $offer['OfferStatus'] = 'active';
            $offer['PreviewURL'] = $off->preview_link;
            $offer['OfferURL'] = 'http://track.pingstart.com/api/v4/click?campaign_id='.$offer['OfferID'].'&publisher_id=1103';
            $offer['Thumbnail'] = $off->icon_url;
            $offer['RequireApproval'] = '0';
            $cap = $off->cap;
            $offer['ConversionCap'] = $cap->day_cap;
            $offer['DefaultPayout'] = $off->payout;
            $offer['PayoutType'] = $off->payout_type;
            $offer['Incent'] = ($off->traffic == 'Incent') ? '0' : '1';
            $offer['Category'] = $off->category;
            $offer['OperatingSystem'] = $off->platform;
            $offer['Country'] = implode(',', $off->geo);
            
            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;
        
    }

//added by sahil
  function offers_1339() {

$pages = 10;
        $offers = array();
        for ($i = 1; $i <= $pages; $i++) {
            $url = 'http://api.inatrx.affise.com/2.1/partner/offers?limit=500&page=' . $i;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("API-Key: ".$this->config->item('advertiser_1339_token')));
            $output = curl_exec($ch);
            $records = json_decode($output);
            curl_close($ch);

            $t = $records->pagination;
            $total_offers = $t->total_count;
            $count_pages = $total_offers / 200;
            $pages = round($count_pages + 1);

            foreach ($records->offers as $off) {
                $offer = array();
                $offer['OfferID'] = $off->id;
                $offer['AdvertiserID'] = '1339';
                $offer['OfferName'] = $off->title;
                $offer['PreviewURL'] = $off->preview_url;
                $offer['OfferDescription'] = $off->description;
                $offer['OfferStatus'] = 'active';
                $offer['OfferURL'] = $off->link;
                $offer['Thumbnail'] = $off->logo;

                $payments = $off->payments;
                $p = $payments[0];

                $offer['DefaultPayout'] = $p->revenue;
                $offer['PayoutType'] = $p->type;
                $device = '';
                if (!empty($p->devices)) {
                    $device = implode(',', $p->devices);
                }
                $offer['OperatingSystem'] = $device;
                $offer['Category'] = implode(',', $off->categories);
                $offer['Country'] = implode(',', $off->countries);
                $offer['ConversionCap'] = $off->cap;

                $offer['RequireApproval'] = $off->required_approval == '' ? 0 : 1;


                $offers[$offer['OfferID']] = $offer;
            }
        }
        return $offers;


}

//added by sahil
  function offers_1361() {

 $response = file_get_contents('http://export.apprevolve.com/v2/getAds?accessKey='.$this->config->item('advertiser_1361_accessKey').'&secretKey='.$this->config->item('advertiser_1361_secretKey').'&applicationKey='.$this->config->item('advertiser_1361_applicationKey'));
        $records = json_decode($response);

        $offers = array();
        foreach ($records->ads as $off) {
            $offer = array();
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '1361';
            $offer['OfferName'] = $off->title;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = 'active';
            $offer['OfferURL'] = $off->clickURL;
            $offer['DefaultPayout'] = $off->bid;
            $offer['OperatingSystem'] = $off->platform;
            $offer['PayoutType'] = $off->pricingModel;
            $offer['ConversionCap'] = $off->completionActionText.' , '.'Campaign Type : '.$off->campaignType;
            $offer['Country'] = implode(',', $off->geoTargeting);
            $offer['Category'] = $off->category;
            $img = $off->creatives;
            $thumb = $img[0]->url;
            $offer['Thumbnail'] = $thumb;


            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;

}


//added by sahil
  function offers_1363() {

 $response = file_get_contents('http://export.apprevolve.com/v2/getAds?accessKey='.$this->config->item('advertiser_1363_accessKey').'&secretKey='.$this->config->item('advertiser_1363_secretKey').'&applicationKey='.$this->config->item('advertiser_1363_applicationKey'));
        $records = json_decode($response);

        $offers = array();
        foreach ($records->ads as $off) {
            $offer = array();
            $offer['OfferID'] = $off->offer_id;
            $offer['AdvertiserID'] = '1363';
            $offer['OfferName'] = $off->title;
            $offer['OfferDescription'] = $off->description;
            $offer['OfferStatus'] = 'active';
            $offer['OfferURL'] = $off->clickURL;
            $offer['DefaultPayout'] = $off->bid;
            $offer['OperatingSystem'] = $off->platform;
            $offer['Country'] = implode(',', $off->geoTargeting);
            $offer['Category'] = $off->category;
            $offer['PayoutType'] = $off->pricingModel;
            $offer['ConversionCap'] = $off->completionActionText.' , '.'Campaign Type : '.$off->campaignType;
            $img = $off->creatives;
            $thumb = $img[0]->url;
            $offer['Thumbnail'] = $thumb;


            $offers[$offer['OfferID']] = $offer;
        }

        return $offers;

}


//added by sahil
    function offers_1141($query = false) {

        $pages = 10;
        $limit = 100;
        $offers = array();
        for ($i = 1; $i <= $pages; $i++) {
$result = file_get_contents('http://pub.smartlead.mobi/api/json?token='.$this->config->item('advertiser_1141_token').'&method=Offer.findAll&limit= 100&page=' . $i);

            $records = json_decode($result);
            $total_offers = $records->response->data->total_count;
            $count_pages = $total_offers / $limit;
            $pages = round($count_pages + 1);

            foreach ($records->response->data->offers as $off) {
                $offer = array();
                $country = $category = '';
                $offer['OfferID'] = $off->id;
                $offer['AdvertiserID'] = '1141';
                $offer['OfferName'] = $off->name;
                $offer['OfferDescription'] = $off->description;
                $offer['OfferStatus'] = 'active';
                $offer['PreviewURL'] = $off->preview_url;
                $offer['DefaultPayout'] = $off->payout;
                $offer['OfferURL'] = 'http://tds.smartlead.mobi/click/122/' . $off->uuid;
                $offer['Thumbnail'] = $off->image_url;
                $offer['OperatingSystem'] = $off->platform;
                $offer['ConversionCap'] = $off->daily_cap;

                $offer['ExpiryDate'] = $off->end_date;
                if ($off->traffic_type == 'incent') {
                    $incent = '1';
                } else {
                    $incent = '0';
                }
                $offer['Incent'] = $incent;

                foreach ($off->countries as $con) {
                    $country .= $con->code . ',';
                }
                $offer['Country'] = $country;

                foreach ($off->categories as $cat) {
                    $category .= $cat->name . ',';
                }
                $offer['Category'] = $category;

                $offers[$offer['OfferID']] = $offer;
            }
        }
        
        return $offers;

    }


    function offers_1157($query = false) {

        $result = file_get_contents('http://api2.winclap.com/market/v1/campaigns/?token=' . $this->config->item('advertiser_1157_key') . '&affiliate=' . $this->config->item('advertiser_1157_id'));

        $records = json_decode($result);

        $offers = array();



        foreach ($records as $off) {
            $offer = array();
            $offer['OfferID'] = $off->id;
            $offer['AdvertiserID'] = '1157';
            $offer['OfferName'] = $off->name;
            $offer['OfferStatus'] = 'active';
            //$offer['PreviewURL'] = $off->previewURL;
            $offer['OfferURL'] = "http://api2.winclap.com/offers/click/65/" . $offer['OfferID'];
            $offer['DefaultPayout'] = $off->payout;
            //$offer['Incent'] = $off->incent;
            //$offer['Thumbnail'] = $off->icon;
            $offer['Country'] = implode(', ', $off->countries);
            $offer['ConversionCap'] = $off->daily_cap;


            $offers[$offer['OfferID']] = $offer;
        }


        return $offers;
    }

}
