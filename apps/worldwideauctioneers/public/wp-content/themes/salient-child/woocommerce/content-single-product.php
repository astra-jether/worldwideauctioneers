<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() )
	 {
	 	echo get_the_password_form();

	 	return;
	 }

	$nectar_options          = get_nectar_theme_options();
	$product_style           = (!empty($nectar_options['product_style'])) ? $nectar_options['product_style'] : 'classic';
	$product_gallery_style   = (!empty($nectar_options['single_product_gallery_type'])) ? $nectar_options['single_product_gallery_type'] : 'default';
	$product_hide_sku        = (!empty($nectar_options['woo_hide_product_sku'])) ? $nectar_options['woo_hide_product_sku'] : 'false';
	$nectar_woo_lazy_load    = (isset( $nectar_options['product_lazy_load_images'] ) && !empty( $nectar_options['product_lazy_load_images'] )) ? $nectar_options['product_lazy_load_images'] : 'off';
	$product_gallery_variant = 'default';

	if( 'left_thumb_sticky_fullwidth' === $product_gallery_style)
	{
		$product_gallery_style = 'left_thumb_sticky';

		$product_gallery_variant = 'fullwidth';
	}

	$eventID 			= get_field('auction_no');
	$referenceID 		= get_field('reference_no');
	$video 				= get_field('video_url');
	$red_car_bg_image 	= get_field('red_car_bg_image');
	$red_car_text 		= get_field('red_car_text');
	$below_red_bg_text 	= get_field('below_red_bg_text');
	$lot_number 		= get_field('lot_number');
	$addendum           = get_field('addendum');
	$vin                = get_field('vin');
	$sLong_array 		= [];

	$product_gallery_style = ( ! empty( $nectar_options['single_product_gallery_type'] ) ) ? $nectar_options['single_product_gallery_type'] : 'default';

	if( in_array( $product_gallery_style, ['left_thumb_sticky','two_column_images'] ) )
	{
		wp_enqueue_script('stickykit');
	}

	if( in_array( $product_gallery_style, ['ios_slider','left_thumb_sticky'] ) )
	{
		wp_enqueue_script('flickity');
	}

	// In case product is called from product_page shortcode
	wp_enqueue_style( 'nectar-woocommerce-single' ); 
	wp_enqueue_script('nectar-single-product');

	if( isset( $nectar_options['product_reviews_style'] ) && 'off_canvas' === $nectar_options['product_reviews_style'] )
	{
		wp_enqueue_script('nectar-single-product-reviews');
	}

	$directory = ABSPATH . "photos/{$eventID}/{$referenceID}/";

	$count_images = count( glob( $directory . "*") );
?>

	<?php if( function_exists('wc_product_class') ) : ?>
		<div itemscope data-project-style="<?php echo esc_attr($product_style); ?>" data-gallery-variant="<?php echo esc_attr($product_gallery_variant); ?>" data-n-lazy="<?php echo esc_attr($nectar_woo_lazy_load); ?>" data-hide-product-sku="<?php echo esc_attr($product_hide_sku); ?>" data-gallery-style="<?php echo esc_attr($product_gallery_style); ?>" data-tab-pos="<?php echo (!empty($nectar_options['product_tab_position'])) ? esc_attr($nectar_options['product_tab_position']) : 'default'; ?>" id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
	<?php else : ?>
		<div itemscope data-project-style="<?php echo esc_attr($product_style); ?>" data-gallery-variant="<?php echo esc_attr($product_gallery_variant); ?>" data-hide-product-sku="<?php echo esc_attr($product_hide_sku); ?>" data-gallery-style="<?php echo esc_attr($product_gallery_style); ?>" data-tab-pos="<?php echo (!empty($nectar_options['product_tab_position'])) ? esc_attr($nectar_options['product_tab_position']) : 'default'; ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php endif; ?>

	<div class="blackTitle" style="width:100%;height:100%;background-color:black;">
		<div class="subBlackTitle">
			<a class="bti" id="bti" href="https://listings.worldwideauctioneers.com/inventory/"><i class="fa fa-reply"></i>  Back to Inventory</a>
			<span> The Scottsdale Auction  |  January 26 </span>
			<a class="rtb" href="https://worldwideauctioneers.com/bidder-registration/"> Register to Bid  <i class="fa fa-check"></i></a>
		</div>
	</div>

	<script>
		/*var element = document.getElementById('bti');
		element.setAttribute('href', document.referrer);
		element.onclick = function() {
			history.back();
			return false;
			}*/
	</script>

