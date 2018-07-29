<?php include('header.php'); ?>

<div class="container ">

    <div class="panel panel-default">
      <div class="panel-body">
            
 <form action="<?= current_url(); ?>" method="post" class="form-horizontal">
			<div class="col-sm-12 form-inline">
            		<?php if(!empty($success)){ ?>
                    <p>
                       
                         <label class="control-label warning">Records Updated Successfully!!</label>

                        <br/>
                        
                        
                        </p>
                     <?php } ?>
                    
                        <div class="form-group ">
                       
                         <label class="control-label">Enter One IP Address in one field:</label>
                        <br/>
                        <br/>
                        
                        
                        </div>
                       
                       
                     
                     
                    
            </div>
            
            <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEmail3">IP Addresses *</label>
                        <div class="col-sm-5">
                          <textarea name="ips" rows="3" class="form-control" required="required" placeholder="Each IP Seprated by new line"></textarea>
                        </div>
                      </div>
            
            <!--<div class="col-sm-12 form-inline" id="ip_input_fields">
                    
                        <div class="input_fields_wrap">
                            
                            <p><input type="text" placeholder="IP Address" name="ips[]" required="required" class="form-control"></div>
                        </p>
                        <h3><i class="add_field_button fa fa-plus "></i></h3>
                        <br/>
                        <br/>
                        <br/>
                        <button type="Submit"  class="btn btn-default">Submit</button>
                       
                   </div>
          
            
      </div>-->
     
      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button class="btn btn-default" type="submit">Submit</button>
                        </div>
                      </div>
      </form>
    </div>
 
 
 
 <!--<script>
 	$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<p><input type="text" placeholder="IP Address" name="ips[]" class="form-control"/>&nbsp;&nbsp;<a href="#" class="remove_field"><i class="fa fa-close "></i></a></p>'); //add input box
        }
    });
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('p').remove(); x--;
    })
});
 </script>-->
</body>
</html>
