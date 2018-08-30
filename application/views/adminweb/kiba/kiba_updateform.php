 <script type="text/javascript">
    $(document).ready(function(){
		
        $("#Harga").keyup(function(){
            var Harga = $("#Harga").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#Luas_M2").keyup(function(){
            var Luas_M2 = $("#Luas_M2").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
        $('form').submit(function(e){
            var kd_aset1 	= $("#kd_aset1").val();
			var datepicker 	= $("#datepicker").val();
          	var No_Register = $("#No_Register").val();
			var Harga 		= $("#Harga").val();
           
		    if(kd_aset1 == ''){
                alert("Nama aset belum diisi !");
                return false;
            }
			
			 if(No_Register == ''){
                alert("Kode register belum diisi, silahkan isi kembali nama aset !");
                return false;
            }
			
			 if(datepicker == ''){
                alert("Tanggal Pembelian belum diisi !");
                return false;
            }
			
			 if(Harga == ''){
                alert("Harga Pembelian belum diisi !");
                return false;
            }
        });
    });
</script>
<script>
	$(document).ready(function(){
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
						kd_bidang:$("#kd_bidang").val(),kd_unit:$("#kd_unit").val(),kd_sub: $("#kd_sub").val(),kd_upb: $("#kd_upb").val(),
						kd_aset1:$("#kd_aset1").val(),kd_aset2:$("#kd_aset2").val(),kd_aset3: $("#kd_aset3").val(),
						kd_aset4:$("#kd_aset4").val(),kd_aset5:$("#kd_aset5").val(),no_register:$("#No_Register").val()
								};
				
				
				$.ajax({
									type: "POST",
									url : "<?php echo base_url(); ?>kiba/cek_register",
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
		        		url: "<?php echo base_url(); ?>kiba/json",
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
						title:"CARI KODE BARANG KIB A. TANAH",
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
					url: '<?php echo base_url(); ?>kiba/lookup', //URL Tujuan Yg Mengenerate data Json nya
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
								kd_bidang:$("#kd_bidang").val(),kd_unit:$("#kd_unit").val(),kd_sub: $("#kd_sub").val(),kd_upb: $("#kd_upb").val(),
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
          <input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $default['Kd_Bidang']; ?>">
          <input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $default['Kd_Unit']; ?>">
          <input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $default['Kd_Sub']; ?>">
          <input type=hidden name='Kd_UPB' id='kd_upb' value="<?php echo $default['Kd_UPB']; ?>">
          <tr><td>Kode Pemilik</td>      <td> : 
          <?php
			echo form_dropdown('Kd_Pemilik', $option_pemilik, $default['Kd_Pemilik']);
		  ?>
          <?php echo form_error('Kd_Pemilik', '<p class="field_error">', '</p>');?>
          </td>
          </tr>
		  <tr><td>Kode Aset</td>    <td>
          <div class="form-inline"> :
		  <input type=text name='kd_aset1' size=2 id='kd_aset1' readonly='readonly' value="<?php echo $default['Kd_Aset1']; ?>"  class="input-mini required">
		  <input type=text name='kd_aset2' size=2 id='kd_aset2' readonly='readonly' value="<?php echo $default['Kd_Aset2']; ?>"  class="input-mini required">
		  <input type=text name='kd_aset3' size=2 id='kd_aset3' readonly='readonly' value="<?php echo $default['Kd_Aset3']; ?>"  class="input-mini required">
		  <input type=text name='kd_aset4' size=2 id='kd_aset4' readonly='readonly' value="<?php echo $default['Kd_Aset4']; ?>"  class="input-mini required">
		  <input type=text name='kd_aset5' size=2 id='kd_aset5' readonly='readonly' value="<?php echo $default['Kd_Aset5']; ?>"  class="input-mini required">
          <input type=text name='Nm_Aset5' size=88 id='Nm_Aset5'  value="<?php echo $default['Nm_Aset5']; ?>">
		  <button id='kode-search' class='tombol' >&nbsp;CARI</button>
           </div>
          </td>
		    </tr>
           <tr><td>No Register</td><td> : 
           <span id='register'>
           <input type=text name='No_Register' size=5 value="<?php echo $default['No_Register']; ?>"  id="No_Register"  class="input-mini required">
        </span>
              <span id="msg"></span>     
           </td>
           </tr>

           <tr><td>Status</td>      <td> : 
	          <?php
				echo form_dropdown('Kd_KA', array('1'=>"Aset Operasional",'0'=>"Aset Non Operasional"), $default['Kd_KA']);
			  ?>
	          <?php echo form_error('Kd_KA', '<p class="field_error">', '</p>');?>
	          </td>
          </tr>
           
          <tr><td>Tgl Pembelian</td><td> : <input type=text name='Tgl_Perolehan' size=10 
          value="<?php echo tgl_ymd($default['Tgl_Perolehan']); ?>" id='datepicker' readonly='readonly'  class="input-small required"></td>
            </tr>
           <tr><td>Tgl Pembukuan</td><td> : <input type=text name='Tgl_Pembukuan' size=10 
           value="<?php echo tgl_ymd($default['Tgl_Pembukuan']); ?>" id='datepicker2' readonly='readonly'  class="input-small required"></td>
            </tr>
          <tr><td>Luas</td>    <td> : <input type=text name='Luas_M2' size=15 value="<?php echo $default['Luas_M2']; ?>" id='jml_satuan'><span id='pesan2'></span></td>
            </tr>
          <tr><td>Alamat</td>    <td> : <textarea name='Alamat' id='loko' style='width: 600px; height: 80px;'><?php echo $default['Alamat']; ?></textarea></td>
            </tr>
          <tr><td>Hak Tanah</td>    <td> :  <?php
		  $option_hak = array('Hak Pakai' => 'Hak Pakai','Hak Pengelolaan'=>'Hak Pengelolaan');
		  
			echo form_dropdown('Hak_Tanah', $option_hak, $default['Hak_Tanah']);
		  ?>
			</td>
            </tr>
		  <tr><td>Tgl Sertifikat</td>    <td> : <input type=text name='Sertifikat_Tanggal' size=10 
          value="<?php echo tgl_ymd($default['Sertifikat_Tanggal']); ?>" id='datepicker3' readonly='readonly'  class="input-small required"></td>
		    </tr>
		  <tr><td>Nomor Sertifikat</td>    <td> : <input type=text name='Sertifikat_Nomor' size=30 value="<?php echo $default['Sertifikat_Nomor']; ?>"></td>
		    </tr>
		  <tr><td>Asal Usul</td>    <td> : 
          <?php
		  	$option_hak = array('Pembelian' => 'Pembelian','Hibah'=>'Hibah','Pinjam'=>'Pinjam','Sewa'=>'Sewa','Guna Usaha'=>'Guna Usaha','Penyerahan'=>'Penyerahan');
			echo form_dropdown('Asal_usul', $option_hak, $default['Asal_usul']);
		  ?>
               </td>
		    </tr>
            <tr><td>Penggunaan</td>    <td> : <input type=text name='Penggunaan' size=80 value="<?php echo $default['Penggunaan']; ?>"></td>
		    </tr>
		  <tr>
          <td>Harga</td>    
          <td> : <input type=text name='Harga' size=20 value="<?php echo $default['Harga']; ?>" id="Harga" class="required number">
          <span id='pesan'></span></td>
		    </tr>
		  <tr><td class='ket'>Keterangan</td>  <td></td>
		    </tr>
		  <tr><td colspan='3'><textarea name='Keterangan' id='loko' style='width: 600px; height: 80px;'><?php echo $default['Keterangan']; ?></textarea></td>  </tr>
		  <tr><td colspan=3><input type="submit" name="submit" class="submit" value="UPDATE" id="submit">
          <input type=button value=BATAL id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>
     

