<?php

Class Email_model extends CI_Model {

    //this class handles all the outgoing mails to users

    function __construct() {

        parent::__construct();
        $this->load->library('mandrill');
    }

    function send_pause_email($data) {

        $account_managers_emails = $this->config->item('managers_emails');
        foreach ($account_managers_emails as $am) {
            $data['Emails'][] = $am;
        }

        $email['subject'] = $data['OfferID'] . ' - ' . $data['OfferName'] . ' Paused';
        $email['msg'] = 'The campaign below has been paused at the request of the advertiser:<br><br/>
' . $data['OfferID'] . ' - ' . $data['OfferName'] . ' <br/><br/>

Please pause it on your end. <br/>
<br/>
Thanks,<br/><br/>
Network Operations';

        foreach ($data['Emails'] as $dm) {
            $t = array();
            $t['email'] = $dm;
            $t['type'] = 'to';
            $email['to'] = $t;
            self::send_email($email);
        }
    }

    function send_active_email($data) {

        $account_managers_emails = $this->config->item('managers_emails');

        foreach ($account_managers_emails as $am) {
            $data['Emails'][] = $am;
        }

        $email['subject'] = $data['OfferID'] . ' - ' . $data['OfferName'] . ' Resumed';
        $email['msg'] = 'Hi,<br><br/>
Campaign ' . $data['OfferID'] . ' - ' . $data['OfferName'] . ' has been reactivated. You can resume your original link. If you have any questions, please contact your Account Manager. <br/><br/>

<br/>
Thanks,<br/><br/>
Network Operations';

        foreach ($data['Emails'] as $dm) {
            $t = array();
            $t['email'] = $dm;
            $t['type'] = 'to';
            $email['to'] = $t;
            self::send_email($email);
        }
    }

    function send_morning_email($data) {

        $account_managers_emails = $this->config->item('daily_updates_managers_emails');
        foreach ($account_managers_emails as $am) {
            $data['Emails'][] = $am;
        }
        


        $email['subject'] = 'Daily Offer Updates ' . date('M-d');
        $new_offers = $ho_offers = '';
        $csvdata = array();
        foreach ($data['newoffers'] as $no) {
            $status = 'Review';
            $new_offers .= $no->OfferID . ': ' . $no->OfferName . ' (<b>ADV:</b> #' . $no->AdvertiserID . '-' . $no->AdvertiserName . ', <b>Manager:</b>' . $no->AdvertiserManager . ' <b>Payout:</b> $' . $no->DefaultPayout . ')<br/>';
            if (in_array($no->AdvertiserID, $this->config->item('whitelist_advertisers'))) {
                $status = 'Approved to Load';
            }
            $csvdata[] = array(
                $no->AdvertiserName,
                $no->OfferID,
                $no->AdvertiserManager,
                $no->OfferName,
                $no->ConversionCap,
                '',
                '',
                $no->DefaultPayout,
                '',
                $no->ExpiryDate,
                '',
                'API',
                '',
                $status
            );
        }
        $ho_offer_head = '<table border="1">
                  <thead>
                  	<th>
                       <td>HO ID</td>
                        <td>Offer Name</td>
                        <td>Current Advertiser</td>
                        <td>Suggested Advertiser</td>
                        <td>Suggested Offer ID</td>
                        <td>Current Payout</td>
                        <td>Max Payout</td>
                    </th>
                  </thead>
                  <tbody>';
        $ho_offer_footer = '</tbody></table>';
        $i = '1';
        foreach ($data['ho_records'] as $ho) {
            $status = 'Review';
            $old_price = round($ho['ho']['DefaultPayout'], 2);
            $new_price = round($ho['offer']->DefaultPayout, 2);
            $percentage = round($ho['offer']->DefaultPayout, 2) / 10;
            $old_price = $old_price + $percentage;
            if (in_array($ho['offer']->AdvertiserID, $this->config->item('whitelist_advertisers')) && $new_price >= $old_price) {
                $status = 'Approved to Replace';
            }

            $csvdata[] = array(
                $ho['offer']->AdvertiserName,
                $ho['offer']->OfferID,
                $ho['offer']->AdvertiserManager,
                $ho['offer']->OfferName,
                $ho['offer']->ConversionCap,
                $ho['ho']['OfferID'],
                round($ho['ho']['DefaultPayout'], 2),
                round($ho['offer']->DefaultPayout, 2),
                '',
                $ho['offer']->ExpiryDate,
                '',
                'API',
                '',
                $status
            );
            $ho_offers .= '<tr><td>' . $i . '</td><td>' . $ho['ho']['OfferID'] . '</td>
                        <td>' . $ho['ho']['OfferName'] . '</td>
                        <td>' . $ho['ho']['AdvertiserName'] . '(<b>#</b>' . $ho['ho']['AdvertiserID'] . ', <b>Manager:</b> ' . $ho['ho']['AdvertiserManager'] . ')</td>
                        <td>' . $ho['offer']->AdvertiserName . '(<b>#</b>' . $ho['offer']->AdvertiserID . ', <b>Manager:</b> ' . $ho['offer']->AdvertiserManager . ')</td>
                         <td>' . $ho['offer']->OfferID . '</td>
                        <td>$' . round($ho['ho']['DefaultPayout'], 2) . '</td>
                          <td>$' . round($ho['offer']->DefaultPayout, 2) . '</td></tr>';
            $i++;
        }

        $ho_offer_all = $ho_offer_head . $ho_offers . $ho_offer_footer;
        $email['msg'] = 'Hi,<br><br/>
API V2.0 Auto notification<br/><br/>

<b>New Offers to Promote:</b><br/><br/>' .
                $new_offers
                . '<br/><br/>

<b>HO Offers with Max Payouts:</b><br/><br/>' .
                $ho_offer_all
                . '<br/>
Thanks,<br/><br/>
Network Operations';



        $fp = fopen('files/new_offers.csv', 'w');
        $csv_fields = array();

        $csv_fields[] = 'Advertiser name';
        $csv_fields[] = 'Offer ID';
        $csv_fields[] = 'Manager';
        $csv_fields[] = 'Offer Name';
        $csv_fields[] = 'Caps';
        $csv_fields[] = 'RO';
        $csv_fields[] = 'Revenue';
        $csv_fields[] = 'Max Payout';
        $csv_fields[] = 'Payout';
        $csv_fields[] = 'Expiry Date';
        $csv_fields[] = 'Skip';
        $csv_fields[] = 'API';
        $csv_fields[] = 'Skip';
        $csv_fields[] = 'Status';

        fputcsv($fp, $csv_fields);
        foreach ($csvdata as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        $attachment = file_get_contents('files/new_offers.csv');
        $attachment_encoded = base64_encode($attachment);

        foreach ($data['Emails'] as $dm) {
            $t = array();
            $t['email'] = $dm;
            $t['type'] = 'to';
            $email['to'] = $t;
            $email['attachments'] = array(
                array(
                    'content' => $attachment_encoded,
                    'type' => "text/csv",
                    'name' => 'offers_' . date('M-d') . '.csv',
                )
            );
            self::send_email($email);
        }
    }

    function send_suggestion_email($data) {

        $account_managers_emails = $this->config->item('daily_suggestion_managers_emails');
        foreach ($account_managers_emails as $am) {
            $data['Emails'][] = $am;
        }


        $email['subject'] = 'Daily Offer Suggestions ' . date('M-d');
        $single_offers = $payout_offers = '';

        $so_offer_head = '<table border="1">
                  <thead>
                  	<tr>
                        <th>S.No</th>
                       <th>Advertser</th>
                       <th>Adv. Offer ID</th>
                        <th>Offer Name</th>
                        <th>Offer Payout</th>
                        <th>Adv. Manager</th>
                    </tr>
                  </thead>
                  <tbody>';
        $so_offer_footer = '</tbody></table>';


        if (!empty($data['single'])) {
            $i = 1;
            $so_offers = '';
            $csvdata_single = array();
            foreach ($data['single'] as $so) {


                $so_offers .= '<tr>'
                        . '<td>' . $i . '</td>'
                        . '<td>' . $so->AdvertiserName . ' (<b>#</b>' . $so->AdvertiserID . ')</td>
                        <td>' . $so->OfferID . '</td>
                        <td>' . $so->OfferName . '</td>
                        <td>$' . round($so->DefaultPayout, 2) . '</td>
                        <td>' . $so->AdvertiserManager . '</td>';
                $csvdata_single[] = array(
                    $so->AdvertiserID,
                    $so->AdvertiserName,
                    $so->OfferID,
                    $so->OfferName,
                    round($so->DefaultPayout, 2),
                    $so->AdvertiserManager
                );
                $i++;
            }
        } else {
            $so_offers = '<tr><td colspan="6" align="ceenter">No Offer found</td></tr>';
        }

        $so_offer_all = $so_offer_head . $so_offers . $so_offer_footer;


        $po_offer_head = '<table border="1">
                  <thead>
                  	<tr>
                        <th>S.No</th>
                       <th>Advertser</th>
                       <th>Adv. Offer ID</th>
                        <th>Offer Name</th>
                        <th>Offer Payout</th>
                        <th>Adv. Manager</th>
                    </tr>
                  </thead>
                  <tbody>';
        $po_offer_footer = '</tbody></table>';


        if (!empty($data['payout'])) {
            $j = 0;
            $po_offers = '';
            $csvdata_payout = array();
            foreach ($data['payout'] as $po) {


                $po_offers .= '<tr><td>' . $j . '</td><td>' . $po->AdvertiserName . ' (<b>#</b>' . $po->AdvertiserID . ')</td>
                        <td>' . $po->OfferID . '</td>
                        <td>' . $po->OfferName . '</td>
                        <td>$' . round($po->DefaultPayout, 2) . '</td>
                        <td>' . $po->AdvertiserManager . '</td>';
                
                $csvdata_payout[] = array(
                    $po->AdvertiserID,
                    $po->AdvertiserName,
                    $po->OfferID,
                    $po->OfferName,
                    round($po->DefaultPayout, 2),
                    $po->AdvertiserManager
                );
                $j++;
            }
        } else {
            $po_offers = '<tr><td colspan="6" align="ceenter">No Offer found</td></tr>';
        }

        $po_offer_all = $po_offer_head . $po_offers . $po_offer_footer;


        $email['msg'] = 'Hi,<br><br/>
API V2.0 Auto Offer Suggestion<br/><br/>

<h3>Offers Live at Single Avertiser( payout > $2 ):</h3><br/><br/>' .
                $so_offer_all
                . '<br/><br/>

<h3>Offers Live with Highest Payout( Our Payout > Other Advertisers payout ):</h3><br/><br/>' .
                $po_offer_all
                . '<br/>
Thanks,<br/><br/>
Network Operations';
        
        $fp = fopen('files/suggestions.csv', 'w');
        $csv_fields = array();
        
        $csv_fields[] = 'Advertiser ID';
        $csv_fields[] = 'Advertiser name';
        $csv_fields[] = 'Offer ID';
        $csv_fields[] = 'Offer Name';
        $csv_fields[] = 'Payout';
        $csv_fields[] = 'Manager';
        
        fputcsv($fp, array('', 'Single Advertiser'));
        fputcsv($fp, $csv_fields);
        foreach ($csvdata_single as $fields) {
            fputcsv($fp, $fields);
        }
        fputcsv($fp, array('', 'Highest Payout'));
        fputcsv($fp, $csv_fields);
        foreach ($csvdata_payout as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        $attachment = file_get_contents('files/suggestions.csv');
        $attachment_encoded = base64_encode($attachment);



        foreach ($data['Emails'] as $dm) {
            $t = array();
            $t['email'] = $dm;
            $t['type'] = 'to';
            $email['to'] = $t;
            $email['attachments'] = array(
                array(
                    'content' => $attachment_encoded,
                    'type' => "text/csv",
                    'name' => 'suggestions_' . date('M-d') . '.csv',
                )
            );

            self::send_email($email);
        }
    }

    //final function to sent mails
    function send_email($data) {
        try {

            $this->mandrill->init($this->config->item('mandrill_api_key'));
            $mandrill_ready = TRUE;
        } catch (Mandrill_Exception $e) {

            $mandrill_ready = FALSE;
        }
        if ($mandrill_ready) {
            //Send us some email!

            $from_email = $this->config->item('from_email');
            $from_name = $this->config->item('from_name');
            $sendemail = array(
                'html' => $data['msg'],
                'subject' => $data['subject'],
                'from_email' => $from_email,
                'from_name' => $from_name,
                'headers' => array('Reply-To' => $this->config->item('reply_to')),
                'important' => true,
                'to' => array($data['to'])//Check documentation for more details on this one
            );

            if (!empty($data['attachments'])) {
                $sendemail['attachments'] = $data['attachments'];
            }

            $result = $this->mandrill->messages_send($sendemail);

        }
    }

}
