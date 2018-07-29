<?php include('header.php'); ?>

<div class="container ">

    <div class="panel panel-default">
      <div class="panel-body">
      
       <div style=" overflow:scroll">
             <table class="table table-striped table-bordered table-hover table-responsive dataTable" id="dataTable">
                  <thead>
                  	<th>
       
                        <td>Offer ID</td>
                        <td>Offer Name</td>
                        <td>Campaigns</td>
                        
                        
                    </th>
                  </thead>
                 
                  <tbody>
                  <?php $i = 1; foreach($offers as $offer): ?>
                  	<tr>
                    <td><?= $i; ?></td>
                    	<td><?= $offer->id; ?></td>
                        <td><?= $offer->name; ?></td>
                        <td>
                        
                        <?php if(count($offer->campaigns->campaign)>1){ ?>
                        
							<?php foreach($offer->campaigns->campaign as $camp): ?>
                            <b>Platform: </b><?= $camp->platform; ?>, <b>Payout: </b> <?= $camp->payout; ?>, <b>Incent: </b> <?= $camp->incent; ?>, <b>Countries: </b> <?php if(count($camp->countries->country)>1){ foreach($camp->countries->country as $con): echo $con.','; endforeach; }else{ echo $camp->countries->country;  } ?> <br/>
                            <?php endforeach; 
						}else{ ?>
							 <b>Platform: </b><?= $offer->campaigns->campaign->platform; ?>, <b>Payout: </b> <?= $offer->campaigns->campaign->payout; ?>, <b>Incent: </b> <?= $offer->campaigns->campaign->incent; ?>, <b>Countries: </b> <?php if(count($offer->campaigns->campaign->countries->country)>1){ foreach($offer->campaigns->campaign->countries->country as $con): echo $con.','; endforeach; } else { echo $offer->campaigns->campaign->countries->country; } ?> <br/>
						<?php }?>
                        <b>Icon: </b> <a href="<?= $offer->icon_url; ?>" target="_blank">View</a><br/>
                        <b>Tracking URL: </b> <?= $offer->tracking_url; ?><br/>
                           
                        </td>
                    </tr>
                    <?php $i++; endforeach; ?>
                  </tbody>

              </table>
			</div>      
   </div>
   </div>
   </div>
   
   <script type="text/javascript">
   $(document).ready(function()
{
	var tableData = $('#dataTable').dataTable({
		"iDisplayLength": 100,
    "aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]]
		
	});
});
   </script>
  </body>
  </html>