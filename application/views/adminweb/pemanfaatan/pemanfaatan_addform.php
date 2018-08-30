 <script type="text/javascript">
    $(document).ready(function(){
		
        $("#Harga").keyup(function(){
            var Harga = $("#Harga").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#Jumlah").keyup(function(){
            var Jumlah = $("#Jumlah").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#percepatan").keyup(function(){
            var Harga = $("#percepatan").val();
            this.value = this.value.replace(/[^0-9]/,'');
        });
		
        $('form').submit(function(e){
            var kd_aset1 	= $("#kd_aset1").val();
			var datepicker 	= $("#datepicker").val();
			var datepicker3	= $("#datepicker3").val();
          	var No_Register = $("#No_Register").val();
			var Jumlah 		= $("#Jumlah").val();
			var Harga 		= $("#Harga").val();

			 if(No_Register == ''){
                alert("Kode register belum diisi, silahkan isi kembali nama aset !");
                return false;
            }
			
			 if(datepicker == '' && datepicker3 == ''){
                alert("Silahkan isi tanggal Kontrak atau Tanggal Kwitansi terlebih dahulu !");
                return false;
            }
			
			 if(Harga == '' || Harga == ''){
                alert("Harga Pembelian atau Jumlah belum diisi !");
                return false;
            }
        });
    });
	
function aktif(){
	if(document.fadd.perc.checked){
		alert ("percepatan dikatifkan");
		document.fadd.jmlperc.disabled=false;
		document.fadd.jmlperc.focus();
	}else{
		alert ("percepatan dimatikan");
		document.fadd.jmlperc.disabled=true;
		document.fadd.jmlperc.value='';		
	}
}
</script>
<script>
function del_project(id){
    
    $("#project-wrap #row_project"+id).remove();
}
var $i=1;

function del_pic(id){

    $("#pic-table tbody #pic_row"+id).remove();
}
$j=1;
function add_pic(){

	var $pic =  '<tr id="pic_row'+$j+'">'+
				'<td>'+
					'<div class="form-inline">'+
					  '<input type=text name="data2['+$j+'][kd_aset1]" id="kd_aset1_'+$j+'" readonly="readonly" class="input-mini required">.'+
					  '<input type=text name="data2['+$j+'][kd_aset2]" id="kd_aset2_'+$j+'" readonly="readonly" class="input-mini required">.'+
					  '<input type=text name="data2['+$j+'][kd_aset3]" id="kd_aset3_'+$j+'" readonly="readonly" class="input-mini required">.'+
					  '<input type=text name="data2['+$j+'][kd_aset4]" id="kd_aset4_'+$j+'" readonly="readonly" class="input-mini required">.'+
					  '<input type=text name="data2['+$j+'][kd_aset5]" id="kd_aset5_'+$j+'" readonly="readonly" class="input-mini required"> '+
					  '<input type=text name="data2['+$j+'][no_register]" id="no_register_'+$j+'" readonly="readonly" class="input-mini required"> '+
			          '<input type=text name="data2['+$j+'][nm_aset5]" id="nm_aset5_'+$j+'" class="autocomplete input-xlarge" style="width:50%;" placeholder="ketik nama barang disini !" required>'+
			        '</div>'+
				'</td>'+
				'<td>'+
					'<a href="javascript:;" title="Remove" onclick="del_pic('+$j+');" class="a-danger"></i>Hapus</a>'+
				'</td>'+				
				'</tr>';
                   
   $("#pic-table tbody").append($pic);
   autocomplete();
 ++$j;
}
</script>
<script type="text/javascript">