<div class="main-content">
	
	<div class="summary entry-summary">
		<div class="left-images">
			<?php if ($lot_number != ''): ?>
			<div class="lot-number">
				LOT <span><?= $lot_number; ?></span>
			</div>
			<?php endif;?>

			<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images images" data-has-gallery-imgs="true" style="opacity: 1;">
				<div class="flickity product-slider">
					<div class="slider generate-markup">

						<?php for ( $x = 1; $x<=$count_images; $x++ ) : ?>
	
							<?php if ( file_exists( ABSPATH.'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.jpg' ) ) : ?>

								<?php list($width, $height) = getimagesize(ABSPATH.'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.jpg'); ?>

       							<?php $product_description = '/photos/' . $eventID . '/' . $referenceID . '/1.jpg'; ?>

								<div class="slide">
									<div>
										<a data-fancybox="gallery" href="<?= "/photos/{$eventID}/{$referenceID}/{$x}.jpg" ?>">
											<img src='<?= "/photos/{$eventID}/{$referenceID}/{$x}.jpg" ?>' alt="">
										</a>
									</div>
								</div>

								<?php $totalOutput++; $bufferNeeded = true; ?>

							<?php elseif ( file_exists( ABSPATH . 'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.JPG' ) ) : ?>

								<?php list($width, $height) = getimagesize( ABSPATH.'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.JPG' ); ?>
								
								<div class="col span_6">
									<div class="img-thumbnail">
										<a data-fancybox="gallery" href="<?= "/photos/{$eventID}/{$referenceID}/{$x}.JPG" ?>" class="thumb">
											<img src='<?= "/photos/{$eventID}/{$referenceID}/{$x}.JPG" ?>' alt=""><span class="thumb-overlay"></span>
										</a>
									</div>
								</div>
								
							<?php endif; ?>
							
						<?php endfor;  ?>
					</div>
				</div>
				<div class="flickity product-thumbs">
					<div class="slider generate-markup">

						<?php  for ($x = 1; $x<=$count_images; $x++) : ?>

							<?php if (file_exists(ABSPATH.'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.jpg')) : ?>
								
								<?php list($width, $height) = getimagesize(ABSPATH.'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.jpg'); ?>
								
								<div class="thumb slide">
									<div class="thumb-inner">
										<img src='<?= "/photos/{$eventID}/{$referenceID}/{$x}.jpg" ?>' alt="">
									</div>
								</div>

								<?php $totalOutput++; $bufferNeeded = true;?>

							<?php elseif (file_exists(ABSPATH.'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.JPG')) : ?>

								<?php list($width, $height) = getimagesize(ABSPATH.'photos/'.$eventID.'/'.$referenceID.'/'.$x.'.JPG'); ?>
								
								<div class="col span_6">
									<div class="img-thumbnail">
										<a data-fancybox="gallery" href="<?= "/photos/{$eventID}/{$referenceID}/{$x}.JPG" ?>" class="thumb">
											<img src='<?= "/photos/{$eventID}/{$referenceID}/{$x}.JPG" ?>' alt=""><span class="thumb-overlay"></span>
										</a>
									</div>
								</div>

							<?php endif; ?>
							
						<?php endfor; ?>
					</div>
					<?php if (get_field('addendum') != ""): ?>
						<div class="addendum">
							<br>
									<span>Addendum:<?php echo " ".get_field('addendum'); ?> </span>
						</div>
					<?php endif; ?>	
				</div>
			</div>
		</div>
		<div class="right-content">
			<p class="selling-day">Selling on <?php the_field('selling_day'); ?></p>

			<?php if ( get_field( 'short_description' ) ) : ?>
			<div class="blog-post-time blog-shortdesc"><?= get_field( 'short_description' ); ?></div>
			<?php endif; ?>

		    <h1><?php the_title(); ?></h1>

			<?php if( get_field( 'offered_without_reserve' ) == '1' ) : ?>
				<div class="reservation">OFFERED WITHOUT RESERVE</div>
			<?php endif; ?>

			<div class="blog-post-body text-center">
				

				<?php if ( get_field('features') ) : ?>
					<?php $features = explode( '|', get_field('features') ) ?>

					<ul class="features-list">
						<?php foreach( $features as $feature ) : ?>
							<li><?= $feature ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( get_field( 'vin' ) != '' ) : ?>
					<h4>VIN: <?= get_field( 'vin' ) ?></h4>
				<?php endif; ?>		

				<p class="buttons-container">
				
				<?php $dir = explode( 'public', $directory ); ?>
				<?php $dir = str_replace('/photos/','docs/sale/public/', $dir['1'] );
						//$dirnew = "docs/sale/public/101/18/";
						//$dirnew1 = $dirnew.scandir($dirnew)[2];
						//$dirnew2 = scandir($dirnew)[2];
						//echo $dirnew2;
						$num = count(glob($dir . "*"));
				
				?>
				
				<a href="https://worldwideauctioneers.com/bidder-registration/" target="_blank" title="Register To Bid" class="btn-main btn-colored" style="width:46%;align-items: center;" >Register To Bid <i class="fa fa-check"></i></a>

				<a href="https://worldwideauctioneers.com/auburn-auction-2023/" target="_blank" title="Auction Info" class="btn-main btn-regular" style="width:46%;">Auction Info <i class="fa fa-info"></i></a>
					
					<?php if ( $num >= 1 ) echo '<a href="#documents-section" title="VIEW FILES & DOCUMENTS" class="btn-main btn-regular" style="width:100%;">VIEW FILES & DOCUMENTS<i class="fa fa-file-pdf-o"></i></a>'; ?>
					
					<?php if( ! empty($video)) echo '<a href="#video-section" title="Play Video" class="btn-main btn-regular" style="width:100%;">Play Video <i class="fa fa-play-circle-o"></i></a>'; ?>
					
					
				</p>
			</div>
		</div>
	</div><!-- .summary -->
