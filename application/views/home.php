   
      <script type="text/javascript">
      $(window).load(function(){
          $( "#result" ).load( "<?php echo base_url();?>adminweb/total_kib" );
      });
      </script>
        
     <div id='result' align="center">
        sedang menghitung total aset...  <img src='<?php echo base_url() ?>asset/img/ajax_loading.gif' />
     </div> 

        <div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header">HOME</div>
                    <div class="panel-content">
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1" data-toggle="tab">Tentang Simda BMD</a></li>
                        </ul>

                        <div class="tab-content">
                            <div align="justify" style="font-size:18px">
                                          
                                    <p>Hello <b><?php echo $this->session->userdata('nama_lengkap')?></b></p>
                                      <p>Selamat datang di halaman login aplikasi SIMDA Online,</p>
                                      <p>Anda Login Sebagai  
                                    <?php echo '<b>'.$nama_upb.'</b> | Tahun Anggaran <b>'.$this->session->userdata('tahun_anggaran').'</b>';
                                          ?>.</p>
                                      <table width="100%">
                                        <tr>
                                          <td width="124">Provinsi</td>
                                          <td width="509">: <strong>(2)</strong>Sumatera Utara</td>
                                        </tr>
                                        <tr>
                                          <td>Kabupaten</td>
                                          <td>: <strong><?php // echo getInfoPemda("Kd_Kab_Kota");?></strong><?php // echo getKabKota("Nm_Kab_Kota");?>
                                            <strong>(5)</strong>PEMERINTAH KABUPATEN SIMALUNGUN
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>Bidang</td>
                                          <td>: <?php echo $nama_bidang;?></td>
                                        </tr>
                                         <tr>
                                          <td>Unit</td>
                                          <td>: <?php echo $nama_unit;?></td>
                                        </tr>
                                        <tr>
                                           <td>Sub Unit</td>
                                           <td>: <?php echo $nama_sub_unit;?></td>
                                        </tr>
                                        <tr>
                                          <td>UPB</td>
                                          <td>: <?php echo $nama_upb;?></td>
                                        </tr>
                                      </table>
                                      <p>&nbsp;</p>
                                    <p>
                                        Silahkan pilih menu yang tersedia untuk melakukan invetarisasi barang. jika mengalami kendala mengenai aplikasi ini harap segera hubungi administrator aplikasi ini di Biro Perlengkapan dan Aset Pemerintah Kabupaten Simalungun
                                    </p>

                                </div>

                        </div>                        

                    </div>
                </div>

            </div>
        </div>