$(function() {
    $('#Jenis_Dokumen').change(function() {
        $.ajax({
            url: '<?php echo base_url(); ?>pemanfaatan/nomor',
            dataType: 'json',
            type: 'POST',
            data: {Jenis_Dokumen : $('#Jenis_Dokumen').val()},
            success: function(msg) {
                $('input[name=No_Dokumen]').val(msg);
                if ($("#Jenis_Dokumen").val() == 2){
					  $("#kode_surat").html("/BAPP");
					  $("#Tgl_Pinjam").show("slow");
					  $("#Tgl_Kembali").show("slow");
				}else{
					  $("#kode_surat").html("/BAST");
					  $("#Tgl_Pinjam").hide("slow");
					  $("#Tgl_Kembali").hide("slow");
				}
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
});


/*function cekdokumen(){
	if ($("#Jenis_Dokumen").val() == 2){
		  $("#kode_surat").html("/BAPP");
		  $("#Tgl_Pinjam").show("slow");
		  $("#Tgl_Kembali").show("slow");
	}else{
		  $("#kode_surat").html("/BAST");
		  $("#Tgl_Pinjam").hide("slow");
		  $("#Tgl_Kembali").hide("slow");
	}
}*/

function autocomplete(){
	$('input.autocomplete').each(function(i, el) {
		el = $(el);
		el.autocomplete({
      			minLength: 1,
      			source: 
	        		function(req, add){
	          			$.ajax({
			        		url: "<?php echo base_url(); ?>pemanfaatan/json",
			          		dataType: 'json',
			          		type: 'POST',
			          		data: req,
			          		success:    
			            	function(data){
			              		if(data.response =="true"){
			                 		add(data.message);            						
			              		}
			            	},
	              		});
	         		},
	         	select: 
	         		function(event, ui) {
	            		$("#kd_aset1_"+i).val(ui.item.id1);
						$("#kd_aset2_"+i).val(ui.item.id2);
						$("#kd_aset3_"+i).val(ui.item.id3);
						$("#kd_aset4_"+i).val(ui.item.id4);
						$("#kd_aset5_"+i).val(ui.item.id5);
						$("#no_register_"+i).val(ui.item.id6);
         		},		
    		});
	});
}

$(this).ready( function() {
		autocomplete();
    });
</script>
<?php
	
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
?>	
<div class="row">
    <div class="span12">

    		<div class="panel"></div>

                    <div class="panel-header"><i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
                    <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
		          <table width='100%'>
		          	  <tr><td>Jenis Dokumen</td>    <td> : <?php
						  	$jenis_dokumen = array('' => '- Pilih Jenis Dokumen -','1'=>'Berita Acara Serah Terima','2'=>'Berita Acara Pinjam Pakai');
							echo form_dropdown('Jenis_Dokumen', $jenis_dokumen, '','class="input-large required" id="Jenis_Dokumen"');
						  ?></td>
				      </tr>
			          <tr>
			          	<td>Tanggal Dokumen</td>
			          	<td> : <input type=text name='Tgl_Dokumen' id='datepicker' readonly='readonly' class="required input-small"></td>
			          </tr>
			          <tr>
			          		<td>No Dokumen</td>
			          		<td> :	<span style="font-size: 18px; ">032/</span>
			          				<input type=text name='No_Dokumen' id="No_Dokumen" class="input-mini" value="auto" readonly="true">
			          				<span id="kode_surat" style="font-size: 18px; ">/-</span><span style="font-size: 18px; ">/<?php echo strtoupper($this->session->userdata('username')); ?>/<?php echo $this->session->userdata('tahun_anggaran'); ?></span></td>
			          </tr>
			          <tr id="Tgl_Pinjam" style="display:none;">
			          	<td>Tanggal Pinjam</td>
			          	<td> : <input type=text name='Tgl_Pinjam' id='datepicker2' readonly='readonly' class="required input-small"></td>
			          </tr>
			          <tr id="Tgl_Kembali" style="display:none;">
			          	<td>Tanggal Kembali</td>
			          	<td> : <input type=text name='Tgl_Kembali' id='datepicker3' readonly='readonly' class="required input-small"></td>
			          </tr>
			          <tr><td colspan="2"><h3>IDENTITAS</h3><hr></td>
			            </tr>  
			          <tr><td>Nama Pihak Pertama</td>    <td> : <input type=text name='Nama_Pihak_1' id='Nama_Pihak_1' class="input-xlarge"></td>
					    </tr>
					    <tr><td>NIP</td>    <td> : <input type=text name='Nip_Pihak_1' id='Nip_Pihak_1' class="input-medium"></td>
					    </tr>
					    <tr><td>Jabatan</td>    <td> : <input type=text name='Jabatan_Pihak_1' id='Jabatan_Pihak_1' class="input-medium"></td>
					    </tr>
					    <tr><td>Alamat</td>    <td> : <textarea name='Alamat_Pihak_1' id='Alamat_Pihak_1' style='width:60%;' class="input-medium"></textarea></td>
					    </tr>
					   <tr><td colspan="2"><b><hr/></b></td>
					  <tr><td>Nama Pihak Kedua</td>    <td> : <input type=text name='Nama_Pihak_2' id='Nama_Pihak_2' class="input-xlarge"></td>
					    </tr>
					    <tr><td>NIP</td>    <td> : <input type=text name='Nip_Pihak_2' id='Nip_Pihak_2' class="input-medium"></td>
					    </tr>
					    <tr><td>Jabatan</td>    <td> : <input type=text name='Jabatan_Pihak_2' id='Jabatan_Pihak_2' class="input-medium"></td>
					    </tr>
					    <tr><td>Alamat</td>    <td> : <textarea name='Alamat_Pihak_2' id='Alamat_Pihak_2' style='width:60%;' class="input-medium"></textarea></td>
					    </tr> 
		          </table>
                  <div class="list-actions">
                        <div class="btn-toolbar pull-right">
                        	<div class="btn-group">
                                <button class="btn btn-success" onclick="add_pic();"><i class="icon-plus-sign"></i> Tambah List Barang</button>
                            </div>
                        </div>
                    </div>
		          <table id="pic-table" class="table table-striped table-bordered table-hover" >
	                 <thead>
	                    <tr>
	                        <th colspan="2">List Barang</th>
	                    </tr>
	                 <thead>
	                 <tbody>
	                    <tr>
	                        <td width="90%">
	                        	<div class="form-inline">
								  <input type=text name='data2[0][kd_aset1]' id='kd_aset1_0' readonly='readonly' class="input-mini required">
								  <input type=text name='data2[0][kd_aset2]' id='kd_aset2_0' readonly='readonly' class="input-mini required">
								  <input type=text name='data2[0][kd_aset3]' id='kd_aset3_0' readonly='readonly' class="input-mini required">
								  <input type=text name='data2[0][kd_aset4]' id='kd_aset4_0' readonly='readonly' class="input-mini required">
								  <input type=text name='data2[0][kd_aset5]' id='kd_aset5_0' readonly='readonly' class="input-mini required">
								  <input type=text name='data2[0][no_register]' id='no_register_0' readonly='readonly' class="input-mini required">
						          <input type=text name='data2[0][nm_aset5]' id='nm_aset5_0' style='width:50%;' class="autocomplete input-xlarge" placeholder="ketik nama barang disini !" required>
						         </div>
						    </td>
	                        <td>#</td>
	                    </tr>
	                 </tbody>
	                 <tfoot>
	                    <tr><td class='ket' colspan="2"><b>Keterangan</b></td>
					    </tr>
					  <tr><td colspan='2'><textarea name='Keterangan' id='loko' style='width: 99%; height: 80px;'></textarea></td></tr>
	                 <tr>
	                      <td colspan="2">
	                        <div class="form-input">
	                            <button type="submit" class="btn medium ui-state-default radius-all-4">Simpan</button>
	                            <button type="reset" class="btn medium ui-state-default radius-all-4">Reset</button>
	                        </div>
	                      </td>
	                    </tr>
	                 </tfoot>
                </table>
          </form>

       </div><!-- span 12 -->
       
	</div>
</div>
