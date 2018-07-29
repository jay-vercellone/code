<?php include('header.php'); ?>

<div class="container ">

    <div class="panel panel-default">
        <div class="panel-body">





            <div class="row">
                <form class="form-inline" >
                    <div class="col-sm-12 form-inline">

                        <div class="form-group ">
                            <label class="control-label">Filters:</label>

                            <select class="form-control" name="adv_id" id="advertiser" multiple="multiple">
                                <option value="" selected="selected">Select Advertiser</option>
                                <?php foreach ($Advertisers as $adv): ?>
                                    <option value="<?= $adv->AdvertiserID; ?>" ><?= $adv->AdvertiserName; ?> (<?= $adv->AdvertiserID; ?>)</option>
                                <?php endforeach; ?>

                            </select>
                            <?php
                            $country_array = array('AF' => 'Afghanistan', 'AX' => 'Åland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia, Plurinational State of', 'BQ' => 'Bonaire, Sint Eustatius and Saba', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, the Democratic Republic of the', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Côte dIvoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curaçao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and McDonald Islands', 'VA' => 'Holy See (Vatican City State)', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea, Democratic Peoples Republic of', 'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia, the former Yugoslav Republic of', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory, Occupied', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Réunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthélemy', 'SH' => 'Saint Helena, Ascension and Tristan da Cunha', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin (French part)', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten (Dutch part)', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia the South Sandwich Islands', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan, Province of China', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela, Bolivarian Republic of', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe')
                            ;
                            ?>
                            <select class="form-control" name="country" id="country" multiple="multiple">
                                <option value="" selected="selected">Select Country</option>
                                <?php foreach ($country_array as $key => $con): ?>
                                    <option value="<?= $key; ?>" ><?= substr($con, 0, 35); ?> (<?= $key; ?>)</option>
                                <?php endforeach; ?>

                            </select>

                            <select class="form-control" name="cate" id="category" multiple="multiple">
                                <option value="" selected="selected">Select Category</option>
                                <?php foreach ($categories as $category): if ($category != '') { ?>
                                        <option value="<?= $category; ?>" ><?= substr($category, 0, 25); ?></option>
                                    <?php } endforeach; ?>

                            </select>


                            <select class="form-control" name="device" id="device" multiple="multiple">
                                <option value="" selected="selected">Select Device</option>
                                <?php foreach ($devices as $device): if ($device != '') { ?>
                                        <option value="<?= $device; ?>" ><?=
                                            substr($device, 0, 20);
                                            ;
                                            ?></option>
                                    <?php } endforeach; ?>

                            </select>


                        </div>




                </form>
            </div>
        </div><!-- row end -->

        <div class="row">
            <div class="col-sm-12">
                <br/>
            </div>
        </div>

        <div class="row">
            <form class="form-inline" >
                <div class="col-sm-12 form-inline">

                    <div class="form-group ">
                        <label class="control-label">Filters:</label>


                        <select class="form-control" name="incent" id="incent">
                            <option value="" selected="selected">Incent</option>
                            <option value="1" >Yes</option>
                            <option value="0" >No</option>

                        </select>

                        <select class="form-control" name="status" id="status">
                            <option value="" >Active & Paused</option>
                            <option selected="selected" value="active" >Active</option>
                            <option value="paused" >Paused</option>

                        </select>

                        <select class="form-control" name="require_approval" id="require_approval">
                            <option value="" selected="selected">Require Approval</option>
                            <option value="1" >Yes</option>
                            <option value="0" >No</option>

                        </select>


                        <input type="text" class="form-control datepicker" id="created" placeholder="Show After Date" />

                        <label class="control-label">Update:</label>


                        <select class="form-control" name="adv_id" id="updateAdvID">
                            <option value="" selected="selected">Select Advertiser</option>
                            <?php foreach ($Advertisers as $adv): ?>
                                <option value="<?= $adv->AdvertiserID; ?>" ><?= $adv->AdvertiserName; ?> (<?= $adv->AdvertiserID; ?>)</option>
                            <?php endforeach; ?>

                        </select>

                        <a  id="updateAdv" class="btn btn-default">Refresh</a>
                    </div>




            </form>
        </div>
    </div><!-- row end -->

    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>
    <div >
        <b>Show/ Hide Columns:</b> 	
        <input type="checkbox" value="1" checked="checked" class="toggle-vis" /> Offer ID  &nbsp;&nbsp;  
        <input type="checkbox" value="2" checked="checked" class="toggle-vis" /> Offer Name   &nbsp;&nbsp;

        <input type="checkbox" checked="checked" value="3" class="toggle-vis" /> Advertiser ID  &nbsp;&nbsp;

        <input type="checkbox" value="4" checked="checked" class="toggle-vis" /> Advertiser Name   &nbsp;&nbsp;

        <input type="checkbox" value="5" checked="checked" class="toggle-vis" /> Payout Type&nbsp;&nbsp;
        <input type="checkbox" value="6" checked="checked" class="toggle-vis" /> Payout&nbsp;&nbsp;
        <input type="checkbox" value="7" checked="checked" class="toggle-vis" /> Incent&nbsp;&nbsp;
        <input type="checkbox" value="8" checked="checked" class="toggle-vis" /> Device&nbsp;&nbsp;
        <input type="checkbox" value="9" checked="checked" class="toggle-vis" /> Category&nbsp;&nbsp;
        <input type="checkbox" value="10" checked="checked" class="toggle-vis" /> Caps&nbsp;&nbsp;
        <input type="checkbox" value="11" checked="checked" class="toggle-vis" /> Require Approval&nbsp;&nbsp;
        <input type="checkbox" value="12" checked="checked" class="toggle-vis" /> Status&nbsp;&nbsp;
        <input type="checkbox" value="13" checked="checked" class="toggle-vis" /> HO Status&nbsp;&nbsp;
        <input type="checkbox" value="14" checked="checked" class="toggle-vis" /> Created&nbsp;&nbsp;
        <input type="checkbox" value="15" checked="checked" class="toggle-vis" /> Country &nbsp;&nbsp;
        <input type="checkbox" value="16" checked="checked" class="toggle-vis" /> Preview URL&nbsp;&nbsp;

    </div>	
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-right">
            <a class="btn btn-info"   id="push_showinapp" data-offer_id="" data-appstatus="" role="button"> Show In App</a> 
            <a class="btn btn-info"  id="remove_compare_button" data-compareid="" role="button"> Compare Offer</a>
            <a class="btn btn-info"   id="create_afftrack" data-offer_id=""  role="button"> Create AffTrack Offer</a> 
<!--            <a class="btn btn-info"   id="create_jungggle" data-offer_id=""  role="button"> Create Jungggle Offer</a> -->
            <hr />
        </div>
    </div>
    <div class="">
        <table class="table table-striped table-bordered table-hover table-responsive dataTable" id="dataTable">
            <thead>
            <th>
            <td>Offer ID</td>
            <td>Offer Name</td>
            <td>Advertiser ID</td>
            <td>Advertiser Name</td>
            <td>Payout Type</td>
            <td>Payout</td>
            <td>Incent</td>
            <td>Device</td>
            <td>Category</td>
            <td>Caps</td>
            <td>Require Approval</td>
            <td>Status</td>
            <td>Status at HO</td>
            <td>Created ON</td>
            <td>Country</td>
            <td>Preview URL</td>
            </th>
            </thead>

            <tbody></tbody>
            <tfoot></tfoot>
        </table>
    </div>                  

</div>
</div>
<script type="text/javascript">
    /*$(document).ready(function(){
     $('#dataTable').dataTable({
     "paging":   true,
     "ordering": true,	
     "aLengthMenu": [[50, 100, 200], [50, 100, 200]],
     "iDisplayLength": 50
     
     });
     
     });*/

    $(document).ready(function ()
    {
        var tableData = $('#dataTable').dataTable({
            "sScrollX": "100%",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "searchCols": [null, null, null, null, null, null, null, null, null, null, null, null, {"search": "active"}, null, null, null, null],
            "sAjaxSource": "dash/getoffers",
            "iDisplayLength": 50,
            "aLengthMenu": [[50, 100, 200], [50, 100, 200]],
            "aaSorting": [[0, 'asc']],
            "aoColumns": [
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true,
                    "mRender": function (data, type, full)
                    {
                        if (data == '1') {
                            return 'Yes';
                        } else if (data == '0') {
                            return 'No';
                        } else {
                            return 'N/A';
                        }
                    }
                },
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true,
                    "mRender": function (data, type, full)
                    {
                        if (data != '') {
                            return data;
                        } else {
                            return 'None';
                        }
                    }
                },
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true,
                    "mRender": function (data, type, full)
                    {
                        if (data == '1') {
                            return 'Yes';
                        } else if (data == '0') {
                            return 'No';
                        } else {
                            return 'N/A';
                        }
                    }
                },
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": false,
                    "mRender": function (data, type, full)
                    {
                        if (data == '') {

                            return '<a target="blank" href="<?= base_url(); ?>offers/create/' + full[0] + '">Create Offer</a>';
                        } else {
                            return data;
                        }
                    }
                },
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true},
                {"bVisible": true, "bSearchable": true, "bSortable": true,
                    "mRender": function (data, type, full)
                    {
                        var str = full[16];
                        if (str.length > 11)
                            str = str.substring(0, 15) + '...';
                        return '<a href="" title="' + full[16] + '">' + str + '</a>';
                    }
                },
            ],
        }).fnSetFilteringDelay(700);

        $('select#advertiser').on('change', function () {
            var selectedValue = $(this).val();
            tableData.fnFilter('Multiple ' + selectedValue, 3, true); //Exact value, column, reg
        });
        $('select#incent').on('change', function () {
            var selectedValue = $(this).val();
            tableData.fnFilter(selectedValue, 7, true); //Exact value, column, reg
        });
        $('select#device').on('change', function () {
            var selectedValue = $(this).val();
            tableData.fnFilter('Multiple Like ' + selectedValue, 8, true); //Exact value, column, reg
        });
        $('select#category').on('change', function () {
            var selectedValue = $(this).val();
            tableData.fnFilter('Multiple Like ' + selectedValue, 9, true); //Exact value, column, reg
        });
        $('select#require_approval').on('change', function () {
            var selectedValue = $(this).val();
            tableData.fnFilter(selectedValue, 11, true); //Exact value, column, reg
        });
        $('select#status').on('change', function () {
            var selectedValue = $(this).val();
            tableData.fnFilter(selectedValue, 12, true); //Exact value, column, reg
        });
        $('select#country').on('change', function () {
            var selectedValue = $(this).val();
            tableData.fnFilter('Multiple Like ' + selectedValue, 15, true); //Exact value, column, reg
        });

        var tableDatanew = $('#dataTable').DataTable();
        $('#dataTable tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $('#remove_compare_button').attr('data-compareid', '');
                $('#remove_compare_button').text('Select Row to compare');
                $('#push_showinapp').text('Select Row to Show/Hide in App');
                $('#push_showinapp').attr('data-offer_id', '');
                $('#create_afftrack').attr('data-offer_id', '');
                $('#create_jungggle').attr('data-offer_id', '');
                $('#push_showinapp').attr('data-appstatus', '');
                $('#push_supersonic').attr('href', '');
            }
            else {
                tableDatanew.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                var datatable_values = tableDatanew.row(this).data();
                $('#remove_compare_button').attr('data-compareid', datatable_values[0]);
                $('#remove_compare_button').text('Compare Offer');
                if (datatable_values[17] == '1') {
                    $('#push_showinapp').text('Hide From App');
                } else {
                    $('#push_showinapp').text('Show In App');
                }
                $('#push_showinapp').attr('data-offer_id', datatable_values[0]);
                $('#create_afftrack').attr('data-offer_id', datatable_values[0]);
                $('#create_jungggle').attr('data-offer_id', datatable_values[0]);
                $('#push_showinapp').attr('data-appstatus', datatable_values[17]);


            }
        });
        $('#remove_compare_button').on('click', function () {
            var compareID = $(this).attr('data-compareid');
            if (compareID == '') {
                $('#remove_compare_button').text('Select Row to compare');
                tableData.fnFilter('', 13, true);
            } else {
                tableData.fnFilter(compareID, 13, true);
                $('#remove_compare_button').text('Remove Compare');
            }
            $('#remove_compare_button').attr('data-compareid', '');
        });


        $('#create_afftrack').on('click', function () {
            var offerID = $(this).attr('data-offer_id');
         
            if (offerID == '') {
                alert('Select a row to create an offer');
                return;
            }

           window.open('<?= base_url(); ?>afftrack/create/' + offerID)

        });
        
        $('#create_jungggle').on('click', function () {
            var offerID = $(this).attr('data-offer_id');
         
            if (offerID == '') {
                alert('Select a row to create an offer');
                return;
            }

           window.open('<?= base_url(); ?>jungggle/create/' + offerID)

        });


        $('#push_showinapp').on('click', function () {
            var offerID = $(this).attr('data-offer_id');
            var appStatus = $(this).attr('data-appstatus');
            if (offerID == '' || appStatus == '') {
                alert('Select a row to show/hide in app');
                return;
            }

            if (appStatus == '1') {
                //hide in app
                var appStatus = '0';
            } else {
                //show in app
                var appStatus = '1';
            }

            $.get("dash/showinapp/" + offerID + '/' + appStatus, function (data, status) {
                alert(data);
            });

        });

        /*$('input#created').on('blur',function(){
         var selectedValue = $(this).val();
         tableData.fnFilter('Greater '+selectedValue, 11, true); //Exact value, column, reg
         });*/
        $('#updateAdv').on('click', function () {
            var updateAdvID = $('#updateAdvID').val();
            if (updateAdvID == '') {
                alert('Please select an advertiser to update');
            }
            else {
                window.open('<?= base_url(); ?>cron/adv_offers/' + updateAdvID)
            }
        });


        $(function () {

            $(".datepicker").datepicker(
                    {
                        dateFormat: 'yy-mm-dd',
                        onSelect: function (dateText, inst) {
                            var date = $(this).val();
                            tableData.fnFilter('Greater ' + date, 14, true);
                        },
                        minDate: '2015-01-10'

                    }
            );
        });

        $('.toggle-vis').change(function () {


            // Get the column API object
            var column = $(this).val()


            var bVis = tableData.fnSettings().aoColumns[column].bVisible;
            tableData.fnSetColumnVis(column, bVis ? false : true);
        });


    });


    $(function () {
        $(document).tooltip();
    });
</script>

</body>
</html>
