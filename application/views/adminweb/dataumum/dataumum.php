<?php
	
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
?>	
        <div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header">
                    <i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
                    <div class="panel-content">
<!-- Validation -->
                         <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku' class="form-horizontal">
                            <fieldset>
                                <legend><small>(silahkan isi data umum dengan benar)</small></legend>
                                
                                <table width='100%'>
          <input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $default['Kd_Bidang']; ?>">
          <input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $default['Kd_Unit']; ?>">
          <input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $default['Kd_Sub']; ?>">
          <input type=hidden name='Kd_UPB' id='kd_upb'value="<?php echo $default['Kd_UPB']; ?>">
    	<tr>
    	  <td>Tahun Angaran</td>
    	  <td><input type="text" name='Tahun' size="6" readonly value="<?php echo set_value('Tahun', isset($default['Tahun']) ? $default['Tahun'] : $this->session->userdata('tahun_anggaran')); ?>" />
          </td>
  	  </tr>
    	<tr>
    	  <td>Alamat</td>
    	  <td><textarea name="Alamat" id="textarea" cols="65" rows="2"><?php echo set_value('Alamat', isset($default['Alamat']) ? $default['Alamat'] : ''); ?></textarea></td>
  	  </tr>
    	<tr>
    	  <td>&nbsp;</td>
    	  <td>&nbsp;</td>
  	  </tr>
    	<tr>
    	  <td colspan="2"><strong>Pimpinan</strong></td>
   	  </tr>
    	<tr>
			<td width="15%">Nama</td>
            <td><input type="text" name='Nm_Pimpinan' size="65"  value="<?php echo set_value('Nm_Pimpinan', isset($default['Nm_Pimpinan']) ? $default['Nm_Pimpinan'] : ''); ?>"/></td>
		</tr>
        <tr>
			<td>NIP</td>
            <td><input type=text name='Nip_Pimpinan' size=30   value="<?php echo set_value('Nip_Pimpinan', isset($default['Nip_Pimpinan']) ? $default['Nip_Pimpinan'] : ''); ?>"/></td>
		</tr>
        <tr>
          <td>Jabatan</td>
          <td><input type="text" name='Jbt_Pimpinan' size="55"  value="<?php echo set_value('Jbt_Pimpinan', isset($default['Jbt_Pimpinan']) ? $default['Jbt_Pimpinan'] : ''); ?>"/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><strong>Pengurus Barang</strong></td>
        </tr>
        <tr>
          <td>Nama</td>
          <td><input type="text" name='Nm_Pengurus' size="65"   value="<?php echo set_value('Nm_Pengurus', isset($default['Nm_Pengurus']) ? $default['Nm_Pengurus'] : ''); ?>"/></td>
        </tr>
        <tr>
          <td>NIP</td>
          <td><input type="text" name='Nip_Pengurus' size="30"  value="<?php echo set_value('Nip_Pengurus', isset($default['Nip_Pengurus']) ? $default['Nip_Pengurus'] : ''); ?>" /></td>
        </tr>
        <tr>
          <td>Jabatan</td>
          <td><input type="text" name='Jbt_Pengurus' size="55"  value="<?php echo set_value('Jbt_Pengurus', isset($default['Jbt_Pengurus']) ? $default['Jbt_Pengurus'] : ''); ?>" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><strong>Penyimpan Barang</strong></td>
        </tr>
        <tr>
          <td>Nama</td>
          <td><input type="text" name='Nm_Penyimpan' size="65" value="<?php echo set_value('Nm_Penyimpan', isset($default['Nm_Penyimpan']) ? $default['Nm_Penyimpan'] : ''); ?>" /></td>
        </tr>
        <tr>
          <td>NIP</td>
          <td><input type="text" name='Nip_Penyimpan' size="30"  value="<?php echo set_value('Nip_Penyimpan', isset($default['Nip_Penyimpan']) ? $default['Nip_Penyimpan'] : ''); ?>" /></td>
        </tr>
        <tr>
          <td>Jabatan</td>
          <td><input type="text" name='Jbt_Penyimpan' size="55" value="<?php echo set_value('Jbt_Penyimpan', isset($default['Jbt_Penyimpan']) ? $default['Jbt_Penyimpan'] : ''); ?>" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><button type="submit" class="btn btn-success">Submit</button>
              <button type="reset" class="btn">Cancel</button></td>
        </tr>
       </table>
                            </fieldset>
                        </form>

       </div>
       </div>
       </div>
       </div>