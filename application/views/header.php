<?php $usr = $this->authpool->user(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Offer Scrapper</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap.min.css">
<link href="<?= base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/custom.css" rel="stylesheet">
<link href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet">
<link href="http://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" rel="stylesheet">



<!-- Latest compiled and minified JavaScript -->
<script src="<?= base_url(); ?>assets/jquery-1.11.0.min.js"></script>
<script src="<?= base_url(); ?>assets/bootstrap.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="http://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/jquery.dataTables.delay.min.js"></script>
<script src="<?= base_url(); ?>assets/jquery-ui.js"></script>

</head>

<body>

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Offer Scrapper</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     <ul class="nav navbar-nav">
            
            
          
             <li class="dropdown">
              <a href="<?= base_url(); ?>dash" >Home</a>
              
            </li>
          </ul>
         
      <ul class="nav navbar-nav navbar-right">
     
       
        <li><a href="<?= base_url(); ?>login/logout"> <i class="fa fa-sign-out"></i> Logout</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
