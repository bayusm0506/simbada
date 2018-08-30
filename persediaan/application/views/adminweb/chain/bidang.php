 <script>
	$(document).ready(function(){
			$("#id_bidang").change(function(data){
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
												}
										});				
										
										
				}else{
					$("#kd_unit option").attr("disabled", true);
				}
	    
				})
	});
	</script> 
 Bidang : <br/>
			<?php echo form_dropdown("kd_bidang",$option_bidang,"","id='id_bidang' style='width:100%'");  ?>
             <div id="unit">
                Unit :<br/>
                <?php
                    echo form_dropdown("kd_unit",array('pilihunit'=>'Pilih Bidang Dahulu'),'',"style='width:100%' disabled");
                ?>
                </div>
                <div id="subunit">
                Sub Unit :<br/>
                <?php
                    echo form_dropdown("kd_sub_unit",array('pilihunit'=>'Pilih Unit Dahulu'),'',"style='width:100%' disabled");
                ?>
                </div>
                
                <div id="upb">
                UPB :<br/>
                <?php
                    echo form_dropdown("kd_upb",array('pilihunit'=>'Pilih Sub Unit Dahulu'),'',"style='width:100%' disabled");
                ?>
                </div>