 <script type="text/javascript">
    $(document).ready(function(){
		
        $("#Harga").keyup(function(){
            var Harga = $("#Harga").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		
        $('form').submit(function(e){
        
        var kd_riwayat = $("#kd_riwayat ").val();  
        var datepicker = $("#datepicker").val();
        var No_Dokumen = $("#No_Dokumen").val();
        var Harga      = $("#Harga").val();


       if(kd_riwayat == ''){
                alert("Silahkan riwayat !");
                return false;
            }
           		
			 if(datepicker == ''){
                alert("Tanggal Dokumen belum diisi !");
                return false;
            }

       if(No_Dokumen == ''){
                alert("Nomor dokumen wajib diisi !");
                return false;
            }

        });
    });
</script>
 
<script type="text/javascript">
$(function() {
    $('#kd_riwayat').change(function() {
        var n = $("#kd_riwayat").val();
        if (n == 1){
             $(".att_all").hide("slow");
            $("#id_kondisi").show("slow");
        }else if (n == 2){
            $(".att_all").hide("slow");
            $("#id_harga").show("slow");
            $("#id_masa_manfaat").show("slow");
        }else if (n == 5 || n == 6 || n == 7 || n == 21 || n == 23){
            $(".att_all").hide("slow");
            $("#id_harga").show("slow");
        }else if ($("#kd_riwayat").val() == 3){
            $(".att_all").hide("slow");
            $("#id_skpd").show("slow");
        }else if ($("#kd_riwayat").val() == 4){
            $(".att_all").hide("slow");
            $("#id_ruang").show("slow");
        }else if ($("#kd_riwayat").val() == 5){
            $("#id_kondisi").show("slow");
        }else if ($("#kd_riwayat").val() == 9){ /*pinjam pakai*/
            $(".att_all").hide("slow");
            $("#id_pinjam").show("slow");
            $("#id_kembali").show("slow");
        }else{
            $(".att_all").hide("slow");
        }

    });
});
    
</script>

        
<style type="text/css">
       label.error {
           color: red; padding-left: .5em;
       }
</style>

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
                    <i class="icon-tasks"></i> <?php echo ! empty($header) ?  $header: ''; ?></div>
                    <div class="panel-content">
                <!-- Validation -->
                          <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
                          
                              <table  width='100%'>
          
                                    <tr><td>Nama Aset</td>      <td>
                                      <b style='font-size: 18px;'><?php echo ! empty($namaaset) ?  $namaaset: ''; ?></b><br><br>
                                      <?php echo ! empty($Merk) ?  $Merk.' / '.$Type: ''; ?>
                                      <br>
                                      Tahun Perolehan <?php echo thn($Tgl_Perolehan); ?>
                                      <br>
                                      <?php echo $Keterangan; ?>
                                      <br>
                                      <?php echo "Rp. ".rp($Harga); ?>
                                      </td>
                                    </tr>

                                    <tr><td colspan="2"><hr></td></tr>

                                <tr>
                                    <td>Riwayat</td>
                                    <td> : 
                                    <?php echo form_dropdown('Kd_Riwayat', $option_q, '','class="input-large required" id="kd_riwayat"'); ?>
                                    <?php echo form_error('Kd_Riwayat', '<p class="field_error">', '</p>');?>
                                    </td>
                                </tr>

                                <tr>
                                      <td>Tgl Dokumen</td>
                                      <td> : <input type=text name='Tgl_Dokumen' size=10 id='datepicker' value="<?php 
                                 $y = $this->session->userdata('tahun_anggaran');
                                 echo date("$y-m-d");?>" readonly='readonly' class="input-small">
                                      </td>
                                    </tr>
                                <tr>
                                  <td>No Dokumen</td>
                                  <td> : <input type=text name='No_Dokumen' size=15 required ></td>          
                                </tr>
                                
                                <tr id="id_skpd" class="att_all" style="display:none;">
                                        <td>SKPD TUJUAN</td>
                                        <td>
                                         <?php
                                            $this->load->view('adminweb/chain/bidang');
                                         ?>
                                        </td>
                                </tr>

                                <tr id="id_kondisi" class="att_all" style="display:none;">
                                        <td>Kondisi</td>
                                        <td>:
                                        <?php
                                        $kondisi = array('1' => 'Baik','2'=>'Kurang Baik','3'=>'Rusak Berat');
                                        echo form_dropdown('Kondisi', $kondisi, '','class="input-medium required"');
                                        ?> 
                                        </td>
                                </tr>
                                
                                <tr id="id_pinjam" class="att_all" style="display:none;">
                                          <td>Tanggal Pinjam</td>
                                          <td> : <input type=text name='Tgl_Mulai' id='datepicker2' readonly='readonly' class="input-small"></td>
                                </tr>

                                <tr id="id_kembali" class="att_all" style="display:none;">
                                  <td>Tanggal Kembali</td>
                                  <td> : <input type=text name='Tgl_Selesai' id='datepicker3' readonly='readonly' class="input-small"></td>
                                </tr>
                                
                                <tr id="id_harga" class="att_all" style="display:none;">
                                    <td>Nilai</td>    
                                    <td> : <input type=text name='Harga' value="" size=20 id="Harga" class="required number">
                                    <span id='pesan'></span></td>
                                </tr>

                                <tr id="id_masa_manfaat" class="att_all" style="display:none;">
                                    <td>Masa Manfaat</td>    
                                    <td> :
                                        <?php

                                        $mf = array('1'=>'Bertambah','2'=>'Tidak Bertambah');
                                        echo form_dropdown('Masa_Manfaat', $mf, '','class="input-medium required"');
                                        ?> 
                                    </td>
                                </tr>

                                <tr>
                                  <td class='ket'>Keterangan</td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td colspan='3'><textarea name='Keterangan' style='width: 600px; height: 80px;'></textarea></td>
                                </tr>
                                    
                                <tr>
                                  <td colspan=3>
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
