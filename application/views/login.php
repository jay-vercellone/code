<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>RainyDayMarketing</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap.min.css">
<link href="<?= base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/custom.css" rel="stylesheet">


<!-- Latest compiled and minified JavaScript -->
<script src="<?= base_url(); ?>assets/jquery-1.11.0.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap.min.js"></script>
</head>

<body>


<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
   

   
  </div><!-- /.container-fluid -->
</nav>

<div class="container ">

<div class="row">
      	<div class="col-sm-6 col-sm-offset-3">
        	<img src="<?= base_url(); ?>assets/logo.gif" />
        </div>
      </div>
      
      <div class="row">
      	<div class="col-sm-12">
        	<br/>
        </div>
      </div>

    <div class="panel panel-default">
      <div class="panel-body">
      <?php if(!empty($error)){ ?>
      <div class="alert alert-danger" role="alert"><?= $error; ?></div>
      <?php } ?>		
      
      <form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>">
  <div class="form-group">
    <label for="inputEmail3" class=" col-sm-offset-1 col-sm-2 control-label">Email</label>
    <div class="col-sm-5">
      <input type="email" class="form-control" id="inputEmail3" name="email" required="required" placeholder="Email">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-offset-1 col-sm-2 control-label">Password</label>
    <div class="col-sm-5">
      <input type="password" class="form-control" id="inputPassword3" name="password" required="required" placeholder="Password">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
</form>
      
      
      		
      
      
      
      
      		

              </div>

</div><!-- panel  -->

</div><!-- container off -->


</body>
</html>
