<?php
session_start();
$_SESSION['username'] = $this->session->userdata('username'); // Must be already set
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="utf-8" />
    <title><?php echo isset($title) ? "SIMBADA Online | ".$title : "SIMBADA ONLINE"; ?></title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0" /> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> -->
    <meta name="description" content="" />
    <meta name="author" content="CV. SANJAYA MEDIATAMA" />

    <link href="<?php echo base_url() ?>asset/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>asset/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>asset/css/jasny-bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>asset/css/jasny-bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>asset/css/font-awesome.css" rel="stylesheet" />

    <style type="text/css">#loading {position: fixed; left: 50%; top: 20%; z-index: 9999; } .preload-wrapper {z-index:9999999999; position: fixed; top:0; left: 0; right: 0; bottom:0; background:#fff; overflow: hidden; } #preloader5{position:relative; width:30px; height:30px; margin:23% auto; background:#3498db; border-radius:50px; animation: preloader_5 1.5s infinite linear; -webkit-animation: preloader_5 1.5s infinite linear; } #preloader5:after{position:absolute; width:50px; height:50px; border-top:10px solid #9b59b6; border-bottom:10px solid #9b59b6; border-left:10px solid transparent; border-right:10px solid transparent; border-radius:50px; content:''; top:-20px; left:-20px; animation: preloader_5_after 1.5s infinite linear; -webkit-animation: preloader_5_after 1.5s infinite linear; } @keyframes preloader_5 {0% {transform: rotate(0deg);} 50% {transform: rotate(180deg);background:#2ecc71;} 100% {transform: rotate(360deg);} } @keyframes preloader_5_after {0% {border-top:10px solid #9b59b6;border-bottom:10px solid #9b59b6;} 50% {border-top:10px solid #3498db;border-bottom:10px solid #3498db;} 100% {border-top:10px solid #9b59b6;border-bottom:10px solid #9b59b6;} } @-webkit-keyframes preloader_5 {0% {-webkit-transform: rotate(0deg);} 50% {-webkit-transform: rotate(180deg);background:#2ecc71;} 100% {-webkit-transform: rotate(360deg);} } @-webkit-keyframes preloader_5_after {0% {border-top:10px solid #9b59b6;border-bottom:10px solid #9b59b6;} 50% {border-top:10px solid #3498db;border-bottom:10px solid #3498db;} 100% {border-top:10px solid #9b59b6;border-bottom:10px solid #9b59b6;} } </style>
    
    <script src="<?php echo base_url() ?>asset/js/jquery-1.7.2.min.js"></script>
    <script src="<?php echo base_url() ?>asset/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>asset/js/jquery.flot.js"></script>
    <script src="<?php echo base_url() ?>asset/js/jquery.flot.pie.js"></script>
    <script src="<?php echo base_url() ?>asset/js/jquery.flot.resize.js"></script>
    <script src="<?php echo base_url() ?>asset/js/jquery.sparkline.min.js"></script>
    <script src="<?php echo base_url() ?>asset/js/sparkline.sanjaya.js"></script>
    <script src="<?php echo base_url() ?>asset/js/sanjaya.js"></script>
    <script src="<?php echo base_url() ?>asset/js/jquery.maskMoney.min.js"></script>
    <script src="<?php echo base_url() ?>asset/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">var base_url = '<?php echo base_url();?>';</script>
    <script type="text/javascript" charset="utf-8">
        function back() {
          if (1 < history.length) {
            history.back();
            return false;
          }
          return true;
        }
  	  
  	  function next() {
          if (1 < history.length) {
            history.forward();
            return false;
          }
          return true;
        }
      </script>
    <script type="text/javascript"> function numericFilter(txb) {txb.value = txb.value.replace(/[^\0-9]/ig, ""); } </script>
    
    <link href="<?php echo base_url() ?>asset/css/bootstrap-datepicker.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url() ?>asset/fa/css/font-awesome.min.css">
    
  	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery-ui-1.8.16.custom.min.js"></script>
  	<script type="text/javascript" src="<?php echo base_url();?>media/js/i18n/grid.locale-en.js"></script>
  	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.jqGrid.min.js"></script>

  	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>media/css/smoothness/jquery-ui-1.10.4.custom.min.css" />
  	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>media/css/ui.jqgrid.css" />
      
    
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo base_url()?>chat/css/chat.css" />
    <script type="text/javascript" src="<?php echo base_url()?>chat/js/chat.js"></script>

    <link href="<?php echo base_url() ?>asset/css/admin.css" rel="stylesheet" />

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo base_url() ?>asset/img/ico/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url() ?>asset/img/ico/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url() ?>asset/img/ico/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url() ?>asset/img/ico/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url() ?>asset/img/ico/apple-touch-icon-57-precomposed.png" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
  </head>

<body>

<div class="preload-wrapper">
<div id="preloader5"></div>
</div>

<div id="top-strip">
    <div class="container">
        <div class="row">
            <div class="offset8 span4">
                <div class="pull-right">
                    <a href="#">T.A. <?php echo ucfirst($this->session->userdata('tahun_anggaran'));?></a> |
                    <a href="<?php echo base_url()?>login/logout"><i class="fa fa-sign-out"></i>SIGN OUT</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logo-strip">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="logo">
                    <img src="<?php echo base_url() ?>asset/img/simbada-full.png" height="40" />
                </div>
            </div>
        </div>
    </div>
</div>

<div id="nav-strip">

  <div class="container">
        <div class="row">
            <div class="span12">

              <div class="navbar">
                <div class="navbar-inner">
                  <div class="container">
                     <?php $this->load->view('menu'); ?>
                  </div>
                </div>
              </div>

            </div>
        </div>
    </div>

</div>

<div id="subnav-strip">

    <div class="container">
        <div class="row">
            <div class="span12">
           		
                  <div class="container" style="padding-top:5px;">
                    <a onclick="return back();" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>asset/img/back-button.png"></a>
                    <!-- <a onclick="return back();" href="<?php echo $this->session->userdata('last_url')?>">DATA</a> -->
                    <div class="pull-right"><a onclick="return next();" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>asset/img/next.png"></a></div>
                  </div>
           
            </div>
        </div>
    </div>

</div>

<div id="content">
    <div class="container">
    <div id="loading"></div>
   	 <?php echo $contents ?>
    </div>
</div>

<?php $this->load->view('footer');?>

<script type="text/javascript">
$(window).load(function() { $(".preload-wrapper").fadeOut("slow"); })
</script>

</body>
</html>
