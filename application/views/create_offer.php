<?php include('header.php'); ?>

<div class="container ">

    <div class="panel panel-default">
      <div class="panel-body">
      
      		<div class="row">
                
                	<div class="col-sm-12"><h4>Please verify all fields before offer is created: </h4>
                    <h6>Fields with (*) are required </h6>
                    </div>
            <div class="col-sm-12"><hr/></div>
            
            </div>
            <?php if(!empty($error)){ ?>
            <div class="row">
                
                	<div class="col-sm-6">
           				 <div class="alert alert-danger" role="alert"><?=  $error; ?></div>
                       </div>
                 </div>
               <?php  } ?>        
            
            <div class="row">
            
                    <form class="form-horizontal" action="<?= current_url(); ?>" method="post">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Offer Name *</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inputEmail3" name="name" required="required" value="<?= $offer->OfferName; ?>" placeholder="Offer Name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Advertiser ID *</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inputPassword3" name="advertiser_id" required="required" value="<?= $offer->AdvertiserID; ?>" placeholder="Advertiser ID">
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Offer Description</label>
                        <div class="col-sm-5">
                         <textarea class="form-control" rows="3" name="description"><?= $offer->OfferDescription; ?></textarea>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Preview URL</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inputPassword3" name="preview_url" value="<?= $offer->PreviewURL; ?>" placeholder="Preview URL">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Offer URL *</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" required="required" name="offer_url" id="inputPassword3" value="<?= $offer->OfferURL; ?>" placeholder="Offer URL">
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Protocol *</label>
                        <div class="col-sm-5">
                          <select required class="form-control" name="protocol">
                              <option value="http">HTTP</option>
                              <option value="https">HTTPS</option>
                              <option value="http_img">HTTP IMAGE</option>
                              <option value="https_img">HTTPS IMAGE</option>
                              <option selected="selected" value="server">Server to Server with Transaction ID</option>
                              <option value="server_affiliate">Server to Server with Affiliate ID</option>
                            </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Offer Status *</label>
                        <div class="col-sm-5">
                          <select class="form-control" name="status">
                              <option <?php if($offer->RequireApproval=='0' || $offer->RequireApproval==''){ ?> selected="selected" <?php } ?>  value="active">Active</option>
                              <option <?php if($offer->RequireApproval=='1'){ ?> selected="selected" <?php } ?> value="pending">Pending</option>
                              <option value="paused">Paused</option>
                            </select>
                        </div>
                        
                         <div class="col-sm-4">
                          <h5>Advertiser Status : <b><?= $offer->OfferStatus; ?></b>, Advertiser Require Approval : <b><?php if($offer->RequireApproval=='1'){ echo 'Yes'; } elseif($offer->RequireApproval=='0') { echo 'No';} else { } ?></b></h5>
                        </div>
                      </div>
                      
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Require Approval *</label>
                        <div class="col-sm-5">
                          <select class="form-control" name="require_approval">
                              <option <?php if($offer->Incent=='0'){ ?> selected="selected" <?php } ?>  value="1">Yes</option>
                              <option <?php if($offer->Incent=='0' || $offer->Incent==''){ ?> selected="selected" <?php } ?> value="0">No</option>
                            
                            </select>
                        </div>
                        
                         <div class="col-sm-4">
                          <h5>Incent : <b><?php if($offer->Incent=='1'){ echo 'Yes'; } elseif($offer->Incent=='0') { echo 'No';} else { } ?></b></h5>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-5">
                        <?php $categories = array('40' => 'APK', '25' => 'Dating', '52' => 'Desktop CPA', '23' => 'Download/Install - PC', '19' => 'eBook', '1' => 'Email Submits, Surveys', '3' => 'Finance/Credit Reports', '11' => 'Free Membership', '7' => 'Free/$1 Trials', '21' => 'Games/Entertainment', '44' => 'Gaming/Casino', '39' => 'Health', '9' => 'Incent', '24' => 'Insurance', '56' => 'Landing Page Click-Through', '15' => 'Mobile App - Android', '17' => 'Mobile App - iPad', '13' => 'Mobile App - iPhone', '50' => 'Mobile CPA', '27' => 'Mobile Subscription/PIN Submit', '54' => 'Paid App', '35' => 'Pay Per Call', '42' => 'Premium', '33' => 'Rewards', '5' => 'Sale Offers - CC', '31' => 'Shopping', '46' => 'Social', '29' => 'Travel', '48' => 'Utility', '37' => 'Video', '58'=>'Video Creative Available'
); ?>
                          <select class="form-control" name="category_ids[]" multiple="multiple">
                              <option value="">Select Category</option>
                              <?php foreach($categories as $key => $category): ?>
                              <option value="<?= $key; ?>"><?= $category; ?></option>
                              <?php endforeach; ?>
                            
                            </select>
                        </div>
                        
                         <div class="col-sm-4">
                          <h5>Advertiser Category : <b><?= $offer->Category; ?></b></h5>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Payout Type</label>
                        <div class="col-sm-5">
                          <select class="form-control" name="payout_type">
                          <option value="">Select Payout Type</option>
                              <option value="cpa_flat" selected="selected">CPA Flat</option>
                              <option value="cpa_percentage">CPA Percentage</option>
                              <option value="cpa_both">CPA Both</option>
                              <option value="cpc">CPC</option>
                              <option value="cpm">CPM</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                         	<h5>Advertiser Payout type is: <b><?= $offer->PayoutType; ?></b></h5>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Default Payout</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inputPassword3" required="required" name="default_payout" placeholder="Default Payout">
                        </div>
                         <div class="col-sm-4">
                         	<h5>Advertiser Payout: <b><?= $offer->DefaultPayout; ?></b></h5>
                        </div>
                      </div>
                      
                      
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Daily Cap</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inputPassword3"  name="conversion_cap" placeholder="Daily Cap">
                        </div>
                         <div class="col-sm-4">
                         	<h5>Advertiser Caps: <b><?= $offer->ConversionCap; ?></b></h5>
                        </div>
                      </div>
                      
                      
                       <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Expiry Date *</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="datepicker" name="expiration_date" value="<?php if($offer->ExpiryDate=='' || $offer->ExpiryDate < date('Y-m-d')) { echo date('Y-m-d', strtotime('+12 month')); } else { echo $offer->ExpiryDate ; } ?>" required="required"  placeholder="Expiry Date">
                        </div>
                         <div class="col-sm-4">
                         	<h5>Advertiser Expiry Date: <b><?= $offer->ExpiryDate; ?></b></h5>
                        </div>
                      </div>
                       
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">REF ID *</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="inputPassword3" name="ref_id" required="required" value="<?= $offer->OfferID; ?>" placeholder="Advertiser Offer ID">
                        </div>
                      </div>
                      
                      <?php if($offer->App_Icon!=''){ ?>
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">App Icon (View Only): </label>
                        <div class="col-sm-5">
                         <img src="<?= $offer->App_Icon; ?>" style=" max-width:200px; max-height:200px;" /><br/>
                         <a href="<?= $offer->App_Icon; ?>" target="_blank" >
   <b>Download</b>
</a>
                        </div>
                      </div>
                      <?php } ?>

                      
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-default">Create Offer</button>
                        </div>
                      </div>
                    </form>            </div><!-- row end -->

			<div class="row">
            	<div class="col-sm-12">
                	<hr/>
                </div>
            </div>
             
                  
            
      </div>
    </div>
 
    <script type="text/javascript">
	$(function() {
    $( "#datepicker" ).datepicker(
	{
			 dateFormat: 'yy-mm-dd',
			minDate : '2015-01-10'

		 }
		 );
  });
	</script>
</body>
</html>
