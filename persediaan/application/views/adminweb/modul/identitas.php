<?php
	foreach ($query as $row):
	echo form_open_multipart('adminweb/identitas/editprocess');?>
						<!-- Form -->
						<div class="form">
							<table class="tableform" width="100%" border="0" cellspacing="0" cellpadding="0">
								<input type="hidden" name="id" value="<?php echo $row->id_identitas;?>" />

								<tr><td width ="170px">	<label>Nama Website</label></td>
									<td><input type="text" class="field size6"  name="nama_website" value="<?php echo $row->nama_website;?>" placeholder="nama website" autofocus /></td>
								</tr>
								<tr><td>	<label>Alamat Website</label></td>
									<td><input type="text" class="field size4" name="alamat_website" value="<?php echo $row->alamat_website;?>" placeholder="alamat website" /></td>
								</tr>
								<tr><td valign="top">	<label>Meta Deskripsi</label></td>
									<td><textarea class="field size6" rows="5" name="meta_deskripsi" cols="20"><?php echo $row->meta_deskripsi;?></textarea></td>
								</tr>
								<tr><td valign="top">	<label>Meta Keyword</label></td>
									<td><textarea class="field size6" rows="5" name="meta_keyword" cols="20"><?php echo $row->meta_keyword;?></textarea></td>
								</tr>
								<tr><td>	<label>Gambar Favicon</label></td>
									<td><img src="../media/images/<?php echo $row->favicon;?>"></td>
								</tr>
								<tr><td valign="top">	<label>Ganti Favicon</label></td>
									<td><input type=file size=20 name="fupload"><br>nama file gambar favicon harus favicon.ico</td>
								</tr>
								  
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
			form_close();?>
