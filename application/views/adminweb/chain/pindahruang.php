<div id="form_ruang" title="PILIH RUANG TUJUAN">
         <?php echo form_open('daily/submit'); ?>
                 <input type=hidden value='' id="no" name="no">
                 <input type=hidden name='Kd_Aset1' id='kd_aset1' value="">
                 <input type=hidden name='Kd_Aset2' id='kd_aset2' value="">
                 <input type=hidden name='Kd_Aset3' id='kd_aset3' value="">
                 <input type=hidden name='Kd_Aset4' id='kd_aset4' value="">
                 <input type=hidden name='Kd_Aset5' id='kd_aset5' value="">
                 <input type=hidden name='No_Register' id='no_register' value="">
                 <?php echo form_dropdown("kd_ruang",$option_ruang,'',"id='idruang' class='span4'"); ?>
         </form> 
</div> 