 <script type="text/javascript">
    $(document).ready(function(){
		
        $("#Nilai").keyup(function(){
            var Nilai = $("#Nilai").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
		$("#Luas_M2").keyup(function(){
            var Luas_M2 = $("#Luas_M2").val();
            this.value = this.value.replace(/[^0-9.]/,'');
        });
		
        $('form').submit(function(e){
            var No_Kontrak 	= $("#No_Kontrak").val();
			var datepicker 	= $("#datepicker").val();
			var Harga 		= $("#Harga").val();
           
		    if(No_Kontrak == ''){
                alert("No kontrak belum diisi !");
                return false;
            }
			
			 if(datepicker == ''){
                alert("Tanggal kontrak belum diisi !");
                return false;
            }
			
			 if(Nilai == ''){
                alert("Nilai belum diisi !");
                return false;
            }
        });
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
					url: '<?php echo base_url(); ?>kiba/lookup',
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
                    <i class="icon-tasks"></i> <?php echo ! empty($header) ?  $header: ''; ?> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
                    <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
          <table  width='100%'>
          <input type=hidden name='Kd_Bidang' id='kd_bidang' value="<?php echo $default['Kd_Bidang']; ?>">
          <input type=hidden name='Kd_Unit' id='kd_unit' value="<?php echo $default['Kd_Unit']; ?>">
          <input type=hidden name='Kd_Sub' id='kd_sub' value="<?php echo $default['Kd_Sub']; ?>">
          <input type=hidden name='Kd_UPB' id='kd_upb' value="<?php echo $default['Kd_UPB']; ?>">
          <tr>
            <td>No. SPK/Perjanjian/Kontrak</td><td> : <input type=text name='No_Kontrak' size=50 id="No_Kontrak"></td>
          </tr>
           <tr><td>Tgl SPK/Perjanjian/Kontrak</td><td> : <input type=text name='Tgl_Kontrak' size=10 id='datepicker' value="<?php 
		   $y = $this->session->userdata('tahun_anggaran');
		   echo date("$y-m-d");?>" readonly='readonly' class="input-small"></td>
            </tr>
          <tr>
            <td>Keterangan</td>    <td> : <textarea name='Keterangan' id='loko' style='width: 600px; height: 80px;'></textarea></td>
          </tr>
            <tr>
              <td>Jangka waktu</td>    <td> : <input type=text name='Waktu' size="10" ></td>
            </tr>
		  <tr>
          <td>Nilai</td>    
          <td> : <input type=text name='Nilai' size=20 id="Nilai" class="required number">
          <span id='pesan'></span></td>
		    </tr>
		  <tr>
		    <td>Jenis Posting</td>
		    <td><p>
		      <input type="radio" name="Kd_Posting" value="1" id="jenis_posting_0"> Aset Baru
		      <input type="radio" name="Kd_Posting" value="2" id="jenis_posting_1"> Kapitalisasi
		      <br>
		      </p></td>
		    </tr>
		  <tr>
		    <td colspan="2"><hr></td>
		    </tr>
		  <tr>
		    <td>Data Perusahaan</td>
		    <td>&nbsp;</td>
		    </tr>
		  <tr>
		    <td colspan="2">
            <table width="913" height="216">
  <tr>
    <td width="65">Nama</td>
    <td width="300"><input type=text name='Nm_Perusahaan' size=50></td>
    <td width="163">BANK</td>
    <td width="357"><input type=text name='Nm_Bank' size=50></td>
  </tr>
  <tr>
    <td>Bentuk</td>
    <td><input type=text name='Bentuk' size=50></td>
    <td>ATAS NAMA</td>
    <td><input type=text name='Nm_Rekening' size=50></td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td><input type=text name='Alamat' size=50></td>
    <td>No. Rekening</td>
    <td><input type=text name='No_Rekening' size=50></td>
  </tr>
  <tr>
    <td>Pimpinan</td>
    <td><input type=text name='Nm_Pemilik' size=50></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>NPWP</td>
    <td><input type=text name='NPWP' size=50></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

            </td>
		  </tr>
		  
		  <tr><td colspan=3><input type="submit" name="submit" class="submit" value="SIMPAN" id="submit" >
		    <input type=button value=BATAL id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>
