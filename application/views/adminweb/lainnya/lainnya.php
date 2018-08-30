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
												url : "<?php echo site_url('lainnya/select_q')?>",
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
	<!-- end tanggal ->
	
	<!-- start hapus  data ->
	$(".hapus").click(function(event) {
	var no 		 = $(this).attr("no");
	var kd_bidang 	= $(this).attr("Kd_Bidang");
	var kd_unit 	= $(this).attr("Kd_Unit");
	var kd_sub 		= $(this).attr("Kd_Sub");
	var kd_upb 		= $(this).attr("Kd_UPB");
	
	var kd_aset1 = $(this).attr("Kd_Aset1");
	var kd_aset2 = $(this).attr("Kd_Aset2");
	var kd_aset3 = $(this).attr("Kd_Aset3");
	var kd_aset4 = $(this).attr("Kd_Aset4");
	var kd_aset5 = $(this).attr("Kd_Aset5");
	var no_register = $(this).attr("No_Register");
	
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
					no_register:no_register};

	
	if(confirm("Yakin data ini mau di Hapus ??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>lainnya/ajax_hapus",
				data:kode,
			 	cache: false,
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
<!-- end hapus data ->

	<!-- start script hapus semua data ->
	$(".hapus_semua").click(function(event) {
	if(confirm("Yakin data ini mau di Hapus ??")){	
		i=0;
		$("input.checkbox1:checked").each(function(){
			var no 		 	= $(this).attr("no");
			var kd_bidang 	= $(this).attr("Kd_Bidang");
			var kd_unit 	= $(this).attr("Kd_Unit");
			var kd_sub 		= $(this).attr("Kd_Sub");
			var kd_upb 		= $(this).attr("Kd_UPB");
			
			var kd_aset1 	= $(this).attr("Kd_Aset1");
			var kd_aset2 	= $(this).attr("Kd_Aset2");
			var kd_aset3 	= $(this).attr("Kd_Aset3");
			var kd_aset4 	= $(this).attr("Kd_Aset4");
			var kd_aset5 	= $(this).attr("Kd_Aset5");
			var no_register = $(this).attr("No_Register");
			
				
			var kode= {
							kd_bidang:kd_bidang,
							kd_unit:kd_unit,
							kd_sub:kd_sub,
							kd_upb:kd_upb,
							kd_aset1:kd_aset1,
							kd_aset2:kd_aset2,
							kd_aset3:kd_aset3,
							kd_aset4:kd_aset4,
							kd_aset5:kd_aset5,
							no_register:no_register};
			i++;
			$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>lainnya/ajax_hapus",
					data:kode,
					cache: false,
					success: function(html){
						$("#listhapus_"+no).fadeTo(300,0, function() {
												$(this).animate({width:0},200,function(){
														$(this).remove();
													});
											});
						$('.checkbox1').each(function() { //loop through each checkbox
							this.checked = false; //deselect all checkboxes with class "checkbox1"                      
						});
						$("#loading" ).empty().html();
						window.location.reload(true);
				}
			});
		})
		return false;
	}
					
});
<!-- end script hapus semua data ->

<!-- start script aktifkan semua data ->
$(".aktifkan_semua").click(function(event) {
	if(confirm("Yakin data ini sudah benar dan akan diaktifkan ??")){
		i=0;
		$("input.checkbox1:checked").each(function(){
			var no 		 	= $(this).attr("no");
			var kd_bidang 	= $(this).attr("Kd_Bidang");
			var kd_unit 	= $(this).attr("Kd_Unit");
			var kd_sub 		= $(this).attr("Kd_Sub");
			var kd_upb 		= $(this).attr("Kd_UPB");
			
			var kd_aset1 	= $(this).attr("Kd_Aset1");
			var kd_aset2 	= $(this).attr("Kd_Aset2");
			var kd_aset3 	= $(this).attr("Kd_Aset3");
			var kd_aset4 	= $(this).attr("Kd_Aset4");
			var kd_aset5 	= $(this).attr("Kd_Aset5");
			var no_register = $(this).attr("No_Register");
			
				
			var kode= {
							kd_bidang:kd_bidang,
							kd_unit:kd_unit,
							kd_sub:kd_sub,
							kd_upb:kd_upb,
							kd_aset1:kd_aset1,
							kd_aset2:kd_aset2,
							kd_aset3:kd_aset3,
							kd_aset4:kd_aset4,
							kd_aset5:kd_aset5,
							no_register:no_register};
			i++;
			$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>lainnya/aktifkan",
					data:kode,
					cache: false,
					success: function(html){
						$("#listhapus_"+no).fadeTo(300,0, function() {
												$(this).animate({width:0},200,function(){
														$(this).remove();
													});
											});
						$('.checkbox1').each(function() { //loop through each checkbox
							this.checked = false; //deselect all checkboxes with class "checkbox1"                      
						});
						$("#loading" ).empty().html();
						window.location.reload(true);
				}
			});
		})
		
		return false;
	}
					
});
<!-- end script aktifkan semua data ->

