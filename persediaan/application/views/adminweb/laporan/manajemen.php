 <script>
	$(document).ready(function(){
			$("#FormName").submit(function(data){
				var laporan_value = $('input:radio[name=laporankib]:checked').val();
				
				
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
				
				if (laporan_value == 'mutasi'){
					var kibval = 'mutasi';	
				}else if (laporan_value == 'rekapmutasi'){
					var kibval = 'rekapmutasi';	
				}else{
					alert ("silahkan pilih laporan !");
					return false;	
				}
				$("#FormName").attr("action","<?php echo base_url();?>laporan/"+kibval);
				window.open('', 'formpopup', 'width=800,height=600,left = 250,location=no,');
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
    <input name="laporankib" type="radio" value="mutasi" id='kib'/> Laporan mutasi barang<br>
    <input name="laporankib" type="radio" value="rekapmutasi" id='kib'/> Rekap Mutasi Barang<br>
    
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