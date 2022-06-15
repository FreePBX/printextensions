$(document).ready(function() {
    $('input[id^="module_"]').click(function () {
	    if ($(this).prop('checked')) {
            $("#"+ $(this).val()).show();
        }
        else {
            $("#"+ $(this).val()).hide();        
        }
    });

    $('#btnPrintPdf').on('click', function ()
    {
        var inputs = $('input[id^="module_"]:checked');
        var checked = inputs.length;
        var names = [];

        if (checked == 0) {
            fpbxToast(_("Skip, not selected data!"),'','error')
        }
        else
        {
            inputs.each(function(){
                names.push($(this).val());
            });
            var ajax_data = {
                module: "printextensions",
                command: "getPdf",
                names: names,
            };
            var form = $('<form>', {
                'method': 'POST', 
                'action': FreePBX.ajaxurl,
                'target': 'print_popup',
                'onsubmit': "window.open('about:blank','print_popup');",
            }).hide();
            $.each(ajax_data, function (k, v) {
                form.append($('<input>', {'type': 'hidden', 'name': k, 'value': v}));
            });
            $('body').append(form);
            form.submit();
            form.remove();
        }
    });
});