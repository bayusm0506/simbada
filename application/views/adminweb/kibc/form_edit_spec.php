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
				            <td>Luas Lantai (M2)</td>
				            <td><input type=text name='Luas_Lantai' value="<?php echo $Luas_Lantai; ?>" id='Luas_Lantai' class="input-small" onkeyup="numericFilter(this);" onblur="if (this.value == '') {this.value = '0';}" onfocus="if (this.value == '0') {this.value = '';}">M2</td>
				        </tr>
				        <tr>
				        	<td>Alamat</td>
				        	<td><textarea name='Lokasi' id='Lokasi' style='width: 600px; height: 80px;'><?php echo $Lokasi; ?></textarea></td>
				        </tr>
				        <tr>
				            <td>Bertingkat</td>
				            <td><?php
						  	$option_bertingkat = array('Bertingkat' => 'Bertingkat','Tidak'=>'Tidak');
							echo form_dropdown('Bertingkat_Tidak', $option_bertingkat, $Bertingkat_Tidak,"id='Bertingkat_Tidak'");
						  	?> 
						  	Beton/tidak
				            <?php
						  	$option_Beton = array('Beton' => 'Beton','Tidak'=>'Tidak');
						  
							echo form_dropdown('Beton_tidak', $option_Beton, $Beton_tidak,"id='Beton_tidak'");
						  	?></td>
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