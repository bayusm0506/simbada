<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Home - SIMDA LOGIN</title>
	<link href="<?php echo base_url();?>media/css/adminstyle.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="shortcut icon" href="../media/images/favicon.ico" />
</head>
<body>
<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1><a href="#">Centrino CMS</a></h1>
			</div>
		<!-- End Logo + Top Nav -->
		
		<!-- Main Nav -->
		<div id="navigation">
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
			<div id='content' style='width:500px;'>	
				<!-- Box -->
				<div class="box">
					<!-- Box Head -->
					<div class="box-head">
						<h2 class="left">Login</h2>
					</div>
					<!-- End Box Head -->	

					<!-- Table -->
					<div class="table">
						<?=form_open()?>
						<!-- Form -->
						<div class="form">
						<?=isset($pesan) ? $pesan : '';?>
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr><td width ="170px">	<label>Username <span>(Required Field)</span></label></td>
									<td><input type="text" class="field size5"  name="username" placeholder="username" required autofocus /></td>
								</tr>
								<tr><td>	<label>Password <span>(Required Field)</span></label></td>
									<td><input type="password" class="field size5" name="password" placeholder="password" required /></td>
								</tr>
							</table>									
						</div>
						<!-- End Form -->
						
						<!-- Form Buttons -->
						<div class="buttons">
							<input type="submit" class="button" value="Login" />
						</div>
						<!-- End Form Buttons -->
					<?=form_close()?>
						
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

<!-- Footer -->
<div id="footer">
	<div class="shell">
		<span class="left">&copy; 2012 Centrino CMS</span>
		<span class="right">
			Developed by <a href="http://twitter.com/fatahabdella" target="_blank" title="Fatah Abdella Sutara">Fatah Abdella Sutara</a>
		</span>
	</div>
</div>
<!-- End Footer -->
	
</body>
</html>