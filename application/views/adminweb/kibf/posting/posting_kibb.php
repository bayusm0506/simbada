 <script type="text/javascript">
    $(document).ready(function(){
		
        $("#Harga").keyup(function(){
            var Harga = $("#Harga").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#Luas_M2").keyup(function(){
            var Luas_M2 = $("#Luas_M2").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#percepatan").keyup(function(){
            var Harga = $("#percepatan").val();
            this.value = this.value.replace(/[^0-9]/,'');
        });
		
        $('form').submit(function(e){
            var No_Register = $("#No_Register").val();
            var Harga       = $("#Harga").val();


            var datepicker  = $("#datepicker").val();
            var datepicker2 = $("#datepicker2").val();
                 
            if(datepicker == '' || datepicker2 == ''){
                alert("Silahkan Isi Tanggal !");
                $("#datepicker").focus();
                return false;
            }

            if (datepicker > datepicker2){
                  alert("Tanggal perolehan tidak boleh lewat dari tanggal pembukuan !");
                  $("#datepicker2").focus();
                  return false;
            }
			
  			 if(No_Register == ''){
                  alert("Kode register belum diisi, silahkan isi kembali nama aset !");
                  return false;
              }
  			
  			 if(Harga == ''){
                  alert("Harga Pembelian belum diisi !");
                  return false;
              }
        });
    });
	
</script>

