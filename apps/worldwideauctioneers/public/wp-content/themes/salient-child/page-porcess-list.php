
<?php global $wp;  ?>

<?php 

$event_id = "106"; // Example, replace with actual event ID or logic to determine it
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 40;

// Fetch products for the requested page
$offset = ($page - 1) * $items_per_page;

?>

<?php $auction = wwa_pull_listings_by_event( $event_id, 't_inventory', $startingLot, $endingLot ); ?>
<?php $featuredproducts = wwa_get_featured_poducts_by_event( $event_id ); ?>

<?php 

	$sortOrder = $_GET['sort'];
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
	}

?>
		<?php
			// Determine the current page. Default to 1 if not set.
			$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

			// Set the number of items per page.
			$items_per_page = 100;

			// Calculate the offset for the query.
			$offset = ($current_page - 1) * $items_per_page;

			// Get a subset of products based on the current page.
			$paginated_products = array_slice($products, $offset, $items_per_page);
		?>
		<div class="auction-list-holder" id="auction-list-holder">
	
			<div class="auction-list" id="auction-list">
		
				<?php if( !empty( $products ) ) : ?>
					<?php $counter = 0; ?>

					<?php foreach( $products as $product ) : ?>
						
						<?php $list = get_post_meta( $product ); 
						
						$title = get_the_title($product);
						$lotNumber = $list['lot_number'][0];
						$featured = $list['featured'][0] == '1' ? 'Featured' : '';
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
							<br/>
							<?php $counter++; ?>
						</div>
						
					<?php endforeach; ?>
					
				<?php endif; ?>
			
			
				<span id="countainer" style="display:none"><?php echo $counter; ?></span>
				

				<script>
					var getcount = document.getElementById("countainer").textContent;
					var showcount = document.getElementById("resultCount");
					var showtext = "Showing "+getcount+" listing(s)";
					showcount.innerHTML = showtext;
				</script>

			
				
		</div>
										

			

			

			


		
			
		
	
			
				
	


