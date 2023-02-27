jQuery(function ($) {

	jQuery.ajax({
        type: 'POST',
        url :  ajax_object.url,
        data: { action: 'api_user_details', nonce: ajax_object.nonce  },
        success: function (data) {
        jQuery("#users-table").html('');
                jQuery("#users-table").append(data);
        },
        error: function ( errorThrown) {
            alert(errorThrown); }
        });
});