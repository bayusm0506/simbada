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
      <td>Tahun</td>
            <td>: <input type=text name='Tahun' size=8 value="<?php echo $Tahun ?>" readonly='readonly' class="required input-mini"></td>
    </tr>

    <tr>
			<td>No SK</td>
            <td>: <input type=text name='No_SK' value="<?php echo $No_SK ?>" size=55 class='required'/></td>
		</tr>
    <tr>
			<td>Tanggal SK</td>
            <td>: <input type=text name='Tgl_SK' value="<?php echo tgl_ymd($Tgl_SK); ?>" size=10  id='datepicker' readonly='readonly' class="required input-small"></td>
		</tr>
    <tr><td class='ket'>Keterangan</td>  <td></td></tr>
    <tr><td colspan='2'><textarea name='Keterangan' maxlength="250" id='loko' style='width: 600px; height: 80px;'><?php echo $Keterangan ?></textarea></td></tr>

    <tr>
			<td colspan="2"><input type="submit" name="submit" class="submit btn btn-success" value="UPDATE" id="submit">
          <input type=button value=BATAL id='reset'  class="btn btn-danger" onclick=self.history.back() ></td>
		</tr>
       </table>
       </form>

       </div>
       </div>
       </div>
       </div>