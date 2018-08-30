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
                    <i class="icon-tasks"></i> <?php echo ! empty($h2_title) ?  $h2_title: ''; ?></div>
                    <div class="panel-content">
<!-- Validation -->
                         <form id="demo-form-2" action="<?php echo $form_action; ?>" class="col-md-10 center-margin" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Nama Website
                        <span class="required">*</span>
                    </label>
                </div>
                <div class="form-input col-md-10">
                    <input type="text" id="nama_website" placeholder="Tulis nama website disini" name="nama_website" value="<?php echo $nama_website ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Alamat Website
                        <span class="required">*</span>
                    </label>
                </div>
                <div class="form-input col-md-10">
                    <input type="text" id="alamat_website" placeholder="Tulis alamat website disini" name="alamat_website" value="<?php echo $alamat_website ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Alamat Kantor
                        <span class="required">*</span>
                    </label>
                </div>
                <div class="form-input col-md-10">
                    <textarea placeholder="Tulis alamat identitas disini" name="alamat"><?php echo $alamat ?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        No Telp
                    </label>
                </div>
                <div class="form-input col-md-10">
                    <input type="text" id="telp" placeholder="Tulis no telp disini" name="telp" value="<?php echo $telp ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Email
                    </label>
                </div>
                <div class="form-input col-md-10">
                    <input type="text" id="email" placeholder="Tulis email disini" name="email" value="<?php echo $email ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="" class="label-description">
                        Meta Deskripsi
                    </label>
                </div>
                <div class="form-input col-md-10">
                    <textarea placeholder="Tulis meta deskripsi identitas disini" name="meta_deskripsi"><?php echo $meta_deskripsi ?></textarea>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="" class="label-description">
                        Meta Keywords
                    </label>
                </div>
                <div class="form-input col-md-10">
                    <textarea placeholder="Tulis meta deskripsi identitas disini" name="meta_keyword"><?php echo $meta_keyword ?></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Logo :
                    </label>
                </div>
                <div class="form-input col-md-6">
                          <?php 
                if (!empty($logo)){
                  echo "<img src=".base_url()."asset/img/".$logo." />";
                    ?>
                                  <a  href="javascript:;"  img="<?php echo $logo?>"
                                              id-identitas="<?php echo $id_identitas?>"
                                              title="Delete" class="a-danger delete-logo">
                                            <i class="fa fa-times"></i> Delete
                                        </a>
                <?php }else{
                  echo '<input type="file" name="userfile" size="40"/>';
                } ?>
                </div>
            </div>
            
            <div class="divider"></div>
            <div class="form-row">
                <div class="form-input col-md-10 col-md-offset-2">
                    <button type="submit" class="btn medium ui-state-default radius-all-4">Update</button>
                </div>
            </div>

        </form>

                    </div>
                </div>
            </div>
       </div>
<script type="text/javascript">
     $(".delete-logo").click(function(){
    
        var img       = $(this).attr("img");
        var id_identitas = $(this).attr("id-identitas");
        $.ajax({
            
            url : base_url+'admin/identitas/unlink',type:'post',dataType:'json',
            data:{img:img,id_identitas:id_identitas},
            success:function(res){
                
                if(res.status)
                    window.location.reload();
            },
            error:function(x,h,r){
                
                alert(r)
            }
        
        });
        
    }); 
                               
     </script>