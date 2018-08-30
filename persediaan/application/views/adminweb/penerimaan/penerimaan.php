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
	 if (t != "all" && t != ""){    		
					$('#kd_unit').attr("disabled",true);
					
					$.ajax({
							type: "POST",
							url : "<?php echo site_url('penerimaan/select_q')?>",
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
	
	<!-- tanggal cek ->
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
				url: "<?php echo base_url(); ?>penerimaan/deletekontrak/"+Kd_Prov+"/"+Kd_Kab_Kota+"/"+Kd_Bidang+"/"+Kd_Unit+"/"+Kd_Sub+"/"+Kd_UPB+"/"+No_ID,
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
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'Persediaan - Barang Masuk'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">
                        
                        <!-- tab w/ pagination and search controls -->
                        <ul class="nav nav-tabs">
                            <li <?php if ($this->uri->segment(2) == 'upb' || $this->uri->segment(2) == '' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($tab1) ?  $tab1: '#'; ?>" class="tab-page" data-toggle="tab">DATA PENERIMAAN BARANG
                            <span class="badge"><?php echo ! empty($jumlah) ? $jumlah : '0';?></span></a></li>                              
                        </ul>
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>penerimaan/set/>
                                     <?php echo form_dropdown("q",$option_q,'','class="input-medium" id="search"'); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Pembelian </strong></span>
                                     <input type=text name='tanggal1' id='datepicker' class='input-small' readonly='readonly'> 
                                     <span><strong> s/d</strong></span>
                                     <input type=text name='tanggal2' id='datepicker2' class='input-small' readonly='readonly'> 
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                        <a class="btn" href="<?php echo base_url(); ?>penerimaan/export"><i class="icon-ok-circle"></i> Excell</a>
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
                                        <?php if($this->session->userdata('lvl') == '01'){ ?>
                                        	<li><a href="#" class="hapus_semua">Hapus yang dipilih</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <!-- <button class="btn"><i class="icon-edit"></i> Edit Selected</button> -->
                                <div class="btn-group">
                                    <a href="<?php echo base_url() ?>penerimaan/add">
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
                          <th>Tgl Diterima</th>
                          <th>Dari (Nama Rekanan)</th>
                          <th>No Kontrak/SPK/Kwitansi</th>
                          <th>Tanggal Kontrak/SPK/Kwitansi</th>
                          <th>No BA Pemeriksaan</th>
                          <th>Tgl BA Pemeriksaan</th>
                          <th style='text-align:right;'>Harga</th>
                          <th>Keterangan</th>
                          <th><i class='icon-wrench'></i> Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
					$i= $offset + 1;
					foreach ($query->result() as $row){
                    echo "<td><input class='checkbox1' type='checkbox' name='check[]' value='item1' Kd_Prov = '$row->Kd_Prov' Kd_Kab_Kota = '$row->Kd_Kab_Kota' Kd_Bidang = '$row->Kd_Bidang' Kd_Unit = '$row->Kd_Unit' Kd_Sub = '$row->Kd_Sub' Kd_UPB = '$row->Kd_UPB' no='$i'></td>
                    	  <td>$i</td>
                          <td>".$row->Tgl_Diterima."</td>
            						  <td>".$row->Nm_Rekanan."</td>
            						  <td>".$row->No_Kontrak."</td>
            						  <td>".$row->Tgl_Kontrak."</td>
            						  <td>".$row->No_BA_Pemeriksaan."</td>
            						  <td>".$row->Tgl_BA_Pemeriksaan."</td>
            						  <td style='text-align:right;'>".rp($row->Total)."</td>
            						  <td>".$row->Keterangan."</td>
                          <td>
                                <div class='btn-group'>
                                    <button class='btn'>Options</button>
                                    <button class='btn dropdown-toggle' data-toggle='dropdown'>
                                        <span class='caret'></span>
                                    </button>
                                    <ul class='dropdown-menu'>";

		       	echo "<li>".anchor('penerimaan/detail/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->No_ID,"<i class='icon-eye-open'></i> Detail",array('class' => 'update'))."</li>";
		    	echo "<li class=divider></li>";
		       	echo "<li>".anchor('penerimaan/edit/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->No_ID,"<i class='fa fa-pencil'></i> Edit",array('class' => 'update'))."</li>";
				echo '<li>'.anchor(current_url().'#',"<i class='fa fa-trash'></i> Hapus",array('class'=> 'hapus','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'No_ID' => $row->No_ID,'no'=>$i)).'</li>';
                echo "</ul>
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