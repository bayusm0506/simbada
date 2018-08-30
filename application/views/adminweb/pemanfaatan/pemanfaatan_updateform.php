<script>
    $(document).ready(function(){

        $(".hapus").click(function(event) {
			var no            = $(this).attr("no");
			var kd_bidang     = $(this).attr("Kd_Bidang");
			var kd_unit       = $(this).attr("Kd_Unit");
			var kd_sub        = $(this).attr("Kd_Sub");
			var kd_upb        = $(this).attr("Kd_UPB");
			
			var kd_aset1      = $(this).attr("Kd_Aset1");
			var kd_aset2      = $(this).attr("Kd_Aset2");
			var kd_aset3      = $(this).attr("Kd_Aset3");
			var kd_aset4      = $(this).attr("Kd_Aset4");
			var kd_aset5      = $(this).attr("Kd_Aset5");
			var no_register   = $(this).attr("No_Register");
			var jenis_dokumen = $(this).attr("Jenis_Dokumen");
			var no_dokumen    = $(this).attr("No_Dokumen");
			
				var kode = {
							kd_bidang:kd_bidang,
							kd_unit:kd_unit,
							kd_sub:kd_sub,
							kd_upb:kd_upb,
							kd_aset1:kd_aset1,
							kd_aset2:kd_aset2,
							kd_aset3:kd_aset3,
							kd_aset4:kd_aset4,
							kd_aset5:kd_aset5,
							no_register:no_register,
							jenis_dokumen:jenis_dokumen,
							no_dokumen:no_dokumen};

            if(confirm("Yakin data ini mau di Hapus ??")){
                    $( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
                    $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>pemanfaatan/ajax_hapus",
                            data:kode,
                            cache: false,
                            success: function(html){
                                $("#listhapus_"+no).fadeTo(300,0, function() {
                                                        $(this).animate({width:0},200,function(){
                                                                $(this).remove();
                                                            });
                                                    });
                                $("#loading" ).empty().html();
                                /*window.location.reload(true);*/
                        }
                    });
                    return false;
                    }
        });
    
   
        });
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
							echo form_dropdown('Jenis_Dokumen', $jenis_dokumen, $Jenis_Dokumen,'class="input-large required" id="Jenis_Dokumen"');
						  ?></td>
				      </tr>
			          <tr><td>Tgl Dokumen</td><td> : <input type=text name='Tgl_Dokumen' value="<?php echo $Tgl_Dokumen; ?>" id='datepicker' readonly='readonly' class="required input-small"></td>
			            </tr>
			          <tr>
			          		<td>No Dokumen</td>
			          		<td> :	<span style="font-size: 18px; ">032/</span>
			          				<input type=text name='No_Dokumen' id="No_Dokumen" class="input-mini" value="<?php echo $No_Dokumen; ?>" readonly="true">
			          				<span id="kode_surat" style="font-size: 18px; ">/<?php echo kode($Jenis_Dokumen); ?></span><span style="font-size: 18px; ">/<?php echo strtoupper($this->session->userdata('username')); ?>/<?php echo $this->session->userdata('tahun_anggaran'); ?></span></td>
			          </tr>
			          <tr id="Tgl_Pinjam" <?php if($Jenis_Dokumen == 1) {echo 'style="display:none;"';} ?> >
			          	<td>Tanggal Pinjam</td>
			          	<td> : <input type=text name='Tgl_Pinjam' value="<?php echo $Tgl_Pinjam; ?>" id='datepicker2' readonly='readonly' class="required input-small"></td>
			          </tr>
			          <tr id="Tgl_Kembali" <?php if($Jenis_Dokumen == 1) {echo 'style="display:none;"';} ?> >
			          	<td>Tanggal Kembali</td>
			          	<td> : <input type=text name='Tgl_Kembali' value="<?php echo $Tgl_Kembali; ?>" id='datepicker3' readonly='readonly' class="required input-small"></td>
			          </tr>
			          <tr><td colspan="2"><h3>IDENTITAS</h3><hr></td>
			            </tr>  
			          <tr><td>Nama Pihak Pertama</td>    <td> : <input type=text name='Nama_Pihak_1' value="<?php echo $Nama_Pihak_1; ?>" id='Nama_Pihak_1' class="input-xlarge"></td>
					    </tr>
					    <tr><td>NIP</td>    <td> : <input type=text name='Nip_Pihak_1' value="<?php echo $Nip_Pihak_1; ?>" id='Nip_Pihak_1' class="input-large"></td>
					    </tr>
					    <tr><td>Jabatan</td>    <td> : <input type=text name='Jabatan_Pihak_1' value="<?php echo $Jabatan_Pihak_1; ?>" id='Jabatan_Pihak_1' class="input-large"></td>
					    </tr>
					    <tr><td>Alamat</td>    <td> : <textarea name='Alamat_Pihak_1' id='Alamat_Pihak_1' style='width:60%;' class="input-large"><?php echo $Alamat_Pihak_1; ?></textarea></td>
					    </tr>
					   <tr><td colspan="2"><b><hr/></b></td>
					  <tr><td>Nama Pihak Kedua</td>    <td> : <input type=text name='Nama_Pihak_2' value="<?php echo $Nama_Pihak_2; ?>" id='Nama_Pihak_2' class="input-xlarge"></td>
					    </tr>
					    <tr><td>NIP</td>    <td> : <input type=text name='Nip_Pihak_2' value="<?php echo $Nip_Pihak_2; ?>" id='Nip_Pihak_2' class="input-large"></td>
					    </tr>
					    <tr><td>Jabatan</td>    <td> : <input type=text name='Jabatan_Pihak_2' value="<?php echo $Jabatan_Pihak_2; ?>" id='Jabatan_Pihak_2' class="input-large"></td>
					    </tr>
					    <tr><td>Alamat</td>    <td> : <textarea name='Alamat_Pihak_2' id='Alamat_Pihak_2' style='width:60%;' class="input-large"><?php echo $Alamat_Pihak_2; ?></textarea></td>
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
	                 <?php 
	                 $no  =0;
	                 $tmp =0;
	                 $i   =1;
	                 foreach ($rincian->result() as $row) {?>
	                    <tr id="<?php echo 'listhapus_'.$i; ?>">
		                    <input type="hidden"  name="<?php echo 'data2['.$no.'][kd_aset1_tmp]'; ?>" value="<?php echo $row->Kd_Aset1; ?>">
		                    <input type="hidden"  name="<?php echo 'data2['.$no.'][kd_aset2_tmp]'; ?>" value="<?php echo $row->Kd_Aset2; ?>">
		                    <input type="hidden"  name="<?php echo 'data2['.$no.'][kd_aset3_tmp]'; ?>" value="<?php echo $row->Kd_Aset3; ?>">
		                    <input type="hidden"  name="<?php echo 'data2['.$no.'][kd_aset4_tmp]'; ?>" value="<?php echo $row->Kd_Aset4; ?>">
		                    <input type="hidden"  name="<?php echo 'data2['.$no.'][kd_aset5_tmp]'; ?>" value="<?php echo $row->Kd_Aset5; ?>">
		                    <input type="hidden"  name="<?php echo 'data2['.$no.'][no_register_tmp]'; ?>" value="<?php echo $row->No_Register; ?>">
	                        <td width="90%">
	                        	<div class="form-inline">
								  <input type='text' name="<?php echo 'data2['.$no.'][kd_aset1]'; ?>" value="<?php echo $row->Kd_Aset1; ?>" id='<?php echo 'kd_aset1_'.$no; ?>' readonly='readonly' class="input-mini required">
								  <input type='text' name="<?php echo 'data2['.$no.'][kd_aset2]'; ?>" value="<?php echo $row->Kd_Aset2; ?>" id='<?php echo 'kd_aset2_'.$no; ?>' readonly='readonly' class="input-mini required">
								  <input type='text' name="<?php echo 'data2['.$no.'][kd_aset3]'; ?>" value="<?php echo $row->Kd_Aset3; ?>" id='<?php echo 'kd_aset3_'.$no; ?>' readonly='readonly' class="input-mini required">
								  <input type='text' name="<?php echo 'data2['.$no.'][kd_aset4]'; ?>" value="<?php echo $row->Kd_Aset4; ?>" id='<?php echo 'kd_aset4_'.$no; ?>' readonly='readonly' class="input-mini required">
								  <input type='text' name="<?php echo 'data2['.$no.'][kd_aset5]'; ?>" value="<?php echo $row->Kd_Aset5; ?>" id='<?php echo 'kd_aset5_'.$no; ?>' readonly='readonly' class="input-mini required">
								  <input type='text' name="<?php echo 'data2['.$no.'][no_register]'; ?>" value="<?php echo $row->No_Register; ?>" id='<?php echo 'no_register_'.$no; ?>' readonly='readonly' class="input-mini required">
						          <input type='text' name="<?php echo 'data2['.$no.'][nm_aset5]'; ?>" value="<?php echo $row->Nm_Aset5; ?>" id='<?php echo 'nm_aset5_'.$no; ?>' style='width:50%;' class="autocomplete input-xlarge" placeholder="ketik nama barang disini !" required>
						         </div>
						    </td>
	                        <td><?php echo anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'hapus','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'Jenis_Dokumen' => $row->Jenis_Dokumen,'No_Dokumen' => encrypt_url($row->No_Dokumen),'no'=>$i)); ?></td>
	                    </tr>
	                   <?php $no++; $tmp+=1; $i++; } ?>
	                 </tbody>
	                 <tfoot>
	                    <tr><td class='ket' colspan="2"><b>Keterangan</b></td>
					    </tr>
					  <tr><td colspan='2'><textarea name='Keterangan' style='width: 99%; height: 80px;'><?php echo $Keterangan; ?></textarea></td></tr>
	                 <tr>
	                      <td colspan="2">
	                        <div class="form-input">
	                            <button type="submit" class="btn large ui-state-default radius-all-4">Update</button>
	                            <button type="reset" class="btn large ui-state-default radius-all-4">Reset</button>
	                        </div>
	                      </td>
	                    </tr>
	                 </tfoot>
                </table>
          </form>

       </div><!-- span 12 -->
       
	</div>
</div>
<script>
function del_project(id){
    
    $("#project-wrap #row_project"+id).remove();
}
var $i=1;

function del_pic(id){

    $("#pic-table tbody #pic_row"+id).remove();
}
$j=<?php echo $tmp; ?>;
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
