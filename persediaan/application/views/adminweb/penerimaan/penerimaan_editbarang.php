<script type="text/javascript">
$(this).ready( function() {
	$("#Nm_Aset6x").autocomplete({
			minLength: 1,
			source: 
		function(req, add){
  			$.ajax({
        		url: "<?php echo base_url(); ?>penerimaan/json",
          		dataType: 'json',
          		type: 'POST',
          		data: req,
          		success:    
            	function(data){
              		if(data.response =="true"){
                 		add(data.message);
              		}
            	},
            	error:    
            	function(data){
              		alert("error : "+data.message); return false;
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
			$("#kd_aset6").val(ui.item.id6);
			$("#Harga").val(ui.item.harga);
			
			var kode = ui.item.id1+'.'+ui.item.id2+'.'+ui.item.id3+'.'+ui.item.id4+'.'+ui.item.id5+'.'+ui.item.id6;
			$("#kode").text(kode);
 		},		
	});
});
</script>

<script type="text/javascript">			
			$(document).ready(function(){	
				$("#submit").button();
				$("#reset").button();
				
				$("#kode_sshx").click(function(){
					$("#kode-dialog").dialog({
						title:"DAFTAR KODE BARANG PERSEDIAAN",
					    modal: true,
					    draggable: true,
					    resizable: false,
					    position: ['center'],
					    show: 'blind',
					    hide: 'fade',
						height: 'auto',
						width: 850,
						close:function(){
							$(this).dialog('destroy');
						}
					});
										
					$("#tkodebarang").jqGrid({
							url: '<?php echo base_url(); ?>penerimaan/lookup_ssh',
							datatype: "json", 
							mtype: 'GET',
							colNames: ['id','KD 1','KD 2','KD 3','KD 4','KD 5','KD 6','NAMA BARANG'],
							colModel: [
										{name:'id', key:true, index:'no_urut', hidden:true,editable:false,editrules:{required:true}},
								    	{name:'Kd_Aset1', index:'Kd_Aset1', align:'center', sortable: true,width:20},
										{name:'Kd_Aset2', index:'Kd_Aset2', align:'center', sortable: true,width:20},
										{name:'Kd_Aset3', index:'Kd_Aset3', align:'center', sortable: true,width:20},
								    	{name:'Kd_Aset4', index:'Kd_Aset4', align:'center', sortable: true,width:20},
										{name:'Kd_Aset5', index:'Kd_Aset5', align:'center', sortable: true,width:20},
										{name:'Kd_Aset6', index:'Kd_Aset6', align:'center', sortable: true,width:20},
										{name:'Nm_Aset6', index:'Nm_Aset6', align:'left', sortable: true}				
								],
							rownumbers:true,
							rowNum:10,
							rowList:[10,20,30,40,50], 
							pager: '#p_halaman',
							viewrecords: true,
							sortname: 'id',
							sortorder: "asc",
							width: 820,
							height: 'auto',
							caption: '&nbsp;',
							ondblClickRow: function(rowid) {
								var v = $("#tkodebarang").getRowData(rowid);
								kd1 		= v['Kd_Aset1'];
								kd2 		= v['Kd_Aset2'];
								kd3 		= v['Kd_Aset3'];
								kd4 		= v['Kd_Aset4'];
								kd5 		= v['Kd_Aset5'];
								kd6 		= v['Kd_Aset6'];
								Nm_brg		= v['Nm_Aset6'];
								$("#kd_aset1x").val(kd1);
								$("#kd_aset2x").val(kd2);
								$("#kd_aset3x").val(kd3);
								$("#kd_aset4x").val(kd4);
								$("#kd_aset5x").val(kd5);
								$("#kd_aset6x").val(kd6);
								$("#Nm_Aset6x").val(Nm_brg);

			
								var xkode = kd1+'.'+kd2+'.'+kd3+'.'+kd4+'.'+kd5+'.'+kd6;
								$("#xkode").text(xkode);
															 
								$("#kode-dialog").dialog('close');
							}
				      });
					  
					  $("#tkodebarang").jqGrid('navGrid','#p_halaman',
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
<div id="kode-dialog" class="" style="display: none; font-size: 10pt">
	<table id="tkodebarang" class="scroll" cellpadding="0" cellspacing="0"></table> 
	<div id="p_halaman" class='ui-widget-header ui-corner-bl ui-corner-br' style="margin-top:0px;"></div>
</div>
<style type="text/css">
	.info tr td{
		padding-left: 10px;
		padding-right: 10px;
		font-family:Arial;
		font-size: 12px;
		padding: 10px;
	}
</style>
        <div class="panel-content">
<!-- Validation -->
           <form class="form-horizontal" id="form_transaksi" role="form">
				       		<table width='100%'>
					          <tr>
					          	<td>Nama Barang</td>
					          	<td width='60%'> 
					          		<div class="form-inline" >
					          			:<input type="hidden" name='Kd_Aset1' id='kd_aset1x' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset2' id='kd_aset2x' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset3' id='kd_aset3x' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset4' id='kd_aset4x' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset5' id='kd_aset5x' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset6' size=2 id='kd_aset6x' readonly='readonly' class="input-mini required reset">
										<input type=text name='nm_aset6' value="<?php echo $Nm_Aset6 ?>" id='Nm_Aset6x' class="autocomplete input-large reset" placeholder="ketik nama barang disini !" required>
							          	<a href="javascript:;" class="btn btn-primary" id="kode_sshx"><i class="fa fa-search"></i></a>
							      	</div>
							      	<span id="xkode" style="padding-left: 7px; color: #cc6699; font-size: 11px; font-family: verdana, arial, sans-serif""></span>
							    </td>
					          </tr>
		            		  <tr>
		            		  	<td>Merk</td>
		            		  	<td> : <input type=text name='Merk' value="<?php echo $Merk ?>" id='Merk' class="input-small reset" placeholder="" required></td>
							  </tr>
							  <tr>
		            		  	<td>Ukuran</td>
		            		  	<td> : <input type=text name='Ukuran' value="<?php echo $Ukuran ?>" id='Ukuran' class="input-small reset" required></td>
							  </tr>
					          <tr>
					          	<td>Tahun Pembuatan</td>
					          	<td> : <select name="Tahun_Pembuatan" id='Tahun_Pembuatan' class="reset" style="width: 130px;">
							                <option value="">- Pilih Tahun -</option>
							                <?php
							                $thn_skr = date('Y');
							                for ($x = $thn_skr; $x >= 2000; $x--) {
							                	if($Tahun_Pembuatan == $x){
							                		echo "<option value='".$x."' selected>".$x."</option>";
							                	}else{
							                		echo "<option value='".$x."'>".$x."</option>";
							                	}
							                }
							                ?>
							            </select>
							      </td>
		            		  </tr>
							  <tr>
		            		  	<td>Jumlah Satuan</td>
		            		  	<td><div class="form-inline" style="padding-bottom: 10px;">
		            		  		 : <input type='text' name='Jumlah' value="<?php echo $Jumlah ?>"  id='Jumlah' class="input-mini reset" onkeyup="numericFilter(this);" onblur="if (this.value == '') {this.value = '0';}" onfocus="if (this.value == '0') {this.value = '';}" required>
		            		  		   <?php echo form_dropdown('Kd_Satuan', $option_satuan, $Kd_Satuan,'class="reset" id="Kd_Satuan" style="width:140px;"'); ?>
		            		  		 </div>
		            		  	</td>
							  </tr>
							  <tr>
					          	<td>Harga Satuan</td>
					          	<td> : <input type=text name='Harga' value="<?php echo nilai($Harga) ?>" id='Harga' class="input-large uang reset" placeholder="Rp." onkeyup="numericFilter(this);" onblur="if (this.value == '') {this.value = '0';}" onfocus="if (this.value == '0') {this.value = '';}" required></td>
		            		  </tr>
							  
				          </table>
				          </form>
       </div><!-- span 12 -->
