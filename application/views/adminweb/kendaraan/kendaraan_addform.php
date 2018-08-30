<script>
	$(document).ready(function(){
		$("#formku").submit(function(event) {
		
		if(confirm("Yakin data ini mau di Simpan ??")){
			$( "#proses" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' />Processing...");
			return true;
			}else{
			$( "#proses" ).empty().html();
			}
					event.preventDefault(); 
					  
  			});
		
		
		
		  $("#No_Register").keypress(function (data)  
	      { 
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
									url : "<?php echo base_url(); ?>kendaraan/cek_register",
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
		        		url: "<?php echo base_url(); ?>kendaraan/json",
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
								kd_bidang:$("#kd_bidang").val(),kd_unit:$("#kd_unit").val(),kd_sub: $("#kd_sub").val(),kd_upb: $("#kd_upb").val(),
								kd_aset1:$("#kd_aset1").val(),kd_aset2:$("#kd_aset2").val(),kd_aset3: $("#kd_aset3").val(),
								kd_aset4:$("#kd_aset4").val(),kd_aset5:$("#kd_aset5").val()
								};
				
				
				$.ajax({
									type: "POST",
									url : "<?php echo base_url(); ?>kendaraan/register",
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
				
				$("#kode-search").button({icons: {primary: "ui-icon-search"}, text: false });
				$("#submit").button();
				$("#reset").button();
				/* End */
				
				$("#kode-search").click(function(){
					$("#kode-dialog").dialog({
						title:"CARI KODE BARANG KIB B. KENDARAAN",
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
					url: '<?php echo base_url(); ?>kendaraan/lookup', 
					datatype: "json",
					mtype: 'GET',
					colNames: ['id','KD 1','KD 2','KD 3','KD 4','KD 5','NAMA BARANG'],
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
								kd_bidang:$("#kd_bidang").val(),kd_unit:$("#kd_unit").val(),kd_sub: $("#kd_sub").val(),kd_upb: $("#kd_upb").val(),
								kd_aset1:$("#kd_aset1").val(),kd_aset2:$("#kd_aset2").val(),kd_aset3: $("#kd_aset3").val(),
								kd_aset4:$("#kd_aset4").val(),kd_aset5:$("#kd_aset5").val()
								};
				
				
				$.ajax({
									type: "POST",
									url : "<?php echo base_url(); ?>kendaraan/register",
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
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	echo "<div id='result'></div>";

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
     <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
          <table  width='100%'>
          <input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $default['Kd_Bidang']; ?>">
          <input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $default['Kd_Unit']; ?>">
          <input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $default['Kd_Sub']; ?>">
          <input type=hidden name='Kd_UPB' id='kd_upb' value="<?php echo $default['Kd_UPB']; ?>">
          <tr><td>Kode Pemilik</td>      <td> : 
          <?php
			echo form_dropdown('Kd_Pemilik', $option_pemilik, 12);
		  ?>
          <?php echo form_error('Kd_Pemilik', '<p class="field_error">', '</p>');?>
          </td>
          </tr>
		  <tr><td>Kode Aset</td>    <td> : 
		  <input type=text name='kd_aset1' size=2 id='kd_aset1' readonly='readonly' >
		  <input type=text name='kd_aset2' size=2 id='kd_aset2' readonly='readonly' >
		  <input type=text name='kd_aset3' size=2 id='kd_aset3' readonly='readonly' >
		  <input type=text name='kd_aset4' size=2 id='kd_aset4' readonly='readonly' >
		  <input type=text name='kd_aset5' size=2 id='kd_aset5' readonly='readonly' >
          <input type=text name='Nm_Aset5' size=88 id='Nm_Aset5'  placeholder='isikan nama barang'>
		  <button id='kode-search' class='tombol'>&nbsp;CARI</button>
          </td>
		    </tr>
           <tr><td>No Register</td><td> : 
           <span id='register'>
           <input type=text name='No_Register' size=5  id="No_Register" class="required">
        </span>
              <span id="msg"></span>     
           </td>
           </tr>
           
          <tr><td>Tgl Pembelian</td><td> : <input type=text name='Tgl_Perolehan' size=10  id='datepicker' readonly='readonly'></td>
            </tr>
           <tr><td>Tgl Pembukuan</td><td> : <input type=text name='Tgl_Pembukuan' size=10 id='datepicker2' value='<?php echo date("Y-m-d"); ?>' readonly='readonly'></td>
            </tr>
          <tr><td>Merk</td>    <td> : <input type=text name='Merk' size=15 ></td>
            </tr>
          <tr><td>Type</td>    <td> : <input type=text name='Type' size=15 ></td>
            </tr>
          <tr><td>CC</td> <td> : <input type=text name='CC' size=15 > Bahan : <input type=text name='Bahan' size=15></td>
            </tr>
           <tr><td>No. Pabrik</td> <td> : <input type=text name='CC' size=15 ></td>
            </tr>
             <tr><td>No. Rangka</td> <td> : <input type=text name='CC' size=15 ></td>
            </tr>
             <tr><td>No. Mesin</td> <td> : <input type=text name='CC' size=15 ></td>
            </tr>
            <tr><td>No. BPKP</td> <td> : <input type=text name='CC' size=15 > No. Polisi : <input type=text name='CC' size=15 > </td>
            </tr>
		  <tr><td>Asal Usul</td>    <td> : 
          <?php
		  	$option_hak = array('Pembelian' => 'Pembelian','Hibah'=>'Hibah','Pinjam'=>'Pinjam','Sewa'=>'Sewa','Guna Usaha'=>'Guna Usaha');
			echo form_dropdown('Asal_usul', $option_hak, '');
		  ?>
               </td>
		    </tr>
            <tr><td>Kondisi</td>    <td> : <?php
		  	$option_hak = array('1' => 'Baik','2'=>'Kurang Baik','3'=>'Rusak Berat');
			echo form_dropdown('Kondisi', $option_hak, '');
		  ?> | Masa Manfaat  <input type=text name='Masa_Manfaat' size=15 ></td>
		    </tr>
		  <tr>
          <td>Harga</td>    
          <td> : <input type=text name='Harga' size=20 id="Harga" class="required number">
          <span id='pesan'></span></td>
		    </tr>
		  <tr><td class='ket'>Keterangan</td>  <td></td>
		    </tr>
		  <tr><td colspan='3'><textarea name='Keterangan' id='loko' style='width: 600px; height: 80px;'></textarea></td>  </tr>

          <tr><td colspan=3><input type="submit" name="submit" class="submit" value="SIMPAN" id="submit">
          <input type=button value=BATAL id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

