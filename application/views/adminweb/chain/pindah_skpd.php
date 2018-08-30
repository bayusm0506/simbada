<script type="text/javascript">

         $( "input[class^=MyDate]" ).datepicker({
                        changeMonth: true,
                        showAnim: 'slideDown',
                        dateFormat: "yy-mm-dd",
                        yearRange: '-116:+2',
                        changeYear: true
                });

</script>

<div id="form_input" title="PILIH SKPD TUJUAN">
        <form method='POST' action="<?php echo $form_action; ?>" enctype="multipart/form-data">
                 <table width='100%'>
                        
                        <tr>
                                <td>No Dokumen</td>
                                <td>
                                        <input type=text name='No_Dokumen' id='No_Dokumen' value="" size=25 >

                                </td>
                        </tr>
                        <tr>
                                <td>Tanggal Dokumen</td>
                                <td>
                                        <input type=text name='Tgl_Dokumen' value='<?php 
                                        $thn = $this->session->userdata('tahun_anggaran');
                                        echo date("$thn-m-d"); ?>' size=10  id='Tgl_Dokumen'  class="MyDate required input-small">

                                </td>
                        </tr>
                        <tr>
                                <td>Pilih SKPD Tujuan</td>
                                <td><?php echo $this->load->view('adminweb/chain/bidang');?></td>
                        </tr>
                </table>
         </form> 
</div> 