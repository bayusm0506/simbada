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

function attKendaraanShow(){
  $("#no_rangka").show("slow");
  $("#no_bpkb").show("slow");
  $("#no_mesin").show("slow");
  $("#jumlah_roda").show("slow");
  $("#pemakai").show("slow");
}

function attKendaraanHide(){
  $("#no_rangka").hide("slow");
  $("#no_bpkb").hide("slow");
  $("#no_mesin").hide("slow");
  $("#jumlah_roda").hide("slow");
  $("#pemakai").hide("slow");
}

	    $(this).ready( function() {
    		$("#Nm_Aset5").autocomplete({
      			minLength: 1,
      			source: 
	        		function(req, add){
	          			$.ajax({
			        		url: "<?php echo base_url(); ?>kibb/json",
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
									kd_bidang:$("#kd_bidang").val(),
									kd_unit:$("#kd_unit").val(),
									kd_sub:$("#kd_sub").val(),
									kd_upb:$("#kd_upb").val(),
									kd_aset1:$("#kd_aset1").val(),
									kd_aset2:$("#kd_aset2").val(),
									kd_aset3:$("#kd_aset3").val(),
									kd_aset4:$("#kd_aset4").val(),
									kd_aset5:$("#kd_aset5").val()
									};
				
				
						$.ajax({
									type: "POST",
									url : "<?php echo base_url(); ?>kibb/register",
									data: kode,
									success: function(msg){
										$('#register').html(msg);
								            if ($("#kd_aset2").val() == 3 && $("#kd_aset3").val() == 1){
								                attKendaraanShow();
								            }else{
								            	attKendaraanHide();
								            }

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
						title:"CARI KODE BARANG KIB B. PERALATAN DAN MESIN",
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
					url: '<?php echo base_url(); ?>kibb/lookup',
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
									url : "<?php echo base_url(); ?>kibb/register",
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
			echo form_dropdown('Kd_Pemilik', $option_pemilik, 11);
		  ?>
          <?php echo form_error('Kd_Pemilik', '<p class="field_error">', '</p>');?>
          </td>
          </tr>
		  <tr><td>Kode Aset</td>    <td>  
          <div class="form-inline"> :
		  <input type=text name='kd_aset1' size=2 id='kd_aset1' readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset2' size=2 id='kd_aset2' readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset3' size=2 id='kd_aset3' readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset4' size=2 id='kd_aset4' readonly='readonly' class="input-mini required">
		  <input type=text name='kd_aset5' size=2 id='kd_aset5' readonly='readonly' class="input-mini required">
          <input type=text name='Nm_Aset5' size=88 id='Nm_Aset5' placeholder="ketik nama barang disini !">
		  <button id='kode-search' class='tombol' ><i class="icon-search icon-large"></i>CARI</button>
          </div>
          </td>
		    </tr>
           <tr><td>No Register</td><td> : 
           <span id='register'>
		       <input type=text name='No_Register' size=5 id="No_Register" class="required input-mini" readonly='readonly'>
		   </span>
              <span id="msg"></span>     
           </td>
           </tr>
           <tr><td>Ruang</td>      <td> : 
	          <?php
				echo form_dropdown('Kd_Ruang', $option_ruang, '');
			  ?>
	          <?php echo form_error('Kd_Ruang', '<p class="field_error">', '</p>');?>
	          </td>
          </tr>

          <tr><td>Status</td>      <td> : 
	          <?php
				echo form_dropdown('Kd_KA', array('1'=>"Aset Operasional",'0'=>"Aset Non Operasional"), '');
			  ?>
	          <?php echo form_error('Kd_KA', '<p class="field_error">', '</p>');?>
	          </td>
          </tr>
          
          <tr><td>Tgl Pembelian</td><td> : <input type=text name='Tgl_Perolehan' size=10  id='datepicker' readonly='readonly' class="required input-small"></td>
            </tr>
           <tr><td>Tgl Pembukuan</td><td> : <input type=text name='Tgl_Pembukuan' size=10 id='datepicker2' value="<?php 
		   $y = $this->session->userdata('tahun_anggaran');
		   echo date("$y-m-d");?>" readonly='readonly' class="input-small"></td>
            </tr>
          <tr><td>Merk</td>    <td> : <input type=text name='Merk' size=15 ></td>
            </tr>
          <tr><td>Type</td>    <td> : <input type=text name='Type' size=15 ></td>
            </tr>
          <tr><td>Ukuran</td> <td> : <input type=text name='CC' size=15 maxlength="50"></td>
            </tr>
		  <tr><td>Bahan</td>    <td> :  <input type=text name='Bahan' size=15></td>
		    </tr>
             <tr><td>No. Pabrik</td> <td> : <input type=text name='Nomor_Pabrik' size=15  ></td>
            </tr>
             <tr id="jumlah_roda" style="display:none;">
             	<td>Jumlah Roda</td> <td> : 
             	  <?php
				  	$jlh_roda = array('' => 'Pilih Roda','2'=>'Roda Dua','3'=>'Roda Tiga','4'=>'Roda Empat','6'=>'Roda Enam','8'=>'Roda Delapan','10'=>'Roda Sepuluh');
					echo form_dropdown('Jumlah_Roda', $jlh_roda, '');
				  ?>
				 </td>
            </tr>
             <tr id="no_rangka" style="display:none;">
             	<td>No. Rangka</td> <td> : <input type=text name='Nomor_Rangka' size=15 ></td>
            </tr>
             <tr id="no_mesin" style="display:none;">
             	<td>No. Mesin</td> <td> : <input type=text name='Nomor_Mesin' size=15 ></td>
            </tr>
            <tr id="no_bpkb" style="display:none;">
	            <td>No. BPKB</td> 
	            <td> : <input type=text name='Nomor_BPKB' size=15 class="input-small" > 
	            No. Polisi : <input type=text name='Nomor_Polisi' size=15 class="input-small"> </td>
            </tr>
		  <tr><td>Asal Usul</td>    <td> : 
          <?php
		  	$option_hak = array('Pembelian' => 'Pembelian','Hibah'=>'Hibah','Pinjam'=>'Pinjam','Sewa'=>'Sewa','Guna Usaha'=>'Guna Usaha','Penyerahan'=>'Penyerahan');
			echo form_dropdown('Asal_usul', $option_hak, '');
		  ?>
               </td>
		    </tr>
            <tr><td>Kondisi</td>    <td> : <?php
		  	$option_hak = array('1' => 'Baik','2'=>'Kurang Baik','3'=>'Rusak Berat');
			echo form_dropdown('Kondisi', $option_hak, '','class="input-medium required"');
		  ?></td>
		    </tr>
		  <tr>
		  <tr>
             <td>Nama Pemakai</td> <td> : <input type=text name='Pemakai' size=50 ></td>
          </tr>
          <td>Harga</td>    
          <td> : <input type=text name='Harga' size=20 id="Harga" class="required number">
          <span id='pesan'></span></td>
		    </tr>
		  <tr>
		  	<td class='ket'>Keterangan</td>  <td></td>
		   </tr>
		  <tr>
		  	<td colspan='3'>
		  	<textarea name='Keterangan' id='loko' style='width: 600px; height: 80px;'></textarea>
		  	</td>
		  </tr>
		  
		  <tr><td><input type='checkbox' onclick='aktif()' id='cekbox' title='klik untuk mengaktifkan' name='perc'>Percepatan</td> 
		  	  <td><input type=text size=5 id='percepatan' name='jmlperc' disabled=disabled class="required number input-mini" placeholder='unit'>
              <span id="proses"></span></td>
		  </tr>	
          
          <tr><td colspan=3><input type="submit" name="submit" class="submit" value="SIMPAN" id="submit">
          <input type=button value=BATAL id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>
