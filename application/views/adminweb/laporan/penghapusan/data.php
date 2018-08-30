<script type="text/javascript">
$(function() {
    $('input:radio[name=pilihan]').change(function() {
          var val = $(this).attr('value');
          if (val == 'sk_penghapusan'){
                $('#id_sk').attr("disabled",false);
          }else{ 
                $('#id_sk').attr("disabled",true);
          }
    });
});
    
</script>
<script>
	$(document).ready(function(){

    /*$('#sk_penghapusan').click(function() {     
      var checked = $(this).attr('checked', true);
      if(checked){ 
        $('#id_sk').attr("disabled",false);
      }
      else{ 
        $('#id_sk').attr("disabled",true);
      }
    });*/

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
					
				if (rekap_value == 'usul_penghapusan'){
					var rekapval = 'usul_penghapusan';
				}else if (rekap_value == 'sk_penghapusan'){
                    var rekapval = 'sk_penghapusan';   
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
                                
                                <input name="pilihan" type="radio" id="sm_radio" value="usul_penghapusan" /> Daftar Usulan Penghapusan (Demo)<br>

                                <input name="pilihan" type="radio" id="sm_radio" value="sk_penghapusan"/> Lampiran SK Penghapusan (Demo)<br>
                                <hr>
                                <!-- <input name="pilihan" type="radio" id="sm_radio" value="pemeliharaan"/> Daftar pemeliharaan (demo)<br> -->
                            </div>
                            <div class="span3">
                                    SK : <?php
                                        echo form_dropdown("No_SK",$option_sk,'',"id='id_sk' disabled");
                                    ?>
                            </div>
                        </div>
   			 <br>
               <?php
                $tgl 	= date("d");
                $tahun  = date("Y");
                $tahun_login = $this->session->userdata('tahun_anggaran');
                $tahunawal 		= date("$tahun_login-01-01");
                $tahunakhir 	= date("$tahun_login-m-d");
                $bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
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