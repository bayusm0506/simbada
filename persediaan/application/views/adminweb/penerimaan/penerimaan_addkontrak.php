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
            var kd_aset1 	= $("#kd_aset1").val();
			var datepicker 	= $("#datepicker").val();
			var datepicker3	= $("#datepicker3").val();
          	var No_Register = $("#No_Register").val();
			var Jumlah 		= $("#Jumlah").val();
			var Harga 		= $("#Harga").val();

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
	
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
?>

<?php
		$tahun_login = $this->session->userdata('tahun_anggaran');
		$tgl_login  = date("$tahun_login-m-d");
?>

        <div class="panel-header"><i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
        <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
		          <div class="row">
			          <div class="span8">
				          <table width='100%'>
					          <tr>
					          	<td>Tanggal Diterima</td>
					          	<td> : <input type=text name='Tgl_Diterima' id='datepicker' readonly='readonly' value="<?php echo $tgl_login; ?>" class="required input-small"></td>
					          </tr>
		            		  <tr>
		            		  	<td>Dari ( Nama Rekanan )</td>
		            		  	<td> : <input type=text name='Nm_Rekanan' size=30 ></td>
							  </tr>
							  <tr>
		            		  	<td>No Kontrak/SP/SPK/Kwitansi</td>
		            		  	<td> : <input type=text name='No_Kontrak' size=30 ></td>
							  </tr>
					          <tr>
					          	<td>Tanggal Kontrak/SPK/Kwitansi</td>
					          	<td> : <input type=text name='Tgl_Kontrak' value="<?php echo $tgl_login; ?>" size=10  id='datepicker2' readonly='readonly' class="required input-small"></td>
		            		  </tr>
							  <tr>
		            		  	<td>No BA Pemeriksaan/Penerimaan</td>
		            		  	<td> : <input type=text name='No_BA_Pemeriksaan' size=30 ></td>
							  </tr>
					          <tr>
					          	<td>Tgl BA Pemeriksaan/Penerimaan</td>
					          	<td> : <input type=text name='Tgl_BA_Pemeriksaan' value="<?php echo $tgl_login; ?>" size=10  id='datepicker3' readonly='readonly' class="required input-small"></td>
		            		  </tr>
		            		  <tr>
		            		  	<td class='ket' colspan="2"><b>Keterangan / Uraian</b></td>
							  </tr>
							  <tr><td colspan='2'><textarea name='Keterangan' id='loko' style='width: 80%; height: 80px;'></textarea></td></tr>
				          </table>
				       </div>
				       
			       </div>

                <table>
			       		<tr align="right">
		                	<button type="submit" class="btn medium ui-state-default radius-all-4">SIMPAN</button>
		                	<button type="reset" class="btn medium ui-state-default radius-all-4">BATAL</button>
				        </tr>
				</table>
		         <hr>
          </form>

       </div><!-- span 12 -->