</div><!-- .main-content -->

<?php if ( ! empty( $sLong = get_field( 'long_description' ) ) ) : $sLong_array = explode( chr(10), $sLong ); ?>
	<div class="longdesc-wrapper">
		<div class="wrapper">
			<p class="text-sm-left text-left"><?= $sLong_array[0] ?></p>
		</div>
	</div>
<?php endif; ?>

<div class="red-car-section" style="background-image: url( <?= '/photos/'. $eventID .'/'. $referenceID .'/2.jpg'; ?>); ">
	<div class="red-car-overlay"></div>

	<?php if( ! empty( $sLong_array ) ) : ?>
		<div class="wrapper">
			<div class="longbg-wrapper">
				<p><?= $sLong_array[2]; ?></p>
			</div>
		</div>
	<?php endif;?>
</div>

<?php if( ! empty( $sLong_array ) ): ?>
	<div class="below-car-text">
		<?php $slicedArray = array_slice($sLong_array, 4); ?>
			
		<?php foreach($slicedArray as $p) : $p1 = trim($p); ?>
			<p><?= $p1 != '' ? $p : '' ?></p>
		<?php endforeach; ?>
	</div>
	<div class=regbot>
		<a class="registerBottom" href="https://worldwideauctioneers.com/bidder-registration/"> Register to Bid  <i class="fa fa-check"></i></a>
	</div>
<?php endif;?>

<?php if (  ! empty( $directory ) )  : ?>

		<?php $dirnew = explode( 'public', $directory ); ?>
		<?php $dirnew = str_replace('/photos/','docs/sale/public/', $dirnew['1'] );
			  //$dirnew = "docs/sale/public/101/18/";
			  $dirnew1 = $dirnew.scandir($dirnew)[2];
			  $dirnew2 = scandir($dirnew)[2];
			  //echo $dirnew2;
			  $num = count(glob($dirnew . "*"));
				
		?>
	<?php if (  ! empty( scandir($dirnew)[2] ) )  : ?>
		<div id="documents-section" class="btns_section" anchor= >
			<div class="wrapper">
				<div class="headings-container">
					<h2>Documents & Files</h2>
					<p>Click Buttons to View and Download</p>
				</div>
				<div class="btn-container">
					
					<?php 
					//echo $dirnew;
					//echo $num;
					foreach ( $files as $file ) : ?>
						
						<?php
							$fileExtension = pathinfo($file, PATHINFO_EXTENSION);
							$icon = '';
							$remove_extension = explode('.', $file);
						?>

						<?php if ( $fileExtension == 'pdf' ) : ?>
							<?php $icon = get_bloginfo('wpurl') . '/wp-content/uploads/default-icon1.png'; ?>
						<?php elseif ($fileExtension === 'doc' || $fileExtension === 'docx') : ?>
							<?php $icon = get_bloginfo('wpurl') . '/wp-content/uploads/car-icon.png'; ?>
						<?php else : ?>		
							<?php $icon = get_bloginfo('wpurl') . '/wp-content/uploads/default-icon.png'; ?>
						<?php endif; ?>

                      	<?php $pathnew='https://listings.worldwideauctioneers.com/'.$dirnew1; ?>

						<a href="<?= $pathnew; ?>"  target="_blank"><span><?= $remove_extension[0]; ?></span><img src="<?= $icon; ?>" alt="<?= $file; ?>" /></a>
					
					<?php endforeach;?>
					
					<?php if ($num == 1): ?>
						<?php $pathnew="https://listings.worldwideauctioneers.com/".$dirnew1; ?>
						<a href="<?= $pathnew; ?>"  target="_blank"><img src="https://listings.worldwideauctioneers.com/wp-content/uploads/default-icon1.png" style="margin-right:10px;"/><?php echo $dirnew2; ?></a>
					
					<?php elseif ($num	> 1): ?>
						<?php $num1 = $num; ?>
						
						<?php for ( $x = 1; $x <= $num1; $x++): ?>
						<?php $pathnew='https://listings.worldwideauctioneers.com/'.$dirnew.scandir($dirnew)[$x+1]; ?>
						<?php //echo $pathnew; 
							$newname = scandir($dirnew)[$x+1];
						?>
							<a href="<?= $pathnew; ?>" target="_blank"><img src="https://listings.worldwideauctioneers.com/wp-content/uploads/default-icon1.png" style="margin-right:10px;"/><?php echo $newname; ?> </a>
						<?php endfor; ?>
						
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>



