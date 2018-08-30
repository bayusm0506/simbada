<div id="form_input" title="PILIH RUANG">
        <form method='POST' action="<?php echo $form_action; ?>">
				<input type=hidden value='' id="no" name="no">
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
                                <td> <?php echo form_dropdown("kd_ruang",$option_ruang,'',"id='idruang'"); ?></td>
                        </tr>
                </table>
         </form> 
</div> 