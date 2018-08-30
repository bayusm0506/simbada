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
	
		$("#FormName").submit(function(data){
			var rekap_value = $('input:radio[name=pilihan]:checked').val();
				
			if (rekap_value == 'laporankondisi'){
				var rekapval = 'laporankondisi';
			}else if (rekap_value == 'asetnonoperasional'){
                var rekapval = 'asetnonoperasional';   
            }else if (rekap_value == 'asettidakberwujud'){
                var rekapval = 'asettidakberwujud';   
            }else if (rekap_value == 'bi'){
                var rekapval = 'bi';   
            }else if (rekap_value == 'rinventaris'){
                var rekapval = 'rekap_inventaris';   
            }else if (rekap_value == 'pemeliharaan'){
                var rekapval = 'pemeliharaan';   
            }else if (rekap_value == 'mutasi'){
                var rekapval = 'mutasi';   
            }else if (rekap_value == 'rekap_mutasi'){
                var rekapval = 'rekap_mutasi';   
            }else if (rekap_value == 'daftar_kendaraan'){
                var rekapval = 'daftar_kendaraan';   
            }else if (rekap_value == 'rekap_kendaraan'){
                var rekapval = 'rekap_kendaraan';   
            }else if (rekap_value == 'rincian_barang'){
                var rekapval = 'rincian_barang';   
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
                    <div class="panel-header"><?php echo $header;?>
                        <span class="pull-right">
                            <?php 
                                echo '<i class="fa fa-file-pdf-o" style="color: red;"></i> PDF <input name="output" type="radio" value="pdf" class="form-export" checked> | ';
                                echo '<i class="fa fa-file-excel-o" style="color: green;"></i> Excel <input name="output" type="radio" value="xls" class="form-export">';
                            ?>
                        </span>
                    </div>
                    <div class="panel-content">
                        <div class="row">
                            <div class="span4">
                            <a href="#"><strong>PILIHAN LAPORAN</strong></a><br/>

                                <hr>

                                <input name="pilihan" type="radio" id="sm_radio" value="rekap_mutasi"/> Rekap Mutasi<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="mutasi"/> Laporan Mutasi<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="mutasi_rinci"/> Mutasi Rinci<br>
                                <hr>
    						    <input name="pilihan" type="radio" id="sm_radio" value="laporankondisi"/> Daftar Aset Menurut Kondisi<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="asetnonoperasional"/> Daftar Aset Non Operasional<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="asettidakberwujud"/> Daftar Aset Tidak Berwujud<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="bi"/> Buku Inventaris<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="rinventaris"/> Rekap Buku Inventaris<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="daftar_kendaraan"/> Daftar Kendaraan<br>
                                <input name="pilihan" type="radio" id="sm_radio" value="rekap_kendaraan"/> Rekap Kendaraan<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="rincian_barang"/> Rincian Barang<br>
                                
                                <hr>
                                <!-- <input name="pilihan" type="radio" id="sm_radio" value="pemeliharaan"/> Daftar pemeliharaan<br> -->
                            </div>
                            <div class="span3">
                                    Kondisi : <?php
                                        echo form_dropdown("kondisi",array(''=>'Semua Kondisi','1'=>'Baik','2'=>'Kurang Baik','3'=>'Rusak Berat','4'=>'Baik dan Kurang Baik'),'',"id='listkondisi' disabled");
                                    ?>
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