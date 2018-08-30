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
                                <td>Pilih Ruang</td>
                                <td> <?php echo form_dropdown("Kd_Ruang",$option_ruang,$Kd_Ruang,"id='Kd_Ruang'"); ?></td>
                        </tr>
                        <tr>
                                <td>Merk</td>
                                <td><input type="text" name="Merk" value="<?php echo $Merk; ?>" id="Merk"></td>
                        </tr>
                        <tr>
                                <td>Type</td>
                                <td><input type=text name='Type' value="<?php echo $Type; ?>" id="Type"></td>
                        </tr>
                        <tr>
                                <td>Ukuran</td>
                                <td><input type=text name='CC' value="<?php echo $CC; ?>" id="CC"></td>
                        </tr>
                        <tr>
                                <td>Bahan</td>
                                <td><input type=text name='Bahan' value="<?php echo $Bahan; ?>" id="Bahan"></td>
                        </tr>
                        <tr>
                                <td>No Pabrik</td>
                                <td><input type=text name='Nomor_Pabrik' value="<?php echo $Nomor_Pabrik; ?>" id="Nomor_Pabrik"></td>
                        </tr>
                        <?php if($Kd_Aset1==2 AND $Kd_Aset2==3){ ?>
                        <tr id="jumlah_roda">
			             	<td>Jumlah Roda</td> <td> 
			             	  <?php
							  	$jlh_roda = array('' => 'Pilih Roda','2'=>'Roda Dua','3'=>'Roda Tiga','4'=>'Roda Empat','6'=>'Roda Enam','8'=>'Roda Delapan','10'=>'Roda Sepuluh');
								echo form_dropdown('Jumlah_Roda', $jlh_roda, $Jumlah_Roda,"id='Jumlah_Roda'");
							  ?>
							 </td>
			            </tr>
			            <?php } ?>
			            <?php if($Kd_Aset1==2 AND $Kd_Aset2==3){ ?>
			            <tr>
			             	<td>No. Rangka</td> <td><input type=text name='Nomor_Rangka' id="Nomor_Rangka"></td>
			            </tr>
			            <?php } ?>
			            <?php if($Kd_Aset1==2 AND $Kd_Aset2==3){ ?>
			            <tr>
			             	<td>No. Mesin</td> <td><input type=text name='Nomor_Mesin' id="Nomor_Mesin"></td>
			            </tr>
			            <?php } ?>
			            <?php if($Kd_Aset1==2 AND $Kd_Aset2==3){ ?>
			            <tr>
			             	<td>No. BPKB</td> <td><input type=text name='Nomor_BPKB' id="Nomor_BPKB"></td>
			            </tr>
			            <?php } ?>
			            <?php if($Kd_Aset1==2 AND $Kd_Aset2==3){ ?>
			            <tr>
			             	<td>No. Polisi</td> <td><input type=text name='Nomor_Polisi' id="Nomor_Polisi"></td>
			            </tr>
			            <?php } ?>
			            <?php if($Kd_Aset1==2 AND $Kd_Aset2==3){ ?>
			            <tr>
			             	<td>Nama Pemakai</td> <td><input type=text name='Pemakai' id="Pemakai"></td>
			          	</tr>
			          	<?php } ?>
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