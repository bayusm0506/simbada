<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Home - Centrino CMS</title>
	<link href="<?php echo base_url();?>media/css/adminstyle.css" rel="stylesheet" type="text/css" media="all" />
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/js/jqueryslidemenu.js"></script>
	<link rel="shortcut icon" href="../media/images/favicon.ico" />
	<!--[if lte IE 7]>
	<style type="text/css">
	html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
	</style>
	<![endif]-->
</head>
<body>
<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
        <img src="<?php echo base_url();?>media/images/logo.png" width="80" />
			<div id="top-navigation">
				Welcome <strong><?php echo $this->session->userdata('username');?></strong>
				<span>|</span>
				
				<a href="<?=base_url()?>" target="_blank">View Site</a>
				<span>|</span>
				<a href="<?=site_url('adminweb/help')?>">Help</a>
				<span>|</span>
				<a href="<?=site_url('adminweb/profile')?>">Profile Settings</a>
				<span>|</span>
				<?php echo anchor('login/logout', 'Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));?>
			</div>
		</div>
		<!-- End Logo + Top Nav -->

		<!-- Main Nav -->
		<div id="myslidemenu" class="jqueryslidemenu">
			<ul>
			   <?php echo $this->load->view('adminweb/menu');?>
			</ul>
		</div>
		<!-- End Main Nav -->
	</div>
</div>
<!-- End Header -->

<!-- Container -->
<div id="container">
	<div class="shell">
		<!-- Main -->
		<div id="main">
					
			<!-- Content -->
			<?php if($sidebar=='1'){ echo "<div id='content' style='width:750px;'>";
			}else { echo "<div id='content' style='width:990px;'>"; }?>	
				<!-- Box -->
				<div class="box">
					<!-- Box Head -->
					<div class="box-head">
						<h2 class="left"><?php echo $header?></h2>
					</div>
					<!-- End Box Head -->	

					<!-- Table -->
					<div class="tables">
						<?php echo $this->load->view($main_view);?>
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