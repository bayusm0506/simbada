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
												url : "<?php echo site_url('kibf/select_q')?>",
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
	$(function() {
	
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#form_input" ).dialog({
			resizable: false,
			autoOpen: false,
			height: 360,
			width: 400,
			modal: false,
			hide: 'Slide',
			buttons: {
				"Proses": function() {
												
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
					
					var kode = {
						no:$("#no").val(),kd_bidang:$("#kd_bidang").val(),kd_unit:$("#kd_unit").val(),kd_sub: $("#kd_sub").val(),
		kd_upb: $("#kd_upb").val(),
						kd_aset1:$("#kd_aset1").val(),kd_aset2:$("#kd_aset2").val(),kd_aset3: $("#kd_aset3").val(),
						kd_aset4:$("#kd_aset4").val(),kd_aset5:$("#kd_aset5").val(),no_register:$("#no_register").val(),
						tkd_bidang:$("#id_bidang").val(),tkd_unit:$("#idunit").val(),tkd_sub: $("#idsubunit").val(),tkd_upb: $("#idupb").val()
								};
								
				  $.ajax({
					url : "<?php echo site_url('kibf/pindahskpd')?>",
					type : 'POST',
					data : kode,
					success: function(msg){
						$("#form_input").dialog( "close" );
						$("#listhapus_"+$("#no").val()).slideUp('slow', function() {$(this).remove();});
						$("#loading" ).empty().html();
						window.location.reload(true);
						},
					error: function (data) {
						alert("gagal")
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

	});
	
	$(".pindah").live("click",function(){
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
		
		$('#no').val(no);
		$('#kd_bidang').val(kd_bidang);
		$('#kd_unit').val(kd_unit);
		$('#kd_sub').val(kd_sub);
		$('#kd_upb').val(kd_upb);
		$('#kd_aset1').val(kd_aset1);
		$('#kd_aset2').val(kd_aset2);
		$('#kd_aset3').val(kd_aset3);
		$('#kd_aset4').val(kd_aset4);
		$('#kd_aset5').val(kd_aset5);
		$('#no_register').val(no_register);
		
        $( "#form_input" ).dialog( "open" );
        
        return false;
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
				url: "<?php echo base_url(); ?>kibf/ajax_hapus",
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
					url: "<?php echo base_url(); ?>kibf/ajax_hapus",
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
					url: "<?php echo base_url(); ?>kibf/aktifkan",
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
				url: "<?php echo base_url(); ?>kibf/aktifkan",
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
<!-- end script aktifkan data ->
	
		
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
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'KIB F. KONSTRUKSI DALAM PENGERJAAN'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">
                        
                        <!-- tab w/ pagination and search controls -->
                        <ul class="nav nav-tabs">
                            <li <?php if ($this->uri->segment(2) == 'upb' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($tab1) ?  $tab1: '#'; ?>" class="tab-page" data-toggle="tab">DATA KIB F. KONSTRUKSI DALAM PENGERJAAN</a></li>
                            <li <?php if ($this->uri->segment(2) == 'waiting' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($tab2) ?  $tab2: '#'; ?>" class="tab-page"  data-toggle="tab">BELUM DI PERIKSA
                            <span class="badge"><?php echo !empty($jml_waiting) ? $jml_waiting : '0';?></span></a></li>
                            <?php if ($this->session->userdata('lvl') != '03' ){ ?>
                                <li <?php if ($this->uri->segment(2) == 'skpd' ) {echo "class='active'";}?>>
                                <a href="<?php echo ! empty($tab3) ?  $tab3: '#'; ?>" class="tab-page"  data-toggle="tab">DATA PER SKPD</a></li>
							<?php } ?>                                
                        </ul>
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>kibf/set/>
                                     <?php echo form_dropdown("q",$option_q,'','class="input-medium" id="search"'); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Pembelian </strong></span>
                                     <input type=text name='tanggal1' id='datepicker' class='input-small' readonly='readonly'> 
                                     <span><strong> s/d</strong></span>
                                     <input type=text name='tanggal2' id='datepicker2' class='input-small' readonly='readonly'> 
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                        <a class="btn" href="<?php echo base_url(); ?>kibf/export"><i class="icon-ok-circle"></i> Excell</a>
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
                                        <li><a href="#" class="hapus_semua">Hapus dipilih</a></li>
                                        <?php if($this->session->userdata('lvl') == '01'){
                                        echo '<li><a href="#" class="aktifkan_semua">Aktifkan dipilih</a></li>';
										} ?>
                                    </ul>
                                </div>
                                <!-- <button class="btn"><i class="icon-edit"></i> Edit Selected</button> -->
                                <div class="btn-group">
                                    <a href="<?php echo base_url() ?>kibf/add">
                                    <button class="btn btn-success"><i class="icon-plus-sign"></i> Tambah Data</button>
                                    </a>
                                </div>
                                
                                
                                
                            </div>
                  </div>

                  <table class="table table-striped">
                    <thead>
                      <tr>
                          <th><input type="checkbox" id="cek-all"/></th>
                          <th>No</th>
                          <th>Nama Barang</th>
                          <th>Register</th>
                          <th>Beton/ Tidak</th>
                          <th>Luas Lantai</th>
                          <th>Tgl Perolehan</th>
                          <th>Alamat</th>
                          <th style='text-align:right;'>Harga</th>
                          <th>Keterangan</th>
                          <th><i class='icon-wrench'></i> Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
					$i= $offset + 1;
					foreach ($query->result() as $row){
					$thn 		= date('Y', strtotime($row->Tgl_Perolehan));
                      echo "<tr id='listhapus_$i'>
                          <td><input class='checkbox1' type='checkbox' name='check[]' value='item1' Kd_Prov = '$row->Kd_Prov' Kd_Kab_Kota = '$row->Kd_Kab_Kota' Kd_Bidang = '$row->Kd_Bidang' Kd_Unit = '$row->Kd_Unit' Kd_Sub = '$row->Kd_Sub' Kd_UPB = '$row->Kd_UPB' Kd_Aset1 = '$row->Kd_Aset1' Kd_Aset2 = '$row->Kd_Aset2' Kd_Aset3 = '$row->Kd_Aset3' Kd_Aset4 = '$row->Kd_Aset4' Kd_Aset5 = '$row->Kd_Aset5' No_Register = '$row->No_Register' no='$i'></td>
                          <td>$i</td>
                          <td>$row->Nm_Aset5</td>
						  <td>$row->No_Register</td>
						  <td>$row->Beton_tidak</td>
						  <td>$row->Luas_Lantai</td>
						  <td>".tgl_indo($row->Tgl_Perolehan)."</td>
                          <td>$row->Lokasi</td>
						  <td style='text-align:right;'>".rp($row->Harga)."</td>
                          <td width='20%'>$row->Keterangan</td>
                          <td>
                                            <div class='btn-group'>
                                                <button class='btn'>Options</button>
                                                <button class='btn dropdown-toggle' data-toggle='dropdown'>
                                                    <span class='caret'></span>
                                                </button>
                                                <ul class='dropdown-menu'><li>".anchor('kibf/lihat/'
			.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,
			"<i class='icon-eye-open'></i>Lihat",array('class' => 'update'))."</li>
												<li>".anchor('kibf/update/'
			.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,
			"<i class='icon-pencil'></i>Ubah",array('class' => 'update')).'</li><li>'
			.anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'hapus','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i)).'</li>';
			if($this->session->userdata('lvl') == '01'){
				echo "<li class=divider></li>";
				echo "<li>".anchor(current_url().'#',"<i class='icon-ok'></i>Aktifkan",array('class'=> 'aktifkan','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i))."</li>";
			}
		echo "<li>".anchor(current_url().'#',"<i class='icon-refresh'></i>Pindah SKPD",array('class'=> 'pindah','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i))."</li>";
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
        <?php
		$this->load->view('adminweb/chain/pindahskpd');
		?>