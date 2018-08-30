 <script type="text/javascript">
    // var stok;
    // stok = $('.test').DataTable({
    //     paging: false,
    //     "info": false,
    //     "bProcessing": true,
    //     "searching": true,

    //     //Set column definition initialisation properties.
    //     "columnDefs": [
    //         { 
    //           "targets": [ 1,2 ], //last column
    //           "orderable": true, //set not orderable
    //         },
    //     ],

    //   });
    if ( $.fn.dataTable.isDataTable( '#data_barang' ) ) {
        table = $('#data_barang').DataTable();
    }
    else {
        table = $('.test').DataTable( {
            paging: true,
            filter:true
        } );
    }

</script>
     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered test" id="data_barang">
         <thead>
             <tr>
                <th>No</th>
                <th>Kode Aset1</th>
                <th>Kode Aset2</th>
                <th>Kode Aset3</th>
                <th>Kode Aset4</th>
                <th>Kode Aset5</th>
                <th>Kode Aset6</th>
                <th>Nama/Jenis Barang</th>
                <th>#</th>
            </tr>
         <thead>
         <tbody>
    <?php
      $i= 1; 
      $temp_data = array();
      foreach ($data_barang->result() as $row){
      echo "<tr onclick='test()'>
                <td align='center'>".$i."</td>
                <td align='center'>".$row->Kd_Aset1."</td>
                <td align='center'>".$row->Kd_Aset2."</td>
                <td align='center'>".$row->Kd_Aset3."</td>
                <td align='center'>".$row->Kd_Aset4."</td>
                <td align='center'>".$row->Kd_Aset5."</td>
                <td align='center'>".$row->Kd_Aset6."</td>
                <td align='left'>".$row->Nm_Aset6." ".$row->Spesifikasi."</td>
                <td align='center'><a class='pilih pointer' Kd_Aset1='$row->Kd_Aset1' Kd_Aset2='$row->Kd_Aset2' Kd_Aset3='$row->Kd_Aset3' Kd_Aset4='$row->Kd_Aset4' Kd_Aset5='$row->Kd_Aset5' Kd_Aset6='$row->Kd_Aset6' Nm_Aset6='$row->Nm_Aset6'><i class='fa fa-download'></i> Pilih</a></td>
                </tr>";
       $i++;
      }              
    ?>  
    </tbody>
    </table>
