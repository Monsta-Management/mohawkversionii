(function($) {
    function ImportCSV() {
        var taget = $('#import_button');
        var notif = $('#import_notif');

        if (taget.length){
            taget.click(function() {

                if (confirm("Do you really want to import the checked CSV files?")) {
                    taget.text('Importing...');
                    notif.addClass('hidden');
                    notif.css('display', 'none');
    
                    var data = {
                        'action': 'mohawk_import_csv',
                    };
    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function (response) {
                            console.log(response);
    
                            taget.text('Submit');
                            notif.removeClass('hidden');
                            notif.css('display', 'block');
    
                        }
                    });
                }
            });
        }
    }

    $(document).ready(function() {
        ImportCSV();
    });
}(window.jQuery || window.$));