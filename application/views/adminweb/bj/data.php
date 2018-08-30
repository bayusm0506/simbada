<script type="text/javascript">
$(function(){
    // link to pages instead of tabs
    $('.tab-page').click(function(){
        var _url = $(this).attr("href");
        location.href = _url;
    });
    // bind the checkbox change event to the 'edit selected' dropdown disabled state
    $('input[type=checkbox]').change(function(){
        if ($('input:checkbox:checked').length > 0){
            $('.edit-selected').removeClass('disabled');
        }else{
            $('.edit-selected').addClass('disabled');
        }
    });
	
	$('#cek-all').click(function() {
    	
		  //on click
        if(this.checked) { // check select status
			$('.edit-selected').removeClass('disabled');
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
			$('.edit-selected').addClass('disabled');
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });        
        }
	
	});
	

$( "#search" ).change(function() {
	var t = $('#search').val();
	 if (t != "all" && t != "" && t != "all_skpd"){    		
	    								$('#kd_unit').attr("disabled",true);
										
										$.ajax({
												type: "POST",
												url : "<?php echo site_url('penghapusan/select_q')?>",
												data: "q="+t,
												success: function(msg){
													$('#f_cari').html(msg);
												}, 
												error:function(XMLHttpRequest){
													alert(XMLHttpRequest.responseText);
												}
												
												
										});				
										
										
				}else{
					$("#cari").hide();
				}
});
	
	$("span.icon input:checkbox, th input:checkbox").click(function() {
		var checkedStatus = this.checked;
		var checkbox = $(this).parents('.widget-box').find('tr td:first-child input:checkbox');		
		checkbox.each(function() {
			this.checked = checkedStatus;
			if (checkedStatus == this.checked) {
				$(this).closest('.checker > span').removeClass('checked');
			}
			if (this.checked) {
				$(this).closest('.checker > span').addClass('checked');
			}
		});
	});	
	
	
});
</script>
    
<script>
	$(document).ready(function(){
	
	$("#form_view").submit(function(e){
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

	
		$(".hapus").click(function(event) {
			var no          = $(this).attr("no");
			var Tahun       = $(this).attr("Tahun");
			var Kd_Prov     = $(this).attr("Kd_Prov");
			var Kd_Kab_Kota = $(this).attr("Kd_Kab_Kota");
			var Kd_Bidang   = $(this).attr("Kd_Bidang");
			var Kd_Unit     = $(this).attr("Kd_Unit");
			var Kd_Sub      = $(this).attr("Kd_Sub");
			var Kd_UPB      = $(this).attr("Kd_UPB");
			var No_ID       = $(this).attr("No_ID");

			
			if(confirm("Yakin data ini mau di Hapus ??"))
			{
				$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
				$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>bj/delete/"+Tahun+"/"+Kd_Prov+"/"+Kd_Kab_Kota+"/"+Kd_Bidang+"/"+Kd_Unit+"/"+Kd_Sub+"/"+Kd_UPB+"/"+No_ID,
					 	cache: false,
		            	dataType: "JSON",
					 	success: function(html){
					 		$("#listhapus_"+no).fadeTo(300,0, function() {
													$(this).animate({width:0},200,function(){
															$(this).remove();
														});
												});
							$("#loading" ).empty().html();
							window.location.reload(true);
					}
				});
				return false;
				}
							
		});
	
		
	});
</script>

    
    <?php
	
	echo ! empty($message) ? '<p class="alert alert-succes">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="alert alert-succes">' . $flashmessage . '</p>': '';
?>
<div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'Data Pengadaan'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">
                        
                        <!-- tab w/ pagination and search controls -->
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>pengadaan/set/>
                                     <?php echo form_dropdown("q",$option_q,'','class="input-medium" id="search"'); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Kontrak </strong></span>
                                     <input type=text name='tanggal1' id='datepicker' class='input-small' readonly='readonly'> 
                                     <span><strong> s/d</strong></span>
                                     <input type=text name='tanggal2' id='datepicker2' class='input-small' readonly='readonly'> 
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                    </form>
                                </div>

                                <div class="list-pagination">
                                   <?php echo ! empty($pagination) ? $pagination: '<button class="btn"><i class="icon-filter"></i></button>';?>
                                </div>                              

                            </div>
                             
                                                  
                        </div>
                        <!-- end tab content --> 
                        

                        

                	<div class="list-actions">
                            <div class="btn-toolbar">
                                <div class="btn-group">
                                    <button class="btn disabled edit-selected"><i class="icon-edit"></i>Aksi</button>
                                    <button class="btn dropdown-toggle disabled edit-selected" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">Pilih Aksi</li>
                                        <li><a href="#" class="hapus_semua">Hapus yang dipilih</a></li>
                                    </ul>
                                </div>
                                <!-- <button class="btn"><i class="icon-edit"></i> Edit Selected</button> -->
                                <div class="btn-group">
                                    <a href="<?php echo base_url() ?>bj/add">
                                    <button class="btn btn-success"><i class="icon-plus-sign"></i> Tambah Data</button>
                                    </a>
                                </div>
                                
                                
                            </div>
                  </div>

                  <table class="table">
                    <thead>
                      <tr>
                          <th><input type="checkbox" id="cek-all"/></th>
                          <th>No</th>
                          <th>Uraian Kegiatan</th>
                          <th>Tgl Kontrak</th>
                          <th>No Kontrak</th>
                          <th>Jumlah</th>
                          <th>Harga Satuan</th>
                          <th>Total</th>
                          <th>Dipergunakan pada Unit</th>
                          <th>Keterangan</th>
                          <th><i class='icon-wrench'></i> Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
					$i= $offset + 1;
					foreach ($query as $row){

                      echo "<tr id='listhapus_$i'>
                          <td><input class='checkbox1' type='checkbox' name='check[]' Kd_Prov = '$row->Kd_Prov' Kd_Kab_Kota = '$row->Kd_Kab_Kota' Kd_Bidang = '$row->Kd_Bidang' Kd_Unit = '$row->Kd_Unit' Kd_Sub = '$row->Kd_Sub' Kd_UPB = '$row->Kd_UPB' No_ID = '$row->No_ID' no='$i'></td>
                          <td>$i</td>
						  <td>$row->Uraian_Kegiatan</td>
						  <td>".tgl_indo($row->Tgl_Kontrak)."</td>
						  <td>$row->No_Kontrak</td>
						  <td style='text-align:center'>".nilai($row->Jumlah)." Paket</td>
						  <td style='text-align:right'>".rp($row->Harga)."</td>
                          <td style='text-align:right'>".rp($row->Harga*$row->Jumlah)."</td>
						  <td>$row->Dipergunakan</td>
                          <td>$row->Keterangan</td>
                            <td>
                                            <div class='btn-group'>
                                                <button class='btn'>Aksi</button>
                                                <button class='btn dropdown-toggle' data-toggle='dropdown'>
                                                    <span class='caret'></span>
                                                </button>
                                                <ul class='dropdown-menu'>
                                                	<li>".anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'hapus','Tahun' => $row->Tahun, 'Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'No_ID' => $row->No_ID,'no'=>$i))."</li>
                                                	<li>".anchor('bj/update/'.$row->Tahun.'/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->No_ID,"<i class='icon-pencil'></i>Ubah",array('class' => 'update'))."</li>
												</ul>
                                            </div>
                                        </td>
                      </tr>";
				 $i++;
				} ?>
                      
                    </tbody>
                  </table>                       

                </div>
                </div>

            </div>
        </div>