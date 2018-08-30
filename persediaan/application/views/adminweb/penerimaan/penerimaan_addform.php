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
	
function aktif(){
	if(document.fadd.perc.checked){
		alert ("percepatan dikatifkan");
		document.fadd.jmlperc.disabled=false;
		document.fadd.jmlperc.focus();
	}else{
		alert ("percepatan dimatikan");
		document.fadd.jmlperc.disabled=true;
		document.fadd.jmlperc.value='';		
	}
}
</script>

<script type="text/javascript">

 function addbarang(){
		var id_barang = $('#id_barang').val();
		var qty       = $('#qty').val();
        if (id_barang == '') {
          $('#id_barang').focus();
        }else if(qty == ''){
          $('#qty').focus();
        }else{
       // ajax adding data to database
          $.ajax({
            url : "<?= site_url('kasir/addbarang')?>",
            type: "POST",
            data: $('#form_transaksi').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //reload ajax table
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding data');
            }
        });

          showTotal();
          showKembali($('#bayar').val());
          //mereset semua value setelah btn tambah ditekan
          $('.reset').val('');
        };
    }

$(function() {
    $('#Jenis_Dokumen').change(function() {
        $.ajax({
            url: '<?php echo base_url(); ?>pemanfaatan/nomor',
            dataType: 'json',
            type: 'POST',
            data: {Jenis_Dokumen : $('#Jenis_Dokumen').val()},
            success: function(msg) {
                $('input[name=No_Dokumen]').val(msg);
                if ($("#Jenis_Dokumen").val() == 2){
					  $("#kode_surat").html("/BAPP");
					  $("#Tgl_Pinjam").show("slow");
					  $("#Tgl_Kembali").show("slow");
				}else{
					  $("#kode_surat").html("/BAST");
					  $("#Tgl_Pinjam").hide("slow");
					  $("#Tgl_Kembali").hide("slow");
				}
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
});

function autocomplete(){
	$('input.autocomplete').each(function(i, el) {
		el = $(el);
		el.autocomplete({
      			minLength: 1,
      			source: 
	        		function(req, add){
	          			$.ajax({
			        		url: "<?php echo base_url(); ?>pemanfaatan/json",
			          		dataType: 'json',
			          		type: 'POST',
			          		data: req,
			          		success:    
			            	function(data){
			              		if(data.response =="true"){
			                 		add(data.message);            						
			              		}
			            	},
	              		});
	         		},
	         	select: 
	         		function(event, ui) {
	            		$("#kd_aset1_"+i).val(ui.item.id1);
						$("#kd_aset2_"+i).val(ui.item.id2);
						$("#kd_aset3_"+i).val(ui.item.id3);
						$("#kd_aset4_"+i).val(ui.item.id4);
						$("#kd_aset5_"+i).val(ui.item.id5);
						$("#no_register_"+i).val(ui.item.id6);
         		},		
    		});
	});
}

$(this).ready( function() {
		autocomplete();
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

<!-- dialog start -->
<div class="modal fade" id="modal-cari-barang" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal cari barang dengan AJAX</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
          	<div class="form-group has-primary has-feedback">
            	<input type="text" class="form-control input-lg" placeholder="Search for...">
            	<span class="glyphicon glyphicon-search form-control-feedback"></span>
          	</div>
          	<table class="table">
          		<thead>
          			<tr>
          				<th>asd</th>
          				<th>asd</th>
          				<th>asd</th>
          				<th>asd</th>
          				<th>asd</th>
          				<th>asd</th>
          			</tr>
          		</thead>
          	</table>
		  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        </div>
      </div>
      
    </div>
  </div>
<!-- dialog end -->

        <div class="panel-header"><i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
        <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
		          <div class="row">
			          <div class="span6">
				          <table width='100%'>
					          <tr>
					          	<td>Tanggal Diterima</td>
					          	<td> : <input type=text name='Tgl_Diterima' id='datepicker' readonly='readonly' value="<?php echo $tgl_login; ?>" class="required input-small"></td>
					          </tr>
		            		  <tr>
		            		  	<td>Dari ( Nama Rekanan )</td>
		            		  	<td> : <input type=text name='Dari' size=30 ></td>
							  </tr>
							  <tr>
		            		  	<td>No Kontrak/SPK/Kwitansi</td>
		            		  	<td> : <input type=text name='No_Kuitansi' size=30 ></td>
							  </tr>
					          <tr>
					          	<td>Tanggal Kontrak/SPK/Kwitansi</td>
					          	<td> : <input type=text name='Tgl_Kontrak' size=10  id='datepicker2' readonly='readonly' class="required input-small"></td>
		            		  </tr>
							  <tr>
		            		  	<td>No BA Pemeriksaan</td>
		            		  	<td> : <input type=text name='No_BA_Pemeriksaan' size=30 ></td>
							  </tr>
					          <tr>
					          	<td>Tgl BA Pemeriksaan</td>
					          	<td> : <input type=text name='Tgl_BA_Pemeriksaan' size=10  id='datepicker3' readonly='readonly' class="required input-small"></td>
		            		  </tr>
		            		  <tr>
		            		  	<td class='ket' colspan="2"><b>Keterangan</b></td>
							  </tr>
							  <tr><td colspan='2'><textarea name='Keterangan' id='loko' style='width: 80%; height: 80px;'></textarea></td></tr>
				          </table>
				       </div>
				       <div class="span5" style="border:2px solid #000; height: 210px; padding: 10px; text-align: center; padding-top: 150px; font-size:35pt; font-style: bold; font-family: 'arial'">
				       		Total Harga
				       </div>
			       </div>
			       <hr>
			       <table>
			       		<tr>
						  <input type="hidden" name='data2[0][kd_aset1]' id='kd_aset1_0' readonly='readonly' style='width:7%;' class="required">
						  <input type="hidden" name='data2[0][kd_aset2]' id='kd_aset2_0' readonly='readonly' style='width:7%;' class="required">
						  <input type="hidden" name='data2[0][kd_aset3]' id='kd_aset3_0' readonly='readonly' style='width:7%;' class="required">
						  <input type="hidden" name='data2[0][kd_aset4]' id='kd_aset4_0' readonly='readonly' style='width:7%;' class="required">
						  <input type="hidden" name='data2[0][kd_aset5]' id='kd_aset5_0' readonly='readonly' style='width:7%;' class="required">
						  <input type="hidden" name='data2[0][no_register]' id='no_register_0' readonly='readonly' style='width:7%;' class="required">
						    
						    <td width="20%"><input type=text name='nm_aset5' id='nm_aset6' class="autocomplete input-xlarge" placeholder="ketik nama barang disini !" required></td>
						    <td><input type=text name='data2[0][nm_aset5]' id='nm_aset5_0' style='width:87%;' class="autocomplete input-xlarge" placeholder="ketik merk !" required></td>
						    <td><input type=text name='data2[0][nm_aset5]' id='nm_aset5_0' style='width:87%;' class="autocomplete input-xlarge" placeholder="ketik ukuran !" required></td>
						    <td><input type=text name='data2[0][nm_aset5]' id='nm_aset5_0' style='width:87%;' class="autocomplete input-xlarge" placeholder="ketik jumlah !" required></td>
						    <td><input type=text name='data2[0][nm_aset5]' id='nm_aset5_0' style='width:87%;' class="autocomplete input-xlarge" placeholder="ketik harga !" required></td>
						    <td><input type=text name='data2[0][nm_aset5]' id='nm_aset5_0' style='width:87%;' class="autocomplete input-xlarge"></td>
						</tr>
						<tr>
							<td colspan="3">
								<div class="col-md-1">
							      	<a href="javascript:;" class="btn btn-primary" 
							      		data-toggle="modal" 
							      		data-target="#modal-cari-barang">
							      		<i class="fa fa-search"></i> Cari Barang</a>
						          </div>
							</td>
						    <td colspan="3" align="right">
						    	<button type="button" class="btn btn-primary"id="tambah" onclick="addbarang()"> <i class="fa fa-cart-plus"></i> Tambah</button>
						    	<button type="submit" class="btn btn-warning">Batal</button>
						    </td>
						</tr>
			       </table>
			       <hr>
                  <table id="pic-table" class="table table-striped table-bordered table-hover" >
	                 <thead>
	                     <tr>
	                        <th>Kode Barang</th>
	                        <th>Nama/Jenis Barang</th>
	                        <th>Merk</th>
	                        <th>Ukuran</th>
	                        <th>Harga</th>
	                        <th>Jumlah</th>
	                        <th>Total</th>
	                        <th>#</th>
	                    </tr>
	                 <thead>
	                 <tbody>
	                 	<tr>
	                        <td width="20%">
								  01.02.03.04.05.06
						    </td>
						    <td width="20%">Sample Barang</td>
						    <td>Sample Merk</td>
						    <td>Sample Ukuran</td>
						    <td style="text-align: right;">Sample Harga</td>
						    <td style="text-align: center;">Sample Jumlah</td>
						    <td style="text-align: right;">Sample Harga Akhir</td>
						    <td><a>Hapus</a></td>
						</tr>
	                 </tbody>    
                </table>
                <hr>
                <table>
			       		<tr align="right">
		                	<button type="submit" class="btn medium ui-state-default radius-all-4">SIMPAN</button>
				        </tr>
				</table>
		         <hr>
          </form>

       </div><!-- span 12 -->
