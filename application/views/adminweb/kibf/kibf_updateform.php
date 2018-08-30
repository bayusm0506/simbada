<script>
	$(document).ready(function(){
		  $("#No_Register").keypress(function (data)  
	      { 
	         // kalau data bukan berupa angka, tampilkan pesan error
	         if(data.which!=8 && data.which!=46 && data.which!=0 && (data.which<48 || data.which>57))
	         {
		          alert("Diisi dengan angka !");
	            return false;
           }	
	      });
		  
			$("#No_Register").change(function(data){
			
			$('#msg').html("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> checking …");
			
			var kode = {
								kd_aset1:$("#kd_aset1").val(),kd_aset2:$("#kd_aset2").val(),kd_aset3: $("#kd_aset3").val(),
								kd_aset4:$("#kd_aset4").val(),kd_aset5:$("#kd_aset5").val(),no_register:$("#No_Register").val()
								};
				
				
				$.ajax({
									type: "POST",
									url : "<?php echo base_url(); ?>kibd/cek_register",
									data: kode,
									success: function(data){
									if(data==0){
										$("#msg").html("<img src='<?php echo base_url();?>media/images/tick_circle.png' /> OK");
										$('#No_Register').css('border', '1px #090 solid');
									}else{
										$("#msg").html("<img src='<?php echo base_url();?>media/images/cross.png' /> Sudah Digunakan");
										$('#No_Register').css('border', '2px #C33 solid');
									}
									}
			
			});
			})
	});
	</script> 
      
<script type="text/javascript">
	    $(this).ready( function() {
    		$("#Nm_Aset5").autocomplete({
      			minLength: 1,
      			source: 
        		function(req, add){
          			$.ajax({
		        		url: "<?php echo base_url(); ?>kibd/json",
		          		dataType: 'json',
		          		type: 'POST',
		          		data: req,
		          		success:    
		            	function(data){
		              		if(data.response =="true"){
		                 		add(data.message);
		              		}
		            	},
              		});
         		},
         	select: 
         		function(event, ui) {
            		$("#kd_aset1").val(ui.item.id1);
					$("#kd_aset2").val(ui.item.id2);
					$("#kd_aset3").val(ui.item.id3);
					$("#kd_aset4").val(ui.item.id4);
					$("#kd_aset5").val(ui.item.id5);
					
					var kode = {
								kd_aset1:$("#kd_aset1").val(),kd_aset2:$("#kd_aset2").val(),kd_aset3: $("#kd_aset3").val(),
								kd_aset4:$("#kd_aset4").val(),kd_aset5:$("#kd_aset5").val()
								};
				
				
				$.ajax({
									type: "POST",
									url : "<?php echo base_url(); ?>kiba/register",
									data: kode,
									success: function(msg){
									$('#register').html(msg);
								}
						});	           		
         		},		
    		});
	    });
	    </script>
	
		<script type="text/javascript">			
			$(document).ready(function(){	
				
				//Mempercantik Button dengan Jquery UI
				$("#kode-search").button({icons: {primary: "ui-icon-search"}, text: false });
				$("#submit").button();
				$("#reset").button();
				/* End */
				
				$("#kode-search").click(function(){
					$("#kode-dialog").dialog({
						title:"CARI KODE BARANG KIB F. Konstruksi dalam Pekerjaan",
						resizable:false, 
						width:608, 
						height:'auto',
						show: 'drop',
						hide: 'scale',
						modal:true,
						close:function(){
							$(this).dialog('destroy');
						}
					});
										
					$("#tkodebarang").jqGrid({
					url: '<?php echo base_url(); ?>kibf/lookup', //URL Tujuan Yg Mengenerate data Json nya
					datatype: "json", //Datatype yg di gunakan
					mtype: 'GET',
					colNames: ['id','KD 1','KD 2','KD 3','KD 4','KD 5','NAMA BARANG'],
					//array($line->Kd_Rek_1,$line->Kd_Rek_2,$line->Kd_Rek_3,$line->Kd_Rek_4,$line->Kd_Rek_5);
					colModel: [
								{name:'id', key:true, index:'no_urut', hidden:true,editable:false,editrules:{required:true}},
						    	{name:'Kd_Aset1', index:'Kd_Aset1', align:'center', sortable: true,width:20},
								{name:'Kd_Aset2', index:'Kd_Aset2', align:'center', sortable: true,width:20},
								{name:'Kd_Aset3', index:'Kd_Aset3', align:'center', sortable: true,width:20},
						    	{name:'Kd_Aset4', index:'Kd_Aset4', align:'center', sortable: true,width:20},
								{name:'Kd_Aset5', index:'Kd_Aset5', align:'center', sortable: true,width:20},
								{name:'Nm_Aset5', index:'Nm_Aset5', align:'left', sortable: true}				
						],
					rownumbers:true,
					rowNum:5,
					rowList:[5,10,15,30], 
					pager: '#pkodebuku',
					viewrecords: true,
					sortname: 'id',
					sortorder: "asc",
					width: 580,
					height: 'auto',
					caption: '&nbsp;',
					ondblClickRow: function(rowid) {
						var v = $("#tkodebarang").getRowData(rowid);
						kd1 		= v['Kd_Aset1'];
						kd2 		= v['Kd_Aset2'];
						kd3 		= v['Kd_Aset3'];
						kd4 		= v['Kd_Aset4'];
						kd5 		= v['Kd_Aset5'];
						Nm_brg		= v['Nm_Aset5'];
						$("#kd_aset1").val(kd1);
						$("#kd_aset2").val(kd2);
						$("#kd_aset3").val(kd3);
						$("#kd_aset4").val(kd4);
						$("#kd_aset5").val(kd5);
						$("#Nm_Aset5").val(Nm_brg);
						
						var kode = {
								kd_aset1:$("#kd_aset1").val(),kd_aset2:$("#kd_aset2").val(),kd_aset3: $("#kd_aset3").val(),
								kd_aset4:$("#kd_aset4").val(),kd_aset5:$("#kd_aset5").val()
								};
				
				
				$.ajax({
									type: "POST",
									url : "<?php echo base_url(); ?>kibf/register",
									data: kode,
									success: function(msg){
									$('#register').html(msg);
								}
						});
							 
						$("#kode-dialog").dialog('close');
					}
				      });
					  
					  $("#tkodebarang").jqGrid('navGrid','#pkodebuku',
							{edit:false,add:false,del:false},
							{},
							{},
							{},
							{sopt:['cn']}
							);
				      return false;
				});
							
			});			
		</script>
        
        <style>
	    	/* Autocomplete
			----------------------------------*/
			.ui-autocomplete { position: absolute; cursor: default; }	
			.ui-autocomplete-loading { background: white url('<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif') right center no-repeat; }*/

			/* workarounds */
			* html .ui-autocomplete { width:1px; } /* without this, the menu expands to 100% in IE6 */
			#No_Register{
	color:#F00;
	background-color: #FDECE3;
	border: thin solid #F00;
}
	    </style>
        
	<body style="font-size: 9pt;">
	
		<!-- Untuk Dialog yg akan munculkan dari Tombol Search Kode -->
		<div id="kode-dialog" class="" style="display: none; font-size: 10pt">
			<table id="tkodebarang" class="scroll" cellpadding="0" cellspacing="0"></table> 
			<div id="pkodebuku" class='ui-widget-header ui-corner-bl ui-corner-br' style="margin-top:0px;"></div>
		</div>
	</body>
