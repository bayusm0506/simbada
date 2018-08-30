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
                          <td>: <input type=text name='Tahun' class='input-small required' value="<?php echo $this->session->userdata('tahun_anggaran'); ?>" readonly/></td>
                  </tr>
                      <tr>
                    <td>Pilih KIB</td>
                          <td>: <?php
                          $data = array('' => '- Pilih -','1'=>'KIB A. Tanah','2'=>'KIB B. Peralatan dan Mesin','3'=>'KIB C. Gedung dan Bangunan','4'=>'KIB D. Jalan, Irigasi dan Jaringan','5'=>'KIB E. Aset Tetap Lainya','6'=>'KIB F. Konstruksi dalam Pekerjaan','7'=>'Aset Lainya');
                        echo form_dropdown('Kd_Aset1', $data, '');
                        ?></td>
                  </tr>
                      <tr>
                    <td>Minimal Satuan</td>
                          <td>: <input type=text name='Harga' size=15 id='harga'><span id='pesan2'></span></td>
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