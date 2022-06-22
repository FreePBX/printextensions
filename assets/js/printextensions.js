$(document).ready(function() {
    var id_ls_gropus = '#ls_groups_extensions';

	// https://www.codehim.com/demo/bootstrap-multiselect-dropdown/    
    $(id_ls_gropus).multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: true,
        selectAllNumber: false,
        disableIfEmpty: true,
        inheritClass: true,
        onChange: function(option, checked)
        {
            if (option != undefined)
            {
                var sId = "#" + $(option).val();
                if (checked === true) {
                    $(sId).show();
                }
                else {
                    $(sId).hide();
                }
            }
        },
        onSelectAll: function()
        {
            $(id_ls_gropus + ' option').each(function() {
                $("#" + $(this).val()).show();
            });
        },
        onDeselectAll: function()
        {
            $(id_ls_gropus + ' option').each(function() {
                $("#" + $(this).val()).hide();
            });
        },
    });

    $('#btnPrintPdf').on('click', function ()
    {
        var options = $(id_ls_gropus + " option:selected");
        var checked = options.length;
        var names = [];

        if (checked == 0) {
            fpbxToast(_("Skip, not selected data!"),'','error')
        }
        else
        {
            options.each(function(){
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

    $('#btn_save_settings').on("click", settings_update);
    $('#btn_set_default_settings').on("click", settings_set_default);    
});

function settings_update(e) {
    e.preventDefault();
    t = e.target || e.srcElement;
   
    
    var form = $('#form_more_options');
    var data = form.serializeArray().reduce(function(obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});

    var post_data = {
		module: 'printextensions',
		command: 'settings_set',
        settings: data,
	};

    $.post(window.FreePBX.ajaxurl, post_data)
	.done(function(data)
	{
		fpbxToast(data.message, '', (data.status ? 'success' : 'error') );
        document.getElementById('dropdownMenuMoreOptions').click();
	});
}

function settings_set_default(e) {
    e.preventDefault();
    t = e.target || e.srcElement;

    var post_data = {
		module: 'printextensions',
		command: 'settings_set_default',
	};

    $.post(window.FreePBX.ajaxurl, post_data)
	.done(function(data)
	{
		fpbxToast(data.message, '', (data.status ? 'success' : 'error') );
        document.location.reload(true);
	});
}