<script>
	$(document).ready(function(){
	
	<!-- tanggal cek ->
	$("#FormName").submit(function(e){
			var datepicker 	= $("#datepicker").val();
			var datepicker2	= $("#datepicker2").val();
           
			if(datepicker == '' && datepicker2 !== ''){
                alert("Silahkan Isi Tanggal !");
				$("#datepicker").focus();
                return false;
            }
			
			 if(datepicker != '' && datepicker2 == ''){
                alert("Silahkan Isi Tanggal !");
				$("#datepicker2").focus();
                return false;
            }
			
			if (datepicker > datepicker2){
				alert("Tanggal akhir tidak boleh lebih kecil dari tanggal mulai !");
				$("#datepicker2").focus();
                return false;
			}
        });
	<!-- end tanggal ->
	
			$("#FormName").submit(function(data){
				var rekap_value = $('input:radio[name=rekap]:checked').val();
				
				if ($('#id_bidang').val() == 0) {
						alert('pilih bidang terlebih dahulu');
						return false;
					}
				if ($('#idunit').val() == 0) {
						alert('pilih unit terlebih dahulu');
						return false;
					}
					
				if ($('#idsubunit').val() == 0) {
						alert('pilih sub unit terlebih dahulu');
						return false;
					}
				
				if ($('#idupb').val() == 0) {
						alert('pilih upb terlebih dahulu');
						return false;
					}
					
				if (rekap_value == 'inventaris'){
					var rekapval = 'inventaris';	
				}else if (rekap_value == 'rinventaris'){
					var rekapval = 'rinventaris';	
				}else if (rekap_value == 'rkodebarang'){
					var rekapval = 'rkodebarang';	
				}else if (rekap_value == 'rekap_upb'){
					var rekapval = 'rekap_upb';	
				}else if (rekap_value == 'ekstrakomtabel'){
					var rekapval = 'ekstrakomtabel';	
				}else if (rekap_value == 'intrakomtabel'){
					var rekapval = 'intrakomtabel';	
				}else if (rekap_value == 'usul_hapus'){
					var rekapval = 'usul_hapus';	
				}else if (rekap_value == 'usul_guna'){
					var rekapval = 'usul_guna';	
				}else if (rekap_value == 'pengadaan'){
					var rekapval = 'pengadaan';	
				}else if (rekap_value == 'kendaraan'){
					var rekapval = 'kendaraan';	
				}else if (rekap_value == 'rkendaraan'){
					var rekapval = 'rkendaraan';	
				}else if (rekap_value == 'penyusutan'){
					var rekapval = 'penyusutan';	
				}else{
					alert ("silahkan pilih laporan !");
					return false;	
				}
				$("#FormName").attr("action","<?php echo base_url();?>laporan/"+rekapval);
				window.open('', 'formpopup', 'width=800,height=600,left = 250,location=no,scrollbars=yes');
        		this.target = 'formpopup';
				})
	});

</script> 


<form method='POST' action='' id='FormName' name="FormName">
<div class="row">
                <div class="span4">
                <div class="panel">
                    <div class="panel-header">PILIH UPB</div>
                    <div class="panel-content">
				 	 <?php
                    	$this->load->view('adminweb/chain/bidang');
              		 ?>
                    </div>
                </div>
            </div>
            
            <div class="span8">
                <div class="panel">
                    <div class="panel-header">KARTU INVENTARIS BARANG</div>
                    <div class="panel-content">
                        <p /><a href="#"><strong>PILIHAN LAPORAN</strong></a><p />
						    <input name="rekap" type="radio" value="inventaris" id='rekap'/> Buku Inventaris<br>
						    <input name="rekap" type="radio" value="rinventaris" id='rekap'/> Rekap Buku Inventaris<br>
						    <input name="rekap" type="radio" value="rkodebarang" id='rekap'/> Rekap Per Kode Barang<br>
						    <input name="rekap" type="radio" value="rekap_upb" id='rekap'/> Rekap Per UPB<br>
						    <input name="rekap" type="radio" value="ekstrakomtabel" id='rekap'/> Ekstrakomtabel<br>
						    <input name="rekap" type="radio" value="intrakomtabel" id='rekap'/> Intrakomtabel<br>
						    <input name="rekap" type="radio" value="usul_hapus" id='rekap'/> Daftar usulan barang yang akan dihapus<br>
						    <input name="rekap" type="radio" value="usul_guna" id='rekap'/> Daftar usulan penggunaan barang<br>
						    <input name="rekap" type="radio" value="pengadaan" id='rekap'/> Daftar pengadaan<br>
						    <input name="rekap" type="radio" value="kendaraan" id='rekap'/> Kendaraan<br>
						    <input name="rekap" type="radio" value="rkendaraan" id='rekap'/> Rekap Kendaraan<br>
						    <input name="rekap" type="radio" value="penyusutan" id='rekap'/> Daftar Penyusutan<br>
    
   			 <br>
               <?php
                $tgl 	= date("d");
                $tahun  = date("Y");
                $tahun_login = $this->session->userdata('tahun_anggaran');
                $tahunawal 		= date("$tahun_login-01-01");
                $tahunakhir 	= date("$tahun_login-m-d");
                $bulan = array("January","Pebruary","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
                $month = intval(date('m')) - 1;
                $bulan = $bulan[$month];
               ?>
               
               <div class="well">
                              <table class="table">
				<thead>
					<tr>
                    <td>Tanggal</td>
						<td><div class="input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                                    <input type=text name='tahunawal' size=10  id='datepicker' value="<?php echo $tahunawal;?>" readonly='readonly' class="required input-small">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div></th>
                        <td>s/d </td>
						<td><div class="input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                                    <input type=text name='tahunakhir' size=10 id='datepicker2' value="<?php echo $tahunakhir;?>" readonly='readonly' class="input-small">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div></td>
					</tr>
				</thead>
                <tbody>
                <tr>
                	<td colspan="4">Footer : <input type=text name=tanggal value='<?php echo ucfirst(strtolower(LOKASI)).", $tgl $bulan $tahun";?>' size=50>
   <input type='submit' value=' CETAK LAPORAN ' class="btn btn-success"/></td>
                </tr>
                </tbody>
			</table>
                </div>
               
                    </div>
                </div>
            </div>
</div>
</form>