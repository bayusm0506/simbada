<script type="text/javascript">
$(function() {
         $( "input[class^=MyDate]" ).datepicker({
                        changeMonth: true,
                        showAnim: 'slideDown',
                        dateFormat: "yy-mm-dd",
                        yearRange: '-116:+2',
                        changeYear: true
                });
});

</script>

         <form method='POST' action="<?php echo $form_action; ?>" enctype="multipart/form-data">
                 <table width='600px'>
                        <tr>
                                <td>No Usulan Penghapusan</td>
                                <td> : <input type=text name='No_UP' id='No_UP' value="<?php echo $this->session->userdata("No_UP") ?>" size=25 ></td>
                        </tr>

                        <tr>
                                <td width='200px'>Tgl Usulan Penghapusan</td>
                                <td> : <input type=text name='Tgl_UP' value='<?php 
                                $tgl_up = $this->session->userdata("Tgl_UP");
                                echo !empty($tgl_up) ? $tgl_up : date("Y-m-d");?>' size=10  id='Tgl_UP'  class="MyDate required input-small"></td>
                        </tr>

                        <tr>
                                <td>Alasan Penghapusan</td>
                                <td> : <?php echo form_dropdown('Kd_Alasan', $option_alasan, '', 'id="Kd_Alasan"style="width: 340px; font-size: 13px"'); ?></td>
                        </tr>
                        <tr>
                                <td class='ket'>Keterangan</td>
                                <td></td>
                        </tr>
                        <tr>
                                <td colspan='2'>
                                <textarea name='Ket_Alasan' id='Ket_Alasan' style='width: 600px; height: 80px;'></textarea>
                                </td>
                        </tr>
                        <tr>
                                <td>Foto</td>
                                <td> : <input type="file" name="userfile"/></td>
                        </tr>
                 </table>
         </form>