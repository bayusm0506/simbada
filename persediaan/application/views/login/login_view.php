<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>LOGIN SIMBADA ONLINE</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sistem Informasi Manajemen Barang Milik Daerah (SIMDA BMD) Berbasis Akrual - Pemerintah Provinsi Sumatera Utara" />
    <meta name="keywords" content="simda bmd, simda aset, simda akrual, simda pemprovsu, simda provinsi sumatera utara,simda berbasis akrual"/>
    <meta name="author" content="CV. BAYU SM" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('asset/img/favicon-simalungun.png')?>">

    <link href="<?php echo base_url(); ?>asset/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>asset/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>asset/css/jasny-bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>asset/css/jasny-bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>asset/css/font-awesome.css" rel="stylesheet" />

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- <link href='http://fonts.googleapis.com/css?family=Pontano+Sans' rel='stylesheet' type='text/css'> -->
    <link href="<?php echo base_url(); ?>asset/css/admin.css" rel="stylesheet" />

    <script type="text/javascript" src="<?php echo base_url();?>media/js/jquery-1.6.1.min.js"></script>
    <script>
    $(document).ready(function() {
            $("#tahun").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                     // Allow: Ctrl+A, Command+A
                    (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
                     // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        });
	</script> 

    <!-- Le fav and touch icons -->
    <!--<link rel="shortcut icon" href="<?php // echo base_url(); ?>asset/img/ico/favicon.ico" />-->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url(); ?>asset/img/ico/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url(); ?>asset/img/ico/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url(); ?>asset/img/ico/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url(); ?>asset/img/ico/apple-touch-icon-57-precomposed.png" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
  .gambar {
	position: absolute;
	height: 237px;
	width: 237px;
	left: 441px;
	top: 55px;
}
  </style>
  </head>

<body>

<div id="top-strip">
    <div class="container">
        <div class="row">
            <div class="offset8 span4">
                <div class="pull-right">
                    <a href="#">Register</a> |
                    <a href="#">Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logo-strip">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="logo">
                    <img src="<?php echo base_url(); ?>asset/img/persediaan-full.png" height="40" />
                </div>
            </div>
        </div>
    </div>
</div>

<div id="nav-strip">

    <div class="container">
        <div class="row">
            <div class="span12"></div>
        </div>
    </div>
    
</div>

<div class="container-signin">
  <div class="panel">
        <div class="panel-header"><i class="icon-lock icon-large"></i> LOGIN APLIKASI</div>
        <div class="panel-content">
            <div class="row">
                                <div class="span5">
                                    
                                     <?php
                                        $attributes = array('name' => 'login_form', 'id' => 'login_form');
                                        echo form_open('login/proses_login', $attributes);
                                        ?>
                                        <?php 
                                        $message = $this->session->flashdata('message');
                                        echo $message == '' ? '' : '<div class="alert alert-error">
                                    <button class="close" data-dismiss="alert">×</button>
                                    <strong>Error!</strong> ' . $message . '</div>';
                                        ?>

                                     <div class="control-group">
                                                        <div class="controls">
                                                            <div class="input-prepend">
                                                                <span class="add-on"><i class="icon-envelope icon-large"></i></span><input class="span3" placeholder="Username" id="prependedInput" size="16" type="text" name="username" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="input-prepend">
                                                                <span class="add-on"><i class="icon-key icon-large"></i></span><input class="span3" placeholder="Password" id="prependedInput" size="16" type="password" name="password" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="input-prepend">
                                                                <span class="add-on"><i class="icon-calendar icon-large"></i></span><input class="span1" placeholder="Tahun anggaran" id="tahun" type="text" value="<?php echo date("Y"); ?>" name="tahun"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <?php echo $this->recaptcha->render(); ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <button class="btn btn-large">Sign In</button>
                                                            <span class="signin-remember"><input type="checkbox" /> Remember Me</span>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <span class="text-small">Belum punya akun?</span><br><a href="#">silahkan daftarkan di BPKAD Pemkab. Simalungun</a>
                                                        </div>
                                                    </div>
                                    </form>

                                </div>

                                <div class="span5">
                                    <h3>Selamat Datang di Aplikasi Pengelolaan Barang Milik Daerah</h3>
                                    <img src="<?php echo base_url(); ?>asset/img/kantor-bupati-simalungun.png" align="right">
                                    <div class="divider"></div>
                                    <p>Bergabunglah dengan kami di group facebook</p>
                                    <a href="#" target="_blank"> 
                                        <button class ="btn btn-facebook" title="Facebook"><i class="icon-facebook icon-large"></i></button> Group Facebook Team Aset Pemkab. Simalungun
                                    </a>

                                </div>

            </div>


        </div>
    </div>   
</div>

<!-- Footer -->
<?php
	echo $this->load->view('footer');
?>
<!-- End Footer -->

<script src="<?php echo base_url(); ?>asset/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo base_url(); ?>asset/js/bootstrap.js"></script>

</body>
</html>