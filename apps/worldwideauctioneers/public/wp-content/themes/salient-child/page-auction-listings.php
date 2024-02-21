<?php
/* template name: Auctions Listing */

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
nectar_page_header( $post->ID );
$nectar_fp_options = nectar_get_full_page_options();

?>

<div class="container-wrap">
	<div class="<?php if ( $nectar_fp_options['page_full_screen_rows'] !== 'on' ) { echo 'container'; } ?> main-content">
		<div class="row">
			<?php
				nectar_hook_before_content(); 
				
				if ( have_posts() ) :
					while ( have_posts() ) :
						
						the_post();
						the_content();
						
					endwhile;
				endif;
				
				nectar_hook_after_content();
				
				$eventID = get_field('auction_id');
				$listingType = get_field('listing_type');
				$startingLot = get_field('starting_lot');
				$endingLot = get_field('ending_lot');
			?>
		
			<?php if($listingType == 1){ ?>
			<div class="col span_12" style="margin:1rem 0;">
				<label for="textSearcher"><strong>Search</strong>
					<input type="text" id="textSearchers" placeholder="Type here to search by lot number, name, or description" />
				</label>
				<div class="mycolm">
					<label for="textSearcher" ><strong>Filter by Make</strong>
						<select id="textSearcher">
							<option value="">Select Make</option>
							<option value="Alfa Romeo">Alfa Romeo</option>
							<option value="AMC">AMC</option>
							<option value="Aston Martin">Aston Martin</option>
							<option value="Auburn ">Auburn</option>
							<option value="Bentley">Bentley</option>
							<option value="BMW">BMW</option>
							<option value="Brewster-Ford">Brewster-Ford</option>
							<option value="Buick">Buick</option>
							<option value="Cadillac">Cadillac</option>
							<option value="Chevrolet">Chevrolet</option>
							<option value="Chrysler">Chrysler</option>
							<option value="Cord">Cord</option>
							<option value="Delahaye">Delahaye</option>
							<option value="Dodge">Dodge</option>
							<option value="Ferrari">Ferrari</option>
							<option value="Ford">Ford</option>
							<option value="GMC">GMC</option>
							<option value="Graham">Graham</option>
							<option value="Hudson">Hudson</option>
							<option value="Intermeccanica">Intermeccanica</option>
							<option value="Isotta">Isotta</option>
							<option value="Jaguar">Jaguar</option>
							<option value="Lamborghini">Lamborghini</option>
							<option value="LaSalle">LaSalle</option>
							<option value="Lincoln">Lincoln</option>
							<option value="Lozier">Lozier</option>
							<option value="Mercedes-Benz">Mercedes-Benz</option>
							<option value="Mercury">Mercury</option>
							<option value="Muntz">Muntz</option>
							<option value="Packard">Packard</option>
							<option value="Plymouth">Plymouth</option>
							<option value="Pontiac">Pontiac</option>
							<option value="Porsche">Porsche</option>
							<option value="Rolls-Royce">Rolls-Royce</option>
							<option value="Shelby">Shelby</option>
							<option value="Studebaker">Studebaker</option>
							<option value="Sunbeam">Sunbeam</option>
							<option value="Tesla">Tesla</option>
							<option value="Toyota">Toyota</option>
							<option value="Triking">Triking</option>
							<option value="Volkswagen">Volkswagen</option>
							<option value="Volvo">Volvo</option>
							<option value="Willys">Willys</option>
						</select>
					</label>

					
					<label for="textSearcher"><strong>Filter by Day or Collection</strong>
						<select id="collection" >
							<option value="">Select Day or Collection</option>
							<option value="Selling on Saturday Evening">Saturday Evening</option>
							<option value="Selling on Saturday">Saturday</option>
							<option value="Selling on Friday">Friday</option>
							<option value="Selling on Thursday">Thursday</option>
	                                           	<option value="Offered Without Reserve">Offered Without Reserve</option>
							<option value="From The Tom Haag Estate Collection">The Tom Haag Estate Collection</option>
							<option value="From The Head Brothers' Collection">The Head Brothers' Collection</option>
						</select>
					</label>

			

				</div>
				<script>
					jQuery(document).ready(function() {
						jQuery("#textSearcher").on('change', function(){
							var searchString = jQuery(this).val().toLowerCase();
							
							if (searchString != "") {
								jQuery("#filterArea").empty();
								
								var counter = 0;
								var currentContainer = jQuery('<div class="col span_12"></div>');
								
								jQuery(".searchable").each(function(index, item){
									if(jQuery(item).html().toLowerCase().includes(searchString)){
                                        jQuery(currentContainer).append(jQuery(item).parent().clone());

										counter++;
										
										if ((counter % 3) == 0) {
											jQuery("#filterArea").append(currentContainer);
											currentContainer = jQuery('<div class="col span_12"></div>');
										}
                                    }
								});
								
								if(counter == 0){
                                    jQuery("#filterArea").append('<p>No results found.</p>');
                                }else{
									jQuery("#filterArea").append(currentContainer);
									jQuery("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
								}
								
								jQuery("#filterArea").slideDown();
							}else{
								jQuery("#filterArea").slideUp();
								jQuery("#filterArea").empty();
							}
						});

						jQuery("#textSearchers").on('keyup', function(){
							var searchString = jQuery(this).val().toLowerCase();
							
							if (searchString != "") {
								jQuery("#filterArea").empty();
								
								var counter = 0;
								var currentContainer = jQuery('<div class="col span_12"></div>');
								
								jQuery(".searchable").each(function(index, item){
									if(jQuery(item).html().toLowerCase().includes(searchString)){
                                        jQuery(currentContainer).append(jQuery(item).parent().clone());

										counter++;
										
										if ((counter % 3) == 0) {
											jQuery("#filterArea").append(currentContainer);
											currentContainer = jQuery('<div class="col span_12"></div>');
										}
                                    }
								});
								
								if(counter == 0){
                                    jQuery("#filterArea").append('<p>No results found.</p>');
                                }else{
									jQuery("#filterArea").append(currentContainer);
									jQuery("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
								}
								
								jQuery("#filterArea").slideDown();
							}else{
								jQuery("#filterArea").slideUp();
								jQuery("#filterArea").empty();
							}
						});

						jQuery("#collection").on('change', function(){
							var searchString = jQuery(this).val().toLowerCase();
							
							if (searchString != "") {
								jQuery("#filterArea").empty();
								
								var counter = 0;
								var currentContainer = jQuery('<div class="col span_12"></div>');
								
								jQuery(".searchable").each(function(index, item){
									if(jQuery(item).html().toLowerCase().includes(searchString)){
                                        jQuery(currentContainer).append(jQuery(item).parent().clone());

										counter++;
										
										if ((counter % 3) == 0) {
											jQuery("#filterArea").append(currentContainer);
											currentContainer = jQuery('<div class="col span_12"></div>');
										}
                                    }
								});
								
								if(counter == 0){
                                    jQuery("#filterArea").append('<p>No results found.</p>');
                                }else{
									jQuery("#filterArea").append(currentContainer);
									jQuery("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
								}
								
								jQuery("#filterArea").slideDown();
							}else{
								jQuery("#filterArea").slideUp();
								jQuery("#filterArea").empty();
							}
						});
					});
				</script>
			</div>
			
			<div id="filterArea" class="col span_12" style="display:none; padding: 1rem 0; background-color: #eee; margin-bottom:1rem;"></div>
			<?php } ?>
			
			<?php	
				if($listingType == 1){
					echo '<div id="mainArea">';
					$carlist = wwa_pull_listings_by_event($eventID, 't_inventory', $startingLot, $endingLot);
					
					foreach ($carlist as $item) {
						//Look for our fancy new links that we get from syncing
						$metaQueryAnd = ['relation' => 'AND'];
                        $metaQueryAnd[] = array(
                            'key' => 'auctionID',
                            'value' => $eventID,
                            'compare' => '=',
                        );
                        $metaQueryAnd[] = array(
                            'key' => 'referenceID',
                            'value' => $item['sreference'],
                            'compare' => '=',
                        );
                        
                        $args = array(
                            'meta_query' => $metaQueryAnd,
                            'numberposts' => '1',
                            'post_type' => 'listings',
                            'post_status' => 'publish',
                        );
                        
                        $postsByID = wp_get_recent_posts($args);
						
						echo ('<div class="col span_12" style="margin:1rem 0;">
							  <div class="col span_4">');
						
							//Echo link to listing view
							if($postsByID){
								echo '<a href="'.get_permalink($postsByID[0]['ID']).'">';
							}else{
								echo '<a href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'">';
							}
							
							if (file_exists($_SERVER['DOCUMENT_ROOT'].'/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg')) {
								echo('<img style="margin-top:1rem;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
							} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG')) {
								echo('<img style="margin-top:1rem;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
							}else {
								echo('<img style="margin-top:1rem;" src="/photos/pcs.jpg" class="image-round" alt="Photo Coming Soon" width="300px">');
							}
							echo('</a></div>
							  <div class="col span_8 searchable">');
						if ($item['slotnumber']>"") { if ($eventID == 75) { echo('<h4>Ref # '.$item['slotnumber'].'</h4>'); } else { echo('<h4>Lot '.$item['slotnumber'].'</h4>');}}
						if ($item['sday']>"") {echo('<font style="font-weight: bold;">Selling on ' . $item['sday'] . '</font><br>');}
						if ($item['sshortdescription']>"") {echo ('<font style="font-style: italic;">'.$item['sshortdescription'].'</font><br>');}
						
						if($postsByID){
							echo '<a href="'.get_permalink($postsByID[0]['ID']).'"><h6 class="text-primary">'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'</h6></a>';
						}else{
							echo ('<a href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'"><h6 class="text-primary">'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'</h6></a>');
						}
						
						if ($item['breserve'] == 0){echo('<font style="color: #a71c20; font-weight: bold;">Offered Without Reserve</font><br />');}
						
						if ($item['sfeatureitems']>""){
							//echo '<span class="">';
							$sfeature = str_replace('|','<br>&bull;',$item['sfeatureitems']);
							//$sfeature = str_replace('')
							echo('&bull; '.$sfeature);
							//echo '</span>';
						}
						echo '<br /><br /><div class="nectar-cta" data-style="see-through" data-alignment="left" data-text-color="custom"><h4 style="color: #275b84;"><span class="text"></span><span class="link_wrap">';
						if($postsByID){
							echo '<a class="link_text" href="'.get_permalink($postsByID[0]['ID']).'">View More Info<span class="arrow"></span></a>';
						}else{
							echo '<a class="link_text" href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'">View More Info<span class="arrow"></span></a>';
						}
						echo('</span></h4></div></div></div>');
					}
					echo '</div>';
				}
			?>
			
			</div><!--/row-->
			<?php if($listingType == 2){ ?>
			<div class="row">
				
				<div class="col span_12" style="margin:1rem 0;">
					<input type="text" id="textSearcher" placeholder="Type here to filter by lot number, name, or description" />
					<script>
						jQuery(document).ready(function() {
							jQuery("#textSearcher").on('keyup', function(){
								var searchString = jQuery(this).val().toLowerCase();
								
								if (searchString != "") {
									jQuery("#filterArea").empty();
									
									var counter = 0;
									var currentContainer = jQuery('<div class="col span_12"></div>');
									
									jQuery(".searchable").each(function(index, item){
										if(jQuery(item).html().toLowerCase().includes(searchString)){
											jQuery(currentContainer).append(jQuery(item).clone());
	
											counter++;
											
											if ((counter % 3) == 0) {
												jQuery("#filterArea").append(currentContainer);
												currentContainer = jQuery('<div class="col span_12"></div>');
											}
										}
									});
									
									if(counter == 0){
										jQuery("#filterArea").append('<p>No results found.</p>');
									}else{
										jQuery("#filterArea").append(currentContainer);
										jQuery("#filterArea").prepend('<h3 style="font-weight: bold; text-align: center;">Matched Items</h3>');
									}
									
									jQuery("#filterArea").slideDown();
								}else{
									jQuery("#filterArea").slideUp();
									jQuery("#filterArea").empty();
								}
							});
						});
					</script>
				</div>
				
				<div id="filterArea" class="col span_12" style="display:none; padding: 1rem 0; background-color: #eee; margin-bottom:1rem;"></div>
				
				<div id="mainArea">
					<h3 style="font-weight: bold; text-align: center;">Full Inventory</h3>
					<?php
						$memlist = wwa_pull_memorabilia_listings($eventID, 't_inventory', $startingLot, $endingLot);
						$counter = 0;
						foreach ($memlist as $item) {
							if($counter == 0){
								echo ('<div class="col span_12">');
							}
							
							//echo ('<div class="col span_4 searchable"><a href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'"><img style="object-fit: cover; width: 300px; height: 300px; margin: 0 auto; display: block;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg" class="image-round" alt="'.$item['smake'].$item['smodel'].$item['sstyle'].'" width="300px"></a>');
							if (file_exists(ABSPATH.'/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg')) {
								echo ('<div class="col span_4 searchable"><a href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'"><img style="object-fit: contain; width: 300px; height: 300px; margin: 0 auto; display: block;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg" class="image-round" alt="'.$item['smake'].$item['smodel'].$item['sstyle'].'" width="300px"></a>');
							} elseif (file_exists(ABSPATH.'/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG')) {
								echo ('<div class="col span_4 searchable"><a href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'"><img style="object-fit: contain; width: 300px; height: 300px; margin: 0 auto; display: block;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG" class="image-round" alt="'.$item['smake'].$item['smodel'].$item['sstyle'].'" width="300px"></a>');
							}else {
								echo('<div class="col span_4 searchable"><img style="object-fit: contain; width: 300px; height: 300px; margin: 0 auto; display: block;" src="/photos/pcs.jpg" class="image-round" alt="Photo Coming Soon" width="300px">');
							}
							if ($item['slotnumber']>"") { echo('<h4>Lot# '.$item['slotnumber'].'</h4>');}
							if ($item['sday']>"") {echo('<font style="font-weight: bold;">Selling on ' . $item['sday'] . '</font><br>');}
							if ($item['sshortdescription']>"") {echo ('<font style="font-style: italic;">'.$item['sshortdescription'].'</font><br>');}
							echo ('<a href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'"><h6 class="text-primary">'.$item['smake'].$item['smodel'].$item['sstyle'].'</h6></a>');
							if ($item['breserve'] == 0){echo('<font style="color: #a71c20; font-weight: bold;">Offered Without Reserve</font><br />');}
							if ($item['slongdescription']>"") {echo ('<font style="font-style: italic;">'.$item['slongdescription'].'</font><br>');}
							if ($item['sfeatureitems']>""){
								$sfeature = str_replace('|','<br>&bull;',$item['sfeatureitems']);
								//$sfeature = str_replace('')
								echo('&bull; '.$sfeature);
							}
							echo '<br /><br /><div class="nectar-cta" data-style="see-through" data-alignment="left" data-text-color="custom"><h4 style="color: #275b84;"><span class="text"></span><span class="link_wrap"><a class="link_text" href="/car-details/?id='.$eventID.'&rid='.$item['sreference'].'">View More Info<span class="arrow"></span></a></span></h4></div></div>';
							
							if($counter == 2){
								echo('</div>');
							}
							
							if($counter < 2){
								$counter++;
							}else{
								$counter = 0;
							}
						}
					?>
				</div>
				
			</div>
			<?php } //End if listing type 2 ?>
		
	</div><!--/container-->
</div><!--/container-wrap-->

<?php get_footer(); ?>