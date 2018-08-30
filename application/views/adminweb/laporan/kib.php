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
				var kib_value = $('input:radio[name=kib]:checked').val();
				
				
				if (kib_value == 'kiba'){
					var kibval = 'kiba';	
				}else if (kib_value == 'kibb'){
					var kibval = 'kibb';	
				}else if (kib_value == 'kibc'){
					var kibval = 'kibc';	
				}else if (kib_value == 'kibd'){
					var kibval = 'kibd';	
				}else if (kib_value == 'kibe'){
					var kibval = 'kibe';	
				}else if (kib_value == 'kibf'){
					var kibval = 'kibf';	
				}else{
					alert ("Silahkan pilih laporan !");
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
                    <div class="panel-header"><i class="icon-bar-chart"></i> KARTU INVENTARIS BARANG

                    	<span class="pull-right">
                    		<?php 
                    			echo '<i class="fa fa-file-pdf-o" style="color: red;"></i> PDF <input name="output" type="radio" value="pdf" class="form-export" checked> | ';
                    			echo '<i class="fa fa-file-excel-o" style="color: green;"></i> Excel <input name="output" type="radio" value="xls" class="form-export">';
                    		?>
                    	</span>

                    </div>
                    	
                    <div class="panel-content">
                        <p /><a href="#"><strong>Pilih Kartu Inventaris Barang</strong></a><p />
						<input name="kib" type="radio" value="kiba" id='kib'/> Kartu Inventaris Barang (KIB) A. Tanah<br>
						<input name="kib" type="radio" value="kibb" id='kib'/> Kartu Inventaris Barang (KIB) B. Peralatan & Mesin<br>
						<input name="kib" type="radio" value="kibc" id='kib'/> Kartu Inventaris Barang (KIB) C. Gedung & Bangunan<br>
						<input name="kib" type="radio" value="kibd" id='kib'/> Kartu Inventaris Barang (KIB) D. Jalan, Irigasi & Jaringan<br>
						<input name="kib" type="radio" value="kibe" id='kib'/> Kartu Inventaris Barang (KIB) E. Aset Tetap Lainya<br>
						<input name="kib" type="radio" value="kibf" id='kib'/> Kartu Inventaris Barang (KIB) F. Konstruksi Dalam Pengerjaan<br>
    
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