<!-- start script aktifkan data ->
$(".aktifkan").click(function(event) {
	var no 		 = $(this).attr("no");
	var kd_bidang 	= $(this).attr("Kd_Bidang");
	var kd_unit 	= $(this).attr("Kd_Unit");
	var kd_sub 		= $(this).attr("Kd_Sub");
	var kd_upb 		= $(this).attr("Kd_UPB");
	
	var kd_aset1 = $(this).attr("Kd_Aset1");
	var kd_aset2 = $(this).attr("Kd_Aset2");
	var kd_aset3 = $(this).attr("Kd_Aset3");
	var kd_aset4 = $(this).attr("Kd_Aset4");
	var kd_aset5 = $(this).attr("Kd_Aset5");
	var no_register = $(this).attr("No_Register");
	
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
					no_register:no_register};

	
	if(confirm("Yakin data ini sudah benar dan akan diaktifkan ??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>lainnya/aktifkan",
				data:kode,
			 	cache: false,
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

$(".usul_guna").click(function(event) {
	var no          = $(this).attr("no");
	var kd_bidang   = $(this).attr("Kd_Bidang");
	var kd_unit     = $(this).attr("Kd_Unit");
	var kd_sub      = $(this).attr("Kd_Sub");
	var kd_upb      = $(this).attr("Kd_UPB");
	
	var kd_aset1    = $(this).attr("Kd_Aset1");
	var kd_aset2    = $(this).attr("Kd_Aset2");
	var kd_aset3    = $(this).attr("Kd_Aset3");
	var kd_aset4    = $(this).attr("Kd_Aset4");
	var kd_aset5    = $(this).attr("Kd_Aset5");
	var no_register = $(this).attr("No_Register");
	
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
					no_register:no_register};

	
	if(confirm("Data ini akan diusulkan untuk penetapan status penggunaan barang ??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>lainnya/usul_guna",
				data:kode,
			 	cache: false,
			 	success: function(html){
					$("#listhapus_"+no).toggleClass( "css_usulguna", 1000, "easeOutSine" );
					$('[data-toggle="dropdown"]').parent().removeClass('open');
					$("#loading" ).empty().html();
			}
		});
		return false;
		}
					
});

	});
</script>

<script>
$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$(".usul_hapus").live("click",function(){
		var no          = $(this).attr("no");
		var kd_bidang   = $(this).attr("Kd_Bidang");
		var kd_unit     = $(this).attr("Kd_Unit");
		var kd_sub      = $(this).attr("Kd_Sub");
		var kd_upb      = $(this).attr("Kd_UPB");
		
		var kd_aset1    = $(this).attr("Kd_Aset1");
		var kd_aset2    = $(this).attr("Kd_Aset2");
		var kd_aset3    = $(this).attr("Kd_Aset3");
		var kd_aset4    = $(this).attr("Kd_Aset4");
		var kd_aset5    = $(this).attr("Kd_Aset5");
		var no_register = $(this).attr("No_Register");

		var page = "<?php echo site_url('lainnya/add_usul_hapus')?>";
		var $dialog = $('<div></div>')
		               // .html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
   		               .load(page)
		               .dialog({
		    title:"Usul Penghapusan Barang Aset Lainnya",
		    modal: true,
		    draggable: false,
		    resizable: false,
		    position: ['center'],
		    show: 'blind',
		    hide: 'fade',
			height: 450,
			width: 650,

			buttons: {
				"Proses": function() {

					// alert("val "+kd_aset5); return true;
					var kode = {
								no:no,kd_bidang:kd_bidang,
								kd_unit:kd_unit,kd_sub: kd_sub,
								kd_upb: kd_upb,kd_aset1:kd_aset1,
								kd_aset2:kd_aset2,kd_aset3:kd_aset3,
								kd_aset4:kd_aset4,kd_aset5:kd_aset5,
								no_register:no_register,No_UP:$("#No_UP").val(),Tgl_UP:$("#Tgl_UP").val(),
								Kd_Alasan:$("#Kd_Alasan").val(),Ket_Alasan:$("#Ket_Alasan").val(),
								};
								
				  $.ajax({
					url : "<?php echo site_url('lainnya/proses_usul_hapus')?>",
					type : 'POST',
					data : kode,
					success: function(msg){
							// alert(msg+"data berhasil diusulkan sebagai daftar usulan penghapusan!"); exit();
							$dialog.dialog('close');
							$("#listhapus_"+no).addClass( "css_usulhapus", 1000);

						},
					error: function (data) {
						alert("gagal");
					}
				  });
				
			},

				Cancel: function() {
					$("#loading" ).empty().html();
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				$("#loading" ).empty().html();
			}
		});

	$dialog.dialog('open');
	});
	

});

