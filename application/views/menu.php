<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
</a>


<span class="nav-collapse collapse navbar-responsive-collapse">
                     
<ul class="nav">
   <li <?php if ($this->uri->segment(1) == 'adminweb' ) {echo "class='active'";}?>>
   <a href="<?php echo base_url()?>"><i class="fa fa-home"></i> Dashboard</a></li>
    
    <?php if($this->general->privilege_check('PENGATURAN',VIEW)){?>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i> Pengaturan <b class="caret"></b></a>
      <ul class="dropdown-menu">

        <?php if($this->general->privilege_check('IDENTITAS',VIEW)){?>
        <li><a href="<?php echo base_url()?>identitas"><i class="fa fa-key"></i> Identitas</a></li>
        <?php }?>

        <?php if($this->general->privilege_check('JABATAN',VIEW)){?>
          <li><a href="<?php echo base_url()?>jabatan"><i class="fa fa-check-square-o"></i> Otoritas</a></li>
        <?php }?>

        <?php if($this->general->privilege_check('USER',VIEW)){?>
        <li><a href="<?php echo base_url()?>user"><i class="fa fa-male"></i> User</a></li>
        <?php }?>

      </ul>
    </li>
     <?php }?>

    <?php if($this->general->privilege_check('PARAMETER',VIEW)){?>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i> Parameter <b class="caret"></b></a>
      <ul class="dropdown-menu">
      <?php if($this->general->privilege_check('DATA_RUANG',VIEW)){?>
        <li><a href="<?php echo base_url()?>ruang"><i class="fa fa-map"></i> Data Ruang</a></li>
			<?php }?>
      <?php if($this->general->privilege_check('DATA_UMUM',VIEW)){?>	
        <li><a href="<?php echo base_url()?>dataumum"><i class="fa fa-sitemap"></i> Data Umum UPB</a></li>
      <?php }?>
      <?php if($this->general->privilege_check('KEBIJAKAN',VIEW)){?> 
        <li><a href="<?php echo base_url()?>kebijakan"><i class="fa fa-legal"></i> Kebijakan Akuntansi</a></li>
      <?php }?>
      </ul>
    </li>
    <?php }?>
    
    <?php if($this->general->privilege_check('DATA',VIEW)){?>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-hdd-o"></i> Data <b class="caret"></b></a>
      <ul class="dropdown-menu">
      
      <li class="dropdown-submenu">
        <a tabindex="-1" href="#"><i class="fa fa-clock-o"></i> Perencanaan</a>
        <ul class="dropdown-menu">
        <?php if($this->general->privilege_check('KIB_A',VIEW)){?>
          <li><a href="<?php echo base_url()?>rkbu"><i class="fa fa-map-o"></i> Rencana Kebutuhan Barang</a></li>
        <?php }?>
        <?php if($this->general->privilege_check('KIB_B',VIEW)){?>  
          <li><a href="<?php echo base_url()?>rkpbu"><i class="fa fa-truck"></i> Rencana Kebutuhan Pemeliharaan Barang</a></li>
        <?php }?>
        </ul>
      </li>

      <?php if($this->general->privilege_check('PENGADAAN',VIEW)){?>
      <li class="dropdown-submenu">
        <a tabindex="-1" href="#"><i class="fa fa-money"></i> Pengadaan</a>
        <ul class="dropdown-menu">
        <?php if($this->general->privilege_check('KIB_B',VIEW)){?>
          <li><a href="<?php echo base_url()?>bj"><i class="fa fa-user" aria-hidden="true"></i> Pengadaan Belanja Jasa</a></li>
        <?php }?>
        <?php if($this->general->privilege_check('KIB_B',VIEW)){?>
          <li><a href="<?php echo base_url()?>pengadaan"><i class="fa fa-briefcase" aria-hidden="true"></i> Pengadaan Belanja Modal</a></li>
        <?php }?>
        </ul>
      </li>
      <?php }?>
      	<li class="dropdown-submenu">
          <a tabindex="-1" href="#"><i class="fa fa-puzzle-piece"></i> Penatausahaan</a>
          <ul class="dropdown-menu">
          <?php if($this->general->privilege_check('KIB_A',VIEW)){?>
            <li><a href="<?php echo base_url()?>kiba"><i class="fa fa-map-o"></i> KIB A - Tanah</a></li>
          <?php }?>
          <?php if($this->general->privilege_check('KIB_B',VIEW)){?>  
            <li><a href="<?php echo base_url()?>kibb"><i class="fa fa-truck"></i> KIB B - Peralatan dan Mesin</a></li>
          <?php }?>
          <?php if($this->general->privilege_check('KIB_C',VIEW)){?>
            <li><a href="<?php echo base_url()?>kibc"><i class="fa fa-university"></i> KIB C - Gedung dan Bangunan</a></li>
          <?php }?>
          <?php if($this->general->privilege_check('KIB_D',VIEW)){?>
            <li><a href="<?php echo base_url()?>kibd"><i class="fa fa-road"></i> KIB D - Jalan, Irigasi dan jaringan</a></li>
          <?php }?>
          <?php if($this->general->privilege_check('KIB_E',VIEW)){?>
            <li><a href="<?php echo base_url()?>kibe"><i class="fa fa-book"></i> KIB E - Aset tetap lainya</a></li>
          <?php }?>
          <?php if($this->general->privilege_check('KIB_F',VIEW)){?>
            <li><a href="<?php echo base_url()?>kibf"><i class="fa fa-wheelchair"></i> KIB F - Konstruksi dalam pengerjaan</a></li>
          <?php }?>
          <?php if($this->general->privilege_check('KIB_L',VIEW)){?>
            <li><a href="<?php echo base_url()?>lainnya"><i class="fa fa-pie-chart"></i> Aset Lainnya</a></li>
          <?php }?>
          </ul>
      </li>
      <?php }?>
      
      <?php if($this->general->privilege_check('PENGHAPUSAN',VIEW)){?>
      <li class="dropdown-submenu">
        <a tabindex="-1" href="#"><i class="fa fa-trash"></i> Penghapusan</a>
        <ul class="dropdown-menu">
        <?php if($this->general->privilege_check('USUL_PENGHAPUSAN',VIEW)){?>
          <li><a href="<?php echo base_url()?>penghapusan"><i class="fa fa-list"></i> Daftar Usulan Penghapusan SKPD</a></li>
        <?php }?>
        <?php if($this->general->privilege_check('SK_PENGHAPUSAN',VIEW)){?>
          <li><a href="<?php echo base_url()?>penghapusan/sk"><i class="fa fa-list"></i> Daftar Penghapusan</a></li>
        <?php }?>
        </ul>
      </li>
      <?php }?>

      </ul>
    </li>
    
   
   <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-clipboard"></i> Laporan <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo base_url()?>laporan/perencanaan"><i class="fa fa-list-alt"></i> Perencanaan dan Pengadaan</a></li>
        <li class="dropdown-submenu">
        <a tabindex="-1" href="#"><i class="fa fa-list-alt"></i> Penatausahaan</a>
        <ul class="dropdown-menu">
          <li><a href="<?php echo base_url()?>laporan/kib"><i class="fa fa-list-alt"></i> Kartu Inventaris Barang</a></li>
          <li><a href="<?php echo base_url()?>laporan/kir"><i class="fa fa-list-alt"></i> Kartu Inventaris Ruangan</a></li>
          <li><a href="<?php echo base_url()?>laporan/rekap"> <i class="fa fa-list-alt"></i> Rekap</a></li>
         <!--  <li><a href="<?php echo base_url()?>laporan/bukuinduk">Buku Induk</a></li> -->
          <?php if ($this->session->userdata('lvl') == 01){
           // echo '<li><a href='.base_url().'laporan/gabungan>Gabungan Pemda</a></li>';
         } ?>
        </ul>
      </li>

    <?php
           echo '<li><a href="'.base_url().'laporan/inventarisasi"><i class="fa fa-list-alt"></i> Inventarisasi</a></li>';
    ?>
    <?php
           echo '<li><a href="'.base_url().'laporan/penghapusan"><i class="fa fa-list-alt"></i> Penghapusan</a></li>';
    ?>

     <?php 
           echo '<li><a href="'.base_url().'laporan/akuntansi"><i class="fa fa-list-alt"></i> Akuntasi</a></li>';
    ?>

      </ul>
    </li>
   <li><a href="<?php echo base_url()?>persediaan" target="_blank"><i class="fa fa-external-link"></i> Persediaan</a></li>
   <?php if($this->general->privilege_check('CHATTING',VIEW)){?>
    <li><a href="<?php echo base_url()?>chatting"><i class="fa fa-comments-o"></i> Chatting</a></li>
   <?php }?>
</ul>

<ul class="nav pull-right">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $this->session->userdata('nama_upb');?><b class="caret"></b></a>
    <ul class="dropdown-menu">
			<li>
        <a href="<?php echo base_url()?>user/update"><i class="fa fa-user"></i> Edit Profile</a>
				<a href="<?php echo base_url()?>login/logout"> <i class="fa fa-power-off"></i> Logout </a>
			</li>
    </ul>
  </li>
</ul>
               
                      
                      
</span>