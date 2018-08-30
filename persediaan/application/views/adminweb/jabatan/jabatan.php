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
            <th width="5%">No</th>
            <th width="15%">JABATAN</th>
            <th width="60%">KETERANGAN</th>
            <th width="30%">#</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i= 1; 
    foreach ($query->result() as $row){
    echo "<tr class='gradeA' onclick='test()'>
            <td align='center'>".$i."</td>
            <td align='center' id=".$row->id.">".$row->jabatan."</td>
            <td align='center'>".$row->keterangan."</td>
            <td align='left'>";

        echo '<a href="'.base_url().'jabatan/edit/'.$row->id.'" title="">
                            <i class="glyph-icon icon-edit mrg5R"></i>
                            Edit
                        </a>';
        
        /*administrator*/
        if($row->id !=1){
        
            echo '<a href="'.base_url().'delete/'.$row->id.'" class="font-red class="a-danger" title="">
                            <i class="glyph-icon icon-remove mrg5R"></i>
                            Delete
                        </a>';
        }
            echo '<a href="'.base_url().'privilege/proses/'.$row->id.'" class="font-green class="a-danger" title="">
                            <i class="glyph-icon icon-wrench mrg5R"></i>
                            Privilege
                        </a>';


    echo "</td>
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