jQuery(document).ready(function($) {
    // Event listener for category change
    $('#product-category').change(function() {
        var selectedCategory = $(this).val();

        // Perform AJAX request to get options for the selected category
        $.ajax({
            url: myFilterAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_filter_options', // This should match the hook in your PHP function
                category: selectedCategory
            },
            success: function(response) {
                // Assuming your response is the new HTML to be placed in the filter
                // You may need to adjust this if your response is in a different format (like JSON)
                $('#dynamic-filter').html(response);
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    });

    // Optionally, you can trigger the change event on page load to initialize the filter
    $('#product-category').trigger('change');
});
