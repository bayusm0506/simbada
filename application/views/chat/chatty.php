<?php
session_start();
$_SESSION['username'] = "babydoe" // Must be already set
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/loose.dtd" >
<html>
<head>
<title>Sample Chat Application</title>
<style>
body {
	font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
	font-size:11px;
}
</style>

<link type="text/css" rel="stylesheet" media="all" href="<?php echo base_url()?>chat/css/chat.css" />
<script type="text/javascript" src="<?php echo base_url()?>chat/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>chat/js/chat.js"></script>
<!--[if lte IE 7]>
<link type="text/css" rel="stylesheet" media="all" href="<?php echo base_url()?>chat/css/screen_ie.css" />
<![endif]-->

</head>
<body>
<div id="main_container">

<a href="javascript:void(0)" onClick="javascript:chatWith('johndoe')">Chat With John Doe</a>
<a href="javascript:void(0)" onClick="javascript:chatWith('babydoe')">Chat With Baby Doe</a>
<!-- YOUR BODY HERE -->

</div>

</body>
</html>