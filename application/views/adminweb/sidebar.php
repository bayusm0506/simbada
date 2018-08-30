<?php 
if($nama_modul == 'user'){?>
	<a href="<?=site_url('adminweb/user/addform')?>" class="add-button"><span>Add New User</span></a>
						<div class="cl"></div>

<?php }elseif($nama_modul == 'menuutama'){?>

	<a href="<?=site_url('adminweb/menuutama/addform')?>" class="add-button"><span>Add New Menu</span></a>
						<div class="cl"></div>
						<br>
						<p>Hati hati untuk tidak sembarangan menghapus menu!</p>
						
<?php }elseif($nama_modul == 'submenu'){?>

	<a href="<?=site_url('adminweb/submenu/addform')?>" class="add-button"><span>Add New Sub-Menu</span></a>
						<div class="cl"></div>
						<br>
						<p>Hati hati untuk tidak sembarangan menghapus menu!</p>
						
<?php }