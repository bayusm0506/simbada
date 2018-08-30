<?php 
echo form_hidden("kd_bidang",$this->session->userdata('kd_bidang'));
echo form_hidden("kd_unit",$this->session->userdata('kd_unit'));
echo form_hidden("kd_sub_unit",$this->session->userdata('kd_sub_unit'));
echo form_dropdown("kd_upb",$option_upb,'',"id='idupb'"); ?>

