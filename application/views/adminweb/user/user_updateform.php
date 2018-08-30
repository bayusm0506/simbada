 <script type="text/javascript">
    $(document).ready(function(){
		
        $("#formku").submit(function(e){
            var username 		= $("#username").val();
			var newpassword 	= $("#newpassword").val();
			var newpassword1 	= $("#newpassword1").val();
			var newpassword2 	= $("#newpassword2").val();
			
			var email 			= $("#email").val();
			
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(email)) {
				alert('Isi email anda dengan benar !');
				return false;
			}
		    if(username == ''){
                alert("Username tidak boleh kosong !");
                return false;
            }
			
		    if(newpassword != '' && newpassword2 != newpassword1){
                alert("password tidak sama !");
                return false;
            }
        });
    
		});
</script>
<?php
	
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
?>	
        <div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header">
                    <i class="icon-tasks"></i> <?php echo ! empty($header) ?  $header: ''; ?></div>
                    <div class="panel-content">
<!-- Validation -->
                         <form method=POST action="<?php echo $form_action; ?>" name='fadd' id='formku'>
								
                         		<table width='100%'>
								    	<tr>
											<td width="15%">Username</td>
								            <td>: <input type=text name='username' size=25 value="<?php echo 
								            $username;?>"  id="username"/></td>
										</tr>
										<?php if ($this->session->userdata('lvl') != 01){ ?>
								        <tr>
											<td>Password Lama</td>
								            <td>: <input type='password' name='password' size=25  /></td>
										</tr>
										<?php } ?>
								        <tr>
											<td>Password Baru</td>
								            <td>: <input type='password' name='newpassword1' size=25  id="newpassword1" /></td>
										</tr>
								        <tr>
											<td>Ketik Lagi Password Baru</td>
								            <td>: <input type='password' name='newpassword2' size=25  id="newpassword2" /></td>
										</tr>
								        <tr>
											<td colspan='2'><hr></td>
										</tr>
								        <tr>
											<td>Nama</td>
								            <td>: <input type=text name='nama_lengkap' size=55 value="<?php echo 
								            $nama_lengkap; ?>"  /></td>
										</tr>
								        <tr>
											<td>Email</td>
								            <td>: <input type=text name='email' size=55 value="<?php echo $email; ?>" id="email"/></td>
								        </tr>
								        <tr>
											<td>No Telp</td>
								            <td>: <input type=text name='no_telp' size=15 value="<?php echo 
								            $no_telp; ?>" id='jml_satuan'><span id='pesan2'></span></td>
										</tr>
										<?php if ($this->session->userdata('lvl') == 01){?>
										<tr>
											<td>Level</td>
								        	<td>: <?php echo form_dropdown("lvl",$option_level,$lvl,"id='id_level'");  ?></td>
										</tr>

									    <tr>
									      	<td>Jabatan</td>
									      	<td>: <?php echo form_dropdown("jabatan_id",$option_jabatan,$jabatan_id,"id='id_jabatan'");  ?></td>
									    </tr>
									    <?php }?>
								        <tr>
											<td colspan="2"><input type="submit" name="submit" class="submit btn btn-success" value="UPDATE" id="submit">
								          <input type=button value=BATAL id='reset' class="btn btn-danger" onclick=self.history.back() ></td>
										</tr>
								       </table>

       					</form>

       				</div>
      			</div>
       		</div>
       </div>