<body style="font-size: 9pt;">
		<!-- Untuk Dialog yg akan munculkan dari Tombol Search Kode -->
		<div id="kode-dialog" class="" style="display: none; font-size: 10pt">
			<table id="tkodebarang" class="scroll" cellpadding="0" cellspacing="0"></table> 
			<div id="pkodebuku" class='ui-widget-header ui-corner-bl ui-corner-br' style="margin-top:0px;"></div>
		</div>
	</body>

        
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
                    <i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
                    <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo base_url().'pengadaan/kibb_process'; ?>" name='fadd' id='formku'>
          <table  width='100%'>
          <tr><td>Kode Pemilik</td>      <td> : 
          <?php
			echo form_dropdown('Kd_Pemilik', $option_pemilik, 11);
		  ?>
          <?php echo form_error('Kd_Pemilik', '<p class="field_error">', '</p>');?>
          </td>
          </tr>
		  <tr><td>Kode Aset</td>    <td>  
          <div class="form-inline"> :
      		  <input type=text name='kd_aset1' size=2 id='kd_aset1' readonly='readonly' value="<?php echo $Kd_Aset1 ?>" class="input-mini required">
      		  <input type=text name='kd_aset2' size=2 id='kd_aset2' readonly='readonly' value="<?php echo $Kd_Aset2 ?>" class="input-mini required">
      		  <input type=text name='kd_aset3' size=2 id='kd_aset3' readonly='readonly' value="<?php echo $Kd_Aset3 ?>" class="input-mini required">
      		  <input type=text name='kd_aset4' size=2 id='kd_aset4' readonly='readonly' value="<?php echo $Kd_Aset4 ?>" class="input-mini required">
      		  <input type=text name='kd_aset5' size=2 id='kd_aset5' readonly='readonly' value="<?php echo $Kd_Aset5 ?>" class="input-mini required">
            <input type=text name='Nm_Aset5' size=150 id='Nm_Aset5' readonly='readonly' value="<?php echo Nama_Aset($Kd_Aset1,$Kd_Aset2,$Kd_Aset3,$Kd_Aset4,$Kd_Aset5); ?>" placeholder="ketik nama barang disini !" class="input-large">
          </div>
          </td>
		    </tr>
           <tr><td>No Register</td><td> : 
           <span id='register'>
		       <input type=text name='No_Register' value="<?php echo KIBB_lastRegister($Kd_Prov,$Kd_Kab_Kota,$Kd_Bidang,$Kd_Unit,$Kd_Sub,$Kd_UPB,$Kd_Aset1,$Kd_Aset2,$Kd_Aset3,$Kd_Aset4,$Kd_Aset5); ?>" size=5 id="No_Register" class="required input-mini" readonly='readonly'>
		   </span>
              <span id="msg"></span>     
           </td>
           </tr>
           <tr><td>Ruang</td>      <td> : 
	          <?php
				echo form_dropdown('Kd_Ruang', $option_ruang, '');
			  ?>
	          <?php echo form_error('Kd_Ruang', '<p class="field_error">', '</p>');?>
	          </td>
          </tr>
          <tr><td>Tgl Pembelian</td><td> : <input type=text name='Tgl_Perolehan' value="<?php echo tgl_ymd($Tgl_Kontrak) ?>" size=10  id='datepicker' readonly='readonly' class="required input-small"></td>
            </tr>
           <tr><td>Tgl Pembukuan</td><td> : <input type=text name='Tgl_Pembukuan' size=10 id='datepicker2' value="<?php 
		   $y = $this->session->userdata('tahun_anggaran');
		   echo date("$y-m-d");?>" readonly='readonly' class="input-small"></td>
            </tr>
          <tr><td>Merk</td>    <td> : <input type=text name='Merk' size=15 ></td>
            </tr>
          <tr><td>Type</td>    <td> : <input type=text name='Type' size=15 ></td>
            </tr>
          <tr><td>Ukuran</td> <td> : <input type=text name='CC' size=15 ></td>
            </tr>
		  <tr><td>Bahan</td>    <td> :  <input type=text name='Bahan' size=15></td>
		    </tr>
             <tr><td>No. Pabrik</td> <td> : <input type=text name='Nomor_Pabrik' size=15  ></td>
            </tr>
              <?php 	
            	if($Kd_Aset1 == 2 AND $Kd_Aset2 == 3 AND $Kd_Aset3 == 1){
            		$disp = '';
            	}else{
            		$disp = 'style="display:none;"';
            	}
 			?>
            <tr id="jumlah_roda" <?php echo $disp; ?>>
             	<td>Jumlah Roda</td> <td> : 
             	  <?php
				  	$jlh_roda = array('' => 'Pilih Roda','2'=>'Roda Dua','3'=>'Roda Tiga','4'=>'Roda Empat','6'=>'Roda Enam','8'=>'Roda Delapan','10'=>'Roda Sepuluh');
					echo form_dropdown('Jumlah_Roda', $jlh_roda, "");
				  ?>
				 </td>
            </tr>
            <tr id="no_rangka" <?php echo $disp; ?>><td>No. Rangka</td> <td> : <input type=text name='Nomor_Rangka' size=15 ></td>
            </tr>
             <tr id="no_mesin" <?php echo $disp; ?>><td>No. Mesin</td> <td> : <input type=text name='Nomor_Mesin' size=15 ></td>
            </tr>
            <tr id="no_bpkb" <?php echo $disp; ?>><td>No. BPKB</td> <td> : <input type=text name='Nomor_BPKB' size=15 class="input-small"> No. Polisi : <input type=text name='Nomor_Polisi' size=15 class="input-small"> </td>
            </tr>
		  <tr><td>Asal Usul</td>    <td> : 
          <?php
		  	$option_hak = array('Pembelian' => 'Pembelian','Hibah'=>'Hibah','Pinjam'=>'Pinjam','Sewa'=>'Sewa','Guna Usaha'=>'Guna Usaha','Penyerahan'=>'Penyerahan');
			echo form_dropdown('Asal_usul', $option_hak, "");
		  ?>
               </td>
		    </tr>
            <tr><td>Kondisi</td>    <td> : <?php
		  	$option_hak = array('1' => 'Baik','2'=>'Kurang Baik','3'=>'Rusak Berat');
			echo form_dropdown('Kondisi', $option_hak, "");
		  ?> </tr>
		  <tr>
		  <tr>
             <td>Nama Pemakai</td> <td> : <input type=text name='Pemakai' value="<?php echo $Dipergunakan; ?>" size=50 ></td>
          </tr>
          <td>Harga</td>    
          <td> : <input type=text name='Harga' readonly='readonly' size=20 id="Harga" value="<?php echo nilai($Harga); ?>" class="required number">
          <span id='pesan'></span></td>
		    </tr>
		  <tr><td class='ket'>Keterangan</td>  <td></td>
		    </tr>
		  <tr><td colspan='3'><textarea name='Keterangan' style='width: 600px; height: 80px;'><?php echo $Keterangan; ?></textarea></td>  </tr>
		  
		  <tr>
             <td>Jumlah</td>
             <td>: <input type=text size=5 id='percepatan' name='jmlperc' value="<?php echo nilai($Jumlah); ?>" readonly='readonly' class="required number input-mini" placeholder='unit'></td>
          </tr>
          
          <tr><td colspan=3><input type="submit" name="submit" class="submit" value="P R O S E S" id="submit">
          <input type=button value="B A T A L" id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>
