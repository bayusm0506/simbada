<script type="text/javascript">
$(document).ready(function(){
    $( "#search" ).change(function() {
      var t = $('#search').val();
       if (t != "all" && t != ""){
                        $.ajax({
                            type: "POST",
                            url : "<?php echo site_url('kibd/select_q')?>",
                            data: "q="+t,
                            success: function(msg){
                              $('#f_cari').html(msg);
                            }, 
                            error:function(XMLHttpRequest){
                              alert(XMLHttpRequest.responseText);
                            }
                            
                            
                        });       
                        
                        
            }else{
              $("#f_cari").hide();
            }
    });


  
});
</script>

<script type="text/javascript">
  
  $(document).ready(function(){

      $(".hapus_riwayat").click(function(event) {
        var no          = $(this).attr("no");
        var Kd_Riwayat  = $(this).attr("Kd_Riwayat");
        var Kd_Id       = $(this).attr("Kd_Id");
        var Kd_Prov     = $(this).attr("Kd_Prov");
        var Kd_Kab_Kota = $(this).attr("Kd_Kab_Kota");
        var kd_bidang   = $(this).attr("Kd_Bidang");
        var kd_unit     = $(this).attr("Kd_Unit");
        var kd_sub      = $(this).attr("Kd_Sub");
        var kd_upb      = $(this).attr("Kd_UPB");
        
        var kd_aset1    = $(this).attr("Kd_Aset1");
        var kd_aset2    = $(this).attr("Kd_Aset2");
        var kd_aset3    = $(this).attr("Kd_Aset3");
        var kd_aset4    = $(this).attr("Kd_Aset4");
        var kd_aset5    = $(this).attr("Kd_Aset5");
        var no_register = $(this).attr("No_Register");
        
        var kode = {
              Kd_Riwayat:Kd_Riwayat,
              Kd_Id:Kd_Id,
              Kd_Prov:Kd_Prov,
              Kd_Kab_Kota:Kd_Kab_Kota,
              kd_bidang:kd_bidang,
              kd_unit:kd_unit,
              kd_sub:kd_sub,
              kd_upb:kd_upb,
              kd_aset1:kd_aset1,
              kd_aset2:kd_aset2,
              kd_aset3:kd_aset3,
              kd_aset4:kd_aset4,
              kd_aset5:kd_aset5,
              no_register:no_register};

      
      if(confirm("Yakin data ini mau di Hapus ??"))
      {
          $( "#loading" ).empty().append("<img src='<?php echo base_url();?>media/images/ui-anim_basic_16x16.gif' /> tunggu sebentar...");
          $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>kibd/hapus_riwayat",
              data:kode,
              cache: false,
              success: function(html){
                $("#listhapus_"+no).fadeTo(300,0, function() {
                            $(this).animate({width:0},200,function(){
                                $(this).remove();
                              });
                          });
                $("#loading" ).empty().html();
                window.location.reload(true);
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
                    <div class="panel-header"><i class="icon-group"></i><?php echo ! empty($header) ?  $header: 'KIB'; ?>
                    <span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
             	<div class="panel-content list-content">


                      <!-- tab w/ pagination and search controls -->
                        
                        
                       <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" action="<?php echo $form_action; ?>">
                                     <?php echo form_dropdown("q",$option_q,$this->session->userdata('q'),'class="input-large" id="search" '); ?>
                                     
                                     <span id="f_cari"></span>
                                     
                                     <span><strong> Tgl Pembelian </strong></span>
                                     <input type=text name='tanggal1' id='datepicker' class='input-small' readonly='readonly'> 
                                     <span><strong> s/d</strong></span>
                                     <input type=text name='tanggal2' id='datepicker2' class='input-small' readonly='readonly'> 
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                        <a class="btn" href="<?php echo base_url(); ?>kibd/export"><i class="icon-ok-circle"></i> Excell</a>
                                    </form>
                                </div>

                                <div class="list-pagination">
                                   <?php echo ! empty($pagination) ? $pagination: '<button class="btn"><i class="icon-filter"></i></button>';?>
                                </div>                              

                            </div>
                            
                            
                            <div class="tab-pane list-pane" id="tab2">

                                <div class="pull-right">
                                    <form class="form-inline" />
                                        <input type="text" placeholder="Search Subscriptions" name="search[subscriptions]" />
                                        <button class="btn"><i class="icon-search icon-large"></i></button>
                                    </form>
                                </div>

                                <div class="list-pagination">
                                    <span>Viewing records <strong>1-25</strong> of <strong>320</strong></span>
                                    <button class="btn disabled"><i class="icon-step-backward"></i></button>
                                    <button class="btn disabled"><i class="icon-caret-left"></i></button>
                                    <span>Page <strong>1</strong> of <strong>12</strong></span>
                                    <button class="btn"><i class="icon-caret-right"></i></button>
                                    <button class="btn"><i class="icon-step-forward"></i></button>
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
                                        <li><a href="#" class="hapus_semua">Hapus dipilih</a></li>
                                    </ul>
                                </div>

                                <!-- <button class="btn"><i class="icon-edit"></i> Edit Selected</button> -->
                                <div class="btn-group pull-right">
                                    <form method="POST" action="<?php echo $form_filter; ?>">
                                     <?php echo form_dropdown("riwayat",$riwayat,$this->session->userdata('riwayat'),'class="btn" style="text-align:right"'); ?>
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                    </form>
                                </div>

                                <div class="btn-group">
                                    <a href="<?php echo ! empty($kib) ?  $kib: '#'; ?>">
                                    <button class="btn btn-info"><i class="icon-book"></i> Aset Induk</button>
                                    </a>
                                </div>

                                <div class="btn-group">
                                    <a href="<?php echo ! empty($kib) ?  $kib: '#'; ?>">
                                    <button class="btn btn-info"><i class="icon-book"></i> Aset Induk</button>
                                    </a>
                                </div>
                                
                            </div>
                  </div>

                  <table class="table table-striped">
                    <thead>
                      <tr>
                          <th><input type="checkbox" id="cek-all"/></th>
                          <th>No</th>
                          <th>Riwayat</th>
                          <th>Nama Barang</th>
                          <th>Tgl Dokumen</th>
                          <th>No Dokumen</th>
                          <th>Kondisi</th>
                          <th style='text-align:right;'>Harga</th>
                          <th>Keterangan</th>
                          <th><i class='icon-wrench'></i> Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
          					$i= $offset + 1;
          					foreach ($query->result() as $row){
          					if ($row->Kondisi == 1){
          					$kondisi = 'Baik';
          					}elseif ($row->Kondisi == 2){
          						$kondisi = 'Kurang Baik';
          					}else{
          						$kondisi = 'Rusak Berat';
          					}
          					$thn 		= date('Y', strtotime($row->Tgl_Perolehan));
                      echo "<tr id='listhapus_$i'>
                          <td><input class='checkbox1' type='checkbox' name='check[]' value='item1' Kd_Prov = '$row->Kd_Prov' Kd_Kab_Kota = '$row->Kd_Kab_Kota' Kd_Bidang = '$row->Kd_Bidang' Kd_Unit = '$row->Kd_Unit' Kd_Sub = '$row->Kd_Sub' Kd_UPB = '$row->Kd_UPB' Kd_Aset1 = '$row->Kd_Aset1' Kd_Aset2 = '$row->Kd_Aset2' Kd_Aset3 = '$row->Kd_Aset3' Kd_Aset4 = '$row->Kd_Aset4' Kd_Aset5 = '$row->Kd_Aset5' No_Register = '$row->No_Register' no='$i'></td>
                          <td>$i</td>
                          <td>$row->Nm_Riwayat</td>
            						  <td>$row->Nm_Aset5</td>
            						  <td>".tgl_indo($row->Tgl_Dokumen)."</td>
            						  <td>".$row->No_Dokumen."</td>
            						  <td>$kondisi</td>
            						  <td style='text-align:right;'>".rp($row->Harga)."</td>
            						  <td>$row->Keterangan</td>
                          <td>
                                            <div class='btn-group'>
                                                <button class='btn'>Options</button>
                                                <button class='btn dropdown-toggle' data-toggle='dropdown'>
                                                    <span class='caret'></span>
                                                </button>
                                                   <ul class='dropdown-menu'>";

                                                   echo "<li>".anchor('kibd/lihat/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,"<i class='icon-eye-open'></i>Lihat",array('class' => 'update'))."</li>";
                                    							 echo "<li>".anchor('kibd/update/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Aset1.'/'.$row->Kd_Aset2.'/'.$row->Kd_Aset3.'/'.$row->Kd_Aset4.'/'.$row->Kd_Aset5.'/'.$row->No_Register,"<i class='icon-pencil'></i>Ubah",array('class' => 'update'))."</li>";
                                                   echo "<li>".anchor(current_url().'#',"<i class='icon-trash'></i>Hapus",array('class'=> 'hapus_riwayat','Kd_Prov' => $row->Kd_Prov,'Kd_Kab_Kota' => $row->Kd_Kab_Kota,'Kd_Bidang' => $row->Kd_Bidang,'Kd_Unit' => $row->Kd_Unit,'Kd_Sub' => $row->Kd_Sub,'Kd_UPB' => $row->Kd_UPB,'Kd_Aset1' => $row->Kd_Aset1,'Kd_Aset2' => $row->Kd_Aset2,'Kd_Aset3' => $row->Kd_Aset3,'Kd_Aset4' => $row->Kd_Aset4,'Kd_Aset5' => $row->Kd_Aset5,'No_Register' => $row->No_Register,'no'=>$i))."</li>";

                                              echo "</ul>
                                                      </div>
                          </td>
                      </tr>";
				 $i++;
				} ?>
                      
                    </tbody>
                  </table>                      

                </div>
                </div>

            </div>
        </div>
