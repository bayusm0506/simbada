Unit :<br/>
   	<?php
    	echo form_dropdown("kd_unit",$option_unit,'',"id='idunit' style='width:100%'");
    ?>
 <script type="text/javascript">    
 	$('#idunit').change(function(){
     var t = $('#idunit').val();
     if (t != 0){
		 		var kd_unit = {
								kd_unit:$("#idunit").val(),kd_bidang: '<?php echo $kb;?>'
								};
	    					$('#subunit').attr("disabled",true);
							
							$.ajax({
									type: "POST",
									url : "<?php echo site_url('chain/select_sub_unit')?>",
									data: kd_unit,
									success: function(msg){
									$('#subunit').html(msg);
								}
						});	
		 } else {
					alert ('Silahkan Pilih Unit');
		 }//end if
 }); //end change
 
 
 </script>