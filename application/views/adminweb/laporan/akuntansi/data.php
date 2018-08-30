<script type="text/javascript">
$(function() {
    $('input:radio[name=pilihan]').change(function() {
          var val = $(this).attr('value');
          if (val == 'ekstra' || val == 'intra'){
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
            var datepicker  = $("#datepicker").val();
            var datepicker2 = $("#datepicker2").val();
           
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
					
				if (rekap_value == 'neraca'){
					var rekapval = 'neraca';	
				}else if (rekap_value == 'rekap_neraca'){
                    var rekapval = 'rekap_neraca';    
                }else if (rekap_value == 'rincian_neraca'){
                    var rekapval = 'rincian_neraca';    
                }else if (rekap_value == 'rincian_susut'){
                    var rekapval = 'rincian_susut';    
                }else if (rekap_value == 'proses_rekon'){
                    var rekapval = 'proses_rekon';    
                }else if (rekap_value == 'ekstra'){
                    var rekapval = 'ekstra';   
                }else if (rekap_value == 'intra'){
                    var rekapval = 'intra';   
                }else if (rekap_value == 'kibb_susut'){
                    var rekapval = 'kibb_susut';   
                }else if (rekap_value == 'kibc_susut'){
                    var rekapval = 'kibc_susut';   
                }else if (rekap_value == 'kibd_susut'){
                    var rekapval = 'kibd_susut';   
                }else if (rekap_value == 'kibe_susut'){
                    var rekapval = 'kibe_susut';   
                }else if (rekap_value == 'lainnya_susut'){
                    var rekapval = 'lainnya_susut';   
                }else if (rekap_value == 'rekap_susut'){
                    var rekapval = 'rekap_susut';   
                }else if (rekap_value == 'akm_hapus'){
                    var rekapval = 'akm_hapus';   
                }else if (rekap_value == 'akm_pindah'){
                    var rekapval = 'akm_pindah';   
                }else if (rekap_value == 'akm_rb'){
                    var rekapval = 'akm_rb';   
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
                        <div class="panel-header">AKUNTANSI
                            <span class="pull-right">
                                <?php 
                                    echo '<i class="fa fa-file-pdf-o" style="color: red;"></i> PDF <input name="output" type="radio" value="pdf" class="form-export" checked> | ';
                                    echo '<i class="fa fa-file-excel-o" style="color: green;"></i> Excel <input name="output" type="radio" value="xls" class="form-export">';
                                ?>
                            </span>
                        </div>
                        <div class="panel-content">
                                <div class="row">
                                    <div class="span3">
                                           <!--  <input name="pilihan" type="radio" value="neraca" id='sm_radio'/> Neraca Aset 1<br> -->
                                            <input name="pilihan" type="radio" value="rekap_neraca" id='sm_radio'/> Rekap Neraca Aset<br>
                                            <input name="pilihan" type="radio" value="rincian_neraca" id='sm_radio'/> Rincian Barang Ke Neraca<br>
                                            <input name="pilihan" type="radio" value="rincian_susut" id='sm_radio'/> Rincian Barang Penyusutan<br>
                                            <!-- <input name="pilihan" type="radio" value="proses_rekon" id='sm_radio'/> Proses Rekon<br> -->
                                    </div>
                                    <div class="span4">
                                            <input name="pilihan" type="radio" id="sm_radio" value="kibb_susut"/> Penyusutan KIB B. Peralatan dan Mesin<br>
                                            <input name="pilihan" type="radio" id="sm_radio" value="kibc_susut"/> Penyusutan KIB C. Gedung dan Bangunan<br>
                                            <input name="pilihan" type="radio" id="sm_radio" value="kibd_susut"/> Penyusutan KIB D. Jalan, Irigasi dan Jaringan<br>
                                            <input name="pilihan" type="radio" id="sm_radio" value="kibe_susut"/> Penyusutan KIB E. Aset Tetap Lainya<br>
                                            <input name="pilihan" type="radio" id="sm_radio" value="lainnya_susut"/> Penyusutan Aset Lainnya (Aset Tak Berwujud)<br>
                                            <input name="pilihan" type="radio" id="sm_radio" value="rekap_susut"/> Rekapitulasi Penyusutan<br>
                                    </div>
                                </div>
                <hr>
                                <div class="row">
                                    <div class="span4">
                                            <input name="pilihan" type="radio" id="sm_radio" value="ekstra"/> Ekstrakomptabel<br>
                                            <input name="pilihan" type="radio" id="sm_radio" value="intra"/> Intrakomptabel<br>
                                    </div>
                                    <div class="span3">
                                          <?php
                                                echo form_dropdown("kondisi",array(''=>' - Pilih Kondisi - ','1'=>'Baik','2'=>'Kurang Baik','3'=>'Rusak Berat','4'=>'Baik & Kurang Baik'),'',"id='listkondisi' disabled");
                                            ?>
                                    </div>
                                </div>
                <hr>
                                <div class="row">
                                    <div class="span12">
                                            <input name="pilihan" type="radio" id="sm_radio" value="akm_hapus"/>
                                            Akumulasi penyusutan atas barang yang telah dihapuskan<br>
                                            <input name="pilihan" type="radio" id="sm_radio" value="akm_pindah"/>
                                            Akumulasi penyusutan atas barang yang telah dipindahkan<br>
                                             <input name="pilihan" type="radio" id="sm_radio" value="akm_rb"/>
                                            Akumulasi penyusutan atas barang yang telah direklass Ke Rusak Berat<br>
                                    </div>
                                    
                                </div>

                            
       			 <br>
                   <?php
                    $tgl         = date("d");
                    $tahun       = date("Y");
                    $tahun_login = $this->session->userdata('tahun_anggaran');
                    $tahunawal   = date("1900-01-01");
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