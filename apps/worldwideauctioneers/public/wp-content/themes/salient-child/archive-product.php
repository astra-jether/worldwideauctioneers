<?php get_header(); 



nectar_page_header( $post->ID );
$nectar_fp_options = nectar_get_full_page_options();
echo do_shortcode('
[vc_row type="full_width_background" full_screen_row_position="middle" column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" bg_image="https://listings.worldwideauctioneers.com/wp-content/uploads/Duese-1.jpg" bg_position="center center" background_image_loading="default" bg_repeat="no-repeat" scene_position="center" top_padding="2%" constrain_group_1="yes" bottom_padding="2%" text_color="light" text_align="left" row_border_radius="none" row_border_radius_applies="bg" overflow="visible" color_overlay="rgba(0,0,0,0.87)" advanced_gradient_angle="0" overlay_strength="0.95" gradient_direction="left_t_to_right_b" shape_divider_color="#ffffff" shape_divider_position="bottom" shape_divider_height="180" bg_image_animation="none" video_mute="true" shape_type="curve" gradient_type="default"]
	
	[vc_column column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" centered_text="true" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" column_position="default" gradient_direction="left_to_right" overlay_strength="0.3" width="1/2" tablet_width_inherit="default" tablet_text_alignment="default" phone_text_alignment="default" animation_type="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"]
		[image_with_animation image_url="https://listings.worldwideauctioneers.com/wp-content/uploads/WW_Scottsdale_BlueText.png" image_size="full" animation_type="entrance" animation="None" hover_animation="none" alignment="center" border_radius="none" box_shadow="none" image_loading="default" max_width="75%" max_width_mobile="default"]
	[/vc_column]
	
	[vc_column column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" column_position="default" gradient_direction="left_to_right" overlay_strength="0.3" width="1/2" tablet_width_inherit="default" tablet_text_alignment="default" phone_text_alignment="default" animation_type="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"]
		
		[vc_column_text] <h2 style="text-align: center;">Inventory</h2>[/vc_column_text]
		[divider line_type="Small Line" line_alignment="center" line_thickness="2" divider_color="extra-color-3" custom_height="30px"]
	[/vc_column]

[/vc_row]

[vc_row type="in_container" full_screen_row_position="middle" column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" scene_position="center" text_color="dark" text_align="left" row_border_radius="none" row_border_radius_applies="bg" overflow="visible" overlay_strength="0.3" gradient_direction="left_to_right" shape_divider_position="bottom" bg_image_animation="none"]
	
	[vc_column column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" column_position="default" gradient_direction="left_to_right" overlay_strength="0.3" width="1/1" tablet_width_inherit="default" tablet_text_alignment="default" phone_text_alignment="default" animation_type="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"]
		[my_custom_menu name="inventory-menu"]
	[/vc_column]

[/vc_row]

');
?>
	<?php $event_id = wwa_get_auction_id( home_url( "{$wp->request}/" ) ); ?>
	<?php global $wp; 

	$total_items = count($products);
	$items_per_page = 10;
	$total_pages = ceil($total_items / $items_per_page);

	

	if (isset($_REQUEST['auctionid'])){
        $id = $_REQUEST['auctionid'];
    } else {
        $id = get_field('auction_id');
    }

	/*if(isset($_SERVER['QUERY_STRING'])) {
		// Get the query string
		$queryString = $_SERVER['QUERY_STRING'];
	
		// Parse the query string to get parameters
		parse_str($queryString, $queryParams);
	
		// Check if 'sort' is set in the query parameters
		if(isset($queryParams['sort'])) {
			// Get the value of 'sort'
			$sortOrder = $queryParams['sort'];
			echo $sortOrder; // Output: value of 'sort'
		} else {
			
		}
	} 
	
	
	if ($sortOrder == "lotascending"){
		$products = wwa_get_poducts_by_event_lot_ascending( $event_id );
	}
	else if ($sortOrder == "lotdescending"){
		$products = wwa_get_poducts_by_event_lot_descending( $event_id );	
	}
	else if ($sortOrder == "makeascending"){
		$products = wwa_get_poducts_by_event_make_ascending( $event_id );
	}
	else if ($sortOrder == "makedescending"){
		$products = wwa_get_poducts_by_event_make_descending( $event_id );
	}
	else if ($sortOrder == "yearascending"){
		$products = wwa_get_poducts_by_event_year_ascending( $event_id );
	}
	else if ($sortOrder == "yeardescending"){
		$products = wwa_get_poducts_by_event_year_descending( $event_id );
	}
	else{
		$products = wwa_get_poducts_by_event( $event_id );
	}*/
	
	?>
	
	
	

	<?php //$auction = wwa_pull_listings_by_event( $event_id, 't_inventory', $startingLot, $endingLot ); ?>

	<?php $products = wwa_get_poducts_by_event( $event_id ); ?>

	<?php $featuredproducts = wwa_get_featured_poducts_by_event( $event_id ); ?>

	
	<style>	
		#loadingOverlay {
			position: fixed; /* Positioned relative to the viewport */
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			background: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
			z-index: 1000; /* Ensures it's above other content */
			display: flex;
			justify-content: center;
			align-items: center;
		}

		#loading {
			position: fixed; /* or absolute */
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 1000; /* Ensures it's above other content */
			background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
			padding: 20px;
			border-radius: 5px;
		}

	
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

	<div class="container main-content" id="container-main-content">
		<!--h1>The Scottsdale Auction Inventory Search </h1-->
		<div id="loadingOverlay" style="display: none;">
			<div id="loading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); height:50px; width:50px;">
				<img src="https://listings.worldwideauctioneers.com/wp-content/uploads/34338d26023e5515f6cc8969aa027bca.gif" alt="Loading...">
			</div>
		</div>
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
					
					<div class="toggle-row">
						<div class="toggle-box">
							<label class="switch">
								<input type="checkbox" id="toggleSwitch">
								<span class="slider round"></span>
							</label>
							<span>OFFERED WITHOUT RESERVE</span>
						</div>

						<div class="toggle-box">
							<label class="switch">
								<input type="checkbox" id="toggleSwitch2">
								<span class="slider round"></span>
							</label>
							<span>HIDE FEATURED CARS</span>
						</div>

						<div class="toggle-box">
							<label class="switch">
								<input type="checkbox" id="toggleSwitch3">
								<span class="slider round"></span>
							</label>
							<span>STILL ON SALE</span>
						</div>
					</div>
				</div>
			</div>						
		</div>




		<div class="featured-section" id="featured-section">
			<span><i class="fa fa-star"></i> FEATURED CARS </span>
			<div class="featured-auction-list" id="featured-auction-list">
				
				<?php if( !empty( $featuredproducts ) ) : ?>
					<?php $counter = 0; ?>

					<?php foreach( $featuredproducts as $product ) : ?>
						
						<?php $list = get_post_meta( $product ); 
						
						$title = get_the_title($product);
						$lotNumber = $list['lot_number'][0];
						$reference_num = $list['reference_no'][0];
						$featured = $list['featured'][0] == '1' ? 'Featured' : '';
						$reserved = $list['offered_without_reserve'][0] == '1' ? 'OWR' : '';
						$nameValue = esc_attr($title . " | " . $lotNumber . "-" . $featured."-".$reserved );

						?>
						
						
						<div class="prodholder" name="<?= $nameValue; ?> ">
							<div class="singleprod">
								

								<a href="<?= get_permalink( $product ) ?>">
								<img src='<?= "/photos/106/{$reference_num}/1.jpg" ?>' class="listimg"  alt="Photo Coming Soon" width="100%" height="380px">
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

	
		
									
		<div class="auction-list-holder" id="auction-list-holder">
		
			<?php 
				// Pagination settings
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$perPage = 40;
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
				for ($i = 1; $i <= $totalPages; $i++) {
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
						$reference_num = $list['reference_num'][0];
						$reserved = $list['offered_without_reserve'][0] == '1' ? 'OWR' : '';
						$stillOnSale = $list['still_on_sale'][0] == '1' ? 'OnSale' : '';
						$nameValue = esc_attr($title . " | " . $lotNumber . "-" . $featured."-".$reserved."-".$stillOnSale );

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

								

								
							</div>

							

							<br>
							<div class="list-detail searchable">
								<div class="lot-numbers">
									<?php if( !empty( $list['lot_number'][0] )):?>
										<span id="lotnum"><?= $list['lot_number'][0] == '' ? '' : 'LOT '.$list['lot_number'][0]; ?></span>
										<?php //else : ?>
										<!--LOT<span>-</span-->
									<?php endif;?>
								</div>
								<hr>
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
						<?php $counter++; ?>
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
	
		
		
			
		
	</div>
				

	<script>

			// Event listener for the dropdown
			document.getElementById('sortOrder').addEventListener('change', function() {
				var checkbox = document.getElementById('toggleSwitch2');
				var featuredSection = document.querySelector('.featured-section');

				//var selectedValue = this.value; // Getting the selected value
    			//window.location.href = '/inventory/?sort=' + encodeURIComponent(selectedValue);
				checkbox.checked = true;
					// Check the state of the checkbox
					if(checkbox.checked) {
						// If checked, hide the featured section
						featuredSection.style.display = 'none';
					} else {
						// If unchecked, show the featured section
						featuredSection.style.display = 'inline-block';
					}
			
					var selectedOption = this.value;
					//alert(selectedOption);
					document.getElementById('loadingOverlay').style.display = 'flex';

					fetch('/porcess-list/?sort=' + selectedOption, {
						method: 'GET'
					})
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.text(); // Assuming the server responds with HTML
					})
					.then(html => {
						// Hide the loading overlay and indicator after fetching data
						document.getElementById('loadingOverlay').style.display = 'none';
						document.getElementById('auction-list-holder').innerHTML = html;
					})
					.catch(error => {
						// Hide the loading overlay and indicator on error
						document.getElementById('loadingOverlay').style.display = 'none';
						console.error('Failed to fetch data: ', error);
					});

				//sortDivs1(document.getElementById('sortOrder').value);
			});


			// Wait for the DOM to be fully loaded before initial sort
			document.addEventListener('DOMContentLoaded', () => {
				
				var loadingOverlay = document.getElementById('loadingOverlay');

				// Function to show/hide loading overlay
				function toggleLoading(show) {
					if (loadingOverlay) {
						loadingOverlay.style.display = show ? 'flex' : 'none';
					} else {
						console.error('Loading overlay element not found!');
					}
				}
			});


		// This function will be called when the toggle switch changes state
			function toggleVisibility() {
				// Get all div elements with the class 'prodholder'
				const divs = document.querySelectorAll('.auction-list .prodholder');

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
			function toggleVisibility3() {


				/*var checkbox3 = document.getElementById('toggleSwitch2');
				var featuredSection1 = document.querySelector('.featured-section');
				checkbox3.checked = true;
					// Check the state of the checkbox
					if(checkbox.checked) {
						// If checked, hide the featured section
						featuredSection1.style.display = 'none';
					} else {
						// If unchecked, show the featured section
						featuredSection1.style.display = 'inline-block';
					}
				*/
				

				
				// Get all div elements with the class 'prodholder'
				const divs1 = document.querySelectorAll('.auction-list .prodholder');

				// Get the toggle switch state
				const isToggled3 = document.getElementById('toggleSwitch3').checked;

				// Loop over each div and check if the name contains 'OWR', case-insensitive
				divs1.forEach(div => {
					const nameValue = div.getAttribute('name').toUpperCase(); // Convert the name to uppercase for comparison
					if (isToggled3) {
					// Only show the div if it contains 'OWR' when toggled
						if (div.style.display = nameValue.includes('ONSALE')){
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
				const toggleSwitch2 = document.getElementById('toggleSwitch2');
				const toggleSwitch3 = document.getElementById('toggleSwitch3');
				//const isToggled2 = document.getElementById('toggleSwitch2').checked;
				const featured = document.getElementById('featured-section');

				//toggleSwitch2.checked = true;
				//toggleSwitch3.checked = true;

				if (toggleSwitch) {
					toggleSwitch.addEventListener('change', toggleVisibility);
					// Initialize the display state according to the initial toggle position
					toggleVisibility();
				} 
				else {
					console.error('Toggle switch with ID "toggleSwitch" not found.');
				}

				if(toggleSwitch3){
					toggleSwitch3.addEventListener('change', toggleVisibility3);
					toggleVisibility3();
					console.log('stillonsale');
				}
				else {
					console.error('Toggle switch with ID "toggleSwitch3" not found.');
				}
				
				if(toggleSwitch2){
					var checkbox = document.getElementById('toggleSwitch2');
		
					// Listen for click events on the checkbox
					checkbox.addEventListener('change', function() {
						// Get the featured section div
						var featuredSection = document.querySelector('.featured-section');
						
						// Check the state of the checkbox
						if(checkbox.checked) {
							// If checked, hide the featured section
							featuredSection.style.display = 'none';
						} else {
							// If unchecked, show the featured section
							featuredSection.style.display = 'inline-block';
						}
					});
				}
			});	
    </script>

<?php get_footer(); ?>