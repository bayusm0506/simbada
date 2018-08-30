<link rel="stylesheet" href="<?php echo base_url()?>asset/chosen.css">
  <style type="text/css" media="all">
    /* fix rtl for demo */
    .chosen-rtl .chosen-drop { left: -9000px; }
  </style>
 <script type="text/javascript">
    $(document).ready(function(){
		
        $("#Harga").keyup(function(){
            var Harga = $("#Harga").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#Jumlah").keyup(function(){
            var Jumlah = $("#Jumlah").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#percepatan").keyup(function(){
            var Harga = $("#percepatan").val();
            this.value = this.value.replace(/[^0-9]/,'');
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
	
function aktif(){
	if(document.fadd.perc.checked){
		alert ("percepatan dikatifkan");
		document.fadd.jmlperc.disabled=false;
		document.fadd.jmlperc.focus();
	}else{
		alert ("percepatan dimatikan");
		document.fadd.jmlperc.disabled=true;
		document.fadd.jmlperc.value='';		
	}
}
</script>
      
<script type="text/javascript">
	    $(this).ready( function() {
    		$("#Nm_Aset5").autocomplete({
      			minLength: 1,
      			source: 
        		function(req, add){
          			$.ajax({
		        		url: "<?php echo base_url(); ?>pengadaan/json",
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
									url : "<?php echo base_url(); ?>pengadaan/no_id",
									data: kode,
									success: function(msg){
									$('#No_ID').html(msg);
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
					url: '<?php echo base_url(); ?>pengadaan/lookup',
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
									url : "<?php echo base_url(); ?>pengadaan/no_id",
									data: kode,
									success: function(msg){
									$('#No_ID').html(msg);
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
		</script><body style="font-size: 9pt;">
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
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
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
           <tr><td>No ID</td><td> : 
           <span id='No_ID'>
           <input type=text name='No_ID' size=5 id="No_ID" class="required input-mini" readonly='readonly' value="<?php echo $default['No_ID']; ?>">
        </span>
              <span id="msg"></span>     
           </td>
           </tr>
          <tr><td>No Kontrak</td>    <td> : <input type=text name='No_Kontrak' value="<?php echo $default['No_Kontrak']; ?>" size=30 ></td>
            </tr>
          <tr><td>Tgl Kontrak/DPA/SPM</td><td> : <input type=text name='Tgl_Kontrak' size=10  id='datepicker' readonly='readonly' class="required input-small" value="<?php  $tgl 		= date('Y-m-d', strtotime($default['Tgl_Kontrak']));
		  echo $tgl; ?>"></td>
            </tr>
          <tr><td>No Kuitansi/SP2D</td>    <td> : <input type=text name='No_Kuitansi' size=30 value="<?php echo $default['No_Kuitansi']; ?>"></td>
            </tr> 
          <tr><td>Tgl Kuitansi/SP2D</td><td> : <input type=text name='Tgl_Kuitansi' size=10  id='datepicker2' value="<?php echo tgl_ymd($default['Tgl_Kuitansi']); ?>" readonly='readonly' class="required input-small"></td>
            </tr>  
            <tr><td>Jumlah</td>    <td> : <input type=text name='Jumlah' id='Jumlah' size=15 class="input-mini" value="<?php echo nilai($default['Jumlah']); ?>"></td>
		    </tr>
		  <tr>
          <td>Harga Satuan</td>    
          <td> : <input type=text name='Harga' size=20 id="Harga" class="required number" value="<?php echo nilai($default['Harga']); ?>">
          <span id='pesan'></span></td>
		    </tr>
          <tr><td class='ket'>Dipergunakan pada Unit</td>  <td></td>
		    </tr>
		  <tr><td colspan='3'><textarea name='Dipergunakan' maxlength="250" id='loko' style='width: 600px; height: 40px;' placeholder="Ketik disini. Pisahkan dengan tanda koma (,)"><?php echo $default['Dipergunakan']; ?></textarea></td></tr>
		  <tr><td class='ket'>Keterangan</td>  <td></td>
		    </tr>
		  <tr><td colspan='3'><textarea name='Keterangan' maxlength="250" id='loko' style='width: 600px; height: 80px;'><?php echo $default['Keterangan']; ?></textarea></td></tr>
                   
          <tr><td colspan=3><input type="submit" name="submit" class="submit" value="Update" id="submit">
          <input type=button value='Batal' id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>