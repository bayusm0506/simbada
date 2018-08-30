<script src="<?php echo base_url() ?>asset/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>asset/js/dataTables.bootstrap.js"></script>
<script>
function confirmDialog() {
    return confirm("Anda yakin akan menghapus data ini?")
}
</script>
        
<div class="row">
            <div class="span12">
                <div class="panel">
                    <div class="panel-header">
                    	<i class="icon-sign-blank"></i> Data Ruang
                    	<span class="pull-right"><?php echo $judul; ?> </span>
                    </div>
                    <div class="panel-content">

                    <!-- tab content -->
                       <div class="tab-content">
                            <div class="tab-pane list-pane active" id="tab1">

                                <div class="pull-right">
                                    <form class="form-inline" method="POST" id="form_view" action=<?php echo base_URL(); ?>ruang/set/>
                                                                          
                                     <span><strong> Tahun </strong></span>
                                     <?php echo form_dropdown("tahun",$option_q,$this->session->userdata('tahun'),'class="input-small" id="search"'); ?>
    
                                     <button class="btn"><i class="icon-search icon-large"></i></button>
                                    </form>
                                </div>

                                <div class="list-pagination">

                                   <?php 	
                                   			if($query->num_rows() > 0) {
	                                   		 	echo '<a href="'.base_url().'ruang/add">
	                                   		 			<button class="btn btn-success"><i class="icon-plus-sign"></i> Tambah Data</button>
	                                   		 		  </a>';
	                                   		}else{
	                                   		 	echo '<a href="'.base_url().'ruang/import">
	                                   		 			<button class="btn btn-success"><i class="icon-download icon-large"></i> Import parameter ruang</button>
	                                   		 		  </a>';
	                                   		}
                                   ?>
                                </div>                              

                            </div>
                             
                                                  
                        </div>
                        <!-- end tab content --> 

						<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="example">
						    <thead>
						        <tr>
						            <th>No</th>
						            <th>Kode Ruang</th>
						            <th>Nama Ruang</th>
						            <th>#</th>
						        </tr>
						    </thead>
						    <tbody>
						    <?php
						 		$i=1;
								foreach ($query->result() as $row){
								echo "<tr class='gradeA' onclick='test()' id='listhapus_$i'>
							            <td align='center'>".$i."</td>
										<td align='center'>".$row->Kd_Ruang."</td>
							            <td align='center'><a href=".base_url()."ruang/kir/".$row->Kd_Bidang."/".$row->Kd_Unit."/".$row->Kd_Sub."/".$row->Kd_UPB."/".$row->Kd_Ruang."> $row->Nm_Ruang</a></td>
										<td>
	                                        <div class='btn-group'>
	                                            <button class='btn'>Options</button>
	                                            <button class='btn dropdown-toggle' data-toggle='dropdown'>
	                                            <span class='caret'></span>
	                                            </button>
	                                            <ul class='dropdown-menu'>													
													<li>".anchor('ruang/kir/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Ruang,"<i class='icon-eye-open'></i> Lihat",array('class' => 'view'))."</li>
													<li class='divider'></li>
													<li>".anchor('ruang/update/'.$row->Tahun.'/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Ruang,"<i class='icon-edit'></i> Ubah",array('class' => 'update'))."</li>
													<li>".anchor('ruang/hapus/'.$row->Tahun.'/'.$row->Kd_Prov.'/'.$row->Kd_Kab_Kota.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub.'/'.$row->Kd_UPB.'/'.$row->Kd_Ruang,"<i class='icon-trash'></i> Hapus",array('class'=>'delete', 'onclick'=>"return confirmDialog();"))."</li>
													</ul>
	                                        </div>
	                                    </td>
							        </tr>";
								 $i++;
								}
						?>	
						    </tbody>
						</table>

                    </div>
                </div>

            </div>
        </div>