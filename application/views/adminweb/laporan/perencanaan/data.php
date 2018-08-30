<script type="text/javascript">
$(function() {
    $('input:radio[name=pilihan]').change(function() {
          var val = $(this).attr('value');
          if (val == 'laporankondisi' || val == 'daftar_kendaraan'){
                $('#listkondisi').attr("disabled",false);
          }else{ 
                $('#listkondisi').attr("disabled",true);
          }
    });
});
    
</script>
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
				var rekap_value = $('input:radio[name=pilihan]:checked').val();
					
				if (rekap_value == 'rkbu'){
                    var rekapval = 'rkbu';
                }else if (rekap_value == 'rkpbu'){
                    var rekapval = 'rkpbu';
                }else if (rekap_value == 'pengadaan_bj'){
                    var rekapval = 'pengadaan_bj';
                }else if (rekap_value == 'pengadaan'){
                    var rekapval = 'pengadaan';
                }else if (rekap_value == 'usul_rkbu'){
                    var rekapval = 'usul_rkbu';
                }else if (rekap_value == 'usul_rkpbu'){
                    var rekapval = 'usul_rkpbu';
                }else if (rekap_value == 'telaah_rkbmd'){
                    var rekapval = 'telaah_rkbmd';
                }else if (rekap_value == 'telaah_rkpbmd'){
                    var rekapval = 'telaah_rkpbmd';
                }else if (rekap_value == 'rkbmd'){
                    var rekapval = 'rkbmd';
                }else if (rekap_value == 'rkpbmd'){
                    var rekapval = 'rkpbmd';
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
                    <div class="panel-header"><?php echo $header;?></div>
                    <div class="panel-content">
                        <div class="row">
                            <div class="span7">
                            <a href="#"><strong>PILIHAN LAPORAN</strong></a><br/>

                                <input name="pilihan" type="radio" id="sm_radio" value="usul_rkbu"/> Usulan Rencana Kebutuhan Barang Unit [Permendagri 19]<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="usul_rkpbu"/> Usulan Rencana Kebutuhan Pemeliharaan Barang Unit [Permendagri 19]<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="telaah_rkbmd"/> Hasil Penelaahan RKBMD Pengadaan [Permendagri 19]<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="telaah_rkpbmd"/> Hasil Penelaahan RKBMD Pemeliharaan [Permendagri 19]<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="rkbmd"/> RKBMD Pengadaan [Permendagri 19]<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="rkpbmd"/> RKBMD Pemeliharaan [Permendagri 19]<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="rkbu"/> Daftar Rencana Kebutuhan Barang Unit (RKBU) [Permendagri 17]<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="rkpbu"/> Daftar Rencana Kebutuhan Pemeliharaan Barang Unit (RKPBU) [Permendagri 17]<br>

                                <!-- <input name="pilihan" type="radio" id="sm_radio" value="bi"/> Daftar Kebutuhan Barang<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="rinventaris"/> Daftar Kebutuhan Pemeliharaan<br> -->

                                <hr>
                                <input name="pilihan" type="radio" id="sm_radio" value="pengadaan_bj"/> Daftar Pengadaan Barang dan Jasa<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="pengadaan"/> Daftar Pengadaan Belanja Modal<br>
                                <!-- <input name="pilihan" type="radio" id="sm_radio" value="rekap_kendaraan"/> Daftar  Pemeliharaan<br> -->
                            </div>
                            
                        </div>
   			 <br>
               <?php
                $tgl         = date("d");
                $tahun       = date("Y");
                $tahun_login = $this->session->userdata('tahun_anggaran');
                $tahunawal   = date("$tahun_login-01-01");
                $tahunakhir  = date("$tahun_login-m-d");
                $bulan       = array("January","Pebruary","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
                $month       = intval(date('m')) - 1;
                $bulan       = $bulan[$month];
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