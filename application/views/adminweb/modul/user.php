<?php 
	if(!isset($page))$page='';

	if($page=='edit'){
	foreach ($query as $row):
	echo form_open('adminweb/user/editprocess');?>
						<!-- Form -->
						<div class="form">
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<input type="hidden" name="id" value="<?php echo $row->id;?>" />
							
								<tr><td width ="170px">	<label>Username <span>(Required Field)</span></label></td>
									<td><input type="text" class="field size5"  name="username" value="<?php echo $row->username;?>" placeholder="username" required autofocus /></td>
								</tr>
								<tr><td>	<label>Password </label></td>
									<td><input type="password" class="field size5" name="password" value="" placeholder="password" /></td>
								</tr>
								<tr><td>	<label>Nama Lengkap</label></td>
									<td><input type="text" class="field size4" name="nama_lengkap" value="<?php echo $row->nama_lengkap;?>" placeholder="nama lengkap" /></td>
								</tr>
								<tr><td>	<label>E-mail</label></td>
									<td><input type="text" class="field size4" name="email" value="<?php echo $row->email;?>" placeholder="email"/></td>
								</tr>
								<tr><td>	<label>No Telp/HP</label></td>
									<td><input type="text" class="field size5" name="no_telp" value="<?php echo $row->no_telp;?>" placeholder="no telp/hp" /></td>
								</tr>
								<?php if ($row->blokir =='N'){?>
								<tr><td><label>Blokir</label></td>
									<td><input type="radio" name="blokir" value="Y"> Y   
										&nbsp;<input type="radio" name="blokir" value="N" checked> N </td>
								</tr>
								<?php }else{ ?>
								<tr><td><label>Blokir</label></td>
									<td><input type="radio" name="blokir" value="Y" checked> Y   
										<input type="radio" name="blokir" value="N"> N </td>
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
	echo form_open('adminweb/user/addprocess');?>
						<!-- Form -->
						<div class="form">
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr><td width ="170px">	<label>Username <span>(Required Field)</span></label></td>
									<td><input type="text" class="field size5"  name="username" placeholder="username" required autofocus /></td>
								</tr>
								<tr><td>	<label>Password <span>(Required Field)</span></label></td>
									<td><input type="password" class="field size5" name="password" placeholder="password" required /></td>
								</tr>
								<tr><td>	<label>Nama Lengkap</label></td>
									<td><input type="text" class="field size4" name="nama_lengkap" placeholder="nama lengkap" /></td>
								</tr>
								<tr><td>	<label>E-mail</label></td>
									<td><input type="text" class="field size4" name="email" placeholder="email"/></td>
								</tr>
								<tr><td>	<label>No Telp/HP</label></td>
									<td><input type="text" class="field size5" name="no_telp" placeholder="no telp/hp" /></td>
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
					<th>Username</th>
					<th>Nama Lengkap</th>
					<th>Email</th>
					<th>No. Telp/HP</th>
					<th>Level</th>
					<th>Blokir</th>
					<th class="ac">Control</th>
				</tr>
				<?php $no=1; foreach ($query as $row):?>
				<tr>
					<?php if($no%2!=0){ echo "<tr class='odd'>";} ?>
					<td width='25'><?php echo $no;?></td>
					<td><h3><strong><?php echo $row->username;?></strong></h3></td>
					<td><?php echo $row->nama_lengkap;?></td>
					<td><a href=mailto:<?php echo $row->email;?>><?php echo $row->email;?></a></td>
					<td><?php echo $row->no_telp;?></td>
					<td align="center"><?php echo $row->level;?></td>
					<td align="center"><?php echo $row->blokir;?></td>
					<td align='center'><a href="<?=site_url('modul_users/delprocess')?>/<?php	echo $row->id;?>" class="ico del"></a>
					<a href="<?=site_url('modul_users/editform')?>/<?php echo $row->id;?>" class="ico edit"></a></td>
				</tr>
				<?php $no++; endforeach;?>
			</table>
<?php }