$(function() {

		$(".batal_usul_hapus").live("click",function(){
		var no 		 	= $(this).attr("no");
		var kd_bidang 	= $(this).attr("Kd_Bidang");
		var kd_unit 	= $(this).attr("Kd_Unit");
		var kd_sub 		= $(this).attr("Kd_Sub");
		var kd_upb 		= $(this).attr("Kd_UPB");
		
		var kd_aset1 = $(this).attr("Kd_Aset1");
		var kd_aset2 = $(this).attr("Kd_Aset2");
		var kd_aset3 = $(this).attr("Kd_Aset3");
		var kd_aset4 = $(this).attr("Kd_Aset4");
		var kd_aset5 = $(this).attr("Kd_Aset5");
		var no_register = $(this).attr("No_Register");

		var kode = {
						no:no,kd_bidang:kd_bidang,
						kd_unit:kd_unit,kd_sub: kd_sub,
						kd_upb: kd_upb,kd_aset1:kd_aset1,
						kd_aset2:kd_aset2,kd_aset3:kd_aset3,
						kd_aset4:kd_aset4,kd_aset5:kd_aset5,
						no_register:no_register,batal:'Y'
						
						};
								
				  $.ajax({
					url : "<?php echo site_url('lainnya/proses_usul_hapus')?>",
					type : 'POST',
					data : kode,
					success: function(msg){
							alert("data berhasil dibatalkan sebagai daftar usulan penghapusan!");
							window.location.reload(true);

						},
					error: function (data) {
						alert("gagal");
					}
				  });
			
	});
	

});

</script>

<style>
.css_non_operasional {
background-color:#7CBA86;
}

.css_usulhapus {
background-color:#e67e22;
}

.css_usulguna {
background-color:#3F9D4A;
}
</style>
    
    <?php
	
	echo ! empty($message) ? '<p class="alert alert-succes">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="alert alert-succes">' . $flashmessage . '</p>': '';
