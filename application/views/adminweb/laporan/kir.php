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
				var kib_value = $('input:radio[name=kib]:checked').val();
				
				
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
				
				$("#FormName").attr("action","<?php echo base_url();?>laporan/cetakkir");
				window.open('', 'formpopup', 'width=800,height=600,left = 250,location=no,');
        		this.target = 'formpopup';
				})
	});
	
function semua(){
	if(document.FormName.perc.checked){
		alert ("Pilihan Tanggal Dinonaktifkan");
		document.FormName.tahunawal.disabled=true;
		document.FormName.tahunakhir.disabled=true;
	}else{
		alert ("Pilihan Tanggal Diaktifkan");
		document.FormName.tahunawal.disabled=false;
		document.FormName.tahunakhir.disabled=false;	
	}
}

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
                    <div class="panel-header">KARTU INVENTARIS RUANGAN</div>
                    <div class="panel-content">
                        <div id="ruang">
			                Ruang : <?php
			                    echo form_dropdown("kd_ruang",array('pilihruang'=>'Pilih Ruang Dahulu'),'','disabled');
			                ?>
			            </div>
    
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
                                    <input type=text name='tahunawal' size=10  id='datepicker' value="<?php echo $tahunawal;?>" readonly='readonly' class="input-small">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div></th>
                        <td>s/d </td>
						<td><div class="input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                                    <input type=text name='tahunakhir' size=10 id='datepicker2' value="<?php echo $tahunakhir;?>" readonly='readonly' class="input-small">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div></td>
                         <td>
                         <input type='checkbox' onclick='semua()' id='cekbox' title='klik untuk mengaktifkan' name='perc' value="pilih">
                         <span class="label label-info">Seluruh Tahun Perolehan</span></td>
					</tr>
				</thead>
                <tbody>
                <tr>
                	<td colspan="5">Footer : <input type=text name=tanggal value='<?php echo ucfirst(strtolower(LOKASI)).", $tgl $bulan $tahun";?>' size=50>
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