<?php if( ! empty( $video ) ) : ?>
	<div id="video-section" class="yt-video-container" style="background-color:#929291;">
		<div class="wrapper">
		<div class="headings-container">
					<h2 style="margin-bottom: 30px;">Videos</h2>
				</div>
		<?php
			//echo $video."<br>";
			$newvidurl = (explode("/",$video)[3]);
			//echo $newvidurl;
			$newvid = (explode('watch?v=',$newvidurl)[1]);
			//echo $newvid;
		?>	
		<div class="wrapper" style="margin-bottom: 60px;">
			<div class="embed-container">
			
			<iframe width="540" height="304" src="https://www.youtube.com/embed/<?php echo $newvid;?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
				<?//= $video; ?>
			</div>
		</div>
	</div>
<?php endif;?>

<div class="form-container">
	<div class="form-wrapper">
		<h2>Contact Us</h2>
		<ul>
			<li><strong>Phone</strong>:  <a href="callto:12609256789">+1.260.925.6789</a></li>
			<li><strong>Toll Free:</strong>  <a href="callto:18009906789">800.990.6789</a></li>
			<li><strong>Email</strong>:  <a href="mailto:info@worldwideauctioneers.com">info@worldwideauctioneers.com</a></li>
		</ul>

		<?php echo do_shortcode( '[gravityform id="37" title="false" description="false" ajax="false"]' ); ?>
	</div>
</div>

<div class="wrapper-rel" style="width:100%;height:100%;top:auto;left:auto;background-color:white; padding-top:50px;padding-bottom:50px;">
	<div class="related-products-wrapper" style="width:70%;margin:auto;">
				<?php

			global $product;  // Ensure the global product variable is accessible
			
			$related_products = wc_get_related_products( $product->get_id(), 3); 
			
			if ( ! empty( $related_products ) ) : ?>
			
				<div class="related-products">
			
					<h2><?php _e( 'You may also like: ', 'woocommerce' ); ?></h2>
					<br>
					<ul class="products">
						<?php foreach ( $related_products as $related_product_id ) :
							$related_product = wc_get_product( $related_product_id );

							$image_id = $related_product->get_image_id();

								if ( $image_id ) {
									$image = wp_get_attachment_image_url( $image_id, 'full' );
								} 
								else {
									$image = wc_placeholder_img( 'woocommerce_thumbnail' );
								}

							if ( empty( $image ) ) {
								$image = '<img src="https://listings.worldwideauctioneers.com/wp-content/uploads/WA_Dot-01-1.png" alt="Placeholder Image">';
							}
							?>
							<li style="width:33%;float:left;height:250px;padding:30px;">
								<a href="<?php echo get_permalink( $related_product->get_id() ); ?>" style="text-decoration: none; color: inherit;">
									<div class="product-image" style="text-align:center; margin-bottom: 10px;">
									<?php echo '<img class="relatedimgs" src="' . esc_url( $image ) . '" alt="' . esc_attr( $product->get_name() ) . '">'; ?>
									<!--img src="https://listings.worldwideauctioneers.com/wp-content/uploads/WA_Dot-01-1.png" alt="Image to be placed." height=100px  width=100px-->
									</div>
									<p class="relate" style="text-align: center;font-family:'Manrope',sans-serif;"><b><?php echo $related_product->get_name(); ?></b></p>
									<style>
										.relate:hover{
											color:#0D5AA5;
										}
										.relatedimgs{
											height: 208px !important;
											width: 371px !important;
										}
									</style>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
			
				</div>
			
			<?php endif; ?>
			
    </div>

</div>


<div class="sticky-footer">
  <?php echo do_shortcode('[gravityform id="43"]'); ?>									
</div>


<?php do_action( 'woocommerce_after_single_product' ); ?>
