<script src="<?php echo base_url() ?>asset/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>asset/js/dataTables.bootstrap.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("a.update").click(function() {
		$("#result").empty().append("Tunggu Sebentar.. <img src='<?php echo base_url()?>asset/img/load.gif'/>");
	});
});
</script>
 <script>
	$(document).ready(function(){
	$(".hapus").click(function(event) {
	var no = $(this).attr("no");
	var id_user = $(this).attr("id_user");
	
		var kode = {
					id_user:id_user
					};

	
	if(confirm("Yakin data ini mau di Hapus ??"))
	{
		$( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>user/ajax_hapus",
				data:kode,
			 	cache: false,
			 	success: function(html){
			 		$("#listhapus_"+no).slideUp('slow', function() {$(this).remove();});
					$("#loading" ).empty().html();
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
                    <div class="panel-header"><i class="icon-sign-blank"></i> Data SKPD
                    <?php if ($this->session->userdata('lvl') != 03){
                    echo '<div class="pull-right"><a href='.base_url().'user/add><i class="icon-plus"></i> Tambah Data</a></div>';
					}?>
                    </div>
                    <div class="panel-content">

<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="example">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama UPB</th>
            <th>Username</th>
            <th>Nama Pengurus Barang</th>
            <th>Email / NO Telp</th>
            <th>Status Blokir</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
    <?php
 	$i= 1; 
	foreach ($query->result() as $row){
	if ($row->lvl == 01){
		$lvl = "admin";	
	}elseif($row->lvl == 02){
		$lvl = "Supervisor";	
	}else{
		$lvl = "Operator";
		}
	echo "<tr class='gradeA' onclick='test()' id='listhapus_$i'>
            <td align='center'>".$i."</td>
            <td align='center'>".$row->Nm_UPB."</td>
		 	<td align='center'>".$row->username."</td>
			<td align='center'>".$row->nama_lengkap."</td>
            <td align='center'>".$row->email.'<br><strong>0'.$row->no_telp."</strong></td>
            <td align='center'>";
			if ($row->blokir == "N"){
			echo "<span class='label label-inverse'>".$row->blokir."</span>";
			}else{
			echo "<span class='label label-success'>".$row->blokir."</span>";
			}
			echo "</td>
				
			<td>
                                            <div class='btn-group'>
                                                <button class='btn'>Options</button>
                                                <button class='btn dropdown-toggle' data-toggle='dropdown'>
                                                <span class='caret'></span>
                                                </button>
                                                <ul class='dropdown-menu'>													
													<li>".anchor('user/update/'.$row->id_user,"<img src=".base_url()."media/images/edit.png title='ubah data'> Ubah",array('class' => 'update')).'</li>
													<li>'.anchor(current_url().'#',"<img src=".base_url()."media/images/del.png  title='hapus data'> Hapus",array('class'=> 'hapus','id_user'=>$row->id_user,'no'=>$i)).'</li>
													<li>';
			if ($row->blokir == "N"){
			echo anchor('user/nonaktifkan/'.$row->id_user,"<img src=".base_url()."media/images/1.png  title='status aktif'> NonAktifkan",array('class'=> 'blokir','id_user'=>$row->id_user,'no'=>$i));
			}else{
			echo anchor('user/aktifkan/'.$row->id_user,"<img src=".base_url()."media/images/2.png  title='status non aktif'> Aktifkan",array('class'=> 'blokir','id_user'=>$row->id_user,'no'=>$i));
			}
		echo "</li>
                                                </ul>
                                            </div>
                                        </td>
        </tr>";
	 $i++;
	}              
?>	
    </tbody>
</table>

                    </div>
                </div>

            </div>
        </div>
    