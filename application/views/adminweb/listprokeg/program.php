<script>
    $(document).ready( function (){
        $('#id_program').change(function(){
               var program = $("#id_program").val();
               if (program != 0){           
                    var kode = {
                                    Kd_Prog:$("#id_program").val()
                                };
                                $('#id_kegiatan').attr("disabled",true);
                                $.ajax({
                                        type: "POST",
                                        url : "<?php echo site_url('chain/select_kegiatan')?>",
                                        data: kode,
                                        success: function(msg){
                                            $('#id_kegiatan').attr("disabled",false);
                                            $('#id_kegiatan').html(msg);
                                        }
                                });             
                                                    
                }else{
                    $("#id_kegiatan option").attr("disabled", true);
                }
           });
    });
</script>   
<?php echo form_dropdown('Kd_Prog', $option_program, "id='id_program'"); ?>