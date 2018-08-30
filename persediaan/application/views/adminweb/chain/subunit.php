Sub Unit :<br/>
   	<?php
    	echo form_dropdown("kd_sub_unit",$option_sub_unit,'',"id='idsubunit' style='width:100%'");
    ?>
   <script type="text/javascript">    
 	$('#idsubunit').change(function(){
     var t = $('#idsubunit').val();
     if (t != 0){
		 		var kd_sub_unit = {
								kd_sub_unit:$("#idsubunit").val(),kd_bidang: '<?php echo $kb;?>',kd_unit: '<?php echo $ku;?>'
								};
	    					$('#upb').attr("disabled",true);
							
							$.ajax({
									type: "POST",
									url : "<?php echo site_url('chain/select_upb')?>",
									data: kd_sub_unit,
									success: function(msg){
									$('#upb').html(msg);
								}
						});	
		 } else {
					alert ('Silahkan Pilih Unit');
		 }//end if
 }); //end change