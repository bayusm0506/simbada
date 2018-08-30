<div id="form_input" title="PILIH SKPD TUJUAN">
         <?php echo form_open('daily/submit'); ?>
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
                 <?php echo $this->load->view('adminweb/chain/bidang');?>
         </form> 
</div> 