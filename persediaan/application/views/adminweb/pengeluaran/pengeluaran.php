<script type="text/javascript">

$(function(){
  $( "#search" ).change(function() {
    var t = $('#search').val();
     if (t != "all" && t != ""){        
            $('#kd_unit').attr("disabled",true);
            
            $.ajax({
                type: "POST",
                url : "<?php echo site_url('pengeluaran/select_q')?>",
                data: "q="+t,
                success: function(msg){
                  $('#f_cari').html(msg);
                }, 
                error:function(XMLHttpRequest){
                  alert(XMLHttpRequest.responseText);
                }
            });   
                      
          }else{
            $("#cari").hide();
          }
  });
});

  $(document).ready(function(){

      $(".hapus").click(function(event) {
          var no             = $(this).attr("no");
          var kd_prov        = $(this).attr("Kd_Prov");
          var kd_kab_kota    = $(this).attr("Kd_Kab_Kota");
          var kd_bidang      = $(this).attr("Kd_Bidang");
          var kd_unit        = $(this).attr("Kd_Unit");
          var kd_sub         = $(this).attr("Kd_Sub");
          var kd_upb         = $(this).attr("Kd_UPB");
          var no_id          = $(this).attr("No_ID");
          var kd_aset1       = $(this).attr("Kd_Aset1");
          var kd_aset2       = $(this).attr("Kd_Aset2");
          var kd_aset3       = $(this).attr("Kd_Aset3");
          var kd_aset4       = $(this).attr("Kd_Aset4");
          var kd_aset5       = $(this).attr("Kd_Aset5");
          var kd_aset6       = $(this).attr("Kd_Aset6");
          var no_register    = $(this).attr("No_Register");
          var id_pengeluaran = $(this).attr("Id_Pengeluaran");

          
          if(confirm("Yakin data ini mau di Hapus ??"))
          {
            $( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('pengeluaran/deletebarang')?>/"+kd_prov+"/"+kd_kab_kota+"/"+kd_bidang+"/"+kd_unit+"/"+kd_sub+"/"+kd_upb+"/"+no_id+"/"+kd_aset1+"/"+kd_aset2+"/"+kd_aset3+"/"+kd_aset4+"/"+kd_aset5+"/"+kd_aset6+"/"+no_register+"/"+id_pengeluaran,
                dataType: "JSON",
                success: function(html){
                  $("#listhapus_"+no).fadeTo(300,0, function() {
                              $(this).animate({width:0},200,function(){
                                  $(this).remove();
                                });
                            });
                  $("#loading" ).empty().html();
                  // window.location.reload(true);
              }
            });
            return false;
            }
                  
        });

  });	

</script>
<?php

	echo ! empty($message) ? '<p class="alert alert-succes">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="alert alert-succes">' . $flashmessage . '</p>': '';
?>
<div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'Persediaan - Barang Keluar'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">
                        
                        <!-- tab w/ pagination and search controls -->
                        <ul class="nav nav-tabs">
                            <li <?php if ($this->uri->segment(2) == 'upb' || $this->uri->segment(2) == '' ) {echo "class='active'";}?>>
                            <a href="<?php echo ! empty($tab1) ?  $tab1: '#'; ?>" class="tab-page" data-toggle="tab">DATA PENGELUARAN BARANG
                            <span class="badge"><?php echo ! empty($jumlah) ? $jumlah : '0';?></span></a></li>                              
                        </ul>
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>kibb/set/>
                                     <?php echo form_dropdown("q",$option_q,'','class="input-medium" id="search"'); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Pembelian </strong></span>
                                     <input type=text name='tanggal1' id='datepicker' class='input-small' readonly='readonly'> 
                                     <span><strong> s/d</strong></span>
                                     <input type=text name='tanggal2' id='datepicker2' class='input-small' readonly='readonly'> 
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                    </form>
                                </div>

                                <div class="list-pagination">
                                   <?php echo ! empty($pagination) ? $pagination: '<button class="btn"><i class="icon-filter"></i></button>';?>
                                </div>                              

                            </div>
                                                  
                        </div>
                        <!-- end tab content --> 
                        

                        

                	<div class="list-actions">
                            <div class="btn-toolbar">
                                <div class="btn-group">
                                    <button class="btn disabled edit-selected"><i class="icon-edit"></i>Aksi</button>
                                    <button class="btn dropdown-toggle disabled edit-selected" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">Pilih Aksi</li>
                                        <?php if($this->session->userdata('lvl') == '01'){ ?>
                                        	<li><a href="#" class="hapus_semua">Hapus yang dipilih</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <!-- <button class="btn"><i class="icon-edit"></i> Edit Selected</button> -->
                                <div class="btn-group">
                                    <a href="<?php echo base_url() ?>pengeluaran/add">
                                    <button class="btn btn-success"><i class="icon-plus-sign"></i> Tambah Data</button>
                                    </a>
                                </div>

                            </div>
                  </div>

                  <table class="table">
                    <thead>
                      <tr>
                          <th><input type="checkbox" id="cek-all"/></th>
                          <th>No</th>
                          <th>Tgl Pengeluaran</th>
                          <th>Nama Aset</th>
                          <th>Kepada</th>
                          <th>Jumlah/Satuan</th>
                          <th>No BAST</th>
                          <th>Tgl BAST</th>
                          <th style='text-align:right;'>Total</th>
                          <th>Keterangan</th>
                          <th><i class='icon-wrench'></i> Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
    					$i= $offset + 1;
    					foreach ($query->result() as $row){
                    echo "<tr id='listhapus_$i' title='".$row->No_Kontrak."'>
                          <td><input class='checkbox1' type='checkbox' name='check[]' value='item1' Kd_Prov = '$row->Kd_Prov' Kd_Kab_Kota = '$row->Kd_Kab_Kota' Kd_Bidang = '$row->Kd_Bidang' Kd_Unit = '$row->Kd_Unit' Kd_Sub = '$row->Kd_Sub' Kd_UPB = '$row->Kd_UPB' no='$i'></td>
                    	    <td>$i</td>
                          <td>".$row->Tgl_Pengeluaran."</td>
            						  <td>".$row->Nm_Aset6."</td>
            						  <td>".$row->Kepada."</td>
            						  <td>".$row->Jumlah." ".$row->Nm_Satuan."</td>
            						  <td>".$row->No_BAST."</td>
            						  <td>".$row->Tgl_BAST."</td>
            						  <td style='text-align:right;'>".rp($row->Jumlah*$row->Harga)."</td>
            						  <td>".$row->Keterangan."</td>
                          <td>".anchor(current_url().'#',"<i class='fa fa-trash'></i> Hapus",array('class'=> 'hapus','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'No_ID' => $row->No_ID,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'Kd_Aset6' => $row->Kd_Aset6,'No_Register' => $row->No_Register,'Id_Pengeluaran' => $row->Id_Pengeluaran,'no'=>$i))."</td>
                      </tr>";
				 $i++;
				} ?>
                      
                    </tbody>
                  </table>                      

                </div>
                </div>

            </div>
        </div>