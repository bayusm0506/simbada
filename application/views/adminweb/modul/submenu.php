<?php 
	if(!isset($page))$page='';

	if($page=='edit'){
	foreach ($query as $row):
	echo form_open('adminweb/submenu/editprocess');?>
						<!-- Form -->
						<div class="form">
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<input type="hidden" name="id_sub" value="<?php echo $row->id_sub;?>" />

								<tr><td width ="170px">	<label>Nama Sub-Menu<span>(Required Field)</span></label></td>
									<td><input type="text" class="field size5"  name="nama_sub" value="<?php echo $row->nama_sub;?>" placeholder="nama sub-menu" required autofocus /></td>
								</tr>
								
								<tr><td width ="170px">	<label>Pilih Menu Utama<span></span></label></td>
									<td><select name='menu_utama'>
									<?php if ($row->id_main==0){?>
											<option value=0 selected>- Pilih Menu Utama -</option>
									<?php }
										$query2="SELECT * FROM mainmenu WHERE aktif='Y' ORDER BY nama_menu";
										$query2 = $this->db->query($query2);
										foreach ($query2->result() as $row2){
											if ($row->id_main == 0 || ($row->id_main !=0 && $row->id_submain !=0)){
												  echo "<option value='".$row2->id_main."' selected>".$row2->nama_menu."</option>";
												}
												else{
												  echo "<option value='".$row2->id_main."'>".$row2->nama_menu."</option>";
												}
												}?></select>
									</td>
								</tr>

								<tr><td width ="170px">	<label>Pilih Sub-Menu<span></span></label></td>
									<td><select name='sub_menu'>
									<?php if ($row->id_submain==0){?>
											<option value=0 selected>- Pilih Sub-Menu -</option>
									<?php }
										$query3="SELECT * FROM submenu WHERE id_submain=0 AND aktif='Y' ORDER BY nama_sub";
										$query3 = $this->db->query($query3);
										foreach ($query3->result() as $row3){
											if ($row->id_submain == $row3->id_sub){
												  echo "<option value='".$row3->id_sub."' selected>".$row3->nama_sub."</option>";
												}
												else{
												  echo "<option value='".$row3->id_sub."'>".$row3->nama_sub."</option>";
												}
											}?></select>
									</td>
								</tr>
								
								
								<tr><td>	<label>Link Sub-Menu</label></td>
									<td><input type="text" class="field size4" name="link_sub" value="<?php echo $row->link_sub;?>" placeholder="link sub-menu" /></td>
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
								if ($row->adminsubmenu =='Y'){?>
								<tr><td>	<label>Admin Sub-Menu</label></td>
									<td><input type=radio name='adminsubmenu' value='Y' checked> Y  
										&nbsp;<input type=radio name='adminsubmenu' value='N'> N</td>
								</tr>
								<?php }else{?>
									<tr><td>	<label>Admin Sub-Menu</label></td>
									<td><input type=radio name='adminsubmenu' value='Y'> Y  
										&nbsp;<input type=radio name='adminsubmenu' value='N' checked> N</td>
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
	echo form_open('adminweb/submenu/addprocess');?>
						<!-- Form -->
						<div class="form">
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr><td width ="170px">	<label>Nama Sub-Menu <span>(Required Field)</span></label></td>
									<td><input type="text" class="field size5"  name="nama_sub" placeholder="nama sub-menu" required autofocus /></td>
								</tr>
								<tr><td width ="170px">	<label>Pilih Menu Utama<span></span></label></td>
									<td><select name='menu_utama'>
										<option value=0 selected>- Pilih Menu Utama -</option>
											<?php $query="SELECT * FROM mainmenu WHERE aktif='Y' ORDER BY nama_menu";
											$query = $this->db->query($query);
											foreach ($query->result() as $row){
											echo "<option value='".$row->id_main."'>".$row->nama_menu."</option>";
											}?></select>
									</td>
								</tr>

								<tr><td width ="170px">	<label>Pilih Sub-Menu<span></span></label></td>
									<td><select name='sub_menu'>
										<option value=0 selected>- Pilih Menu Utama -</option>
											<?php $query2="SELECT * FROM submenu WHERE id_submain=0 AND aktif='Y' ORDER BY nama_sub";
											$query2 = $this->db->query($query2);
											foreach ($query2->result() as $row2){
											echo "<option value='".$row2->id_sub."'>".$row2->nama_sub."</option>";
											}?>
									
										</select>
									</td>
								</tr>								
								<tr><td>	<label>Link Sub-Menu</label></td>
									<td><input type="text" class="field size4" name="link_sub" placeholder="link sub-menu" /></td>
								</tr>
								<tr><td>	<label>Aktif</label></td>
									<td><input type=radio name='aktif' value='Y' checked> Y 
                                                             &nbsp;<input type=radio name='aktif' value='N'>N</td>
								</tr>
								<tr><td>	<label>Admin Sub-Menu</label></td>
									<td><input type=radio name='adminsubmenu' value='Y'> Y 
                                                              &nbsp;<input type=radio name='adminsubmenu' value='N' checked>N</td>
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
					<th>Sub-Menu</th>
					<th>Menu Utama</th>
					<th>link sub-menu</th>
					<th align="center">aktif</th>
					<th align="center">admin sub-menu</th>
					<th class="ac">Control</th>
				</tr>
	<?php 
	$subquery="SELECT * FROM submenu,mainmenu WHERE submenu.id_main=mainmenu.id_main";
	$query = $this->db->query($subquery);
	$no=1;
	foreach ($query->result() as $row)
		{
			if($row->id_submain!=0){
				$sub = "SELECT * FROM submenu WHERE id_sub=$r[id_submain]";
				$query2 = $this->db->query($sub);
				foreach ($query2->result() as $row2)
				$mainmenu = $row->nama_menu." &gt; ".$row2->nama_sub;
			} else {
				$mainmenu = $row->nama_menu;
			}?>
				<tr>
					<?php if($no%2!=0){ echo "<tr class='odd'>";} ?>
					<td width='25'><?php echo $no;?></td>
					<td><h3><strong><?php echo $row->nama_sub;?></strong></h3></td>
					<td><?php echo $mainmenu;?></td>
					<td><?php echo $row->link_sub;?></td>
					<td align="center"><?php echo $row->aktif;?></td>
					<td align="center"><?php echo $row->adminsubmenu;?></td>
					<td align='center'><a href="<?=site_url('modul_submenu/delprocess')?>/<?php	echo $row->id_sub;?>" class="ico del"></a>
					<a href="<?=site_url('modul_submenu/editform')?>/<?php echo $row->id_sub;?>" class="ico edit"></a></td>
				</tr>
				<?php $no++; }?>
			</table>
<?php }

