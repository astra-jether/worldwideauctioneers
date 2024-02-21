<?php
/**
 * Plugin Name: WooCommerce Custom Pagination
 * Plugin URI: https://yourwebsite.com/
 * Description: Custom Pagination for WooCommerce Products.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com/
 * License: GPL2
 */
function wc_custom_pagination($query) {
    // Check if on frontend and main query is modified
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive('product') || is_product_category()) {
            // Set the number of products per page
            $query->set('posts_per_page', 100);
        }
    }
}
add_action('pre_get_posts', 'wc_custom_pagination');
function wc_custom_override_pagination_args($args) {
    $args['end_size'] = 3;
    $args['mid_size'] = 3;
    return $args;
}
add_filter('woocommerce_pagination_args', 'wc_custom_override_pagination_args');
?>


<div class="wrapper" style="width:100%;top:auto;left:auto;background-color:white; padding-top:50px;padding-bottom:50px;">
	<div class="related-products-wrapper" style="width:70%;margin:auto;">
				<?php

			global $product;  // Ensure the global product variable is accessible
			
			$related_products = wc_get_related_products( $product->get_id(), 4);  // Limiting to two products
			
			if ( ! empty( $related_products ) ) : ?>
			
				<div class="related-products">
			
					<h2><?php _e( 'You may also like: ', 'woocommerce' ); ?></h2>
					<br>
					<ul class="products">
						<?php foreach ( $related_products as $related_product_id ) :
							$related_product = wc_get_product( $related_product_id );
							$image = $related_product->get_image(); // Gets product image
							if ( empty( $image ) ) {
								$image = '<img src="https://listings.worldwideauctioneers.com/wp-content/uploads/WA_Dot-01-1.png" alt="Placeholder Image">';
							}
							?>
							<li style="width:25%;float:left;height:250px;padding:30px;">
								<a href="<?php echo get_permalink( $related_product->get_id() ); ?>" style="text-decoration: none; color: inherit;">
									<div class="product-image" style="text-align: center; margin-bottom: 10px;">
									<img src="https://listings.worldwideauctioneers.com/wp-content/uploads/WA_Dot-01-1.png" alt="Image to be placed." height=100px  width=100px>
									</div>
									<p class="relate" style="text-align: center;font-family:'Manrope',sans-serif;"><b><?php echo $related_product->get_name(); ?></b></p>
									<style>
										.relate:hover{
											color:#0D5AA5;
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