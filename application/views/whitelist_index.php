<?php include('header.php'); ?>

<div class="container ">

    <div class="panel panel-default">
      <div class="panel-body">
            

			<div class="col-sm-12 form-inline">
                    
                        <div class="form-group ">
                        <form action="<?= current_url(); ?>" method="post">
                         <label class="control-label">Select Advertiser:</label>
                        
                        
                        <select id="adv_id" name="adv_id" class="form-control">
                          <option selected="selected" value="">Select Advertiser</option>
                          <?php foreach($adv as $key => $ad): ?>
                        		<option <?php if($key == $adv_id){ ?> selected="selected" <?php } ?> value="<?= $key; ?>"><?= $ad->company; ?></option>
                                <?php endforeach; ?>
                            
                        </select>
                       
                         
                        
                        <button type="submit" class="btn btn-default" id="updateAdv">Submit</button>
                        </form>
                        </div>
                        
                       
                     
                     
                    
            </div>
            <?php if(empty($ips) && !empty($adv_id)){ ?>
            <div class="col-sm-12">
            <h4>Ther is no IP address assigned for this advertiser. <a href="<?= base_url(); ?>whitelist/assign/<?= $adv_id; ?>" >Click here to assign.</a> </h4>
            
            </div>
            <?php } elseif(!empty($ips)){ 
			?>
             <div class="col-sm-12">
            <h3>Records Found:   <a class="btn btn-default" href="<?= base_url(); ?>whitelist/assign/<?= $adv_id; ?>" >Add New IP</a> </h3>
            
            </div>
           
            <?php
				foreach($ips as $ip){
					?>
                    <p> <b><?= $ip; ?></b> <a target="_blank" href="<?= base_url(); ?>whitelist/delete/<?= $adv_id; ?>/<?= $ip; ?>"><i class="fa fa-close"></i></a></p>
                    
				<?php	
				}
				} ?>      
            
      </div>
    </div>
 
 
</body>
</html>
