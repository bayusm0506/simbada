<script type="text/javascript">
$(function() {
         $( "input[class^=MyDate]" ).datepicker({
                        changeMonth: true,
                        showAnim: 'slideDown',
                        dateFormat: "yy-mm-dd",
                        yearRange: '-116:+2',
                        changeYear: true
                });
});

</script>

<div id="form_input" title="Silahkan Isi data">
        <form method='POST' action="#">
				<input type=hidden name='Kd_Bidang' id='kd_bidang' value="">
				<input type=hidden name='Kd_Unit' id='kd_unit' value="">
				<input type=hidden name='Kd_Sub' id='kd_sub' value="">
				<input type=hidden name='Kd_UPB' id='kd_upb' value="">
				<input type=hidden name='Kd_Aset1' id='kd_aset1' value="">
				<input type=hidden name='Kd_Aset2' id='kd_aset2' value="">
				<input type=hidden name='Kd_Aset3' id='kd_aset3' value="">
				<input type=hidden name='Kd_Aset4' id='kd_aset4' value="">
				<input type=hidden name='Kd_Aset5' id='kd_aset5' value="">
				<input type=hidden name='No_Register' id='no_register' value="">
                 <table width='100%'>
                        <tr>
				            <td>Luas (M2)</td>
				            <td><input type=text name='Luas_M2' value="<?php echo $Luas_M2; ?>" id='Luas_M2' class="input-small" onkeyup="numericFilter(this);" onblur="if (this.value == '') {this.value = '0';}" onfocus="if (this.value == '0') {this.value = '';}">M2</td>
				        </tr>
				        <tr>
				        	<td>Alamat</td>
				        	<td><textarea name='Alamat' id='Alamat' style='width: 600px; height: 80px;'><?php echo $Alamat; ?></textarea></td>
				        </tr>
				        <tr>
				        	<td>Hak Tanah</td><td><?php
							  $option_hak = array('Hak Pakai' => 'Hak Pakai','Hak Pengelolaan'=>'Hak Pengelolaan');
							  
								echo form_dropdown('Hak_Tanah', $option_hak, '',"id='Hak_Tanah'");
							  ?>
							</td>
			            </tr>
			            <tr>
			            	<td>Tgl Sertifikat</td>
			            	<td><input type=text name='Sertifikat_Tanggal' value="<?php echo empty($Sertifikat_Tanggal) ? '' : tgl_ymd($Sertifikat_Tanggal) ?>" id="Sertifikat_Tanggal" size=10 readonly='readonly' class="MyDate required input-small"></td>
					    </tr>
					  	<tr>
					  		<td>Nomor Sertifikat</td>
					  		<td><input type=text name='Sertifikat_Nomor' value="<?php echo $Sertifikat_Nomor; ?>" id="Sertifikat_Nomor" size=30 ></td>
					    </tr>

					    <tr>
					  		<td>Penggunaan</td>
					  		<td><input type=text name='Penggunaan' value="<?php echo $Penggunaan; ?>" id="Penggunaan" size='120' class="input-xlarge" ></td>
					    </tr>
				        
				        <tr>
				            <td>Status Penilaian Harga</td>
				            <td>
				            	<?php
								  	$option_penilaian = array('0' => ' - Pilih Status - ','1'=>'Sudah Dinilai','2'=>'Belum Dinilai');
									echo form_dropdown('Kd_Data', $option_penilaian, $Kd_Data,"id='Kd_Data'");
							  	?>
						  	</td>
				        </tr>
			          	<tr>
					  		<td class='ket'>Keterangan</td><td></td>
					    </tr>
					    
			          	<tr>
						  	<td colspan="2">
						  		<textarea name='Keterangan' id='Keterangan' style='width: 98%; height: 80px;'><?php echo $Keterangan; ?></textarea>
						  	</td>
						</tr>
                </table>
         </form> 
</div> 