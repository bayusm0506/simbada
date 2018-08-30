<?php 
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>

<form name="kelas_form" method="post" action="<?php echo $form_action; ?>">
	<p>
	  <?php echo isset($default['id_kategori']) ? $default['id_kategori'] : '' ?>
	</p>
    
    <p>
	  <label for="nis">Nama Kategori :</label>
		<input type="text" class="form_field" name="nama_kategori" size="30" value="<?php echo set_value('nama_kategori', isset($default['nama_kategori']) ? $default['nama_kategori'] : ''); ?>" />
	</p>
	<?php echo form_error('nama_lengkap', '<p class="field_error">', '</p>');?>
    
	<p>
	  <label for="tanggal">Aktif :</label>
      <?php
	  	$data = array(
					'name'        => 'newsletter',
					'id'          => 'newsletter',
					'value'       => 'accept',
					'checked'     => TRUE,
					);
					echo form_checkbox($data);

	
 echo form_dropdown("option_menu",$option_menu, isset($default['menu_nama']) ? $default['menu_nama'] : '');

	
	  ?>
  </p>
	<?php echo form_error('kelas', '<p class="field_error">', '</p>');?>	

  <p>
		<input type="submit" name="submit" id="submit" value=" Simpan " />
        <input type="reset" name="submit" id="submit" value=" Reset " />
  </p>
</form>

<?php
	if ( ! empty($link))
	{
		echo '<p id="bottom_link">';
		foreach($link as $links)
		{
			echo $links . ' ';
		}
		echo '</p>';
	}
?>