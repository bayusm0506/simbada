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
	var No_SK = $(this).attr("No_SK");
	
		var kode = {
					No_SK:No_SK
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
                    echo '<div class="pull-right"><a href='.base_url().'penghapusan/add_sk><i class="icon-plus"></i> Tambah Data</a></div>';
					}?>
                    </div>
                    <div class="panel-content">

<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="example">
    <thead>
        <tr>
            <th>No</th>
            <th>Tahun</th>
            <th>No SK</th>
            <th>Tanggal SK</th>
            <th>Keterangan</th>
            <th>Status Blokir</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
    <?php
 	$i= 1; 
	foreach ($query as $row){
	echo "<tr class='gradeA' onclick='test()' id='listhapus_$i'>
            <td align='center'>".$i."</td>
            <td align='center'>".$row->Tahun."</td>
		 	<td align='center'>".$row->No_SK."</td>
			<td align='center'>".tgl_dmy($row->Tgl_SK)."</td>
            <td align='left'>".$row->Keterangan."</td>
            <td align='center'></td>
				
			<td>
                <div class='btn-group'>
                    <button class='btn'>Options</button>
                    <button class='btn dropdown-toggle' data-toggle='dropdown'>
                    <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>													
						<li>".anchor('penghapusan/update/'.encrypt_url($row->No_SK),"<img src=".base_url()."media/images/edit.png title='ubah data'> Ubah",array('class' => 'update'))."</li>";
						// <li>".anchor(current_url().'#',"<img src=".base_url()."media/images/del.png  title='hapus data'> Hapus",array('class'=> 'hapus','No_SK'=>$row->No_SK,'no'=>$i))."</li>
						echo "<li><a class='pointer' href='".base_url()."penghapusan/rincian_kiba/".encrypt_url($row->No_SK)."' ><i class='icon-eye-open'></i>Rincian KIB A</a></li>";
						echo "<li><a class='pointer' href='".base_url()."penghapusan/rincian_kibb/".encrypt_url($row->No_SK)."' ><i class='icon-eye-open'></i>Rincian KIB B</a></li>";
						echo "<li><a class='pointer' href='".base_url()."penghapusan/rincian_kibc/".encrypt_url($row->No_SK)."' ><i class='icon-eye-open'></i>Rincian KIB C</a></li>";
						echo "<li><a class='pointer' href='".base_url()."penghapusan/rincian_kibd/".encrypt_url($row->No_SK)."' ><i class='icon-eye-open'></i>Rincian KIB D</a></li>";
						echo "<li><a class='pointer' href='".base_url()."penghapusan/rincian_kibe/".encrypt_url($row->No_SK)."' ><i class='icon-eye-open'></i>Rincian KIB E</a></li>";
						echo "<li><a class='pointer' href='".base_url()."penghapusan/rincian_al/".encrypt_url($row->No_SK)."' ><i class='icon-eye-open'></i>Rincian AL</a></li>";

                   echo "</ul>
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
    