<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo $title;?></title>
	
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jqueryslidemenu.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.validate-rules.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.ui.mouse.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.ui.draggable.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.ui.position.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.ui.resizable.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.ui.dialog.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.effects.core.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.effects.blind.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.effects.explode.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/i18n/grid.locale-en.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.jqGrid.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>media/js/myjs.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.pajinate.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>media/js/main.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.tooltip.js"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>media/css/jquery.tooltip/jquery.tooltip.css" type="text/css" />
    
    <link href="<?php echo base_url();?>media/css/dialog_style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo base_url();?>media/css/adminstyle.css" rel="stylesheet" type="text/css" media="all" />
    <link type="text/css" href="<?php echo base_url();?>media/js/themes/base/jquery.ui.all.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>media/css/sunny/jquery-ui-1.8.16.custom.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>media/css/ui.jqgrid.css" />
<!-- end jq-->
	<link rel="shortcut icon" href="<?php echo base_url();?>media/images/favicon.ico" />
	<!--[if lte IE 7]>
	<style type="text/css">
	html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
	</style>
	<![endif]-->
<style type="text/css">
#loading {
	position:absolute;
	width:100%;
	text-align:center;
	top:300px;
	z-index:1000;
	color:#F00;
	font-size:14px;
	font-family:Helvetica, sans-serif;
	font-weight: bold;
}
</style>
</head>
<body>
<!-- Header -->

<div id="header">
  <div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
        <span class="logo">
        <img src="<?php echo base_url();?>media/images/logo.png"  style="max-width:200px;"/>
        </span>
			<div id="top-navigation">
            Tahun Anggaran <?php echo $this->session->userdata('tahun_anggaran');?>
				| Welcome <strong><?php echo $this->session->userdata('username');?></strong>
				
				<span>|</span>
				<a href="#">Help</a>
				<span>|</span>
				<a href="#">Profile Settings</a>
				<span>|</span>
				<?php echo anchor('login/logout', 'Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));?>
			</div>
		</div>
		<!-- End Logo + Top Nav -->

		<!-- Main Nav -->
		<div id="myslidemenu" class="jqueryslidemenu">
			<ul>
			   <?php echo $this->load->view('adminweb/menu_sementara');?>
			</ul>
		</div>
		<!-- End Main Nav -->
	</div>
</div>
<!-- End Header -->
<span id="loading"></span>
<!-- Container -->
<div id="container">
	<div class="shell">
		<!-- Main -->
		<div id="main">
					
			<!-- Content -->
			<div id='content' style='width:990px;'>	
				<!-- Box -->
				<div class="box">
					<!-- Box Head -->
					<div class="box-head">
						<h2 class="left"><?php echo $header?></h2>
					</div>
					<!-- End Box Head -->	

					<!-- Table -->
					<div class="tables">
						<?php echo $contents ?>
					</div>
					<!-- Table -->
				</div>
				<!-- End Box -->

			</div>
			<!-- End Content -->
			

			
			<div class="cl">&nbsp;</div>			
		</div>
		<!-- Main -->
	</div>
</div>
<!-- End Container -->
	
</body>
</html>