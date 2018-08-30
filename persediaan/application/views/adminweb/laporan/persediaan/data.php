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
				}else if (rekap_value == 'buku_persediaan'){
                    var rekapval = 'buku_persediaan';    
                }else if (rekap_value == 'buku_penerimaan'){
                    var rekapval = 'buku_penerimaan';    
                }else if (rekap_value == 'buku_pengeluaran'){
                    var rekapval = 'buku_pengeluaran';    
                }else if (rekap_value == 'rekap_persediaan'){
                    var rekapval = 'rekap_persediaan';    
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
                    <div class="panel-header">PERSEDIAAN</div>
                    <div class="panel-content">
                            <div class="row">
                                <div class="span3">
                                        <input name="pilihan" type="radio" value="kartu_barang" id='sm_radio'/> Kartu Barang<br>
                                        <input name="pilihan" type="radio" value="buku_persediaan" id='sm_radio'/> Buku Persediaan<br>
                                        <input name="pilihan" type="radio" value="buku_penerimaan" id='sm_radio'/> Buku Penerimaan<br>
                                        <input name="pilihan" type="radio" value="buku_pengeluaran" id='sm_radio'/> Buku Pengeluaran<br>
                                        <input name="pilihan" type="radio" value="rekap_persediaan" id='sm_radio'/> Rekap Persediaan<br>
                                </div>
                                
                            </div>
                            

                        
   			 <br>
               <?php
                $tgl 	= date("d");
                $tahun  = date("Y");
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
                	<td colspan="4">Footer : <input type=text name=tanggal value='<?php echo "Medan, $tgl $bulan $tahun";?>' size=50>
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