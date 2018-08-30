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
                         <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
	<table width='100%'>
    	<tr>
			<td width="15%">Username</td>
            <td><input type=text name='username' size=25  class='required'/></td>
		</tr>
        <tr>
			<td>Password</td>
            <td><input type=text name='password' size=25  class='required'/></td>
		</tr>
        <tr>
        <td>UPB</td> 
        	<td> : 
			<?php echo $this->load->view('adminweb/chain/bidang'); ?>
            </td>
        </tr>
        
        <tr>
			<td>Nama</td>
            <td>: <input type=text name='nama_lengkap' size=55 class='required'/></td>
		</tr>
        <tr>
			<td>Email</td>
            <td>: <input type=text name='email' size=55/></td>
		</tr>
        <tr>
			<td>No Telp</td>
            <td>: <input type=text name='no_telp' size=15 id='jml_satuan'><span id='pesan2'></span></td>
		</tr>
        <tr>
			<td>Level</td>
            <td>: <?php echo $this->load->view('adminweb/level/level');?></td>
		</tr>

    <tr>
      <td>Jabatan</td>
            <td>: <?php echo form_dropdown("jabatan_id",$option_jabatan,"","id='id_jabatan'");  ?></td>
    </tr>

        <tr>
			<td colspan="2"><input type="submit" name="submit" class="submit btn btn-success" value="simpan" id="submit">
          <input type=button value=BATAL id='reset'  class="btn btn-danger" onclick=self.history.back() ></td>
		</tr>
       </table>
       </form>

       </div>
       </div>
       </div>
       </div>