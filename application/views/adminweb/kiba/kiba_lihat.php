<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.form.js'); ?>"></script>
	<script type="text/javascript"> 
        jQuery(document).ready(function() {
			jQuery('#form-upload').on('submit', function(e) {
				e.preventDefault();
				jQuery('#submit-button').attr('disabled', ''); 
				jQuery("#output").html('<div style="padding:10px"><img src="<?php echo base_url('assets/images/loading.gif'); ?>" alt="Please Wait"/> <span>Sedang Mengupload...</span></div>');
				jQuery(this).ajaxSubmit({
					target: '#output',
					success:  sukses 
				});
			});
			
			var count = 1;
            jQuery("#add_btn").click(function(){
				count += 1;
                jQuery('#fill').append('<tr class="records"><td><label>Foto '+count+' </label><input name="image[]" type="file" /> <a class="remove_item" href="#" >Delete</a></td></tr>');
			});
			
			jQuery(".remove_item").live('click', function (ev) {
                if (ev.type == 'click') {
					jQuery(this).parents(".records").fadeOut();
                        jQuery(this).parents(".records").remove();
				}
            });
        }); 

		function sukses()  { 
			jQuery('#form-upload').resetForm();
			jQuery('#submit-button').removeAttr('disabled');

		}	
		
</script>

<script type="text/javascript">
	
$(document).ready(function(){
   
		$('a.delete').on('click',function(e){
			e.preventDefault();
			var imageID = $(this).closest('.image')[0].id;
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
			var no_id = $(this).attr("No_Id");
			
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
							no_id:no_id};
							
							
							
			if(confirm("Yakin data ini mau di Hapus ??"))
			{
				$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
				$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>kiba/hapus_gambar",
						data:kode,
						cache: false,
						success: function(html){
							$("#image_"+no_id).fadeTo(300,0, function() {
											$(this).animate({width:0},200,function(){
													$(this).remove();
												});
										});
							$("#loading" ).empty().html();
					}
				});
				return false;
				}
			
		});
		
});

</script>

