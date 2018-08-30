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

      <?php if($this->general->privilege_check('DATA_UMUM',VIEW)){?>	
        <li><a href="<?php echo base_url()?>dataumum"><i class="fa fa-sitemap"></i> Data Umum UPB</a></li>
      <?php }?>

      </ul>
    </li>
    <?php }?>
    
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-hdd-o"></i> Persediaan <b class="caret"></b></a>
      <ul class="dropdown-menu">

          <li><a href="<?php echo base_url()?>penerimaan"><i class="fa fa-download"></i> Barang Masuk</a></li>
          <li><a href="<?php echo base_url()?>pengeluaran"><i class="fa fa-upload"></i> Barang Keluar</a></li>
      	
      </ul>
    </li>
    
   
   <li class="dropdown">
      <a href="<?php echo base_url() ?>laporan/persediaan"><i class="fa fa-clipboard"></i> Laporan</b></a>
   </li>
   
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