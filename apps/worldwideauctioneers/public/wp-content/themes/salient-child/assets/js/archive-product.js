jQuery(document).ready(function($) {

	$('.switch #owr').on('change', function(){
		if( $(this).is(':checked') ) {
			$("#filterArea").empty();
			
			var counter = 0;
			var currentContainer = $('<div class="filter-results"></div>');

			$(".list-details").each(function(index, item) {
				if( $(this).find('.reservation').length > 0 ) {
                    $(currentContainer).append( $(item).clone() );

					counter++;
                }
			});

			if( counter == '0' ) {
                $("#filterArea").append('<p>No results found.</p>');
            }else{
				$("#filterArea").append(currentContainer);
				$("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
			}

			$("#filterArea").prepend('<span class="clear-search">Clear Search</span>');
			
			$("#filterArea").slideDown();

			$('.auction-list').fadeOut(100);
		} else {
			$("#filterArea").slideUp();

			$("#filterArea").empty();

			$('.auction-list').fadeIn(100);
		}
	});

	$('#sbl').on('change', function() {
		if ($(this).is(':checked')) {
			$("#filterArea").empty();
	
			var items = [];
			$(".list-details").each(function(index, item) {
				var lotNumber = $(this).find('#lotnum').text(); // Find the span with id 'lotnum' and get its text content
				lotNumber = $.trim(lotNumber); // Trim any leading or trailing whitespace
	
				// Check if 'lotNumber' has a value and is a valid number
				if (lotNumber && !isNaN(lotNumber)) {
					items.push({ item: $(item).clone(), lotNumber: parseInt(lotNumber) });
				}
			});
	
			// Sort the items based on the lot number in ascending order
			items.sort(function(a, b) {
				return a.lotNumber - b.lotNumber;
			});
	
			if (items.length === 0) {
				$("#filterArea").append('<p>No results found.</p>');
			} else {
				var currentContainer = $('<div class="filter-results"></div>');
				items.forEach(function(sortedItem) {
					$(currentContainer).append(sortedItem.item);
				});
	
				$("#filterArea").append(currentContainer);
				$("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
			}
	
			$("#filterArea").prepend('<span class="clear-search">Clear Search</span>');
			$("#filterArea").slideDown();
			$('.auction-list').fadeOut(100);
		} else {
			$("#filterArea").slideUp();
			$("#filterArea").empty();
			$('.auction-list').fadeIn(100);
		}
	});
	
	
	
	  
	  

	$("#textSearchers").on('keyup', function() {
		var searchString = $(this).val().toLowerCase();

		$('.select-filters select').prop('selectedIndex', 0);
		
		if ( searchString != "" ) {
			$("#filterArea").empty();
			
			var counter = 0;
			var currentContainer = $('<div class="filter-results"></div>');
			
			$(".searchable").each(function(index, item){
				if( $(item).html().toLowerCase().includes(searchString) ) {
                    $(currentContainer).append( $(item).parent().clone() );

					counter++;
                }
			});
			
			if( counter == '0' ) {
                $("#filterArea").append('<p>No results found.</p>');
            }else{
				$("#filterArea").append(currentContainer);
				$("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
			}

			$("#filterArea").prepend('<span class="clear-search">Clear Search</span>');
			
			$("#filterArea").slideDown();

			$('.auction-list').fadeOut(100);

		} else {
			$("#filterArea").slideUp();

			$("#filterArea").empty();

			$('.auction-list').fadeIn(100);
		}
	});

	$("#textSearcher").on('change', function() {
		var searchString = $(this).val().toLowerCase();

		$("#collection").prop('selectedIndex', 0);
		$("#textSearchers").val('');
		
		if ( searchString != "" ) {
			$("#filterArea").empty();
			
			var counter = 0;
			var currentContainer = $('<div class="filter-results"></div>');
			
			$(".searchable").each(function(index, item){
				if( $(item).html().toLowerCase().includes(searchString) ) {
                    $(currentContainer).append( $(item).parent().clone() );

					counter++;
                }
			});
			
			if( counter == '0' ) {
                $("#filterArea").append('<p>No results found.</p>');
            } else {
				$("#filterArea").append(currentContainer);
				$("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
			}

			$("#filterArea").prepend('<span class="clear-search">Clear Search</span>');

			$("#filterArea").slideDown();

			$('.auction-list').fadeOut(100);

		} else {
			$("#filterArea").slideUp();

			$("#filterArea").empty();

			$('.auction-list').fadeIn(100);
		}
	});

	$("#collection").on('change', function(){
		var searchString = $(this).val().toLowerCase();

		$("#textSearcher").prop('selectedIndex', 0);
		$("#textSearchers").val('');
		
		if (searchString != "") {
			$("#filterArea").empty();
			
			var counter = 0;
			var currentContainer = $('<div class="filter-results"></div>');
			
			$(".searchable").each(function(index, item){
				if( $(item).html().toLowerCase().includes(searchString) ) {
                    $(currentContainer).append( $(item).parent().clone() );

					counter++;
                }
			});
			
			if(counter == 0) {
                $("#filterArea").append('<p>No results found.</p>');
            } else {
				$("#filterArea").append(currentContainer);
				$("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
			}

			$("#filterArea").prepend('<span class="clear-search">Clear Search</span>');
			
			$("#filterArea").slideDown();

			$('.auction-list').fadeOut(100);

		} else {
			$("#filterArea").slideUp();

			$("#filterArea").empty();

			$('.auction-list').fadeIn(100);
		}
	});

	$('body').on('click', '.clear-search', function(){
		console.log('test');
		$('body .select-filters select').prop('selectedIndex', 0).change();
		$("body  #textSearchers").val('');
		$('.switch input[type="checkbox"]').prop( 'checked', false );

		$("#filterArea").empty();

		$("#filterArea").slideUp();

		$("#filterArea").empty();

		$('.auction-list').fadeIn(100);
	});
});