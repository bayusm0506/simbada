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
	
	$(".hapus_semua").click(function(event) {
	if(confirm("Yakin data yang dipilih akan dihapus dalam usulan penghapusan ??")){	
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
					url: "<?php echo base_url(); ?>penghapusan/ajax_hapus_e",
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

$(".batal_usulan").click(function(event) {
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
	var kd_id       = $(this).attr("Kd_Id");
	
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
					kd_id:kd_id};

	
	if(confirm("Yakin data ini akan dihapus dalam usulan penghapusan ??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>penghapusan/hapus_usul_e",
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

$(".tolak_usulan").click(function(event) {
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
	var kd_id       = $(this).attr("Kd_Id");
	var status      = $(this).attr("Status");
	var no_sk       = <?php echo "'$sk_tmp'"; ?>;			
		
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
					no_register:no_register,
					status:status,
					kd_id:kd_id
				};

	
	if(confirm("Anda yakin data ini akan menolak data ini??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url() ?>penghapusan/proses_usul_e",
				data:kode,
			 	cache: false,
			 	success: function(html){
			 		$("#listhapus_"+no).fadeTo(300,0, function() {
											$(this).animate({width:0},200,function(){
													$(this).remove();
												});
										});
					// $("#loading" ).empty().html();
					window.location.reload(true);
			}
		});
		return false;
		}
					
});

$(".terima_usulan").click(function(event) {
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
	var kd_id       = $(this).attr("Kd_Id");
	var status      = $(this).attr("Status");
	var no_sk       = <?php echo "'$sk_tmp'"; ?>;			
		
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
					no_register:no_register,
					status:status,
					kd_id:kd_id
				};

	
	if(confirm("Data ini akan dimasukan kedalam daftar penghapusan dengan no SK "+no_sk+" ??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url() ?>penghapusan/proses_usul_e",
				data:kode,
			 	cache: false,
			 	success: function(html){
			 		$("#listhapus_"+no).fadeTo(300,0, function() {
											$(this).animate({width:0},200,function(){
													$(this).remove();
												});
										});
					// $("#loading" ).empty().html();
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

<?php  echo ! empty($sk_tmp) ? '<p class="alert alert-penghapusan"> <i class="icon icon-backward"></i>
<a class="a-link" href="'.site_url('penghapusan/rincian_kibe/'.encrypt_url($sk_tmp)).'"> Daftar Penghapusan </a> <span class="pull-right">SK PENGHAPUSAN : ' . strtoupper($sk_tmp) . '</span></p>': '';?>

<div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'KIB A. TANAH'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">
                        
                        <!-- tab w/ pagination and search controls -->
                        <ul class="nav nav-tabs">
                            <li <?php if ($this->uri->segment(2) == 'kiba' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($kiba) ?  $kiba: '#'; ?>" class="tab-page" data-toggle="tab">KIB A</a></li>
                           	
                            <li <?php if ($this->uri->segment(2) == 'kibb' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($kibb) ?  $kibb: '#'; ?>" class="tab-page" data-toggle="tab">KIB B</a></li>
                            
                            <li <?php if ($this->uri->segment(2) == 'kibc' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($kibc) ?  $kibc: '#'; ?>" class="tab-page" data-toggle="tab">KIB C</a></li>
                            
                            <li <?php if ($this->uri->segment(2) == 'kibd' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($kibd) ?  $kibd: '#'; ?>" class="tab-page" data-toggle="tab">KIB D</a></li>
                            
                            <li <?php if ($this->uri->segment(2) == 'kibe' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($kibe) ?  $kibe: '#'; ?>" class="tab-page" data-toggle="tab">KIB E
                             <span class="badge"><?php echo ! empty($jumlah) ? $jumlah : '0';?></span></a></li>
                             
                            <li <?php if ($this->uri->segment(2) == 'lainnya' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($lainnya) ?  $lainnya: '#'; ?>" class="tab-page" data-toggle="tab">ASET LAINNYA</a></li>

                        </ul>
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>penghapusan/set/>
                                     <?php echo form_dropdown("q",$option_q,'','class="input-medium" id="search"'); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Pembelian </strong></span>
                                     <input type=text name='tanggal1' id='datepicker' class='input-small' readonly='readonly'> 
                                     <span><strong> s/d</strong></span>
                                     <input type=text name='tanggal2' id='datepicker2' class='input-small' readonly='readonly'> 
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                        <a class="btn" href="<?php echo base_url(); ?>penghapusan/export"><i class="icon-ok-circle"></i> Excell</a>
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
                                        <li><a href="#" class="hapus_semua">Hapus yang dipilih</a></li>
                                    </ul>
                                </div>
                                <!-- <button class="btn"><i class="icon-edit"></i> Edit Selected</button> -->
                                
                                
                                
                            </div>
                  </div>

                  <table class="table table-striped">
                    <thead>
                      <tr>
                          <th><input type="checkbox" id="cek-all"/></th>
                          <th>No</th>
                          <th>UPB</th>
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
					foreach ($query as $row){
					if ($row->Status == 3){
					$status = 'Ditolak';
					}elseif ($row->Status == 2){
						$status = 'Diterima';
					}else{
						$status = 'Belum di proses';
					}
					$thn 		= date('Y', strtotime($row->Tgl_Perolehan));
                      echo "<tr id='listhapus_$i'>
                          <td><input class='checkbox1' type='checkbox' name='check[]' value='item1' Kd_Prov = '$row->Kd_Prov' Kd_Kab_Kota = '$row->Kd_Kab_Kota' Kd_Bidang = '$row->Kd_Bidang' Kd_Unit = '$row->Kd_Unit' Kd_Sub = '$row->Kd_Sub' Kd_UPB = '$row->Kd_UPB' Kd_Aset1 = '$row->Kd_Aset1' Kd_Aset2 = '$row->Kd_Aset2' Kd_Aset3 = '$row->Kd_Aset3' Kd_Aset4 = '$row->Kd_Aset4' Kd_Aset5 = '$row->Kd_Aset5' No_Register = '$row->No_Register' no='$i'></td>
                          <td>$i</td>
                          <td>$row->Nm_UPB</td>
						  <td>$row->Nm_Aset5</td>
						  <td>$row->No_Register</td>
						  <td>".tgl_indo($row->Tgl_Perolehan)."</td>
						  <td>$row->Judul</td>
						  <td>$row->Pencipta</td>
						  <td>$row->Bahan</td>
						  <td style='text-align:right;'>".rp($row->Harga)."</td>
						  <td>$row->Keterangan</td>
                          <td>
                                <div class='btn-group'>
                                    <button class='btn'>Aksi</button>
                                    <button class='btn dropdown-toggle' data-toggle='dropdown'>
                                        <span class='caret'></span>
                                    </button>
                                    <ul class='dropdown-menu'>
                                    <li>".anchor(current_url().'#',"<i class='icon-retweet'></i>Batalkan Usulan",array('class'=> 'batal_usulan','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'Kd_Id' => $row->Kd_Id,'no'=>$i))."</li>";

                                    echo "<li>".anchor(current_url().'#',"<i class='icon-retweet'></i>Tolak Usulan",array('class'=> 'tolak_usulan','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'Kd_Id' => $row->Kd_Id,'Status' => 3,'no'=>$i))."</li>";

                                    if(!empty($sk_tmp)){		
                                    	echo "<li>".anchor(current_url().'#',"<i class='icon-retweet'></i>Terima Usulan",array('class'=> 'terima_usulan','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'Kd_Id' => $row->Kd_Id,'Status' => 2,'no'=>$i))."</li>";
                                    }

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