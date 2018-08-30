$(function(){
    // dialog 1 properties
    $('#dialog1').dialog({
        autoOpen: false,
        show: 'slide', // bounce//explode//clip//fold//highlight//pulsate//puff//scale//shake//slide//blind
        hide: 'explode',
        buttons: { 'Close': function() { $(this).dialog('close'); } },
        closeOnEscape: true,
        resizable: false
    });

    // dialog 1 open/close
    $('#toggle1').click(function() {
        if ($('#dialog1').dialog('isOpen') == true)
            $('#dialog1').dialog('close');
        else
            $('#dialog1').dialog('open');
        return false;
    });


    // dialog 2 properties
    $('#dialog2').dialog({
        autoOpen: false,
        show: 'blind',
        hide: 'fold',
        closeOnEscape: true
    });

    // dialog 2 open/close
    $('#toggle2').click(function() {
        if ($('#dialog2').dialog('isOpen') == true)
            $('#dialog2').dialog('close');
        else
            $('#dialog2').dialog('open');
        return false;
    });

    // dialog 3 properties
    $('#dialog3').dialog({
        autoOpen: false,
        show: 'fade',
        hide: 'fade',
        modal: true,
        buttons: {
            'Ok': function() { $(this).dialog('close'); },
            'Close': function() { $(this).dialog('close'); }
        },
        resizable: false
    });

    // dialog 3 open/close
    $('#toggle3').click(function() {
        if ($('#dialog3').dialog('isOpen') == true)
            $('#dialog3').dialog('close');
        else
            $('#dialog3').dialog('open');
        return false;
    });

    // dialog 4 properties
    $('#dialog4').dialog({
        autoOpen: false,
        show: 'highlight',
        hide: 'scale',
        modal: true,
        buttons: {
            'Login': function() {
                var name = $('#name').val(), password = $('#password').val();
                var mydialog4 = $(this);

                if (name != '' && password != '') {
                    $.ajax({ 
                      type: 'POST', 
                      url: 'some.php', 
                      data: 'name='+name+'&pass='+password, 
                      success: function(msg){ 
                        alert(msg); 
                        $(mydialog4).dialog('close');
                      } 
                    });
                }
            },
            'Close': function() { $(this).dialog('close'); }
        },
        resizable: false,
        width: '400px'
    });

    // dialog 4 open/close
    $('#toggle4').click(function() {
        if ($('#dialog4').dialog('isOpen') == true)
            $('#dialog4').dialog('close');
        else
            $('#dialog4').dialog('open');
        return false;
    });

    // moveToTop functionality
    $('#top1').click(function() {
        $('#dialog1').dialog('moveToTop');
    });

    $('#top2').click(function() {
        $('#dialog2').dialog('moveToTop');
    });

    // enable functionality
    $('#enable1').click(function() {
        $('#dialog1').dialog('enable');
    });

    $('#enable2').click(function() {
        $('#dialog2').dialog('enable');
    });

    // disable functionality
    $('#disable1').click(function() {
        $('#dialog1').dialog('disable');
    });

    $('#disable2').click(function() {
        $('#dialog2').dialog('disable');
    });
});
