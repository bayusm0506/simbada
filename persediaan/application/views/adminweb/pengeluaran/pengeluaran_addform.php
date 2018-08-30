<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/datatables/jquery.dataTables.min.css">
<script src="<?php echo base_url(); ?>asset/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	var $dialog;
	var table;
    $(document).ready(function() {
      table = $('#table_rincian').DataTable({
		paging: false,
        "info": false,
		"bProcessing": true,
		"searching": true,
        
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url()?>pengeluaran/ajax_list_transaksi",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
	        { 
	          "bSortable": false, "bSearchable": true,
	          "targets": [ 2 ], //last column
	          "orderable": false, //set not orderable
	        },
        ],

      });
    });
</script>


<script>

	$(".pilih").live("click",function(){

		var No_ID       = $(this).attr("No_ID");
		var Kd_Aset1    = $(this).attr("Kd_Aset1");
		var Kd_Aset2    = $(this).attr("Kd_Aset2");
		var Kd_Aset3    = $(this).attr("Kd_Aset3");
		var Kd_Aset4    = $(this).attr("Kd_Aset4");
		var Kd_Aset5    = $(this).attr("Kd_Aset5");
		var Kd_Aset6    = $(this).attr("Kd_Aset6");
		var No_Register = $(this).attr("No_Register");
		var Stok        = $(this).attr("Stok");
		var Harga       = $(this).attr("Harga");
		var Nm_Aset     = $(this).attr("Nm_Aset");
		var Nm_Satuan   = $(this).attr("Nm_Satuan");


		$("#kd_aset1").val(Kd_Aset1);
		$("#kd_aset2").val(Kd_Aset2);
		$("#kd_aset3").val(Kd_Aset3);
		$("#kd_aset4").val(Kd_Aset4);
		$("#kd_aset5").val(Kd_Aset5);
		$("#kd_aset6").val(Kd_Aset6);
		$("#no_register").val(No_Register);
		$("#No_ID").val(No_ID);
		$("#stok").text("Stok Tersedia : "+Stok+" "+Nm_Satuan);
		$("#Harga").val(Harga);
		$("#Nm_Aset6").val(Nm_Aset);
		$("#stok_barang").val(Stok);
		
		var kode = Kd_Aset1+'.'+Kd_Aset2+'.'+Kd_Aset3+'.'+Kd_Aset4+'.'+Kd_Aset5+'.'+Kd_Aset6+'.'+No_Register;
		$("#kode").text(kode);

		$dialog.dialog('close');
	});
</script>

