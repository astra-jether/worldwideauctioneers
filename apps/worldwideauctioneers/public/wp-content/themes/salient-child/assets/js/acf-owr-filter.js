jQuery(document).ready(function($) {
    $('#acf-offered-without-reserve-toggle').change(function() {
        var filterValue = $(this).is(':checked') ? '1' : '0';
        $.ajax({
            url: ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_owr_products',
                nonce: ajax_params.nonce,
                offered_without_reserve: filterValue
            },
            success: function(response) {
                // Replace your product grid with the response HTML.
                // You'll need to adjust the selector based on your site's HTML.
                $('.products').html(response);
            }
        });
    });
});
