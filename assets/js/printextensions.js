$(document).ready(function() {
 $('input[id^="module_"]').click(function () {
	 if ($(this).prop('checked')) {
          $("#"+ $(this).val()).show();
        }
        else {
           $("#"+ $(this).val()).hide();        
        }
      });

  });
