<script src="<?php echo base_url() ?>asset/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>asset/js/dataTables.bootstrap.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("a.update").click(function() {
		$("#result").empty().append("Tunggu Sebentar.. <img src='<?php echo base_url()?>asset/img/load.gif'/>");
		//var id = $('.gradeA').attr("id");
		//alert ('xx')
	});
});
	</script>
<div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header"><i class="icon-sign-blank"></i><?php echo ! empty($header) ?  $header: 'Pilih data SKPD'; ?></div>
                    <div class="panel-content">

<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="example">
    <thead>
        <tr>
            <th>No</th>
            <th width="10%">KD BIDANG</th>
            <th width="10%">KD UNIT</th>
            <th width="10%">KD SUB</th>
            <th>NAMA UPB</th>
        </tr>
    </thead>
    <tbody>
    <?php
 	$i= 1; 
	foreach ($query->result() as $row){
	echo "<tr class='gradeA' onclick='test()'>
            <td align='center'>".$i."</td>
            <td align='center' id=".$row->Kd_Bidang.">".$row->Kd_Bidang."</td>
            <td align='center'>".$row->Kd_Unit."</td>
            <td align='center'>".$row->Kd_Sub."</td>
			<td>".anchor($link_kib.'/'.$row->Kd_Bidang.'/'.$row->Kd_Unit.'/'.$row->Kd_Sub,$row->Nm_Sub_Unit,array('class' => 'update'))."</td>
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