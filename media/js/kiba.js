$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");
		
		var name = $("#name"),
			email = $("#email"),
			password = $("#password"),
			allFields = $([]).add(name).add(email).add(password),
			tips = $(".validateTips");

		function updateTips(t) {
			tips
				.text(t)
				.addClass('ui-state-highlight');
			setTimeout(function() {
				tips.removeClass('ui-state-highlight', 1500);
			}, 500);
		}

		function checkLength(o,n,min,max) {

			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("Length of " + n + " must be between "+min+" and "+max+".");
				return false;
			} else {
				return true;
			}

		}

		function checkRegexp(o,regexp,n) {

			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}

		}
		
		$("#dialog-form").dialog({
			autoOpen: false,
			height: 600,
			width:1000,
			modal: true,
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
		


///
$.fx.speeds._default = 500;
	$(function() {
		$('#dialog-form').dialog({
			autoOpen: false,
			show: 'blind',
			hide: 'fold'
		});
		
		$('#create-user').click(function() {
			$('#dialog-form').dialog('open');
			return false;
		});
	});
// prose hapus
$(".hapus").click(function(event) {
	
	var id = $(this).attr("id");
	if(confirm("Yakin data ini mau di Hapus ?? "))
	{
		$( "#result" ).empty().append("Tunggu Sebentar.. <img src='img/loader.gif'/>");
		$.ajax({
				type: "POST",
				url: "./modul/the_kiba/aksi_kiba.php?p=kiba&act=hapus",
				data:"id="+id,
			 	cache: false,
			 	success: function(html){
			 		$(".listhapus_"+id).remove(".listhapus_").animate({opacity:"hide"},"slow");
					$("#result" ).empty().html();
			}
		});
		return false;
		}
					
});
//end hapus
//prose delete
$("a.delete").click(function() {
	id_array=new Array()
	i=0;
	var id = $(this).attr("id");
	$("input.chk:checked").each(function(){
		id_array[i]=$(this).val();
		i++;	
	})
		
	if (i == 0) {
		alert('silahkan pilih data yang akan di hapus');
		return false;
	}

	
	if(confirm(i+" data ini mau di Hapus ?? "))
	{
	
		$("#result").empty().append("Tunggu Sebentar.. <img src='img/loader.gif'/>");
		$.ajax({
				type: "POST",
				url: "./modul/the_kiba/aksi_kiba.php?p=kiba&act=hapusall",
				data:"id="+id_array,
			 	cache: false,
			 	success: function(respon){
					if (respon == 1){
					$("input.chk:checked").each(function(){
							$(this).parent().parent().remove('.listhapus_').animate({opacity:"hide"},"slow");
							$("#result" ).empty().html();
						})
					}
			}
		})
		return false;
	}	
				
});
//
$(".posting").click(function(event) {
	var id = $(this).attr("id");
	if(confirm("Setelah diposting data tidak dapat di ubah, Yakin data ini mau di posting ??"))
	{
		 $( "#result" ).empty().append("Tunggu Sebentar.. <img src='img/loader.gif'/>");
		$.ajax({
				type: "POST",
				url: "./modul/the_kiba/aksi_kiba.php?p=kiba&act=posting2",
				data:"id="+id,
			 	cache: false,
			 	success: function(html){
			 		$("#list_"+id).slideUp('slow', function() {
						$(this).remove();
						$("#list2_"+id).html("verified");
						$("#clist2_"+id).html("X");
						$("#result" ).empty().html();});
			}
		});
		return false;
		}
					
});
//

//
var availableTags = ["APBD", "PAPBD", "Hibah","Pembelian"];
		$("#tags").autocomplete({
			source: availableTags
		});

});
