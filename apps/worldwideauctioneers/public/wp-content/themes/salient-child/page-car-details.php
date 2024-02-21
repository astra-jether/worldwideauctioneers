<?php
/*template name: Car Details*/

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
				
				$eventID = $_REQUEST['id'];
				$referenceID = $_REQUEST['rid'];
				
				if($eventID == 77){
					$tablename = "t_inventory2";
				}else{
					$tablename = "t_inventory";
				}
				
				if(!isset($eventID) || (!isset($referenceID))){
					echo "<p>You must provide an event ID and reference ID to look up an item. Please visit any auction and click a listing to view details.</p>";
				}else{
					$car = wwa_pull_single_listing($eventID, $referenceID, $tablename);
				?>
				
				<?php
				//Look for the listings page for this auction
				
				$args = array(
					'post_status' => 'publish',
					'posts_per_page'   => 1,
					'post_type' => 'page',
					'meta_query' => array(
						array(
							'key' => 'auction_id',
							'value' => $eventID,
							'compare' => '=',
						)
					)
				);
				
				$recent_posts = wp_get_recent_posts($args);
				
				?>
				
				<div class="text-center">
					<?php
						if ($eventID <> '29'){
							echo('<h1 class="bg-white image-round">');
								 foreach(get_field('auction_titles') as $title){
									if($title['auction_id'] == $eventID){
										echo $title['auction_name'] . "<br />";
									}
								 }
							echo('</h1>');
						}
					?>
				  <h1 class="bg-white image-round">
					  <?php 
						if($eventID == 29){
							echo('<span style="padding:20px 0px;">'.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle'].'</span>');
						} else {
							if ($eventID == 75) { echo('<span style="color: black; font-size:65%;">Ref # '.$car['slotnumber'].'</span><br>'); } else { if ($car['slotnumber'] > "") {echo('<span style="color: black; font-size:65%;">Lot '.$car['slotnumber'].'</span><br>');}}
							if($car['sshortdescription'] > '') {echo('<div class="blog-post-time"><span>'. $car['sshortdescription'] . '</span></div>'); } 
							if ($car['ncategoryid']==1){echo('<span style="font-size:75%;">'.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle']);} else {echo('<span style="font-size:75%;">'.$car['smake'].$car['smodel'].$car['sstyle']);} 
							if($car['breserve'] == 0){ 
								echo('<br><font class="offered-without-reserve" style="font-size:70%;">OFFERED WITHOUT RESERVE</font>');
							} 
							echo('</span>');
						}
						?>
					</h1>
				</div>
            
				<div class="range range-md-reverse bg-white image-round">
					<div class="col span_4">
					  <h4>gallery</h4>
					  <!-- PhotoSwipe-->
					  <div class="row">
						  <?php
								if ($car['ncategoryid']==1) {
									$totalOutput = 0;
									$bufferNeeded = false;
									for ($x = 1; $x<=288; $x++) {
										if (file_exists(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg')) {
											list($width, $height) = getimagesize(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg');
											echo('<div class="col span_6">
											  <div class="img-thumbnail"><a data-fancybox="gallery" href="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg" class="thumb"><img src="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg" alt=""><span class="thumb-overlay"></span></a></div>
											</div>');
											$totalOutput++;
											$bufferNeeded = true;
										} elseif (file_exists(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG')) {
											list($width, $height) = getimagesize(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG');
											echo('<div class="col span_6">
											  <div class="img-thumbnail"><a data-fancybox="gallery" href="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG" class="thumb"><img src="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG" alt=""><span class="thumb-overlay"></span></a></div>
											</div>');
											$totalOutput++;
											$bufferNeeded = true;
										}
										
										if(($totalOutput % 2 == 0) && $bufferNeeded){
											echo '</div><div class="row">';
											$bufferNeeded = false;
										}
									}
								} else {
									/*$totalOutput = 0;
									$bufferNeeded = false;
									list($width, $height) = getimagesize(ABSPATH.'/photos/'.$eventID.'/memorabilia/'.$car['sreference'].'.jpg');
									echo('<div class="col span_6">
											  <div class="img-thumbnail"><a data-fancybox="gallery" href="/photos/'.$eventID.'/memorabilia/'.$car['sreference'].'.jpg" class="thumb"><img src="/photos/'.$eventID.'/memorabilia/'.$car['sreference'].'.jpg" alt=""><span class="thumb-overlay"></span></a></div>
											</div>');
									for ($x = 1; $x<=10; $x++) {
										if (file_exists(ABSPATH.'/photos/'.$eventID.'/memorabilia/'.$car['sreference'].'-'.$x.'.jpg')) {
											list($width, $height) = getimagesize(ABSPATH.'/photos/'.$eventID.'/memorabilia/'.$car['sreference'].'-'.$x.'.jpg');
											echo('<div class="col span_6">
											  <div class="img-thumbnail"><a data-fancybox="gallery" href="/photos/'.$eventID.'/memorabilia/'.$car['sreference'].'-'.$x.'.jpg" class="thumb"><img src="/photos/'.$eventID.'/memorabilia/'.$car['sreference'].'-'.$x.'.jpg" alt=""><span class="thumb-overlay"></span></a></div>
											</div>');
											$totalOutput++;
											$bufferNeeded = true;
											if(($totalOutput % 2 == 0) && $bufferNeeded){
												echo '</div><div class="row">';
												$bufferNeeded = false;
											}
										}
									}*/
									$totalOutput = 0;
									$bufferNeeded = false;
									for ($x = 1; $x<=99; $x++) {
										if (file_exists(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg')) {
											list($width, $height) = getimagesize(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg');
											echo('<div class="col span_6">
											  <div class="img-thumbnail"><a data-fancybox="gallery" href="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg" class="thumb"><img src="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.jpg" alt=""><span class="thumb-overlay"></span></a></div>
											</div>');
											$totalOutput++;
											$bufferNeeded = true;
										} elseif (file_exists(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG')) {
											list($width, $height) = getimagesize(ABSPATH.'photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG');
											echo('<div class="col span_6">
											  <div class="img-thumbnail"><a data-fancybox="gallery" href="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG" class="thumb"><img src="/photos/'.$eventID.'/'.$car['sreference'].'/'.$x.'.JPG" alt=""><span class="thumb-overlay"></span></a></div>
											</div>');
											$totalOutput++;
											$bufferNeeded = true;
										}
										
										if(($totalOutput % 2 == 0) && $bufferNeeded){
											echo '</div><div class="row">';
											$bufferNeeded = false;
										}
									}
								}
						  ?>
					  </div>
				  </div>
				  <div class="col span_8">
					<!-- Load main pic for item -->
					<div class="blog-post thumbnail">
						<?php if($car['sday'] > '') {?><div class="blog-post-time"><br><span><?php echo('<h4>Selling on '.$car['sday'] . '</h4>');?></span></div><?php } ?>
						<?php if ($car['ncategoryid']==1) { 
							  if (file_exists(ABSPATH.'/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg')) {
								echo('<img style="margin-top:1rem;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
							} elseif (file_exists(ABSPATH.'/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG')) {
								echo('<img style="margin-top:1rem;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
							}
							  	//echo('<img src="/photos/'.$eventID.'/'.$referenceID.'/1.jpg" class="image-round" style="padding-top: 15px;" alt="'.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle'].'">');
							} else { 
								//echo('<img src="/photos/'.$eventID.'/memorabilia/'.$referenceID.'.jpg" class="image-round" style="padding-top: 15px;" alt="'.$car['smake'].$car['smodel'].$car['sstyle'].'">');
							  	if (file_exists(ABSPATH.'/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg')) {
									echo('<img style="margin-top:1rem;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.jpg" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
								} elseif (file_exists(ABSPATH.'/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG')) {
									echo('<img style="margin-top:1rem;" src="/photos/'.$eventID.'/'.$item['sreference'].'/1.JPG" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
								}
							} ?>
					  <div class="caption-mod-4">
						  
						<div class="blog-post-body text-center">
							<?php
								if ($eventID == '29'){
									if ($car['nlowestimate'] > 0 and $car['nhighestimate'] > 0){ 
										echo('<h4>ESTIMATE: $'.number_format($car['nlowestimate']).' - $'.number_format($car['nhighestimate']).'</h4>');
									} elseif ($car['nlowestimate'] > 0 and $car['nhighestimate'] == 0) {
										echo('<h4>Asking Price: $'.number_format($car['nlowestimate']).'</h4>');
									} else {
										echo('<h4>Available Upon Request</h4>');
									}
								/*} else {
									if ($car[nlowestimate] > 0){ 
										echo('<h4>ESTIMATE: $'.number_format($car[nlowestimate]).' - $'.number_format($car[nhighestimate]).'</h4>');
									} else {
										echo('<h4>Available Upon Request</h4>');
									}*/					
								}
							?>
							<?php
								if ($car['svin'] > ''){ echo('<h4>CHASSIS NO: '.$car['svin'].'</h4>');}
							?>                      	
							<?php
								if ($car['sfeatureitems']>""){
									$sfeature = str_replace('|','<br>&bull;',$car['sfeatureitems']);
									echo('<p style="color: #a71c20; font-weight: bold;">&bull; '.$sfeature.'</p>');
								}
							?>
						</div>
						<div class="blog-post-body">
							<?php
								$file_count = 0;
								foreach (glob(ABSPATH.'/docs/sale/public/'.$eventID.'/'.$referenceID.'/*.pdf') as $filename) {
									$file_count ++;
								}
								if ($file_count > 0){
									echo('<br><h4>Documents</h4><p>');
									foreach (glob(ABSPATH.'/docs/sale/public/'.$eventID.'/'.$referenceID.'/*.pdf') as $filename) {
										echo('<a href="/docs/sale/public/'.$eventID.'/'.$referenceID.'/'.basename($filename).'" target="_blank"><img src="/images/pdf.svg" style="width: 20px; border-radius:0; margin-bottom:0;" align="left"> '.basename($filename).'</a><br>');
									}
									echo('</p>');
								}
							?>
							<?php
								if ($car['saddendum'] > ''){
									$saddendum = str_replace(chr(10),'<br>',$car['saddendum']);
									echo('<br><p class="text-sm-left text-left">Addendum: '.$saddendum.'</p>');
								}
							?>
							<?php
								if ($car['svideo'] > ''){
									echo('<br><h4>Video</h4><p>');
									echo($car['svideo']);
									echo('</p>');
								}
							?>
							<?php
								if ($car['longdescription'] > ''){
									$slong = str_replace(chr(10),'<br>',$car['longdescription']);
									echo('<br><p class="text-sm-left text-left">'.$slong.'</p>');
								}
							?>
							
							<?php 
								if($eventID == 29) {								
									$carinquiry = 'Private Treaty - '.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle'];
								} else {
									if($car['slotnumber'] > ''){
										if($eventID == '102'){
											$carinquiry = 'AU23 - Lot '.$car['slotnumber'].' - '.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle'];	
										} else {
											$carinquiry = $eventID .' - Lot '.$car['slotnumber'].' - '.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle'];	
										}
									} else {
										if($eventID == '102'){
											$carinquiry = 'AU23 - REF '.$referenceID.' - '.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle'];	
										} else {
											$carinquiry = $eventID .' - REF '.$referenceID.' - '.$car['syear'].' '.$car['smake'].' '.$car['smodel'].' '.$car['sstyle'];	
										}
									}
								}
							?>
						</div>
						  <?php if ($eventID == 82 && ($car['sreference'] == 41 || $car['sreference'] == 42)){
								echo('');
							} else {
								
								//echo('src="https://form.jotform.com/jsform/83645598664980?typeA='. $carinquiry .'"');
						echo('<div class="nectar-cta" style="margin-top:1.5rem;" data-style="see-through" data-alignment="center" data-text-color="custom"><h4 style="color: #275b84;"><span class="text"></span><span class="link_wrap"><a class="link_text" target="_blank" href="/bid" style="top:0;">Register to Bid<span class="arrow"></span></a></span></h4></div></div>				  
						
						<div style="text-align:center;">
							<h3>Ask a Question</h3>
							Fill out the form below, email us at <a href="mailto:info@worldwideauctioneers.com"><strong>info@worldwideauctioneers.com</strong></a>, or call us at <a href="tel:2609256789"><strong>(260) 925-6789</strong></a> to get more information on this lot.
						</div>
						<script type="text/javascript" src="https://form.jotform.com/jsform/83645598664980?typeA='. $carinquiry .'"></script>
					  </div>');
							}
					//echo ('&url=http://worldwideauctioneers.com/auctions/car-details/index.php?id='.$eventid.'&rid='.$referenceID); 
					?>
						  
					</div>
				  </div>
				  
				</div>
			<?php } ?>
            
            
		</div><!--/row-->
	</div><!--/container-->
</div><!--/container-wrap-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha256-yt2kYMy0w8AbtF89WXb2P1rfjcP/HTHLT7097U8Y5b8=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" integrity="sha256-P8k8w/LewmGk29Zwz89HahX3Wda5Bm8wu2XkCC0DL9s=" crossorigin="anonymous" />

<style>
    .text-center{
        text-align: center;
    }
    
    .offered-without-reserve{
        color:red;
    }
</style>

<?php get_footer(); ?>