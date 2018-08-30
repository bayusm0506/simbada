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
	 if (t == "Jenis_Dokumen"){    		
		var dokumen =  '<select name="s" class="form-control" required>'+
                          '<option value="1">Berita Acara Serah Terima</option>'+
                          '<option value="2">Berita Acara Pinjam Pakai</option>'+
                      '</select>';
        $('#f_cari').html(dokumen);               
	}else if(t == "all"){
        $('#f_cari').html("");               
	}else{
		var dokumen =  '<input name="s" class="form-control" placeholder="ketik disini" required>';
        $('#f_cari').html(dokumen);
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
	var no 		 	= $(this).attr("no");
	var no_dokumen 	= $(this).attr("No_Dokumen");

	
	if(confirm("Yakin data ini mau di Hapus ??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>pemanfaatan/delete",
				data:{no_dokumen:no_dokumen},
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
			var no_dokumen 	= $(this).attr("No_Dokumen");
			i++;
			$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>pemanfaatan/delete",
					data:{no_dokumen:no_dokumen},
					cache: false,
					success: function(html){
						$("#listhapus_"+no).fadeTo(300,0, function() {
												$(this).animate({width:0},200,function(){
														$(this).remove();
													});
											});
						$('.checkbox1').each(function() {
							this.checked = false; 
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
/*cetak*/
$('.cetak').click(function(){
		window.open('', 'formpopup', 'width=800,height=600,left = 250,location=no,scrollbars=yes');
		this.target = 'formpopup';
    });
/*end cetak*/	
		
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
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'KIB A. TANAH'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">
                        
                        <!-- tab w/ pagination and search controls -->
                        
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>pemanfaatan/set/>
                                     <?php echo form_dropdown("q",$option_q,'','class="input-medium" id="search"'); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Dokumen </strong></span>
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
                                    <a href="<?php echo base_url() ?>pemanfaatan/add">
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
                          <th>Jenis Dokumen</th>
                          <th>Nomor Dokumen</th>
                          <th>Tanggal Dokumen</th>
                          <th>Nama Pihak Pertama</th>
                          <th>Jabatan</th>
                          <th>Alamat</th>
                          <th>Nama Pihak Kedua</th>
                          <th>Jabatan</th>
                          <th>Alamat</th>
                          <th>Keterangan</th>
                          <th><i class='icon-wrench'></i> Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
					$i= $offset + 1;
					foreach ($query->result() as $row){
                      echo "<tr id='listhapus_$i'>
	                          <td><input class='checkbox1' type='checkbox' name='check[]' No_Dokumen = '".encrypt_url($row->No_Dokumen)."' no='$i'></td>
	                          <td>$i</td>
	                          <td>".jenis_dokumen($row->Jenis_Dokumen)."</td>
	                          <td>032/".$row->No_Dokumen."/".kode($row->Jenis_Dokumen)."/".strtoupper($this->session->userdata('username'))."/".thn($row->Log_entry)."</td>
							  <td>".tgl_dmy($row->Tgl_Dokumen)."</td>
							  <td>$row->Nama_Pihak_1</td>
							  <td>$row->Jabatan_Pihak_1</td>
							  <td>$row->Alamat_Pihak_1</td>
							  <td>$row->Nama_Pihak_2</td>
	                          <td>$row->Jabatan_Pihak_2</td>
							  <td>$row->Alamat_Pihak_2</td>
	                          <td>$row->Keterangan</td>
	                            <td>
		                            <div class='btn-group'>
		                                <button class='btn'>Aksi</button>
		                                <button class='btn dropdown-toggle' data-toggle='dropdown'>
		                                    <span class='caret'></span>
										</button>
		                                <ul class='dropdown-menu'>
		                                <li>".anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'hapus','No_Dokumen' => encrypt_url($row->No_Dokumen),'no'=>$i))."</li>
		                                <li>".anchor('pemanfaatan/update/'.$row->Jenis_Dokumen.'/'.encrypt_url($row->No_Dokumen),
											"<i class='icon-pencil'></i>Ubah",array('class' => 'update'))."</li>
		                                <li>".anchor('cetak/ba/'.$row->Jenis_Dokumen.'/'.encrypt_url($row->No_Dokumen),
											"<i class='icon-print'></i>Cetak",array('class'=> 'cetak','Jenis_Dokumen' => $row->Jenis_Dokumen,'No_Dokumen' => $row->No_Dokumen))."</li>
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