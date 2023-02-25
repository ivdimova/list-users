jQuery(function ($) {
    $('.link').on('click', function () {
        var input = $(this).data("user_id");
        console.log(input);

        if (input != '') {
            jQuery.ajax({
                type: 'POST',
                url :  ajax_object.url,
                data: { action: 'api_user_details', user_id: input, nonce: ajax_object.nonce  },
                success: function (data) {
                    jQuery("#users-table").html('');
                    jQuery("#users-table").append(data);
                },
                error: function ( errorThrown) {
                     alert(errorThrown); }
                });
        }
    });

});