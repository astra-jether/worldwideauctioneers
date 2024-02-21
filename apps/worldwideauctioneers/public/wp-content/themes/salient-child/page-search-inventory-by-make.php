<?php get_header();

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

<?php global $wp;  ?>

<?php $event_id = $_GET['eventID'] ?>

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
			</style>

<?php
/* template name: Search Inventory By Make */

if (isset($_GET['make']) && !empty($_GET['make'])) {
    $searchTerm = sanitize_text_field($_GET['make']);
	echo '<div class="container main-content">'; ?>

	<!--h1>The Scottsdale Auction Inventory Search </h1-->
		
	<div class="auction-filter">
			<div class="col span_12" style="margin:1rem 0;">
				<div class="searchForm">
					<div class="searchFormTitle">
						<h6><strong>Search:</strong></h6>
					</div>
					<form action="/search-inventory/" method="GET">
						<div class="form-inputs">
							<input type="text" id="textSearchers1" placeholder="Type here to search by make " name="searchTerm">
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
											if( $row['smake'] == $searchTerm ){
												echo '<option value="' . htmlspecialchars($row['smake']) . '" selected>' . htmlspecialchars($row['smake']) . '</option>';
											}
											else{
											echo '<option value="' . htmlspecialchars($row['smake']) . '">' . htmlspecialchars($row['smake']) . '</option>';
											}
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
				</div>


			</div>						
       
		</div>


        <div id="resultCountDiv"><span id="resultCount"></span></div>
    <?php echo '<div class="auction-list" id="auction-list" >'; ?>

                    <style>
						/*.auction-list {
							display: grid;
							grid-template-columns: 1fr 1fr;
							gap: 10px;
							float:left !important;
						}*/
						
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
			<?php if( !empty( $products ) ) : ?>
				<?php $counter = 0; ?>
				<?php foreach( $products as $product ) : ?>
					
					<?php $list = get_post_meta( $product ); ?>
					<?php if (stripos(get_the_title($product),$searchTerm) !== false ): ?>
						<div class="prodholder" name="<?= get_the_title( $product )." | ".$list['lot_number'][0]; ?>">
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
										LOT <span id="lotnum"><?= $list['lot_number'][0] == '' ? rand( 1, 100 ) : $list['lot_number'][0]; ?></span>
										<?php //else : ?>
										<!--LOT<span>-</span-->
									<?php endif;?>
								</div>

								
							</div>

							<hr><br>

							<div class="list-detail searchable">

								<div class="title"><a href="<?= get_permalink( $product ) ?>"><?= get_the_title( $product ) ?></a></div>
									<span class="prodTitle" style="display:none;"><?= get_the_title( $product ) ?></span>
									<ul class="features">
										<?php foreach( explode( '|', $list['features'][0] ) as $feature ) : ?>
											<li><?= $feature ?></li>
										<?php endforeach; ?>
									</ul>

									<a class="link nectar-cta" href="<?= get_permalink( $product ) ?>">
										<span class="link_wrap">
											<span class="link_text">View Photos & Description</span>
										</span>
									</a>
							</div>
							<?php $counter++; ?>
						</div>
					<? elseif ($searchTerm === "Select All"): ?>
						<div class="prodholder" name="<?= get_the_title( $product ) ?>">
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
										LOT <span id="lotnum"><?= $list['lot_number'][0] == '' ? rand( 1, 100 ) : $list['lot_number'][0]; ?></span>
										<?php //else : ?>
										<!--LOT<span>-</span-->
									<?php endif;?>
								</div>

								
							</div>

							<hr><br>

							<div class="list-detail searchable">

								<div class="title"><a href="<?= get_permalink( $product ) ?>"><?= get_the_title( $product ) ?></a></div>
									<span class="prodTitle" style="display:none;"><?= get_the_title( $product ) ?></span>
									<ul class="features">
										<?php foreach( explode( '|', $list['features'][0] ) as $feature ) : ?>
											<li><?= $feature ?></li>
										<?php endforeach; ?>
									</ul>

									<a class="link nectar-cta" href="<?= get_permalink( $product ) ?>">
										<span class="link_wrap">
											<span class="link_text">View Photos & Description</span>
										</span>
									</a>
							</div>
							<?php $counter++; ?>
						</div>
                    <?php endif; ?>
				<?php endforeach; ?>
			<?php endif; 
            echo '</div></div>';
            ?>
		</div>
<?php }
?>


<span id="countainer" style="display:none"><?php echo $counter; ?></span>
<span id="countainer1" style="display:none"><?php echo $searchTerm; ?></span>

<script>
	var getcount = document.getElementById("countainer").textContent;
	var searchterm = document.getElementById("countainer1").textContent;
	var showcount = document.getElementById("resultCount");
	var showtext = "Showing "+getcount+" result(s) of '"+searchterm+"'";
	showcount.innerHTML = showtext;
</script>

<script>
        // Function to sort child divs
        function sortDivs(order) {
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

        // Event listener for the dropdown
        document.getElementById('sortOrder').addEventListener('change', function() {
            sortDivs(this.value);
        });

        // Wait for the DOM to be fully loaded before initial sort
        document.addEventListener('DOMContentLoaded', () => {
            sortDivs(document.getElementById('sortOrder').value);
        });
    </script>
<?php get_footer(); ?>