<style type="text/css">
       label.error {
           color: red; padding-left: .5em;
       }
</style>


<?php 
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	echo "<div id='result'></div>";

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
     <div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header">
                    <i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
                    <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
          <table  width='100%'>
          <input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $Kd_Bidang; ?>">
          <input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $Kd_Unit; ?>">
          <input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $Kd_Sub; ?>">
          <input type=hidden name='Kd_UPB' id='kd_upb' value="<?php echo $Kd_UPB; ?>">
          <tr><td>Kode Pemilik</td>      <td> : 
          <?php
			echo form_dropdown('Kd_Pemilik', $option_pemilik,'12');
		  ?>
          <?php echo form_error('Kd_Pemilik', '<p class="field_error">', '</p>');?>
          </td>
          </tr>
		  <tr><td>Kode Aset</td>    <td>  
          <div class="form-inline"> :
		  <input type=text name='kd_aset1' val size=2 id='kd_aset1' value="<?php echo $Kd_Aset1; ?>" readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset2' size=2 id='kd_aset2' value="<?php echo $Kd_Aset2; ?>" readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset3' size=2 id='kd_aset3' value="<?php echo $Kd_Aset3; ?>" readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset4' size=2 id='kd_aset4' value="<?php echo $Kd_Aset4; ?>" readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset5' size=2 id='kd_aset5' value="<?php echo $Kd_Aset5; ?>" readonly='readonly' class="input-mini required">
          <input type=text name='Nm_Aset5' size=88 id='Nm_Aset5' value="<?php echo $namaaset; ?>" placeholder="ketik nama barang disini !">
		  <button id='kode-search' class='tombol' ><i class="icon-search icon-large"></i>CARI</button>
          </div>
          </td>
		    </tr>
           <tr><td>No Register</td><td> : 
           <span id='register'>
           <input type=text name='No_Register' size=5 id="No_Register" value="<?php echo $No_Register; ?>" class="required input-mini">
        </span>
              <span id="msg"></span>     
           </td>
           </tr>
          <tr><td>Tgl Pembelian</td><td> : <input type=text name='Tgl_Perolehan' value="<?php  $tgl = date('Y-m-d', strtotime($Tgl_Perolehan));
		  echo $tgl; ?>" size=10  id='datepicker' readonly='readonly'  class="required input-small"></td>
            </tr>
           <tr><td>Tgl Pembukuan</td><td> : <input type=text name='Tgl_Pembukuan' value="<?php  $tgl 		= date('Y-m-d', strtotime($Tgl_Pembukuan));
		   echo $tgl ?>" size=10 id='datepicker2' value="<?php 
		   $y = $this->session->userdata('tahun_anggaran');
		   echo date("$y-m-d");?>" readonly='readonly' class="input-small"></td>
            </tr>
          <tr>
            <td>Panjang</td>    <td> : <input type=text name='Panjang' value="<?php echo $Panjang; ?>" size=15  id='Panjang' class="input-small">
            </td>
            </tr>
            <tr>
            <td>Lebar</td>    <td> : <input type=text name='Lebar' value="<?php echo $Lebar; ?>" size=15  id='Lebar' class="input-small">
            </td>
            </tr>
            <tr>
            <td>Luas Lantai (M2)</td>    <td> : <input type=text name='Luas_Lantai' value="<?php echo $Luas_Lantai; ?>" size=15  id='Luas_Lantai' class="input-small">
            </td>
            </tr>
          <tr><td>Alamat</td>    <td><textarea name='Lokasi' id='loko' style='width: 600px; height: 80px;'><?php echo $Lokasi; ?></textarea></td>
            </tr>
          <tr>
            <td>Kondisi Bangunan</td>
            <td>: <?php
		  	$option_kondisi = array('1' => 'Permanen','2'=>'Semi Permanen','3'=>'Darurat');
			echo form_dropdown('Kondisi', $option_kondisi, $Kondisi,'class="input-medium"');
		  ?></td>
          </tr>
          <tr>
            <td>Bertingkat</td>
            <td>: <?php
		  	$option_bertingkat = array('Bertingkat' => 'Bertingkat','Tidak'=>'Tidak');
			echo form_dropdown('Bertingkat_Tidak', $option_bertingkat, $Bertingkat_Tidak);
		  	?> Beton/tidak : 
            <?php
		  	$option_Beton = array('Beton' => 'Beton','Tidak'=>'Tidak');
			echo form_dropdown('Beton_tidak', $option_Beton, $Beton_tidak);
		  ?></td>
          </tr>
		  <tr>
		    <td>Tgl Dokumen</td>    <td> : <input type=text name='Dokumen_Tanggal' value="<?php echo date('Y-m-d', strtotime($Dokumen_Tanggal)); ?>" size=10  id='datepicker3' readonly='readonly' class="input-small"> 
		      Nomor Dokumen : 
		        <input type=text name='Dokumen_Nomor' value="<?php echo $Dokumen_Nomor; ?>" ></td>
		    </tr>
		  <tr><td>Asal Usul</td>    <td> : 
          <?php
		  	$option_hak = array('Pembelian' => 'Pembelian','Hibah'=>'Hibah','Pinjam'=>'Pinjam','Sewa'=>'Sewa','Guna Usaha'=>'Guna Usaha','Penyerahan'=>'Penyerahan');
			echo form_dropdown('Asal_usul', $option_hak, $Asal_usul);
		  ?>
               </td>
		    </tr>
		  <tr>
          <td>Harga</td>    
          <td> : <input type=text name='Harga' value="<?php echo nilai($Harga); ?>" size=20 id="Harga" class="required number"></td>
		    </tr>
		  <tr>
		    <td class='ket'>Status Tanah</td>
		    <td>: <?php
		  	$option_hak = array('Tanah Milik Pemda' => 'Tanah Milik Pemda',
			'Tanah Milik Negara'=>'Tanah Milik Negara',
			'Tanah Hak Ulayat'=>'Tanah Hak Ulayat',
			'Tanah Hak Guna Bangunan'=>'Tanah Hak Guna Bangunan',
			'Tanah Hak Pakai'=>'Tanah Hak Pakai',
			'Tanah Hak Pengelolaan'=>'Tanah Hak Pengelolaan',
			'Tanah Hak Lainya'=>'Tanah Hak Lainya');
			echo form_dropdown('Status_Tanah', $option_hak, $Status_Tanah);
		  ?></td>
		    </tr>
		  <tr><td class='ket'>Keterangan</td>  <td></td>
		    </tr>
		  <tr><td colspan='3'><textarea name='Keterangan' id='loko' style='width: 600px; height: 80px;'><?php echo $Keterangan; ?></textarea></td>  </tr>
		  <tr><td colspan=3><input type="submit" name="submit" class="submit" value="Update" id="submit">
          <input type=button value=BATAL id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>



