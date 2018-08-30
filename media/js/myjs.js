$(document).ready(function() {
        // ketika input usia di isi, eksekusi bagian ini.
	      $("#Harga").keypress(function (data)  
	      { 
	         // kalau data bukan berupa angka, tampilkan pesan error
	         if(data.which!=8 && data.which!=0 && (data.which<48 || data.which>57))
	         {
		          $("#pesan").html("Harga harus di isi dengan Angka").show().fadeOut("slow"); 
	            return false;
           }	
	      });
		  
		  $("#jml_satuan").keypress(function (data)  
	      { 
	         // kalau data bukan berupa angka, tampilkan pesan error
	         if(data.which!=8 && data.which!=46 && data.which!=0 && (data.which<48 || data.which>57))
	         {
		          $("#pesan2").html("Jumlah satuan harus di isi dengan Angka").show().fadeOut("slow"); 
	            return false;
           }	
	      });
		  
		  

		  /////////////////////
$('#datepicker').datepicker({
			changeMonth: true,
			showAnim: 'slideDown',
			dateFormat: "yy-mm-dd",
			yearRange: '-115:+2',
			changeYear: true
		});
$('#datepicker2').datepicker({
			changeMonth: true,
			showAnim: 'slideDown',
			dateFormat: "yy-mm-dd",
			yearRange: '-115:+2',
			changeYear: true
		});
$('#datepicker3').datepicker({
			changeMonth: true,
			showAnim: 'slideDown',
			dateFormat: "yy-mm-dd",
			yearRange: '-115:+2',
			changeYear: true
		});
		
//
		  
		  
 });
