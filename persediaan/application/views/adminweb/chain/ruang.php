Ruang : 
   	<?php
    	echo form_dropdown("kd_ruang",$option_ruang,'',"id='idruang'");
    ?>

<script type="text/javascript">    
 	$('#idupb').change(function(){
     var t = $('#idupb').val();
     if (t != 0){
		 		var kd_sub_unit = {
				kd_bidang: '<?php echo $kb;?>',kd_unit: '<?php echo $ku;?>',kd_sub_unit:'<?php echo $kupb;?>',kd_upb: $("#idupb").val()
								};
	    					$('#ruang').attr("disabled",true);
							$.ajax({
									type: "POST",
									url : "<?php echo site_url('chain/select_ruang')?>",
									data: kd_sub_unit,
									success: function(msg){
									$('#ruang').html(msg);
								}
						});	
		 } else {
					alert ('Silahkan Pilih UPB');
		 }//end if
 }); //end change