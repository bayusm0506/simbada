 <script type="text/javascript">
    var stok;
    stok = $('.test').DataTable({
        paging: true,
        "info": false,
        "bProcessing": true,
        "searching": true,

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
              "targets": [ 1,2 ], //last column
              "orderable": true, //set not orderable
            },
        ],

      });

    // if ( $.fn.dataTable.isDataTable( '#data_stok' ) ) {
    //    table = $(".test").DataTable( {
    //      destroy: true,
    //      paging: true,
    //      "info": false,
    // });
    //     // alert(1)
    // }
    // else {
    //     table = $('.test').DataTable( {
    //         destroy: true,
    //         paging: true,
    //         "info": false,
    //         "bProcessing": true,
    //         "searching": true,

    //         //Set column definition initialisation properties.
    //         "columnDefs": [
    //             { 
    //               "targets": [ 1,2 ], //last column
    //               "orderable": true, //set not orderable
    //             },
    //         ]
    //     } );
    //     // alert(2)
    // }

</script>
<form method='POST' action="<?php echo $form_action; ?>">
     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered test" id="data_stok">
         <thead>
             <tr>
                <th>No</th>
                <th>Tgl Diterima</th>
                <th>Nama/Jenis Barang</th>
                <th>Stok</th>
                <th>#</th>
            </tr>
         <thead>
         <tbody>
    <?php
      $i= 1; 
      $temp_data = array();
      foreach ($data_stok->result() as $row){

      $ka1         = sprintf ("%02u", $row->Kd_Aset1);
      $ka2         = sprintf ("%02u", $row->Kd_Aset2);
      $ka3         = sprintf ("%02u", $row->Kd_Aset3);
      $ka4         = sprintf ("%02u", $row->Kd_Aset4);
      $ka5         = sprintf ("%02u", $row->Kd_Aset5);
      $ka6         = sprintf ("%02u", $row->Kd_Aset6);
      $kode_barang = $ka1.'.'.$ka2.'.'.$ka3.'.'.$ka4.'.'.$ka5.'.'.$ka6;

      echo "<tr title='No Kontrak : ".$row->No_Kontrak."''>
                <td align='center'>".$i."</td>
                <td align='center'>".tgl_dmy($row->Tgl_Diterima)."</td>
                <td align='center'><small>".$kode_barang.'</small><br>'.$row->Nm_Aset."</td>
                <td align='center'>".$row->Stok." ".$row->Nm_Satuan."</td>";
                // if($kode_barang == $temp_data ){
                //    echo "<td align='center'><i class='fa fa-clock-o'></i> waiting</td>";
                // }else{
                //    $temp_data = $row->Nm_Aset;
                //    echo "<td align='center'><a class='pilih pointer'  No_ID='$row->No_ID' Kd_Aset1='$row->Kd_Aset1' Kd_Aset2='$row->Kd_Aset2' Kd_Aset3='$row->Kd_Aset3' Kd_Aset4='$row->Kd_Aset4' Kd_Aset5='$row->Kd_Aset5' Kd_Aset6='$row->Kd_Aset6' No_Register='$row->No_Register' Nm_Aset='$row->Nm_Aset' Stok='$row->Stok' Nm_Satuan='$row->Nm_Satuan' Harga='".nilai($row->Harga)."' ><i class='fa fa-download'></i> Pilih</a></td>";                    
                // }

                if(!in_array($kode_barang, $temp_data)) {
                  array_push($temp_data, $kode_barang);
                  echo "<td align='center'><a class='pilih pointer'  No_ID='$row->No_ID' Kd_Aset1='$row->Kd_Aset1' Kd_Aset2='$row->Kd_Aset2' Kd_Aset3='$row->Kd_Aset3' Kd_Aset4='$row->Kd_Aset4' Kd_Aset5='$row->Kd_Aset5' Kd_Aset6='$row->Kd_Aset6' No_Register='$row->No_Register' Nm_Aset='$row->Nm_Aset' Stok='$row->Stok' Nm_Satuan='$row->Nm_Satuan' Harga='".nilai($row->Harga)."' ><i class='fa fa-download'></i> Pilih</a></td>"; 
                }else{
                  echo "<td align='center'><i class='fa fa-clock-o'></i> queue</td>";
                }
                
            echo "</tr>";
       $i++;
      }              
    ?>  
    </tbody>
    </table>
</form>
