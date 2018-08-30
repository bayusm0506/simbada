<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Chain Select With Codeigniter and Jquery</title>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/base/jquery-ui.css" type="text/css" media="all" />
		<link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/	css" media="all" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js" type="text/javascript"></script>
  <style type="text/css">
  #b {
	color: #F00;
}
  </style>
  </head>
  <body>
    <!-- page content -->
    <?php echo form_open('chain/submit');?>    
    Bidang : <br/>
    <?php
    	echo form_dropdown("kd_bidang",$option_bidang,"","id='id_bidang'");
    ?>
    
    <div id="unit">
    Unit :<br/>
   	<?php
    	echo form_dropdown("kd_unit",array('pilihunit'=>'Pilih Bidang Dahulu'),'','disabled');
    ?>
    </div>
    <div id="subunit">
    Sub Unit :<br/>
   	<?php
    	echo form_dropdown("kd_sub_unit",array('pilihunit'=>'Pilih Unit Dahulu'),'','disabled');
    ?>
    </div>
    
    <div id="upb">
    Unit :<br/>
   	<?php
    	echo form_dropdown("kd_upb",array('pilihunit'=>'Pilih Sub Unit Dahulu'),'','disabled');
    ?>
    </div>
    
    
    <?php echo form_submit("submit","Submit"); ?>
    <?php echo form_close(); ?>
    
    
    <script type="text/javascript">
		$("#id_bidang").change(function(){
	    		var bidang = $("#id_bidang").val();
	    		if (bidang != 0){    		
						var kd_bidang = {
											kd_bidang:$("#id_bidang").val()
										};
	    								$('#kd_unit').attr("disabled",true);
										
										$.ajax({
												type: "POST",
												url : "<?php echo site_url('chain/select_unit')?>",
												data: kd_bidang,
												success: function(msg){
													$('#unit').html(msg);
													//$('#unit').html(bidang);
												}
										});				
										
										
				}else{
					$("#kd_unit option").attr("disabled", true);
				}
	    });								   	
	 </script>

  </body>
</html>
