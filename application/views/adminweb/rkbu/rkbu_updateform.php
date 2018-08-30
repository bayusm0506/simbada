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
            var Harga  = $("#Harga").val();
            var Total  = Harga*Jumlah;
            $("#Total").val(Total);

        });

		
        $('form').submit(function(e){
			var kd_aset1    = $("#kd_aset1").val();
			var Jumlah      = $("#Jumlah").val();
			var Harga       = $("#Harga").val();
			
			var id_program  = $("#id_program").val();
			var id_kegiatan = $("#id_kegiatan").val();
			var kd_rek_1    = $("#kd_rek_1").val();
           
		    if(kd_aset1 == ''){
                alert("Nama aset belum diisi !");
                return false;
            }
			
			if(Harga == '' || Jumlah == ''){
                alert("Harga Pembelian atau Jumlah belum diisi !");
                return false;
            }

            if(id_program == ''){
                alert("Program belum diisi !");
                return false;
            }

            if(id_kegiatan == ''){
                alert("Kegiatan belum diisi !");
                return false;
            }

            if(kd_rek_1 == ''){
                alert("Kode Rekening belum diisi !");
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
	$("#Nm_Aset6").autocomplete({
			minLength: 1,
			source: 
		function(req, add){
  			$.ajax({
        		url: "<?php echo base_url(); ?>rkbu/json",
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
			
			var kode = {
						kd_bidang:$("#kd_bidang").val(),
						kd_unit:$("#kd_unit").val(),
						kd_sub:$("#kd_sub").val(),
						kd_upb:$("#kd_upb").val(),
						kd_aset1:$("#kd_aset1").val(),
						kd_aset2:$("#kd_aset2").val(),
						kd_aset3:$("#kd_aset3").val(),
						kd_aset4:$("#kd_aset4").val(),
						kd_aset5:$("#kd_aset5").val(),
						kd_aset6:$("#kd_aset6").val()
						};
		
		
		$.ajax({
							type: "POST",
							url : "<?php echo base_url(); ?>rkbu/no_id",
							data: kode,
							success: function(msg){
							$('#No_ID').html(msg);
						}
				});	           		
 		},		
	});
});
</script>
<script>
	$(document).ready( function (){
    	$('#id_program').change(function(){
    		   var program = $("#id_program").val();
               if (program != 0){           
		            var kode = {
		                            Kd_Prog:$("#id_program").val()
		                        };
		                        $('#id_kegiatan').attr("disabled",true);
		                        $.ajax({
		                                type: "POST",
		                                url : "<?php echo site_url('chain/select_kegiatan')?>",
		                                data: kode,
		                                success: function(msg){
		                        			$('#id_kegiatan').attr("disabled",false);
		                                    $('#id_kegiatan').html(msg);
		                                }
		                        });             
				                                    
				}else{
				    $("#id_kegiatan option").attr("disabled", true);
				}
           });
	});
</script>
<script type="text/javascript">
$(this).ready( function() {
	$("#Nm_Rek_5").autocomplete({
			minLength: 1,
			source: 
		function(req, add){
  			$.ajax({
        		url: "<?php echo base_url(); ?>rkbu/json_rekening",
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
    		$("#kd_rek_1").val(ui.item.id1);
			$("#kd_rek_2").val(ui.item.id2);
			$("#kd_rek_3").val(ui.item.id3);
			$("#kd_rek_4").val(ui.item.id4);
			$("#kd_rek_5").val(ui.item.id5);          		
 		},		
	});
});
</script>	
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
		  <tr>
		  	<td>Kode Aset</td>    <td>  
	          <div class="form-inline"> :
				  <input type=text name='Kd_Aset1' value="<?php echo $Kd_Aset1; ?>" size=2 id='kd_aset1' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Aset2' value="<?php echo $Kd_Aset2; ?>" size=2 id='kd_aset2' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Aset3' value="<?php echo $Kd_Aset3; ?>" size=2 id='kd_aset3' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Aset4' value="<?php echo $Kd_Aset4; ?>" size=2 id='kd_aset4' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Aset5' value="<?php echo $Kd_Aset5; ?>" size=2 id='kd_aset5' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Aset6' value="<?php echo $Kd_Aset6; ?>" size=2 id='kd_aset6' readonly='readonly' class="input-mini required">
		          <input type=text name='Nm_Aset6' value="<?php echo $Nm_Aset6; ?>" size=88 id='Nm_Aset6' placeholder="ketik nama barang disini !">
	          </div>
          	</td>
		   </tr>
           <tr><td>No ID</td><td> : 
           <span id='No_ID'>
           <input type=text name='No_ID' value="<?php echo $No_ID; ?>" size=5 id="No_ID" class="required input-mini" readonly='readonly' >
        	</span>
              <span id="msg"></span>     
           </td>
           </tr>
           
            <tr>
            	<td>Jumlah</td>    <td> : <input type=text name='Jumlah' value="<?php echo nilai($Jumlah); ?>" id='Jumlah' size=15 class="input-mini"></td>
		    </tr>
		  <tr>
	          <td>Harga Satuan</td>    
	          <td> : <input type=text name='Harga' value="<?php echo nilai($Harga); ?>" size=20 id="Harga" class="required number">
	          <span id='pesan'></span></td>
		  </tr>
		  <tr>
	          <td>Total</td>    
	          <td> : <input type=text name='Total' value="<?php echo nilai($Harga*$Jumlah); ?>" size=20 id="Total" disabled="true"></td>
		  </tr>
		  <tr>
            	<td>Kebutuhan Max</td>    <td> : <input type=text name='Kebutuhan_Max' value="<?php echo nilai($Kebutuhan_Max); ?>" id='Kebutuhan_Max' size=15 class="input-mini"></td>
		    </tr>
		  <tr>
	          <td>Pilih Program</td>    
	          <td> : <?php echo form_dropdown('Kd_Prog', $option_program, $Kd_Prog,"id='id_program' style='width:600px;'"); ?>
			  </td>
		  </tr>
		  <tr>
	          <td>Pilih Kegiatan</td>    
	          <td> : <?php
						echo form_dropdown('Kd_Keg', $option_kegiatan, $Kd_Keg,'id="id_kegiatan" style="width:650px;"');
					 ?>
			  </td>
		  </tr>
		  <tr>
		  	<td>Kode Rekening</td>    <td>  
	          <div class="form-inline"> :
				  <input type=text name='Kd_Rek_1' value="<?php echo $Kd_Rek_1; ?>" size=2 id='kd_rek_1' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Rek_2' value="<?php echo $Kd_Rek_2; ?>" size=2 id='kd_rek_2' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Rek_3' value="<?php echo $Kd_Rek_3; ?>" size=2 id='kd_rek_3' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Rek_4' value="<?php echo $Kd_Rek_4; ?>" size=2 id='kd_rek_4' readonly='readonly' class="input-mini required">
				  <input type=text name='Kd_Rek_5' value="<?php echo $Kd_Rek_5; ?>" size=2 id='kd_rek_5' readonly='readonly' class="input-mini required">
		          <input type=text name='Nm_Rek_5' value="<?php echo $Nm_Rek_5; ?>" size=88 id='Nm_Rek_5' style="width:400px;" placeholder="cari kode rekening disini !">
	          </div>
          	</td>
		   </tr>
          <tr>
          	<td class='ket'>Keterangan</td>
          	<td></td>
		  </tr>
		  <tr><td colspan='3'><textarea name='Keterangan' maxlength="250" id='loko' style='width: 600px; height: 80px;'><?php echo $Keterangan; ?></textarea></td></tr>         
          <tr><td colspan=3><input type="submit" name="submit" class="submit" value="UPDATE" id="submit">
          <input type=button value=BATAL id='reset' onclick=self.history.back() ></td></tr>
          </table>
          </form>

       </div>
       </div>
       </div>
       </div>
