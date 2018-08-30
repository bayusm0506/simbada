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
          <table  width='100%'>
          <input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $default['Kd_Bidang']; ?>">
          <input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $default['Kd_Unit']; ?>">
          <input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $default['Kd_Sub']; ?>">
          <input type=hidden name='Kd_UPB' id='kd_upb' value="<?php echo $default['Kd_UPB']; ?>">
          <tr>
            <td>Kode Ruang</td>      <td> : 
              <input type=text name='Kd_Ruang' size=5 class="required input-mini" value="<?php echo $default['Kd_Ruang']; ?>"></td>
          </tr>
		  <tr>
		    <td>Nama Ruang</td>    <td> : <input type=text name='Nm_Ruang' size=60 value="<?php echo $default['Nm_Ruang']; ?>" ></td>
		    </tr>
		  <tr><td colspan=3><input type="submit" name="submit" class="submit btn btn-success" value="UPDATE" id="submit">
          <input type=button value=BATAL id='reset' onclick=self.history.back() class="submit btn btn-danger" ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>