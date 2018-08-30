<?php 
	if(!isset($page))$page='';

	if($page=='edit'){
	foreach ($query as $row):
	echo form_open('adminweb/menuutama/editprocess');?>
						<!-- Form -->
						<div class="form">
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<input type="hidden" name="id_main" value="<?php echo $row->id_main;?>" />

								<tr><td width ="170px">	<label>Nama Menu<span>(Required Field)</span></label></td>
									<td><input type="text" class="field size5"  name="nama_menu" value="<?php echo $row->nama_menu;?>" placeholder="nama menu" required autofocus /></td>
								</tr>
								<tr><td>	<label>Link</label></td>
									<td><input type="text" class="field size4" name="link" value="<?php echo $row->link;?>" placeholder="link" /></td>
								</tr>
								 <?php if ($row->aktif =='Y'){?>
								<tr><td>	<label>Aktif</label></td>
									<td><input type=radio name='aktif' value='Y' checked> Y  
										&nbsp;<input type=radio name='aktif' value='N'> N</td>
								</tr>
								<?php }else{?>
									<tr><td>	<label>Aktif</label></td>
									<td><input type=radio name='aktif' value='Y'> Y  
										&nbsp;<input type=radio name='aktif' value='N' checked> N</td>
								</tr>
								<?php } 
								if ($row->adminmenu =='Y'){?>
								<tr><td>	<label>Admin Menu</label></td>
									<td><input type=radio name='adminmenu' value='Y' checked> Y  
										&nbsp;<input type=radio name='adminmenu' value='N'> N</td>
								</tr>
								<?php }else{?>
									<tr><td>	<label>Admin Menu</label></td>
									<td><input type=radio name='adminmenu' value='Y'> Y  
										&nbsp;<input type=radio name='adminmenu' value='N' checked> N</td>
								</tr>
								
								<?php } ?>
							</table>									
						</div>
						<!-- End Form -->
						
						<!-- Form Buttons -->
						<div class="buttons">
							<input type="submit" class="button" value="Ubah" />
							<input type="button" class="button" value="Batal" onclick="self.history.back()">
						</div>
						<!-- End Form Buttons -->
	<?php 	endforeach;
			form_close();
	} elseif($page=='add'){
	echo form_open('adminweb/menuutama/addprocess');?>
						<!-- Form -->
						<div class="form">
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr><td width ="170px">	<label>Nama Menu <span>(Required Field)</span></label></td>
									<td><input type="text" class="field size5"  name="nama_menu" placeholder="nama menu" required autofocus /></td>
								</tr>
								<tr><td>	<label>Link</label></td>
									<td><input type="text" class="field size4" name="link" placeholder="link" /></td>
								</tr>
								<tr><td>	<label>Aktif</label></td>
									<td><input type=radio name='aktif' value='Y' checked> Y 
                                                             &nbsp;<input type=radio name='aktif' value='N'>N</td>
								</tr>
								<tr><td>	<label>Admin Menu</label></td>
									<td><input type=radio name='adminmenu' value='Y'> Y 
                                                              &nbsp;<input type=radio name='adminmenu' value='N' checked>N</td>
								</tr>
							</table>									
						</div>
						<!-- End Form -->
						
						<!-- Form Buttons -->
						<div class="buttons">
							<input type="submit" class="button" value="Simpan" />
							<input type="button" class="button" value="Batal" onclick="self.history.back()">
						</div>
						<!-- End Form Buttons -->
	<?php form_close();
	}
	else{?>
			<table class="tables" width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th>No</th>
					<th>menu utama</th>
					<th>link</th>
					<th align="center">aktif</th>
					<th align="center">admin menu</th>
					<th class="ac">Control</th>
				</tr>
				<?php $no=1; foreach ($query as $row):?>
				<tr>
					<?php if($no%2!=0){ echo "<tr class='odd'>";} ?>
					<td width='25'><?php echo $no;?></td>
					<td><h3><strong><?php echo $row->nama_menu;?></strong></h3></td>
					<td><?php echo $row->link;?></td>
					<td align="center"><?php echo $row->aktif;?></td>
					<td align="center"><?php echo $row->adminmenu;?></td>
					<td align='center'><a href="<?=site_url('modul_menuutama/delprocess')?>/<?php	echo $row->id_main;?>" class="ico del"></a>
					<a href="<?=site_url('modul_menuutama/editform')?>/<?php echo $row->id_main;?>" class="ico edit"></a></td>
				</tr>
				<?php $no++; endforeach;?>
			</table>
<?php }

