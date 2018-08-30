<script src="<?php echo base_url() ?>asset/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>asset/js/dataTables.bootstrap.js"></script>

<div class="row">
            <div class="span12">
                <div class="panel">
                    <div class="panel-header"><i class="icon-sign-blank"></i> Data User
                    </div>
                    <div class="panel-content">

<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="example">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama UPB</th>
            <th>Username</th>
            <th>Nama Pengurus Barang</th>
            <th>Email / NO Telp</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
 	$i= 1; 
	foreach ($query->result() as $row){
	echo "<tr class='gradeA' onclick='test()' id='listhapus_$i'>
            <td align='center'>".$i."</td>
            <td align='center'>".$row->Nm_UPB."</td>
		 	<td align='center'><a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('$row->username')\">".$row->username."</a></td>
			<td align='center'><a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('$row->username')\">".$row->nama_lengkap."</a></td>
            <td align='center'>".$row->email.'<br><strong>0'.$row->no_telp."</strong></td>
			<td align='center'>Status</td>
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
    