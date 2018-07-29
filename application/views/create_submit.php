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
                
                	<div class="col-sm-12"><h4>You have an error during form submit </h4>
                    <h6>Please note it down for reference. </h6>
                    </div>
            <div class="col-sm-12"><hr/></div>
            
            </div>
            
            
            <div class="row">
                
                	<div class="col-sm-6">
           				 <div class="alert alert-danger" role="alert"><?=  $error; ?></div>
                       </div>
                 </div>
               <?php  } ?> 
               
               
                <?php if(!empty($success)){ ?>
            
            
            
            
            <div class="row">
                
                	<div class="col-sm-6">
           				 <div class="alert alert-success" role="alert"><b>Success!!</b> Offer Created Successfully.  <a href="<?= bse_url(); ?>dash">Click Here</a> to return Dashboard</div>
                       </div>
                 </div>
               <?php  } ?>        
            
           
			<div class="row">
            	<div class="col-sm-12">
                	<hr/>
                </div>
            </div>
             
                  
            
      </div>
    </div>
 
  
</body>
</html>