?>
<div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'KIB E. ASET TETAP LAINYA'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">
                        
                        <!-- tab w/ pagination and search controls -->
                        <ul class="nav nav-tabs">
                            <li <?php if ($this->uri->segment(2) == 'upb' || $this->uri->segment(2) == '' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($tab1) ?  $tab1: '#'; ?>" class="tab-page" data-toggle="tab">DATA KIB E. ASET TETAP LAINYA
                            <span class="badge"><?php echo ! empty($jumlah) ? $jumlah : '0';?></span></a></li>
                            <li <?php if ($this->uri->segment(2) == 'waiting' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($tab2) ?  $tab2: '#'; ?>" class="tab-page"  data-toggle="tab">BELUM DI PERIKSA</a></li>
                            <?php if ($this->session->userdata('lvl') != '03' ){ ?>
                                <li <?php if ($this->uri->segment(2) == 'skpd' ) {echo "class='active'";}?>>
                                <a href="<?php echo ! empty($tab3) ?  $tab3: '#'; ?>" class="tab-page"  data-toggle="tab">DATA PER SKPD</a></li>
							<?php } ?>                                
                        </ul>
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>lainnya/set/>
                                     <?php echo form_dropdown("q",$option_q,'','class="input-medium" id="search"'); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Pembelian </strong></span>
                                     <input type=text name='tanggal1' id='datepicker' class='input-small' readonly='readonly'> 
                                     <span><strong> s/d</strong></span>
                                     <input type=text name='tanggal2' id='datepicker2' class='input-small' readonly='readonly'> 
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                        <a class="btn" href="<?php echo base_url(); ?>lainnya/export"><i class="icon-ok-circle"></i> Excell</a>
                                    </form>
                                </div>

                                <div class="list-pagination">
                                   <?php echo ! empty($pagination) ? $pagination: '<button class="btn"><i class="icon-filter"></i></button>';?>
                                </div>                              

                            </div>
                            
                            
                            <div class="tab-pane list-pane" id="tab2">

                                <div class="pull-right">
                                    <form class="form-inline" />
                                        <input type="text" placeholder="Search Subscriptions" name="search[subscriptions]" />
                                        <button class="btn"><i class="icon-search icon-large"></i></button>
                                    </form>
                                </div>

                                <div class="list-pagination">
                                    <span>Viewing records <strong>1-25</strong> of <strong>320</strong></span>
                                    <button class="btn disabled"><i class="icon-step-backward"></i></button>
                                    <button class="btn disabled"><i class="icon-caret-left"></i></button>
                                    <span>Page <strong>1</strong> of <strong>12</strong></span>
                                    <button class="btn"><i class="icon-caret-right"></i></button>
                                    <button class="btn"><i class="icon-step-forward"></i></button>
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
                                    <a href="<?php echo base_url() ?>lainnya/add">
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
                          <th>Nama Barang</th>
                          <th>Register</th>
                          <th>Tgl Perolehan</th>
                          <th>Judul</th>
                          <th>Pencipta</th>
                          <th>Bahan</th>
                          <th style='text-align:right;'>Harga</th>
                          <th>Keterangan</th>
                          <th><i class='icon-wrench'></i> Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
					$i= $offset + 1;
					foreach ($query->result() as $row){

						if($row->Penghapusan == 1){ /*diusulkan*/
							$bg_UP = "bgcolor='#e67e22'";	
						}elseif($row->Penghapusan == 2){ /*diterima*/
							$bg_UP = "bgcolor='#e74c3c'";	
						}elseif($row->Penghapusan == 3){ /*ditolak*/
							$bg_UP = "bgcolor='#ecf0f1'";	
						}else{
							$bg_UP = "";	
						}
					
                    echo "<tr id='listhapus_$i'>";
                      if(empty($row->Penghapusan)){	
                          	echo "<td><input class='checkbox1' type='checkbox' name='check[]' value='item1' Kd_Prov = '$row->Kd_Prov' Kd_Kab_Kota = '$row->Kd_Kab_Kota' Kd_Bidang = '$row->Kd_Bidang' Kd_Unit = '$row->Kd_Unit' Kd_Sub = '$row->Kd_Sub' Kd_UPB = '$row->Kd_UPB' Kd_Aset1 = '$row->Kd_Aset1' Kd_Aset2 = '$row->Kd_Aset2' Kd_Aset3 = '$row->Kd_Aset3' Kd_Aset4 = '$row->Kd_Aset4' Kd_Aset5 = '$row->Kd_Aset5' No_Register = '$row->No_Register' no='$i'></td>";
                       }else{
                       		echo "<td></td>";
                       }   
                    echo "<td>$i</td>
                          <td $bg_UP>$row->Nm_Aset5</td>
						  <td>$row->No_Register</td>
						  <td>".tgl_indo($row->Tgl_Perolehan)."</td>
						  <td>$row->Judul</td>
						  <td>$row->Pencipta</td>
						  <td>$row->Bahan</td>
						  <td style='text-align:right;'>".rp($row->Harga)."</td>
						  <td>$row->Keterangan</td>
                          <td>
                                            <div class='btn-group'>
                                                <button class='btn'>Options</button>
                                                <button class='btn dropdown-toggle' data-toggle='dropdown'>
                                                    <span class='caret'></span>
                                                </button>
                                                <ul class='dropdown-menu'><li>".anchor('lainnya/lihat/'
			.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,
			"<i class='icon-eye-open'></i>Lihat",array('class' => 'update'))."</li>
            <li>".anchor('lainnya/cetak/'
			.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,
			"<i class='icon-print'></i>Cetak Kartu",array('class' => 'update'))."</li>";

			if(empty($row->Penghapusan) || ($row->Penghapusan == 3)){

				if($row->Penghapusan <> 3){
					if($this->session->userdata('lvl') == '01'){                                    
						echo "<li>".anchor('lainnya/update/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,"<i class='icon-pencil'></i>Ubah",array('class' => 'update'))."</li>";
					}

					if($this->session->userdata('lvl') == '01'){
						echo '<li>'.anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'hapus no-drop','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i)).'</li>';
					}
				}
				echo "<li class=divider></li>";
				echo "<li><a class='usul_hapus pointer' Kd_Prov='$row->Kd_Prov' Kd_Kab_Kota='$row->Kd_Kab_Kota' Kd_Bidang='$row->Kd_Bidang' Kd_Unit='$row->Kd_Unit' Kd_Sub='$row->Kd_Sub' Kd_UPB='$row->Kd_UPB' Kd_Aset1='$row->Kd_Aset1' Kd_Aset2='$row->Kd_Aset2' Kd_Aset3='$row->Kd_Aset3' Kd_Aset4='$row->Kd_Aset4' Kd_Aset5='$row->Kd_Aset5' No_Register='$row->No_Register' no='$i'><i class='icon-random'></i>Usul Penghapusan</a></li>";
			}
			
			echo '<li>'.anchor(current_url().'#',"<i class='icon-tag'></i>Usul Penggunaan",array('class'=> 'usul_guna','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i)).'</li>';
				
			echo '<li>'.anchor(current_url().'#',"<i class='icon-ok'></i>Non Operasional",array('class'=> 'non_operasional','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'Kd_KA' => $row->Kd_KA,'no'=>$i));
				
			echo "</li>
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