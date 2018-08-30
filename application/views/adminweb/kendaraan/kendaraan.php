<script type="text/javascript">
      $(document).ready(function(){
        $("div.item").tooltip();
      });
    </script>
    
<script>
	$(function() {
	
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#form_input" ).dialog({
			resizable: false,
			autoOpen: false,
			height: 300,
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
					url : "<?php echo site_url('kendaraan/pindahskpd')?>",
					type : 'POST',
					data : kode,
					success: function(msg){
						$("#form_input").dialog( "close" );
						$("#listhapus_"+$("#no").val()).slideUp('slow', function() {$(this).remove();});
						$("#loading" ).empty().html();
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
				url: "<?php echo base_url(); ?>kendaraan/ajax_hapus",
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
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
	
	if ( ! empty($link))
	{
		echo '<p id="bottom_link">';
		foreach($link as $links)
		{
			echo $links . ' ';
		}
		echo '</p>';
	}
	
	echo "<div id='search'><form method='' action='$form_cari'>";
	echo form_dropdown("q",$option_q,''); 
	echo "<input name='s' type='text' size='20' placeholder='Search' />";
	echo form_submit('', ' CARI ');
	echo "</form></div>";
	$this->table->set_heading('No','Nama Aset', 'Reg','Tgl Perolehan', 'Luas M2', 'Alamat','Harga','Actions');
	 echo "<table width='100%'>
	 	<tr>
          	  <th>No</th>
			  <th>Nama Barang</th>
			  <th>Reg</th>
			  <th>Merk</th>
			  <th>Type</th>
			  <th>Tahun</th>
			  <th>Kondisi</th>
			  <th>Bahan</th>
			  <th>Harga</th>
			  <th>Keterangan</th>
			  <th>Actions</th>
		</tr>";
  $i= $offset + 1;
  
  foreach ($query->result() as $row){
	$thn 		= date('Y', strtotime($row->Tgl_Perolehan));
	$harga=number_format($row->Harga,0,",",".");	
	
	echo "<tr id='listhapus_$i'>
			<td>$i</td>
            <td>
			
			<div id='item_1' class='item'>
        $row->Nm_Aset5
        <div class='tooltip_description' style='display:none' title='$row->Nm_Aset5 Detail'>
        	<div class='detail'>";
			$kd1 = sprintf ('%02u', $row->Kd_Aset1);
			$kd2 = sprintf ('%02u', $row->Kd_Aset2);
			$kd3 = sprintf ('%02u', $row->Kd_Aset3);
			$kd4 = sprintf ('%02u', $row->Kd_Aset4);
			$kd5 = sprintf ('%02u', $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			$tgl 		= date('d-m-Y', strtotime($row->Tgl_Perolehan));
			if ($row->Kondisi == 1){
				$kondisi = 'Baik';
			}elseif ($row->Kondisi == 2){
				$kondisi = 'Kurang Baik';
			}else{
				$kondisi = 'Rusak Berat';
			}
			$harga		= number_format($row->Harga,0,",",".");
			echo "Nama Barang : $row->Nm_Aset5<br>
			Kode barang : $kodebarang ($row->No_Register)<br>
			Tanggal Pembelian : $tgl<br>
			Merk / Type : $row->Merk/$row->Type<br>
			Ukuran : $row->CC<br>
			No Mesin : $row->Nomor_Mesin : No Polsis : $row->Nomor_Polisi<br>
			No BPKB : $row->Nomor_BPKB | Bahan : $row->Bahan<br>
			Asal Usul : $row->Asal_usul | Kondisi : $kondisi<br>
			Harga : Rp $harga<br>
			Keterangan : $row->Keterangan
			</div>
        </div>
</div>
			
			</td>
            <td>$row->No_Register</td>
            <td>$row->Merk</td>
			<td>$row->Type</td>
			<td>$thn</td>
			<td>$row->Kondisi</td>
			<td>$row->Bahan</td>
			<td>$harga</td>
			<td>$row->Keterangan</td>
            <td><center>".anchor('kendaraan/update/'
			.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,
			"<img src=".base_url()."media/images/edit.png title='ubah data'>",array('class' => 'update')).' | '
			.anchor(current_url().'#',"<img src=".base_url()."media/images/cross.png title='hapus data'>",array('class'=> 'hapus','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i)).' | '
			.anchor(current_url().'#',"<img src=".base_url()."media/images/move-icon.png title='Pindah data'>",array('class'=> 'pindah','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i));
			
		echo "</center></td>
			</tr>";
	 $i++;
	}
	echo "</table>";
	
$this->load->view('adminweb/chain/pindahskpd');