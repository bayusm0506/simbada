<script type="text/javascript">
    $(document).ready(function(){
		
        $("#Harga").keyup(function(){
            var Harga = $("#Harga").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#Jumlah").keyup(function(){
            var Jumlah = $("#Jumlah").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#percepatan").keyup(function(){
            var Harga = $("#percepatan").val();
            this.value = this.value.replace(/[^0-9]/,'');
        });
		
        $('form').submit(function(e){
            var kd_aset1    = $("#kd_aset1").val();
            var datepicker  = $("#datepicker").val();
            var datepicker3 = $("#datepicker3").val();
            var No_Register = $("#No_Register").val();
            var Jumlah      = $("#Jumlah").val();
            var Harga       = $("#Harga").val();
           
		    if(kd_aset1 == ''){
                alert("Nama aset belum diisi !");
                return false;
            }
			
			 if(No_Register == ''){
                alert("Kode register belum diisi, silahkan isi kembali nama aset !");
                return false;
            }
			
			 if(datepicker == '' && datepicker3 == ''){
                alert("Silahkan isi tanggal Kontrak atau Tanggal Kwitansi terlebih dahulu !");
                return false;
            }
			
			 if(Harga == '' || Harga == ''){
                alert("Harga Pembelian atau Jumlah belum diisi !");
                return false;
            }
        });
    });
	
</script>
      

<?php

if ($mode == "update") {
   $Kd_Bidang       = $get->Kd_Bidang; 
   $Kd_Unit         = $get->Kd_Unit;
   $Kd_Sub          = $get->Kd_Sub;
   $Kd_UPB          = $get->Kd_UPB;
   $No_ID           = $get->No_ID;
   $Uraian_Kegiatan = $get->Uraian_Kegiatan;
   $No_Kontrak      = $get->No_Kontrak;
   $Tgl_Kontrak     = tgl_ymd($get->Tgl_Kontrak);
   $No_Kuitansi     = $get->No_Kuitansi;
   $Tgl_Kuitansi    = tgl_ymd($get->Tgl_Kuitansi);
   $Jumlah          = $get->Jumlah;
   $Harga           = nilai($get->Harga);
   $Dipergunakan    = $get->Dipergunakan;
   $Keterangan      = $get->Keterangan;	
} else {
   $Kd_Bidang       = $this->session->userdata('addKd_Bidang'); 
   $Kd_Unit         = $this->session->userdata('addKd_Unit');
   $Kd_Sub          = $this->session->userdata('addKd_Sub');
   $Kd_UPB          = $this->session->userdata('addKd_UPB');
   $No_ID           = '';
   $Uraian_Kegiatan = '';
   $No_Kontrak      = '';
   $Tgl_Kontrak     = '';
   $No_Kuitansi     = '';
   $Tgl_Kuitansi    = '';
   $Jumlah          = '';
   $Harga           = '';
   $Dipergunakan    = '';
   $Keterangan      = '';
}
?>
        

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
          <input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $Kd_Bidang; ?>">
          <input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $Kd_Unit; ?>">
          <input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $Kd_Sub ?>">
          <input type=hidden name='Kd_UPB' id='kd_upb' value="<?php echo $Kd_UPB ?>">
		  <tr>
		  	<td class='ket'>Uraian Kegiatan</td><td> : <textarea name='Uraian_Kegiatan' maxlength="250" placeholder="Ketik disini uraian kegiatan" style='width: 600px; height: 80px;'><?php echo $Uraian_Kegiatan; ?></textarea></td>
		  </tr>
		 
          <tr>
           	<td>No Kontrak</td>    <td> : <input type=text name='No_Kontrak' value="<?php echo $No_Kontrak ?>" size=30 ></td>
          </tr>
          <tr><td>Tgl Kontrak/DPA/SPM</td><td> : <input type=text name='Tgl_Kontrak' value="<?php echo $Tgl_Kontrak ?>" size=10  id='datepicker' readonly='readonly' class="required input-small"></td>
            </tr>
          <tr><td>No Kuitansi/SP2D</td>    <td> : <input type=text name='No_Kuitansi' value="<?php echo $No_Kuitansi ?>" size=30 ></td>
            </tr>
          <tr><td>Tgl Kuitansi/SP2D</td><td> : <input type=text name='Tgl_Kuitansi' value="<?php echo $Tgl_Kuitansi ?>" size=10  id='datepicker2' readonly='readonly' class="required input-small"></td>
         
		  <tr>
          <td>Harga</td>    
          <td> : <input type=text name='Harga' value="<?php echo $Harga ?>" size=20 id="Harga" class="required number">
          <span id='pesan'></span></td>
		    </tr>
          <tr><td class='ket'>Dipergunakan pada Unit</td>  <td></td>
		    </tr>
		  <tr><td colspan='2'><textarea name='Dipergunakan' maxlength="500" id='loko' style='width: 800px; height: 40px;' placeholder="Ketik disini. Pisahkan dengan tanda koma (,) [max 250 karakter]"><?php echo $Dipergunakan; ?></textarea></td></tr>
		  <tr><td class='ket'>Keterangan</td>  <td></td>
		    </tr>
		  <tr><td colspan='2'><textarea name='Keterangan' maxlength="250" id='loko' style='width: 800px; height: 80px;'><?php echo $Keterangan; ?></textarea></td></tr>         
          <tr>
          	<td colspan=2>
          		<input type="submit" name="submit" class="submit" value="SIMPAN" id="submit">
          		<input type=button value=BATAL id='reset' onclick=self.history.back() >
          	</td>
          </tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>
