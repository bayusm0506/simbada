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
					
				if (rekap_value == 'rekap'){
					var rekapval = 'rekap';	
				}else if(rekap_value == 'rinventarisinduk'){
					var rekapval = 'rinventarisinduk';
				}else if (rekap_value == 'bukuindukinventaris'){
					var rekapval = 'bukuindukinventaris';	
				}else if (rekap_value == 'rkodebarang_induk'){
					var rekapval = 'rkodebarang_induk';	
				}else if (rekap_value == 'ekstrakomtabel_skpd'){
					var rekapval = 'ekstrakomtabel_skpd';	
				}else if (rekap_value == 'intrakomtabel_skpd'){
					var rekapval = 'intrakomtabel_skpd';	
				}else if (rekap_value == 'lampiran_sk_hapus'){
					var rekapval = 'lampiran_sk_hapus';	
				}else if (rekap_value == 'lampiran_sk_guna'){
					var rekapval = 'lampiran_sk_guna';	
				}else if (rekap_value == 'pengadaan_induk'){
					var rekapval = 'pengadaan_induk';	
				}else if (rekap_value == 'bukuindukinventaris'){
					var rekapval = 'bukuindukinventaris';	
				}else if(rekap_value == 'rekapAinduk'){
					var rekapval = 'kibainduk';
				}else if(rekap_value == 'rekapBinduk'){
					var rekapval = 'kibbinduk';
				}else if(rekap_value == 'rekapCinduk'){
					var rekapval = 'kibcinduk';
				}else if(rekap_value == 'rekapDinduk'){
					var rekapval = 'kibdinduk';
				}else if(rekap_value == 'rekapEinduk'){
					var rekapval = 'kibeinduk';
				}else if(rekap_value == 'rekapFinduk'){
                    var rekapval = 'kibfinduk';
                }else if(rekap_value == 'rekapmutasibaranginduk'){
					var rekapval = 'rekapmutasibaranginduk';
				}else if(rekap_value == 'rekapmutasiinduk'){
					var rekapval = 'rekapmutasiinduk';
				}else if(rekap_value == 'kendaraan_induk'){
                    var rekapval = 'kendaraan_induk';
                }else if(rekap_value == 'rkendaraan_induk'){
					var rekapval = 'rkendaraan_induk';
				}else if(rekap_value == 'penyusutan_induk'){
					var rekapval = 'penyusutan_induk';
				}else{
					alert ("silahkan pilih buku rekap !");
					return false;	
				}
				$("#FormName").attr("action","<?php echo base_url();?>laporan/"+rekapval);
				window.open('', 'formpopup', 'width=800,height=600,left = 250,location=no,scrollbars=yes');
        		this.target = 'formpopup';
				})
	});

</script> 


<form method='POST' action='' id='FormName' name="FormName">
<div class="container">
            <div class="span12">
                <div class="panel">
                    <div class="panel-header">KARTU INVENTARIS BARANG</div>
                    <div class="panel-content">
                    
                    <div class="row">
                        <div class="span4"><h4><u>Cetak Laporan Induk</u></h4>
    <input name="rekap" type="radio" value="rinventarisinduk" id='rinventarisinduk'/> Rekap Buku Induk Inventaris<br>
    <input name="rekap" type="radio" value="bukuindukinventaris" id='bukuindukinventaris'/> Buku Induk Inventaris SKPD<br>
    <input name="rekap" type="radio" value="rekap" id='rekap'/> Rekap SKPD<br>
    <input name="rekap" type="radio" value="rkodebarang_induk" id='rkodebarang_induk'/> Rekap per kode barang<br>
    <input name="rekap" type="radio" value="ekstrakomtabel_skpd"/> Ekstrakomptabel SKPD<br>
    <input name="rekap" type="radio" value="intrakomtabel_skpd"/> Intrakomtabel SKPD<br>
    <input name="rekap" type="radio" value="lampiran_sk_hapus"/> Lampiran SK Penghapusan<br>
    <input name="rekap" type="radio" value="lampiran_sk_guna"/> Lampiran SK Penggunaan<br>
    <input name="rekap" type="radio" value="pengadaan_induk"/> Daftar pengadaan induk<br>
    <input name="rekap" type="radio" value="rekapmutasibaranginduk" id='rekapmutasibaranginduk'/> Laporan Mutasi Barang Induk<br>
    <input name="rekap" type="radio" value="rekapmutasiinduk" id='rekapmutasiinduk'/> Rekap Mutasi Induk<br>
    <input name="rekap" type="radio" value="kendaraan_induk" id='rekap'/> Kendaraan Induk<br>
	<input name="rekap" type="radio" value="rkendaraan_induk" id='rekap'/> Rekap Kendaraan Induk<br>
	<input name="rekap" type="radio" value="penyusutan_induk" id='rekap'/> Daftar Penyusutan Induk<br>

   			 <br></div>
                    <div class="span5"><h4><u>Cetak Laporan Induk KIB</u></h4>
    <input name="rekap" type="radio" value="rekapAinduk" id='rekapAinduk'/> Rekap KIB A Induk Kabupaten<br>
    <input name="rekap" type="radio" value="rekapBinduk" id='rekapBinduk'/> Rekap KIB B Induk Kabupaten<br>
    <input name="rekap" type="radio" value="rekapCinduk" id='rekapCinduk'/> Rekap KIB C Induk Kabupaten<br>
    <input name="rekap" type="radio" value="rekapDinduk" id='rekapDinduk'/> Rekap KIB D Induk Kabupaten<br>
    <input name="rekap" type="radio" value="rekapEinduk" id='rekapEinduk'/> Rekap KIB E Induk Kabupaten<br>
    <input name="rekap" type="radio" value="rekapFinduk" id='rekapFinduk'/> Rekap KIB F Induk Kabupaten<br></div>
                    </div>
                    
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