<script type="text/javascript">
$(this).ready( function() {
	$("#Nm_Aset6").autocomplete({
			minLength: 1,
			source: 
		function(req, add){
  			$.ajax({
        		url: "<?php echo base_url(); ?>pengeluaran/json",
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
 			if(ui.item.stok < 1){
 				alert("Stok Sudah Habis"); return false;
 			}else{
	    		$("#kd_aset1").val(ui.item.id1);
				$("#kd_aset2").val(ui.item.id2);
				$("#kd_aset3").val(ui.item.id3);
				$("#kd_aset4").val(ui.item.id4);
				$("#kd_aset5").val(ui.item.id5);
				$("#kd_aset6").val(ui.item.id6);
				$("#no_register").val(ui.item.id7);
				$("#No_ID").val(ui.item.id8);
				$("#stok").text("Stok Tersedia : "+ui.item.stok+" "+ui.item.nm_satuan);
				$("#Harga").val(ui.item.harga);
				$("#stok_barang").val(ui.item.stok);
				
				var kode = ui.item.id8+'.'+ui.item.id2+'.'+ui.item.id3+'.'+ui.item.id4+'.'+ui.item.id5+'.'+ui.item.id6+'.'+ui.item.id7;
				$("#kode").text(kode);
			}
 		},		
	});
});

function addbarang(){
        var No_ID           = $('#No_ID').val();
		var kd_aset1        = $('#kd_aset1').val();
		var kd_aset2        = $('#kd_aset2').val();
		var kd_aset3        = $('#kd_aset3').val();
		var kd_aset4        = $('#kd_aset4').val();
		var kd_aset5        = $('#kd_aset5').val();
		var kd_aset6        = $('#kd_aset6').val();
		var No_Register     = $('#no_register').val();
		var Tgl_Pengeluaran = $('#datepicker').val();
		var Kepada          = $('#Kepada').val();
		var No_BAST         = $('#No_BAST').val();
		var Tgl_BAST        = $('#datepicker2').val();
		var Jumlah          = $('#Jumlah').val();
		var Keterangan      = $('#Keterangan').val();

		var stok_barang     = $('#stok_barang').val();

		// var tot = parseInt(Jumlah) + parseInt(stok_barang);

		// if(Jumlah < stok_barang){
		// 	alert("Jumlah = "+Jumlah+" | Stok = "+stok_barang+" = "+tot+" ? "+Jumlah+" < "+stok_barang); return false;
		// }else{
		// 	alert("Jumlah = "+Jumlah+" | Stok = "+stok_barang+" = "+tot+" ? "+Jumlah+" > "+stok_barang); return false;
		// }
		var kode = {
					Kd_Prov:$("#Kd_Prov").val(),
					Kd_Kab_Kota:$("#Kd_Kab_Kota").val(),
					Kd_Bidang:$("#Kd_Bidang").val(),
					Kd_Unit:$("#Kd_Unit").val(),
					Kd_Sub:$("#Kd_Sub").val(),
					Kd_UPB:$("#Kd_UPB").val(),
					No_ID:No_ID,
					Kd_Aset1:kd_aset1,
					Kd_Aset2:kd_aset2,
					Kd_Aset3:kd_aset3,
					Kd_Aset4:kd_aset4,
					Kd_Aset5:kd_aset5,
					Kd_Aset6:kd_aset6,
					No_Register:No_Register,
					Tgl_Pengeluaran:Tgl_Pengeluaran,
					Kepada:Kepada,
					No_BAST:No_BAST,
					Tgl_BAST:Tgl_BAST,
					Jumlah:Jumlah,
					Keterangan:Keterangan,
				};

		// alert(stok_barang); return false;
        if (kd_aset1 == '' || kd_aset2 == '' || kd_aset3 == '' || kd_aset4 == '' || kd_aset5 == '' || kd_aset6 == '') {
          	alert("Silahkan Pilih Nama Aset");
          	$('#Nm_Aset6').focus();
        }else if(Kepada == ''){
          	$('#Kepada').focus();
        }else if(Jumlah == ''){
          	$('#Jumlah').focus();
        }else if(parseInt(Jumlah) > parseInt(stok_barang)){
        	alert("Stok barang tidak cukup!");
          	$('#Jumlah').focus();
        }else{
          $( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
          $.ajax({
            url : "<?php echo base_url(); ?>pengeluaran/addbarang",
            type: "POST",
            data: kode,
            dataType: "JSON",
            success: function(data)
            {
                   	$('#data_stok').dataTable().fnDestroy();
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
          $("#stok").text('');
        };
	}

 function reload_table(){
  table.ajax.reload(null,false); //reload datatable ajax
  $("#loading" ).empty().html(); 
 }
	
 function deletebarang(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,No_ID,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,Kd_Aset6,No_Register,Id_Pengeluaran){
 		if(confirm("Yakin data ini mau di Hapus ??"))
		{
		$.ajax({
            url : "<?php echo site_url('pengeluaran/deletebarang')?>/"+Kd_Prov+"/"+Kd_Kab_Kota+"/"+Kd_Bidang+"/"+Kd_Unit+"/"+Kd_Sub+"/"+Kd_UPB+"/"+No_ID+"/"+Kd_Aset1+"/"+Kd_Aset2+"/"+Kd_Aset3+"/"+Kd_Aset4+"/"+Kd_Aset5+"/"+Kd_Aset6+"/"+No_Register+"/"+Id_Pengeluaran,
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

    $("#Jumlah").keyup(function(){
            var Jumlah = $("#Jumlah").val();
            var Harga  = $("#Harga").val();
            var Total  = Harga*Jumlah;
            $("#Total").val(Total);

        });

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
<style type="text/css">
	.info tr td{
		padding-left: 20px;
		padding-right: 10px;
		padding-bottom: 10px;
	}
</style>


<script>
$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$("#kode_ssh").live("click",function(){

		var no          = $(this).attr("no");
		var Kd_Prov     = $(this).attr("Kd_Prov");
		var Kd_Kab_Kota = $(this).attr("Kd_Kab_Kota");
		var Kd_Bidang   = $(this).attr("Kd_Bidang");
		var Kd_Unit     = $(this).attr("Kd_Unit");
		var Kd_Sub      = $(this).attr("Kd_Sub");
		var Kd_UPB      = $(this).attr("Kd_UPB");

		var page = "<?php echo base_url()?>penerimaan/view_stok_barang/"+Kd_Prov+"/"+Kd_Kab_Kota+"/"+Kd_Bidang+"/"+Kd_Unit+"/"+Kd_Sub+"/"+Kd_UPB;
		$dialog = $('<div></div>')
		               // .html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
   		               .load(page)
		               .dialog({
		    title:"DAFTAR STOK PERSEDIAAN",
		    modal: true,
		    draggable: false,
		    resizable: false,
		    position: ['center'],
		    show: 'blind',
		    hide: 'fade',
			height: 600,
			width: 850,

			buttons: {

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
	});

});

</script>

<?php

	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
?>

<?php
		$tahun_login = $this->session->userdata('tahun_anggaran');
		$tgl_login  = date("$tahun_login-m-d");
?>

        <div class="panel-header"><i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
        <div class="panel-content">
<!-- Validation -->
           <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
		          <div class="row">
			          <div class="span5" style="border: 1px solid #ccc; padding-top: 30px; min-height: 300px;">
				          <table width='100%'  class="info">
					          <tr>
					          	<td>Tanggal Pengeluaran</td>
					          	<td> : <input type=text name='Tgl_Pengeluaran' id='datepicker' readonly='readonly' value="<?php echo $tgl_login; ?>" class="required input-small"></td>
					          </tr>
		            		  <tr>
		            		  	<td>Diserahkan Kepada</td>
		            		  	<td> : <input type=text name='Kepada' id='Kepada' size=30 ></td>
							  </tr>
		            		  <tr>
		            		  	<td class='ket' colspan="2"><b>Keterangan / Uraian</b></td>
							  </tr>
							  <tr><td colspan='2'><textarea name='Keterangan' id='Keterangan' style='width: 96%; height: 60px;'></textarea></td></tr>
				          </table>
				       </div>

				       <div class="span6" style="border: 1px solid #ccc; padding: 30px; min-height: 270px;">
				       		<form class="form-horizontal" id="form_transaksi" role="form">
				       		<table width='100%'>
				          		<input type="hidden" name='Kd_Prov'  id='Kd_Prov' value="<?php echo $Kd_Prov; ?>">
								<input type="hidden" name='Kd_Kab_Kota' id='Kd_Kab_Kota' value="<?php echo $Kd_Kab_Kota; ?>">
								<input type="hidden" name='Kd_Bidang' id='Kd_Bidang' value="<?php echo $Kd_Bidang; ?>">
								<input type="hidden" name='Kd_Unit' id='Kd_Unit' value="<?php echo $Kd_Unit; ?>">
								<input type="hidden" name='Kd_Sub' id='Kd_Sub' value="<?php echo $Kd_Sub; ?>">
								<input type="hidden" name='Kd_UPB' id='Kd_UPB' value="<?php echo $Kd_UPB; ?>">
					          	<input type="hidden" name='No_ID' size=2 id='No_ID' readonly='readonly' class="input-mini required reset">
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
										<input type="hidden" name='No_Register' size=2 id='no_register' readonly='readonly' class="input-mini required reset">
										<input type=text name='nm_aset6' id='Nm_Aset6' class="autocomplete input-large reset" placeholder="ketik nama barang disini !" required>
							          	
							          	<?php echo anchor(current_url().'#',"<i class='fa fa-search'></i>",array('class'=> 'btn btn-primary','id'=> 'kode_ssh','Kd_Prov' => $Kd_Prov,'Kd_Kab_Kota' => $Kd_Kab_Kota,'Kd_Bidang' => $Kd_Bidang,'Kd_Unit' => $Kd_Unit,'Kd_Sub' => $Kd_Sub,'Kd_UPB' => $Kd_UPB)); ?>

							      	</div>
							      	<span id="kode" style="padding-left: 7px; color: #cc6699; font-size: 11px; font-family: verdana, arial, sans-serif""></span>
							    </td>
					          </tr>
					          <tr>
		            		  	<td>Jumlah</td>
		            		  	<td> : <input type=text name='Jumlah' id='Jumlah' class="input-mini reset" onkeyup="numericFilter(this);" onblur="if (this.value == '') {this.value = '0';}" onfocus="if (this.value == '0') {this.value = '';}" required>
		            		  			<span id="stok" style="padding-left: 7px; color: #cc6699; font-size: 12px; font-family: verdana, arial, sans-serif""></span>
		            		  			<input type="hidden" id='stok_barang' class="reset">
		            		  	</td>
							  </tr>
							  <tr>
					          	<td>Harga Satuan</td>
					          	<td> : <input type=text name='Harga' id='Harga' class="input-large uang reset" placeholder="Rp." disabled="true"></td>
		            		  </tr>
		            		  <tr>
		            		  	<td>No BA Serah Terima</td>
		            		  	<td> : <input type=text name='No_BAST' id='No_BAST' size=30 ></td>
							  </tr>
					          <tr>
					          	<td>Tgl BA Serah Terima</td>
					          	<td> : <input type=text name='Tgl_BAST' value="<?php echo $tgl_login; ?>" size=10  id='datepicker2' readonly='readonly' class="required input-small"></td>
		            		  </tr>
							  <tr>
		            		  	<td colspan="2">
		            		  		<button type="button" class="btn btn-primary" id="tambah" onclick="addbarang()"> <i class="fa fa-cart-plus"></i> Tambah</button>
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
	                        <th>Jumlah Keluar</th>
	                        <th>Tgl Keluar</th>
	                        <th>#</th>
	                    </tr>
	                 <thead>
	                 <tbody>
	                 </tbody>    
                </table>
                
          </form>

       </div><!-- span 12 -->
