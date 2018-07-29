<?php include('header.php'); ?>

<div class="container ">

    <div class="panel panel-default">
      <div class="panel-body">
      
      	
            <div class="" style="overflow:scroll;">
             <table class="table table-striped table-bordered table-hover table-responsive dataTable" id="dataTable">
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
                 
                  <tbody>
                  	<?php $i=1; foreach($records as $record): ?>
                    	<tr>
                        <td> <?= $i; ?></td>	
                        	<td><?= $record['ho']['OfferID']; ?></td>
                        <td><?= $record['ho']['OfferName']; ?></td>
                        <td><?php echo $record['ho']['AdvertiserName'].'(<b>#</b>'.$record['ho']['AdvertiserID'].', <b>Manager:</b> '.$record['ho']['AdvertiserManager'].')'; ?></td>
                        <td><?php echo $record['offer']->AdvertiserName.'(<b>#</b>'.$record['offer']->AdvertiserID.', <b>Manager:</b> '.$record['offer']->AdvertiserManager.')'; ?></td>
                         <td><?= $record['offer']->OfferID; ?></td>
                        <td><?= round($record['ho']['DefaultPayout'],2); ?></td>
                          <td><?= round($record['offer']->DefaultPayout,2); ?></td>
                       
                        </tr>
                    <?php $i++; endforeach; ?>
                  </tbody>
<tfoot></tfoot>
              </table>
			</div>                  
            
      </div>
    </div>
    <script type="text/javascript">
$(document).ready(function() {
    $('#dataTable').dataTable( {
		 "iDisplayLength": 50,
   		 "aLengthMenu": [[50, 100, 200, -1], [50, 100, 200,  "All"]]
        //"order": [[ 3, "desc" ]]
    } );
} );


</script>
    
</body>
</html>
