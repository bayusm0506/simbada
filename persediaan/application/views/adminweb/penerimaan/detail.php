<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/datatables/jquery.dataTables.min.css">
<script src="<?php echo base_url(); ?>asset/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	var table;
    $(document).ready(function() {
      table = $('#table_rincian').DataTable({
		paging: false,
        "info": false,
		"bProcessing": true,
		"searching": true,
        
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url().'penerimaan/ajax_list_transaksi/'.$Kd_Prov.'/'.$Kd_Kab_Kota.'/'.$Kd_Bidang.'/'.$Kd_Unit.'/'.$Kd_Sub.'/'.$Kd_UPB.'/'.$No_ID; ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
	        { 
	          "targets": [ 9 ], //last column
	          "orderable": false, //set not orderable
	        },
        ],

      });

      $('#table_pencarian').DataTable();

    });

function reload_table(){
      table.ajax.reload(null,false); //reload datatable ajax
      $("#loading" ).empty().html(); 
    }

 function addbarang(){
		var kd_aset1        = $('#kd_aset1').val();
		var kd_aset2        = $('#kd_aset2').val();
		var kd_aset3        = $('#kd_aset3').val();
		var kd_aset4        = $('#kd_aset4').val();
		var kd_aset5        = $('#kd_aset5').val();
		var kd_aset6        = $('#kd_aset6').val();
		var Merk            = $('#Merk').val();
		var Ukuran          = $('#Ukuran').val();
		var Tahun_Pembuatan = $('#Tahun_Pembuatan').val();
		var Jumlah          = $('#Jumlah').val();
		var Kd_Satuan       = $('#Kd_Satuan').val();
		var Harga           = $('#Harga').val();

		var kode = {
					Kd_Prov:$("#Kd_Prov").val(),
					Kd_Kab_Kota:$("#Kd_Kab_Kota").val(),
					Kd_Bidang:$("#Kd_Bidang").val(),
					Kd_Unit:$("#Kd_Unit").val(),
					Kd_Sub:$("#Kd_Sub").val(),
					Kd_UPB:$("#Kd_UPB").val(),
					No_ID:$("#No_ID").val(),
					Kd_Aset1:kd_aset1,
					Kd_Aset2:kd_aset2,
					Kd_Aset3:kd_aset3,
					Kd_Aset4:kd_aset4,
					Kd_Aset5:kd_aset5,
					Kd_Aset6:kd_aset6,
					Merk:Merk,
					Ukuran:Ukuran,
					Tahun_Pembuatan:Tahun_Pembuatan,
					Jumlah:Jumlah,
					Kd_Satuan:Kd_Satuan,
					Harga:Harga
					};

		// alert($("#Kd_Prov").val()); return false;
        if (kd_aset1 == '' || kd_aset2 == '' || kd_aset3 == '' || kd_aset4 == '' || kd_aset5 == '' || kd_aset6 == '') {
          	alert("Silahkan Pilih Nama Aset");
          	$('#Nm_Aset6').focus();
        }else if(Tahun_Pembuatan == ''){
          	$('#Tahun_Pembuatan').focus();
        }else if(Harga == ''){
          	$('#Harga').focus();
        }else if(Jumlah == ''){
          	$('#Jumlah').focus();
        }else if(Kd_Satuan == ''){
        	alert("Silahkan Pilih Satuan Harga");
        	$('#Kd_Satuan').focus();
        	return false;
        }
        else{
          $( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
          $.ajax({
            url : "<?php echo base_url(); ?>penerimaan/addbarang",
            type: "POST",
            data: kode,
            dataType: "JSON",
            success: function(data)
            {
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding data ');
            }
        });
          //mereset semua value setelah btn tambah ditekan
          $('.reset').val('');
          $("#kode").text('');
        };
    }

 function deletebarang(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,No_ID,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,Kd_Aset6,No_Register){
 		if(confirm("Yakin data ini mau di Hapus ??"))
		{
		$.ajax({
            url : "<?php echo site_url('penerimaan/deletebarang')?>/"+Kd_Prov+"/"+Kd_Kab_Kota+"/"+Kd_Bidang+"/"+Kd_Unit+"/"+Kd_Sub+"/"+Kd_UPB+"/"+No_ID+"/"+Kd_Aset1+"/"+Kd_Aset2+"/"+Kd_Aset3+"/"+Kd_Aset4+"/"+Kd_Aset5+"/"+Kd_Aset6+"/"+No_Register,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
		return false;
		}
    }

	 function editbarang(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,No_ID,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,Kd_Aset6,No_Register){
 	
	 	var page = "<?php echo site_url('penerimaan/editbarang')?>/"+Kd_Prov+"/"+Kd_Kab_Kota+"/"+Kd_Bidang+"/"+Kd_Unit+"/"+Kd_Sub+"/"+Kd_UPB+"/"+No_ID+"/"+Kd_Aset1+"/"+Kd_Aset2+"/"+Kd_Aset3+"/"+Kd_Aset4+"/"+Kd_Aset5+"/"+Kd_Aset6+"/"+No_Register;
		var $dialog = $('<div></div>')
		               // .html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
   		               .load(page)
		               .dialog({
		    title:"FORM UBAH DATA BARANG",
		    modal: true,
		    draggable: false,
		    resizable: false,
		    position: ['center'],
		    show: 'blind',
		    hide: 'fade',
			height: 400,
			width: 650,

			buttons: {
				"Proses": function() {

								
				  $.ajax({
					url : "<?php echo site_url('kibb/proses_usul_hapus')?>",
					type : 'POST',
					data : kode,
					success: function(msg){
							// alert("data berhasil diusulkan sebagai daftar usulan penghapusan!");
							$dialog.dialog('close');
							$("#listhapus_"+no).addClass( "css_usulhapus", 1000);

						},
					error: function (data) {
						alert("gagal");
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

	$dialog.dialog('open');

    }

    function subTotal(qty)
	{

		var harga = $('#harga_barang').val().replace(".", "").replace(".", "");

		$('#sub_total').val(convertToRupiah(harga*qty));
	}

	function convertToRupiah(angka)
	{

	    var rupiah = '';    
	    var angkarev = angka.toString().split('').reverse().join('');
	    
	    for(var i = 0; i < angkarev.length; i++) 
	      if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	    
	    return rupiah.split('',rupiah.length-1).reverse().join('');
	
	}

	$('.uang').maskMoney({
		thousands:'.', 
		decimal:',', 
		precision:0
	});

</script>

<script type="text/javascript">
$(this).ready( function() {
	$("#Nm_Aset6").autocomplete({
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
				
				$("#kode_ssh").click(function(){
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
								$("#kd_aset1").val(kd1);
								$("#kd_aset2").val(kd2);
								$("#kd_aset3").val(kd3);
								$("#kd_aset4").val(kd4);
								$("#kd_aset5").val(kd5);
								$("#kd_aset6").val(kd6);
								$("#Nm_Aset6").val(Nm_brg);

			
								var kode = kd1+'.'+kd2+'.'+kd3+'.'+kd4+'.'+kd5+'.'+kd6;
								$("#kode").text(kode);
															 
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
<?php
	
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
?>

        <div class="panel-header"><i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
        <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
		          <div class="row">
			          <div class="span5">
				          <table width='100%'  class="table-striped info" style="border: 1px solid #ccc; border-top: none; border-bottom: none; height: 350px;">
					          <tr >
					          	<td>Tanggal Diterima</td>
					          	<td width='60%'> : <b><?php echo tgl_dmy($Tgl_Diterima); ?></b> </td>
					          </tr>
		            		  <tr>
		            		  	<td>Dari ( Nama Rekanan )</td>
		            		  	<td> : <b><?php echo $Nm_Rekanan; ?></b></td>
							  </tr>
							  <tr>
		            		  	<td>No Kontrak/SPK/Kwitansi</td>
		            		  	<td> : <b><?php echo $No_Kontrak; ?></b></td>
							  </tr>
					          <tr>
					          	<td>Tanggal Kontrak/SPK/Kwitansi</td>
					          	<td> : <b><?php echo tgl_dmy($Tgl_Kontrak); ?></b></td>
		            		  </tr>
							  <tr>
		            		  	<td>No BA Pemeriksaan</td>
		            		  	<td> : <b><?php echo $No_BA_Pemeriksaan; ?></b></td>
							  </tr>
					          <tr>
					          	<td>Tgl BA Pemeriksaan</td>
					          	<td> : <b><?php echo tgl_dmy($Tgl_BA_Pemeriksaan); ?></b></td>
		            		  </tr>
		            		  <tr>
		            		  	<td class='ket' colspan="2">Keterangan</td>
							  </tr>
							  <tr><td colspan='2'><b><?php echo $Keterangan; ?></b></tr>
				          </table>
				       </div>

				       <div class="span6" style="border: 1px solid #ccc; padding: 30px;">
				       		<form class="form-horizontal" id="form_transaksi" role="form">
				       		<table width='100%'>
				          		<input type="hidden" name='Kd_Prov'  id='Kd_Prov' value="<?php echo $Kd_Prov; ?>">
								<input type="hidden" name='Kd_Kab_Kota' id='Kd_Kab_Kota' value="<?php echo $Kd_Kab_Kota; ?>">
								<input type="hidden" name='Kd_Bidang' id='Kd_Bidang' value="<?php echo $Kd_Bidang; ?>">
								<input type="hidden" name='Kd_Unit' id='Kd_Unit' value="<?php echo $Kd_Unit; ?>">
								<input type="hidden" name='Kd_Sub' id='Kd_Sub' value="<?php echo $Kd_Sub; ?>">
								<input type="hidden" name='Kd_UPB' id='Kd_UPB' value="<?php echo $Kd_UPB; ?>">
								<input type="hidden" name='No_ID' id='No_ID' value="<?php echo $No_ID; ?>">
					          <tr>
					          	<td>Nama Barang</td>
					          	<td width='60%'> 
					          		<div class="form-inline" >
					          			:<input type="hidden" name='Kd_Aset1' id='kd_aset1' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset2' id='kd_aset2' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset3' id='kd_aset3' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset4' id='kd_aset4' readonly='readonly' class="required reset">
										<input type="hidden" name='Kd_Aset5' id='kd_aset5' readonly='readonly' class="required reset">
										 <input type="hidden" name='Kd_Aset6' size=2 id='kd_aset6' readonly='readonly' class="input-mini required reset">
										<input type=text name='nm_aset6' id='Nm_Aset6' class="autocomplete input-large reset" placeholder="ketik nama barang disini !" required>
							          	<a href="javascript:;" class="btn btn-primary" id="kode_ssh"><i class="fa fa-search"></i></a>
							      	</div>
							      	<span id="kode" style="padding-left: 7px; color: #cc6699; font-size: 11px; font-family: verdana, arial, sans-serif""></span>
							    </td>
					          </tr>
		            		  <tr>
		            		  	<td>Merk</td>
		            		  	<td> : <input type=text name='Merk' id='Merk' class="input-small reset" placeholder="" required></td>
							  </tr>
							  <tr>
		            		  	<td>Ukuran</td>
		            		  	<td> : <input type=text name='Ukuran' id='Ukuran' class="input-small reset" required></td>
							  </tr>
					          <tr>
					          	<td>Tahun Pembuatan</td>
					          	<td> : <select name="Tahun_Pembuatan" id='Tahun_Pembuatan' class="reset" style="width: 130px;">
							                <option value="">- Pilih Tahun -</option>
							                <?php
							                $thn_skr = date('Y');
							                for ($x = $thn_skr; $x >= 2000; $x--) {
							                ?>
							                    <option value="<?php echo $x ?>"><?php echo $x ?></option>
							                <?php
							                }
							                ?>
							            </select>
							      </td>
		            		  </tr>
							  <tr>
		            		  	<td>Jumlah Satuan</td>
		            		  	<td><div class="form-inline" style="padding-bottom: 10px;">
		            		  		 : <input type='text' name='Jumlah' id='Jumlah' class="input-mini reset" onkeyup="numericFilter(this);" onblur="if (this.value == '') {this.value = '0';}" onfocus="if (this.value == '0') {this.value = '';}" required>
		            		  		   <?php echo form_dropdown('Kd_Satuan', $option_satuan, '','class="reset" id="Kd_Satuan" style="width:140px;"'); ?>
		            		  		 </div>
		            		  	</td>
							  </tr>
							  <tr>
					          	<td>Harga Satuan</td>
					          	<td> : <input type=text name='Harga' id='Harga' class="input-large uang reset" placeholder="Rp." onkeyup="numericFilter(this);" onblur="if (this.value == '') {this.value = '0';}" onfocus="if (this.value == '0') {this.value = '';}" required></td>
		            		  </tr>
							  <tr>
		            		  	<td colspan="2">
		            		  		<button type="button" class="btn btn-primary"id="tambah" onclick="addbarang()"> <i class="fa fa-cart-plus"></i> Tambah</button>
						    		<button type="reset" class="btn btn-warning">Batal</button>
		            		  	</td>
							  </tr>
				          </table>
				          </form>
				       </div>
			       </div>
			       <hr>
			       
                  <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table_rincian">
	                 <thead>
	                     <tr>
	                        <th>No</th>
	                        <th>Kode Barang</th>
	                        <th>Nama/Jenis Barang</th>
	                        <th>No Register</th>
	                        <th>Merk</th>
	                        <th>Ukuran</th>
	                        <th>Harga</th>
	                        <th>Jumlah</th>
	                        <th>Total</th>
	                        <th>#</th>
	                    </tr>
	                 <thead>
	                 <tbody>
	                 </tbody>    
                </table>
                <table>
			       		<tr align="right">
		                	<a href="<?php echo site_url('penerimaan/upb/'.$Kd_Bidang.'/'.$Kd_Unit.'/'.$Kd_Sub.'/'.$Kd_UPB)?>">
		                		<button class="btn">Kembali Ke Data</button>
		                	</a>
				        </tr>
				</table>
          </form>

       </div><!-- span 12 -->
