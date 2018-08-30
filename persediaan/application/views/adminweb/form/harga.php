<script type="text/javascript">    
 $("#harga").keypress(function (data)  
	      { 
	         // kalau data bukan berupa angka, tampilkan pesan error
	         if(data.which!=8 && data.which!=0 && (data.which<48 || data.which>57))
	         {
		          $("#message").html("Harga harus di isi dengan Angka").show().fadeOut("slow"); 
	            return false;
           }	
	      });
 </script>
<input type=text name='s' id='harga' class='input-medium' placeholder='Isi harga disini'> 