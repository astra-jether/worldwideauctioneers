<?php get_header(); 

nectar_page_header( $post->ID );
//$nectar_fp_options = nectar_get_full_page_options();

?>

	<?php global $wp; 

	if (isset($_REQUEST['auctionid'])){
        $id = $_REQUEST['auctionid'];
    } else {
        $id = get_field('auction_id');
    }?>
	
	<?php $event_id = wwa_get_auction_id( home_url( "{$wp->request}/" ) ); ?>

	<?php $auction = wwa_pull_listings_by_event( $event_id, 't_inventory', $startingLot, $endingLot ); ?>

	<?php $products = wwa_get_poducts_by_event( $event_id ); ?>

	<?php $featuredproducts = wwa_get_featured_poducts_by_event( $event_id ); ?>

	
	<style>						
		.auction-list {
			display: flex !important;
			float:left !important;
			flex-wrap: wrap !important;
			justify-content: space-between;				
		}
		.auction-list > div {
			flex: 1 1 47% !important;
		
		}
		.listimg{
			height:380px !important;
		}
						
	</style>

	<style>
			/* Basic styling for pagination */
			.pagination {
				text-align: center;
				margin: 20px 0;
				font-family: Arial, sans-serif;
			}

			.pagination a {
				margin: 0 5px;
				padding: 5px 10px;
				border: 1px solid #ddd;
				color: #337ab7;
				text-decoration: none;
			}

			.pagination a.active {
				background-color: #337ab7;
				color: white;
				border: 1px solid #337ab7;
			}

			.pagination span {
				margin: 0 5px;
				padding: 5px 10px;
			}

			/* Hide the ellipsis on small screens */
			@media (max-width: 600px) {
				.pagination span {
					display: none;
				}
			}


			/* The switch - the box around the slider */
			.switch {
			position: relative;
			display: inline-block;
			width: 60px; /* Width of the switch */
			height: 34px; /* Height of the switch */
			}

			/* Hide default HTML checkbox */
			.switch input { 
			opacity: 0;
			width: 0;
			height: 0;
			}

			/* The slider */
			.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #ccc;
			-webkit-transition: .4s;
			transition: .4s;
			}

			.slider:before {
			position: absolute;
			content: "";
			height: 26px; /* Diameter of the slider */
			width: 26px; /* Diameter of the slider */
			left: 4px;
			bottom: 4px;
			background-color: white;
			-webkit-transition: .4s;
			transition: .4s;
			}

			input:checked + .slider {
			background-color: #2196F3;
			}

			input:focus + .slider {
			box-shadow: 0 0 1px #2196F3;
			}

			input:checked + .slider:before {
			-webkit-transform: translateX(34px); /* Move the slider to the end */
			-ms-transform: translateX(34px); /* Move the slider to the end */
			transform: translateX(34px); /* Move the slider to the end */
			}

			/* Rounded sliders */
			.slider.round {
			border-radius: 34px;
			}

			.slider.round:before {
			border-radius: 50%;
			}


	</style>

	<div class="container main-content">
		<h1>The Scottsdale Auction Inventory Search </h1>
		
		<div class="auction-filter">
			<div class="col span_12" style="margin:1rem 0;">
				<div class="searchForm">
					<div class="searchFormTitle">
						<h6><strong>Search:</strong></h6>
					</div>
					<form action="/search-inventory/" method="GET">
						<div class="form-inputs">
							<input type="text" id="textSearchers1" placeholder="Type here to search by make " name="searchTerm" required>
							<?php echo '<input type="hidden" name="eventID" value="'.$event_id.'">' ; ?>
							<input type="submit" value="SEARCH">
						</div>
					</form>
				</div>

				<style>
					.custom-form-container {
						display: flex;
						justify-content: space-between;
						align-items: center;
						flex-wrap: wrap; /* Allows wrapping on smaller screens */
					}
					.custom-form-container select {
						margin-right: 10px; /* Spacing between the dropdowns */
					}
					.custom-form-container label {
						margin-right: 5px; /* Spacing between the label and the dropdown */
					}
					.custom-form-container input[type=submit] {
						margin-top: 10px; /* Spacing above the submit button on small screens */
					}
				</style>

				

				<div class="searchForm">
					<div class="searchFormTitle">
						<h6><strong>Search by Make:</strong></h6>
					</div>
					<div class="dropsort">
					
						<form action="/search-inventory-by-make/" method="get" class="custom-form-container">
							<!-- Make Dropdown -->
							<?php echo '<input type="hidden" name="eventID" value="'.$event_id.'">' ; ?>
							
								<select id="make" name="make">
									<option value="Select All">Select all</option>
									<?php
									// Database connection variables
									$hostname = "mysql24.ezhostingserver.com";
									$username = "wa_myadmin";
									$password = "W4_my@hm!N";
									$dbname = "wa_online_db";

									// Create connection
									$conn = new mysqli($hostname, $username, $password, $dbname);

									// Check connection
									if ($conn->connect_error) {
										die("Connection failed: " . $conn->connect_error);
									}

									$sql = "SELECT DISTINCT smake FROM t_inventory  WHERE neventid = '".$event_id."' AND ncategoryid = 1 AND smake != '' ORDER BY smake";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
										// Output data of each row
										while($row = $result->fetch_assoc()) {
											$selected = isset($_POST['make']) && $_POST['make'] == $row['smake'] ? ' selected="selected"' : '';
											echo '<option value="' . htmlspecialchars($row['smake']) . '"' . $selected . '>' . htmlspecialchars($row['smake']) . '</option>';
										}
									} else {
										echo '<option value="">No makes available</option>';
									}
									// Close connection
									$conn->close();
									?>
								</select>
							

							<div class="formSubmit"> 

								<input type="submit" value="Submit"/>
								<input type="button" value="Clear Filter" onclick="location.href='/inventory/';" />

							</div>
						</form> 
						<div class="filter-container">
							<label for="sortOrder">Sort By: </label>
							<select id="sortOrder">
								<option value="makeascending">Make-Ascending</option>
								<option value="makedescending">Make-Descending</option>
								<option value="yearascending">Year-Ascending</option>
								<option value="yeardescending">Year-Descending</option>
								<option value="lotascending">Lot-Ascending</option>
								<option value="lotdescending">Lot-Descending</option>
							</select>
						</div>
					</div>
					
					
						<label class="switch">
							<input type="checkbox" id="toggleSwitch">
							<span class="slider round"></span>
						</label>
						<span>OFFERED WITHOUT RESERVE</span>
					
				</div>


			</div>						
       
		</div>




		<div class="featured-section">
			<span><i class="fa fa-star"></i> FEATURED CARS </span>
			<div class="featured-auction-list" id="featured-auction-list">
				
				<?php if( !empty( $featuredproducts ) ) : ?>
					<?php $counter = 0; ?>

					<?php foreach( $featuredproducts as $product ) : ?>
						
						<?php $list = get_post_meta( $product ); 
						
						$title = get_the_title($product);
						$lotNumber = $list['lot_number'][0];
						$featured = $list['featured'][0] == '1' ? 'Featured' : '';
						$reserved = $list['offered_without_reserve'][0] == '1' ? 'OWR' : '';
						$nameValue = esc_attr($title . " | " . $lotNumber . "-" . $featured."-".$reserved );

						?>
						
						
						<div class="prodholder" name="<?= $nameValue; ?> ">
							<div class="singleprod">
								

								<a href="<?= get_permalink( $product ) ?>">
									<?php if ( has_post_thumbnail( $product ) ) : $featured_img = get_the_post_thumbnail_url( $product ); ?>
										<img src="<?= $featured_img ?>" class="listimg"  alt="" width="100%" height="380px">
									<?php else : ?>
										<img src="/photos/pcs.jpg" class="listimg"  alt="Photo Coming Soon" width="100%" height="380px">
									<?php endif; ?>
								</a>
								

								<?php if( $list['offered_without_reserve'][0] == '1' ) : ?>
									<div class="reservation">OFFERED WITHOUT RESERVE</div>
								<?php endif; ?>

								<div class="lot-numbers">
									<?php if( !empty( $list['lot_number'][0] )):?>
										<span id="lotnum"><?= $list['lot_number'][0] == '' ? '' : 'LOT '.$list['lot_number'][0]; ?></span>
										<?php //else : ?>
										<!--LOT<span>-</span-->
									<?php endif;?>
								</div>

								
							</div>

							<hr>

							<br>
							<div class="list-detail searchable">

								<div class="title"><a href="<?= get_permalink( $product ) ?>"><?= get_the_title( $product ) ?></a></div>
								<span class="prodTitle" style="display:none;"><?= get_the_title( $product ) ?></span>
								<ul class="features">
									<?php foreach( explode( '|', $list['features'][0] ) as $feature ) : ?>
										<?php if($feature !=""): ?>
											<li><?= $feature ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>

								<a class="link nectar-cta" href="<?= get_permalink( $product ) ?>">
									<span class="link_wrap">
										<span class="link_text">View Photos & Description</span>
									</span>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				
			</div>
		</div>
		
		<hr>

		<?php
			// Pagination settings
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$perPage = 30;
			$totalProducts = count($products);
			$totalPages = ceil($totalProducts / $perPage);

			// Calculate the start index for the current page
			$start = ($page - 1) * $perPage;
			$productsToShow = array_slice($products, $start, $perPage);

			// Pagination display
			echo '<div class="pagination">';

			// Always show the Previous Page Link (disabled if on the first page)
			if ($page > 1) {
				echo '<a href="?page=' . ($page - 1) . '"> < </a>';
			} else {
				echo '<span class="disabled"> < </span>';
			}

			// Static display for pages 1, 2, 3
			for ($i = 1; $i <= 3; $i++) {
				if ($i == $page) {
					echo '<a href="?page=' . $i . '" class="active">' . $i . '</a> ';
				} else {
					if ($i <= $totalPages) {
						echo '<a href="?page=' . $i . '">' . $i . '</a> ';
					} else {
						// If the page number exceeds total pages, display as disabled
						echo '<span class="disabled">' . $i . '</span> ';
					}
				}
			}

			// Always show the Next Page Link (disabled if on the last page)
			if ($page < $totalPages) {
				echo '<a href="?page=' . ($page + 1) . '"> > </a>';
			} else {
				echo '<span class="disabled"> > </span>';
			}

			echo '</div>';
		?>
		
		<div class="auction-list" id="auction-list">
			<?php if( !empty( $products ) ) : ?>
				<?php $counter = 0; ?>

				<?php foreach( $productsToShow as $product ) : ?>
					
					<?php $list = get_post_meta( $product ); 
					
					$title = get_the_title($product);
					$lotNumber = $list['lot_number'][0];
					$featured = $list['featured'][0] == '1' ? 'Featured' : '';
					$reserved = $list['offered_without_reserve'][0] == '1' ? 'OWR' : '';
					$nameValue = esc_attr($title . " | " . $lotNumber . "-" . $featured."-".$reserved );

					?>
					
					
					<div class="prodholder" name="<?= $nameValue; ?> ">
						<div class="singleprod">
							

							<a href="<?= get_permalink( $product ) ?>">
								<?php if ( has_post_thumbnail( $product ) ) : $featured_img = get_the_post_thumbnail_url( $product ); ?>
									<img src="<?= $featured_img ?>" class="listimg"  alt="" width="100%" height="380px">
								<?php else : ?>
									<img src="/photos/pcs.jpg" class="listimg"  alt="Photo Coming Soon" width="100%" height="380px">
								<?php endif; ?>
							</a>
							

							<?php if( $list['offered_without_reserve'][0] == '1' ) : ?>
								<div class="reservation">OFFERED WITHOUT RESERVE</div>
							<?php endif; ?>

							<div class="lot-numbers">
								<?php if( !empty( $list['lot_number'][0] )):?>
									 <span id="lotnum"><?= $list['lot_number'][0] == '' ? '' : 'LOT '.$list['lot_number'][0]; ?></span>
									<?php //else : ?>
									<!--LOT<span>-</span-->
								<?php endif;?>
							</div>

							
						</div>

						<hr>

						<br>
						<div class="list-detail searchable">

							<div class="title"><a href="<?= get_permalink( $product ) ?>"><?= get_the_title( $product ) ?></a></div>
							<span class="prodTitle" style="display:none;"><?= get_the_title( $product ) ?></span>
							<ul class="features">
								<?php foreach( explode( '|', $list['features'][0] ) as $feature ) : ?>
									<?php if($feature !=""): ?>
										<li><?= $feature ?></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>

							<a class="link nectar-cta" href="<?= get_permalink( $product ) ?>">
								<span class="link_wrap">
									<span class="link_text">View Photos & Description</span>
								</span>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			
		</div>
	
		
		<?php
			echo '<div class="pagination">';

			// Always show the Previous Page Link (disabled if on the first page)
			if ($page > 1) {
				echo '<a href="?page=' . ($page - 1) . '"> < </a>';
			} else {
				echo '<span class="disabled"> < </span>';
			}

			// Static display for pages 1, 2, 3
			for ($i = 1; $i <= 3; $i++) {
				if ($i == $page) {
					echo '<a href="?page=' . $i . '" class="active">' . $i . '</a> ';
				} else {
					if ($i <= $totalPages) {
						echo '<a href="?page=' . $i . '">' . $i . '</a> ';
					} else {
						// If the page number exceeds total pages, display as disabled
						echo '<span class="disabled">' . $i . '</span> ';
					}
				}
			}

			// Always show the Next Page Link (disabled if on the last page)
			if ($page < $totalPages) {
				echo '<a href="?page=' . ($page + 1) . '"> > </a>';
			} else {
				echo '<span class="disabled"> > </span>';
			}

			echo '</div>';
		?>
			
		
	</div>
				

	<script>
        // Function to sort child divs
		function sortFeaturedToTop() {
		const parent = document.getElementById('auction-list');
		let childDivs = Array.from(parent.children).filter(child => child.tagName === 'DIV');

		childDivs.sort((a, b) => {
			// Check if divs have class 'prodholder' and name contains 'Featured'
			const isFeaturedA = a.classList.contains('prodholder') && a.getAttribute('name').toLowerCase().includes('featured');
			const isFeaturedB = b.classList.contains('prodholder') && b.getAttribute('name').toLowerCase().includes('featured');

			if (isFeaturedA && !isFeaturedB) {
				return -1;
			}
			if (!isFeaturedA && isFeaturedB) {
				return 1;
			}

			// Sorting by first 4 characters descending
			const firstFourA = a.getAttribute('name').slice(0, 4);
			const firstFourB = b.getAttribute('name').slice(0, 4);
			if (firstFourA > firstFourB) {
				return -1;
			}
			if (firstFourA < firstFourB) {
				return 1;
			}

			// If first 4 characters are equal, sort by 6th character ascending
			const sixthCharA = a.getAttribute('name').charAt(5);
			const sixthCharB = b.getAttribute('name').charAt(5);
			return sixthCharA.localeCompare(sixthCharB);
		});

		// Remove existing child divs
		while (parent.firstChild) {
			parent.removeChild(parent.firstChild);
		}

		// Append sorted child divs
		childDivs.forEach(div => parent.appendChild(div));
		}



        /*function sortDivs(order) {
			const parent = document.getElementById('auction-list');
			let childDivs = Array.from(parent.children).filter(child => child.tagName === 'DIV');

			childDivs.sort((a, b) => {
				// Check if divs have class 'prodtitle' and name contains 'Featured'
				const isFeaturedA = a.classList.contains('prodholder') && a.getAttribute('name').toLowerCase().includes('featured');
				const isFeaturedB = b.classList.contains('prodholder') && b.getAttribute('name').toLowerCase().includes('featured');

				// Prioritize 'Featured' divs
				if (isFeaturedA && !isFeaturedB) {
					return -1;
				}
				if (!isFeaturedA && isFeaturedB) {
					return 1;
				}
			});
		}*/
		function sortDivs1(order){
			const parent = document.getElementById('auction-list');
            let childDivs = Array.from(parent.children).filter(child => child.tagName === 'DIV');

			childDivs.sort((a, b) => {
                const nameMakeA = a.getAttribute('name').toLowerCase().substring(5); // Start from 6th character
                const nameMakeB = b.getAttribute('name').toLowerCase().substring(5); // Start from 6th character
				const nameYearA = a.getAttribute('name').toLowerCase();
				const nameYearB = b.getAttribute('name').toLowerCase();

				const nameLotA = a.getAttribute('name').toLowerCase();
				const partsA = nameLotA.split('|');
					// Take the substring starting from the second character of the part after "|"
					const desiredStringA = parseInt(partsA[1].substring(1),10);
					// Now, 'desiredString' contains the string from the second character onwards after "|"
					console.log(nameLotA+" "+desiredStringA);
				

				const nameLotB = b.getAttribute('name').toLowerCase();
				const partsB = nameLotB.split('|');
					// Take the substring starting from the second character of the part after "|"
					const desiredStringB = parseInt(partsB[1].substring(1),10);
					// Now, 'desiredString' contains the string from the second character onwards after "|"
					console.log(nameLotB+" "+desiredStringB);
				

                if (order === 'makeascending') {
                    return nameMakeA.localeCompare(nameMakeB);
                } 
				else if (order === 'makedescending') {
                    return nameMakeB.localeCompare(nameMakeA);
                }
				else if (order === 'yearascending'){
					return nameYearA.localeCompare(nameYearB);
				}
				else if (order === 'yeardescending'){
					return nameYearB.localeCompare(nameYearA);
				}
				else if(order === 'lotascending'){
					return desiredStringA - desiredStringB; // For ascending
				}
				else if(order === 'lotdescending'){
					return desiredStringB - desiredStringA; // For descending
				}
				else if(order === "---"){
					//sortFeaturedToTop(); // Sort featured items to top on load
					//sortDivs(document.getElementById('sortOrder').value); // Then apply the selected sort order
				}
				else{

				}
            });

            // Remove existing child divs
            while (parent.firstChild) {
                parent.removeChild(parent.firstChild);
            }

            // Append sorted child divs
            childDivs.forEach(div => parent.appendChild(div));

		}

		/*function sortDivs1(order) {
			const parent = document.getElementById('auction-list');
			let childDivs = Array.from(parent.children).filter(child => child.tagName === 'DIV');

			// Separating featured and non-featured divs
			const featuredDivs = childDivs.filter(div => div.classList.contains('prodholder') && div.getAttribute('name').toLowerCase().includes('featured'));
			const nonFeaturedDivs = childDivs.filter(div => !(div.classList.contains('prodholder') && div.getAttribute('name').toLowerCase().includes('featured')));

			// Apply sorting only to non-featured divs
			nonFeaturedDivs.sort((a, b) => {
				const nameMakeA = a.getAttribute('name').toLowerCase().substring(5); // Start from 6th character
                const nameMakeB = b.getAttribute('name').toLowerCase().substring(5); // Start from 6th character
				const nameYearA = a.getAttribute('name').toLowerCase();
				const nameYearB = b.getAttribute('name').toLowerCase();

				const nameLotA = a.getAttribute('name').toLowerCase();
				const partsA = nameLotA.split('|');
				
					// Take the substring starting from the second character of the part after "|"
					const desiredStringA = partsA[1].substring(1);
					// Now, 'desiredString' contains the string from the second character onwards after "|"
					console.log(nameLotA+" "+desiredStringA);
				

				const nameLotB = b.getAttribute('name').toLowerCase();
				const partsB = nameLotB.split('|');
					// Take the substring starting from the second character of the part after "|"
					const desiredStringB = partsB[1].substring(1);
					// Now, 'desiredString' contains the string from the second character onwards after "|"
					console.log(nameLotB+" "+desiredStringB);
				

                if (order === 'makeascending') {
                    return nameMakeA.localeCompare(nameMakeB);
                } 
				else if (order === 'makedescending') {
                    return nameMakeB.localeCompare(nameMakeA);
                }
				else if (order === 'yearascending'){
					return nameYearA.localeCompare(nameYearB);
				}
				else if (order === 'yeardescending'){
					return nameYearB.localeCompare(nameYearA);
				}
				else if(order === 'lotascending'){
					return desiredStringA.localeCompare(desiredStringB);
				}
				else if(order === 'lotdescending'){
					return desiredStringB.localeCompare(desiredStringA);
				}
				else if(order === "---"){
					sortFeaturedToTop(); // Sort featured items to top on load
					//sortDivs(document.getElementById('sortOrder').value); // Then apply the selected sort order
				}
				else{

				}
			});

			// Remove existing child divs
			while (parent.firstChild) {
				parent.removeChild(parent.firstChild);
			}

			// Append featured divs first and then sorted non-featured divs
			[...featuredDivs, ...nonFeaturedDivs].forEach(div => parent.appendChild(div));
		}*/


			// Event listener for the dropdown
			document.getElementById('sortOrder').addEventListener('change', function() {
				sortDivs1(this.value);
			});

			// Wait for the DOM to be fully loaded before initial sort
			document.addEventListener('DOMContentLoaded', () => {
				//sortFeaturedToTop(); // Sort featured items to top on load
				sortDivs1(document.getElementById('sortOrder').value); // Then apply the selected sort order
			});


		// This function will be called when the toggle switch changes state
			function toggleVisibility() {
			// Get all div elements with the class 'prodholder'
			const divs = document.querySelectorAll('.prodholder');

			// Get the toggle switch state
			const isToggled = document.getElementById('toggleSwitch').checked;

			// Loop over each div and check if the name contains 'OWR', case-insensitive
			divs.forEach(div => {
				const nameValue = div.getAttribute('name').toUpperCase(); // Convert the name to uppercase for comparison
				if (isToggled) {
				// Only show the div if it contains 'OWR' when toggled
					if (div.style.display = nameValue.includes('OWR')){
						div.style.setProperty('display', 'block', 'important');
					}
					else{
						div.style.setProperty('display', 'none', 'important');
					}
				} else {
				// When not toggled, show all divs
				div.style.display = 'block';
				}
			});
			}

			// Event listener for the toggle switch
			document.addEventListener('DOMContentLoaded', () => {
			const toggleSwitch = document.getElementById('toggleSwitch');
			if (toggleSwitch) {
				toggleSwitch.addEventListener('change', toggleVisibility);
				// Initialize the display state according to the initial toggle position
				toggleVisibility();
			} else {
				console.error('Toggle switch with ID "toggleSwitch" not found.');
			}
			});

    </script>

<?php get_footer(); ?>