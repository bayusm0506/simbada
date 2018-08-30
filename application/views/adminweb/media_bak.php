<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Centrino CMS</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="<?php echo base_url();?>media/css/adminstyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
</script>
</head>
<body>
<div id="header">
	<div id="menu">
      <ul>
	  <?php echo $this->load->view('adminweb/menu');?>
      </ul>
	    <p>&nbsp;</p>
 	</div>

  <div id="content">
		<?php echo $this->load->view($main);?>
  </div>
  
		<div id="footer">
			Copyright &copy; 2012 by Centrino CMS. All rights reserved.
		</div>
</div>
</body>
</html>