<script type="text/javascript">
	
	$(document).ready(function(){
   
		/*cetak*/
	$('.cetak').click(function(){
			window.open('', 'formpopup', 'width=800,height=600,left = 250,location=no,scrollbars=yes');
			this.target = 'formpopup';
	    });
	/*end cetak*/


	$(".hapus_riwayat").click(function(event) {
		var no          = $(this).attr("no");
		var Kd_Riwayat  = $(this).attr("Kd_Riwayat");
		var Kd_Id       = $(this).attr("Kd_Id");
		var Kd_Prov     = $(this).attr("Kd_Prov");
		var Kd_Kab_Kota = $(this).attr("Kd_Kab_Kota");
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
					Kd_Riwayat:Kd_Riwayat,
					Kd_Id:Kd_Id,
					Kd_Prov:Kd_Prov,
					Kd_Kab_Kota:Kd_Kab_Kota,
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
					url: "<?php echo base_url(); ?>kiba/hapus_riwayat",
					data:kode,
				 	cache: false,
				 	success: function(html){
				 		// alert(html); return false;
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

<script>
$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$(".edit_spec").live("click",function(){
		var kd_prov     = $(this).attr("Kd_Prov");
		var kd_kab_kota = $(this).attr("Kd_Kab_Kota");
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

		var page = "<?php echo site_url('kiba/form_edit_spec')?>/"+kd_prov+"/"+kd_kab_kota+"/"+kd_bidang+"/"+kd_unit+"/"+kd_sub+"/"+kd_upb+"/"+kd_aset1+"/"+kd_aset2+"/"+kd_aset3+"/"+kd_aset4+"/"+kd_aset5+"/"+no_register;
		var $dialog = $('<div>tunggu sebentar...</div>')
		               // .html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
   		               .load(page)
		               .dialog({
		    title:"EDIT SPESIFIKASI BARANG",
		    modal: true,
		    draggable: false,
		    resizable: false,
		    position: ['center'],
		    show: 'blind',
		    hide: 'fade',
			height: 600,
			width:  800,

			buttons: {
				"Proses": function() {
					
					var kode = {kd_bidang:kd_bidang,
								kd_unit:kd_unit,kd_sub: kd_sub,
								kd_upb: kd_upb,kd_aset1:kd_aset1,
								kd_aset2:kd_aset2,kd_aset3:kd_aset3,
								kd_aset4:kd_aset4,kd_aset5:kd_aset5,
								no_register:no_register,
								Luas_M2:$("#Luas_M2").val(),
								Alamat:$("#Alamat").val(),
								Hak_Tanah:$("#Hak_Tanah").val(),
								Sertifikat_Tanggal:$("#Sertifikat_Tanggal").val(),
								Sertifikat_Nomor:$("#Sertifikat_Nomor").val(),
								Penggunaan:$("#Penggunaan").val(),
								Kd_Data:$("#Kd_Data").val(),
								Keterangan:$("#Keterangan").val()
								};

				  // alert($("#Sertifikat_Nomor").val()); return false;
								
				  $.ajax({
					url : "<?php echo site_url('kiba/proses_edit_spec')?>",
					type : 'POST',
					data : kode,
					success: function(msg){
							// alert('pilih bidang terlebih dahulu '+msg); return false;
							$dialog.dialog('close');
							window.location.reload(true);
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

</script>

<style type="text/css">
#container { overflow:auto; }
.image { width:179px;min-height:100px;float:left;position:relative; }
a.delete { display:none;position:absolute;top:0;right:0;width:30px;height:30px;text-indent:-999px;background:red; }
a.lihat { display:none;position:absolute;top:0;left:0;width:30px;height:30px;text-indent:-999px;background:yellow; }
.image:hover a.delete, .image:hover a.lihat { display:block; }
    </style>
     
<div class="row">
            <div class="span7">

                <div class="panel">
                    <div class="panel-header">
                    	<i class="icon-bar-chart"></i> <?php echo $default['Nm_Aset5']; ?>
                    	<span class="pull-right">
                    		<?php 
                    			echo "<i class='icon-pencil'></i> <a class='edit_spec pointer' Kd_Prov='".$default['Kd_Prov']."' Kd_Kab_Kota='".$default['Kd_Kab_Kota']."' Kd_Bidang='".$default['Kd_Bidang']."' Kd_Unit='".$default['Kd_Unit']."' Kd_Sub='".$default['Kd_Sub']."' Kd_UPB='".$default['Kd_UPB']."' Kd_Aset1='".$default['Kd_Aset1']."' Kd_Aset2='".$default['Kd_Aset2']."' Kd_Aset3='".$default['Kd_Aset3']."' Kd_Aset4='".$default['Kd_Aset4']."' Kd_Aset5='".$default['Kd_Aset5']."' No_Register='".$default['No_Register']."'> Edit Spesifikasi</a>";
                    		?>
                    	</span>
                    </div>
                    <div class="panel-content panel-tables">

                            <table class="table table-bordered table-striped">                           
                            <tbody>
                            <tr>
                                <td class="description">Nama Aset</td>
                                <td class="value"><span><?php echo $default['Nm_Aset5']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="description">Kode Aset</td>
                                <td class="value"><span><?php echo $default['Kd_Aset1']; ?>.
                                						<?php echo $default['Kd_Aset2']; ?>.
                                                        <?php echo $default['Kd_Aset3']; ?>.
                                                        <?php echo $default['Kd_Aset4']; ?>.
                                                        <?php echo $default['Kd_Aset5']; ?> </span></td>
                            </tr>
                            <tr>
                                <td class="description">No. Register</td>
                                <td class="value"><span><?php echo $default['No_Register']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="description">Tanggal Pembelian</td>
                                <td class="value">
                                	<span>
                                		<?php echo tgl_dmy($default['Tgl_Perolehan']); ?></span>
		  						</td>
                            </tr>

                            <tr>
                                <td class="description">Tanggal Pembukuan</td>
                                <td class="value"><span><?php  echo tgl_dmy($default['Tgl_Pembukuan']); ?></span></td>
                            </tr>
                            
                            <tr>
                                <td class="description">Luas</td>
                                <td class="value"><span><strong><?php echo $default['Luas_M2']; ?></strong></span></td>
                            </tr>
                            
                            <tr>
                                <td class="description">Alamat</td>
                                <td class="value"><span><strong><?php echo $default['Alamat']; ?></strong></span></td>
                            </tr>
                            <tr>
                                <td class="description">Hak Tanah</td>
                                <td class="value"><span><strong><?php echo $default['Hak_Tanah']; ?></strong></span></td>
                            </tr>
                            <tr>
                                <td class="description">Tanggal Sertifikat</td>
                                <td class="value"><span><strong><?php echo tgl_dmy($default['Sertifikat_Tanggal']); ?></strong></span></td>
                            </tr>
                            <tr>
                                <td class="description">No Sertifikat</td>
                                <td class="value"><span><strong><?php echo $default['Sertifikat_Nomor']; ?></strong></span></td>
                            </tr>
                            <tr>
                                <td class="description">Penggunaan</td>
                                <td class="value"><span><strong><?php echo $default['Penggunaan']; ?></strong></span></td>
                            </tr>
							<tr>
                                <td class="description">Harga</td>
                                <td class="value"><span><strong>Rp. <?php echo rp($default['Harga']); ?></strong></span></td>
                            </tr>
							<tr>
                                <td class="description">Keterangan</td>
                                <td class="value"><span><strong><?php echo $default['Keterangan']; ?></strong></span></td>
                            </tr>
                            <tr>
                                <td class="view">Peta</td>
                                <td class="value">
                                <?php if(!empty($default['Lat'])){?>
                                <div class="pull-right">
   		                    			<?php 
		                    			$url = $default['Kd_Prov']."/".$default['Kd_Kab_Kota']."/".$default['Kd_Bidang']."/".$default['Kd_Unit']."/".$default['Kd_Sub']."/".$default['Kd_UPB']."/".$default['Kd_Aset1']."/".$default['Kd_Aset2']."/".$default['Kd_Aset3']."/".$default['Kd_Aset4']."/".$default['Kd_Aset5']."/".$default['No_Register'];
		                    			echo '<i class="icon-pencil"></i> <a href="'.base_url().'kiba/addpeta/'.$url.'">Ubah Peta</a>';
                    				?>
		                    	</div>
                                <iframe class="map" width="100%" height="250" src="https://maps.google.com/maps?q=<?php echo $default['Lat'].",".$default['Lng'] ?>&amp;num=1&amp;ie=UTF8&amp;ll=<?php echo $default['Lat'].",".$default['Lng'] ?>&amp;t=m&amp;z=16&amp;output=embed"></iframe> </td>
                            	<?php }else{ ?>
	                    			<?php 
		                    			$url = $default['Kd_Prov']."/".$default['Kd_Kab_Kota']."/".$default['Kd_Bidang']."/".$default['Kd_Unit']."/".$default['Kd_Sub']."/".$default['Kd_UPB']."/".$default['Kd_Aset1']."/".$default['Kd_Aset2']."/".$default['Kd_Aset3']."/".$default['Kd_Aset4']."/".$default['Kd_Aset5']."/".$default['No_Register'];
		                    			echo '<i class="icon-plus"></i> <a href="'.base_url().'kiba/addpeta/'.$url.'">Tambah Peta</a>';
                    				?>
                            	<?php } ?>
                            </tr>
                            </tbody>
                            </table>

                    </div>
                </div>

                <!-- start riwayat -->

                <div class="panel">
                    <div class="panel-header">
                    	<i class="icon-book"></i> RIWAYAT BARANG
                    	<span class="pull-right">
                    		<?php 
                    			$url = $default['Kd_Prov']."/".$default['Kd_Kab_Kota']."/".$default['Kd_Bidang']."/".$default['Kd_Unit']."/".$default['Kd_Sub']."/".$default['Kd_UPB']."/".$default['Kd_Aset1']."/".$default['Kd_Aset2']."/".$default['Kd_Aset3']."/".$default['Kd_Aset4']."/".$default['Kd_Aset5']."/".$default['No_Register'];
                    			echo '<i class="icon-plus"></i> <a href="'.base_url().'kiba/addriwayat/'.$url.'">Tambah Riwayat</a>';
                    		?>
                    	</span>
                    </div>
                    <div class="panel-content">

	                    <?php 
	                    	$i=1;
	                    	foreach ($riwayat->result() as $row) {
	                    	echo "<div id='listhapus_$i'>";
	                    	?>
	                    
		                    <div class="pull-right">
		                    		<?php echo '<span>'.anchor('kiba/update_riwayat/'.$row->Kd_Riwayat.'/'.$row->Kd_Id.'/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,"<i class='icon-pencil'></i>Ubah",array('class' => 'update')).'</span>'; ?> |
	                    			<?php echo '<span>'.anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'hapus_riwayat','Kd_Riwayat' => $row->Kd_Riwayat,'Kd_Id' => $row->Kd_Id,'Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i)).'</span>'; ?>
	                   		</div>
	                        <p /><a href="#"><strong><?php echo $row->Nm_Riwayat; ?></strong></a><p />

	                        <?php echo "<p><b>Tanggal Dokumen ".tgl_indo($row->Tgl_Dokumen)."</b></p>"; ?>
	                        <?php echo "<p><b>Nomor Dokumen    ". $row->No_Dokumen."</b></p>"; ?>

	                        <?php 
	                        $n = $row->Kd_Riwayat;
	                        if ($n == 2){
					             echo "<p><b> Harga Rp.".rp($row->Harga)."</b></p>";
					        }else if ($n == 5 || $n == 6 || $n == 7 || $n == 21 || $n == 23){
					             echo "<p><b> Harga Rp.".rp($row->Harga)."</b></p>";
					        }else if ($n == 3){
					             echo "<p> SKPD Tujuan  <b>".skpd($row->Kd_Bidang1,$row->Kd_Unit1,$row->Kd_Sub1)."</b></p>";
					        }else if ($n == 4){
					            echo "<p><b>".ruang($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$row->Kd_Ruang)."</b></p>";
					        }else if ($n == 9){ /*pinjam pakai*/
					             echo "<p><b> Tanggal Pinjam ".Tgl_Indo($row->Tgl_Mulai)."</b></p>";
					             echo "<p><b> Tanggal Selesai ".Tgl_Indo($row->Tgl_Selesai)."</b></p>";
					        }
					        ?>

	                        <p><?php echo $row->Keterangan; ?></p>
	                        <div>
	                            <em><a title="<?php echo Tgl_Indo($row->Log_entry); ?>"><?php echo RelativeTime($row->Log_entry); ?> Oleh <?php echo $row->Log_User; ?></a></em>
	                        </div>
	                        <hr />
	                   <?php
	                    $i++;
	                    echo "</div>";
	                    } ?>

                    </div>
                </div>

                <!-- end riwayat -->


            </div>


            <div class="span5">

                <div class="panel">
                    <div class="panel-header"><i class="icon-bar-chart"></i> Foto Barang</div>
                    <div class="panel-content">
                        <div align="left" id="wrapper">
			
		<div id="container">
		<?php 
					foreach ($query->result() as $row){
						$gambar = base_url("assets/uploads_kiba/$row->Nama_foto");
						echo "<div class='image' id='image_$row->No_Id' style='background-image:url($gambar); background-size: cover; margin:10px; padding:10px; '>
								".anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'delete','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'No_Id' => $row->No_Id))."
								<a href='javascript:;' onClick='window.open(\"$gambar\",\"scrollwindow\",\"top=200,left=350,width=575,height=400\");'  class='lihat' title='lihat gambar'>Tampil</a>
							</div>";
					}
					?>
		</div>            
            
            
	  <form action="<?php echo site_url('kiba/upload'); ?>" method="post" enctype="multipart/form-data" id="form-upload">
				<table>
					<thead>
						<input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $default['Kd_Bidang']; ?>">
						<input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $default['Kd_Unit']; ?>">
						<input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $default['Kd_Sub']; ?>">
						<input type=hidden name='Kd_UPB' id='kd_upb' value="<?php echo $default['Kd_UPB']; ?>">
						<input type=hidden name='Kd_Aset1' id='kd_bidang' value="<?php echo $default['Kd_Aset1']; ?>">
						<input type=hidden name='Kd_Aset2' id='kd_unit' value="<?php echo $default['Kd_Aset2']; ?>">
						<input type=hidden name='Kd_Aset3' id='kd_sub' value="<?php echo $default['Kd_Aset3']; ?>">
						<input type=hidden name='Kd_Aset4' id='kd_upb' value="<?php echo $default['Kd_Aset4']; ?>">
						<input type=hidden name='Kd_Aset5' id='kd_bidang' value="<?php echo $default['Kd_Aset5']; ?>">
						<input type=hidden name='Nm_Aset5' id='kd_unit' value="<?php echo $default['Nm_Aset5']; ?>">
						<input type=hidden name='No_Register' id='kd_sub' value="<?php echo $default['No_Register']; ?>">
						<tr>
							<td><input type="button" name="add_btn" value="Tambah" id="add_btn" class="btn"></td>
						</tr>
					</thead>
					<tbody id="fill">
						<tr>
							<td><label>Foto 1</label> <input name="image[]" type="file"/></td>
						</tr>
					</tbody>
					<tr>
						<td><input type="submit" id="submit-button" value="Upload" class="btn btn-success" /></td>
					</tr>
				</table>
			</form>
			<div id="output"></div>
		</div>
                    </div>
                </div>

            </div>
        </div>