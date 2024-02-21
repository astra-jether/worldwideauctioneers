<?php

//Include car scripts
include(__DIR__ . "/functions-cars.php");

add_action('wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);
function salient_child_enqueue_styles()
{
	$nectar_theme_version = nectar_get_theme_version();
	wp_enqueue_style('salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version);

	if (is_rtl()) {
		wp_enqueue_style('salient-rtl',  get_template_directory_uri() . '/rtl.css', array(), '1', 'screen');
	}

	if( is_archive('product') )
	{
		wp_enqueue_style( 'archive-product-css', get_stylesheet_directory_uri() . '/assets/css/archive-product.css', [], false );
		wp_register_script( 'archive-product-js', get_stylesheet_directory_uri() . '/assets/js/archive-product.js', [], false, true );
		wp_enqueue_script( 'archive-product-js' );
	}

	if( is_singular( 'product' ) )
	{
		wp_enqueue_style( 'single-product-css', get_stylesheet_directory_uri() . '/assets/css/single-product.css', [], false );
	}
}

function wwa_login_logo()
{
	global $nectar_options;
	$logo = nectar_options_img($nectar_options['logo']);
?>
	<style type="text/css">
		#login h1 a,
		.login h1 a {
			background-image: url('<?php echo $logo; ?>');
			height: 100px;
			width: 200px;
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center;
		}
	</style>
<?php }
add_action('login_enqueue_scripts', 'wwa_login_logo');

function wwa_login_logo_url()
{
	return home_url();
}
add_filter('login_headerurl', 'wwa_login_logo_url');

function wwa_login_logo_url_title()
{
	return get_bloginfo('name');
}
add_filter('login_headertext', 'wwa_login_logo_url_title');

//Redirects login-needed pages to here.
function wwa_login_page($login_url, $redirect, $force_reauth)
{
	return home_url('/login/?redirect_to=' . $redirect);
}
add_filter('login_url', 'wwa_login_page', 10, 3);

//Don't allow non-admins to access wp-admin
function wwa_block_wp_admin_init()
{
	if (
		is_admin() && !is_user_editor() &&
		!(defined('DOING_AJAX') && DOING_AJAX)
	) {
		wp_redirect(home_url());
		exit;
	}
}
add_action('init', 'wwa_block_wp_admin_init');

//Hide admin bar from non-admins
/*function disable_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
  		show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'disable_admin_bar'); */

//Checks to see if the user is an editor
function is_user_editor()
{
	$currentUser = wp_get_current_user();
	$roles = $currentUser->caps;
	if (array_key_exists("editor", $roles) || array_key_exists("administrator", $roles)) {
		return true;
	} else {
		return false;
	}
}

//Theme options page for managing certain elements.
if (function_exists('acf_add_options_page')) {
	$args = array(
		/* (string) The title displayed on the options page. Required. */
		'page_title' => 'Auction Syncing',

		/* (string) The slug name to refer to this menu by (should be unique for this menu). 
		Defaults to a url friendly version of menu_slug */
		'menu_slug' => 'wwa_options',

		/* (int|string) The '$post_id' to save/load data to/from. Can be set to a numeric post ID (123), or a string ('user_2'). 
		Defaults to 'options'. Added in v5.2.7 */
		'post_id' => 'wwa_options',

		/* (boolean)  Whether to load the option (values saved from this options page) when WordPress starts up. 
		Defaults to false. Added in v5.2.8. */
		'autoload' => true
	);

	acf_add_options_page($args);
}

function wwa_listings_init()
{
	$labels = array(
		'name'               => _x('Listings', 'post type general name', 'wwa-listings'),
		'singular_name'      => _x('Listing', 'post type singular name', 'wwa-listings'),
		'menu_name'          => _x('Listings', 'admin menu', 'wwa-listings'),
		'name_admin_bar'     => _x('Listings', 'add new on admin bar', 'wwa-listings'),
		'add_new'            => _x('Add New', 'listing', 'wwa-listings'),
		'add_new_item'       => __('Add New Listing', 'wwa-listings'),
		'new_item'           => __('New Listing', 'wwa-listings'),
		'edit_item'          => __('Edit Listing', 'wwa-listings'),
		'view_item'          => __('View Listing', 'wwa-listings'),
		'all_items'          => __('All Listings', 'wwa-listings'),
		'search_items'       => __('Search Listings', 'wwa-listings'),
		'parent_item_colon'  => __('Parent Listing:', 'wwa-listings'),
		'not_found'          => __('No listings found.', 'wwa-listings'),
		'not_found_in_trash' => __('No listings found in Trash.', 'wwa-listings')
	);

	$args = array(
		'labels'             => $labels,
		'description'        => 'A post type for listings',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'listings/%auction%'),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-media-spreadsheet',
		'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt')
	);

	register_post_type('listings', $args);
}
add_action('init', 'wwa_listings_init');

function wwa_auction_taxonomies()
{
	//NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x('Auction Categories', 'taxonomy general name', 'textdomain'),
		'singular_name'              => _x('Auction Category', 'taxonomy singular name', 'textdomain'),
		'search_items'               => __('Search Auction Categories', 'textdomain'),
		'popular_items'              => __('Popular Auction Categories', 'textdomain'),
		'all_items'                  => __('All Auction Categories', 'textdomain'),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __('Edit Auction Category', 'textdomain'),
		'update_item'                => __('Update Auction Category', 'textdomain'),
		'add_new_item'               => __('Add New Auction Category', 'textdomain'),
		'new_item_name'              => __('New Auction Category Name', 'textdomain'),
		'separate_items_with_commas' => __('Separate auction categories with commas', 'textdomain'),
		'add_or_remove_items'        => __('Add or remove auction categories', 'textdomain'),
		'choose_from_most_used'      => __('Choose from the most used auction categories', 'textdomain'),
		'not_found'                  => __('No auction categories found.', 'textdomain'),
		'menu_name'                  => __('Auction Categories', 'textdomain'),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array('slug' => 'auction'),
	);

	register_taxonomy('auction', 'listings', $args);
}
add_action('init', 'wwa_auction_taxonomies', 0);

function wwa_course_post_link($post_link, $id = 0)
{
	$post = get_post($id);
	if (is_object($post)) {
		$terms = wp_get_object_terms($post->ID, 'auction');
		if ($terms) {
			return str_replace('%auction%', $terms[0]->slug, $post_link);
		}
		return $post_link;
	}
	return $post_link;
}
add_filter('post_type_link', 'wwa_course_post_link', 1, 3);
//flush_rewrite_rules();

//add_action('wwa_sync_cron', 'wwa_sync_cron_func');

/*function wwa_sync_cron_func()
{
	$addcounter = 0;
	$updatecounter = 0;
	$auctions = get_field( 'auctions_to_sync', 'wwa_options' );

	foreach( $auctions as $auction )
	{
		if( function_exists( 'wwa_pull_listings_by_event' ) )
		{
			ini_set('memory_limit', '51200M');
        	set_time_limit ( 0 );

			//for ( $i=0; $i < $estimate; $i+=20 )
			//{
				//wp_die( var_dump( $i ) );
				//$listings = wwa_pull_listings_by_event( $auction['id'], 't_inventory', '', '', true, 20, $i );

				$listings = wwa_pull_listings_by_event( $auction['id'], 't_inventory', '', '', false );

				if( empty( $listings ) || ! $listings )
				{
					wp_die( 'All data has been synced!!!'.$auction['id'].$auction['name'] );
				}

				foreach ( $listings as $listing )
				{
					//if( $listing['sreference'] != '8' ) continue;

					$post_info = [
						'post_author'	=> get_current_user_id(),
						'post_title'	=> $listing['syear'] . ' ' . $listing['smake'] . ' ' . $listing['smodel'] . ' ' . $listing['sstyle'],
						'post_type'		=> 'product',
						'post_status'	=> 'publish',
						'meta_input'	=> [
							'reference_no' 				=> $listing['sreference'],
							'auction_no' 				=> $auction['id'],
							'lot_number'				=> $listing['slotnumber'],
							'selling_day'				=> $listing['sday'],
							'item_color'				=> $listing['scolor'],
							'offered_without_reserve'	=> $listing['breserve'] == '1' ? 0 : 1,
							'short_description'			=> $listing['sshortdescription'],
							'long_description'			=> $listing['longdescription'],
							'features'					=> $listing['sfeatureitems'],
							'make'  					=> $listing['smake'],
							'addendum'					=> $listing['saddendum'],
							'featured'					=> $listing['bfeatured'] == '1' ? 1 : 0,
							'year'						=> $listing['syear'],
							'raw_data'					=> json_encode( $listing ),
						]
					];

					$meta_query = [
						'relation' 		=> 'AND',
						[
							'key' 		=> 'auction_no',
							'value' 	=> $auction['id'],
							'compare' 	=> '=',
						],
						[
							'key' 		=> 'reference_no',
							'value' 	=> $listing['sreference'],
							'compare' 	=> '=',
						],
					];
		
					$args = [
						'meta_query' 		=> $meta_query,
						'posts_per_page' 	=> '1',
						'post_type' 		=> 'product',
						'post_status' 		=> ['publish'],
						'fields'			=> 'ids'
					];

					$product = get_posts( $args );

					if( empty( $product ) )
					{
						//echo $listing['slongdescription'];
						$post_id = wp_insert_post( $post_info );
						$addcounter++;
						
					}
					

					else
					{
						$post_info['ID'] = $product[0];
						$post_id = wp_update_post( $post_info, false );
						$updatecounter++;	
					}

					$term = term_exists( $auction['slug'], 'product_cat' );
					
					
					if ( null === $term )
					{
						$term = wp_insert_term( $auction['name'], 'product_cat' );
					}

					if ( ! is_wp_error( $term ) )
					{
						wp_set_object_terms( $post_id, $term['name'], 'product_cat' );
					}
					else 
					{
						$error_string = $term->get_error_message();

						wp_die( var_dump( $error_string ) );
					}
					
				
					
				}
				echo $addcounter." product(s) added.";
				echo $updatecounter." product(s) updated.";	
						
			//}

			//wp_die( var_dump( $i ) );
		}
		
	}
	wwa_auto_delete_listing();
}*/

add_filter('woocommerce_product_single_add_to_cart_text', 'lw_cart_btn_text');
add_filter('woocommerce_product_add_to_cart_text', 'lw_cart_btn_text');
//Changing Add to Cart text to Buy Now! 
function lw_cart_btn_text()
{
	return __('View', 'woocommerce');
}

/**
 * Validate the extra register fields.
 *
 * @param WP_Error $validation_errors Errors.
 * @param string   $username          Current username.
 * @param string   $email             Current email.
 *
 * @return WP_Error
 */
function wooc_validate_extra_register_fields($errors, $username, $email)
{
	if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
		$errors->add('billing_first_name_error', __('<strong>Error</strong>: First name is required!', 'woocommerce'));
	}

	if (isset($_POST['billing_last_name']) && empty($_POST['billing_last_name'])) {
		$errors->add('billing_last_name_error', __('<strong>Error</strong>: Last name is required!.', 'woocommerce'));
	}


	if (isset($_POST['billing_phone']) && empty($_POST['billing_phone'])) {
		$errors->add('billing_phone_error', __('<strong>Error</strong>: Phone is required!.', 'woocommerce'));
	}

	return $errors;
}

add_filter('woocommerce_registration_errors', 'wooc_validate_extra_register_fields', 10, 3);

/**
 * Save the extra register fields.
 *
 * @param int $customer_id Current customer ID.
 */
function wooc_save_extra_register_fields($customer_id)
{
	if (isset($_POST['billing_first_name'])) {
		// WordPress default first name field.
		update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));

		// WooCommerce billing first name.
		update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
	}

	if (isset($_POST['billing_last_name'])) {
		// WordPress default last name field.
		update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));

		// WooCommerce billing last name.
		update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
	}

	if (isset($_POST['billing_phone'])) {
		// WooCommerce billing phone
		update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
	}
}

add_action('woocommerce_created_customer', 'wooc_save_extra_register_fields');


// REGISTRATION SHORTCODE
function wc_registration_form_function()
{
	if (is_admin()) return;
	if (is_user_logged_in()) return;

	ob_start();

	do_action('woocommerce_before_customer_login_form');

?>

	<script>
		jQuery(document).ready(function() {
			console.log('reg init');
			jQuery('input#billing_company').attr('placeholder', 'Enter your company (optional)');
			if (jQuery('.woocommerce-error').length) {
				jQuery('html, body').animate({
					scrollTop: jQuery(".woocommerce-error").offset().top
				}, 400);
			}
		});
	</script>


	<?php
	$handle = 'wc-country-select';
	wp_enqueue_script($handle, get_site_url() . '/wp-content/plugins/woocommerce/assets/js/frontend/country-select.min.js', array('jquery'), true);
	?>

	<form method="post" class="woocommerce-form woocommerce-form-register register register-form-shortcode" <?php do_action('woocommerce_register_form_tag'); ?>>

		<?php //do_action( 'woocommerce_register_form_start' ); 
		?>

		<div class="af_c_f_extra_fields woocommerce-billing-fields">

			<p class="form-row form-row-first">
				<label for="reg_billing_first_name"><?php _e('First name', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>" />
			</p>

			<p class="form-row form-row-last">
				<label for="reg_billing_last_name"><?php _e('Last name', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>" />
			</p>

			<p class="woocommerce-form-row form-row">
				<label for="reg_billing_phone"><?php _e('Phone', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e($_POST['billing_phone']); ?>" />
			</p>

			<p class="woocommerce-form-row form-row">
				<label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine 
																																																													?>
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_billing_address_1"><?php _e('Street Address', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="text" class="input-text" name="billing_address_1" id="reg_billing_address_1" value="<?php if (!empty($_POST['billing_address_1'])) esc_attr_e($_POST['billing_address_1']); ?>" />
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_billing_address_2"><?php _e('Address 2', 'woocommerce'); ?>&nbsp;<span class="required">(OPTIONAL)</label>
				<input type="text" class="input-text" name="billing_address_2" id="reg_billing_address_2" value="<?php if (!empty($_POST['billing_address_2'])) esc_attr_e($_POST['billing_address_2']); ?>" />
			</p>

			<?php
			$field = [
				'type' => 'country',
				'label' => 'Country&nbsp;<span class="required">(REQUIRED)</span>',
				'required' => 1,
				'class' => ['address-field']
			];
			woocommerce_form_field('billing_country', $field, '');
			$field = [
				'type' => 'state',
				'label' => 'State',
				// 'required' => 1,
				'class' => ['address-field'],
				'validate' => ['state']
			];
			woocommerce_form_field('billing_state', $field, '');
			?>

			<p class="woocommerce-form-row form-row">
				<label for="reg_billing_city"><?php _e('City', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="text" class="input-text" name="billing_city" id="reg_billing_city" value="<?php esc_attr_e($_POST['billing_city']); ?>" />
			</p>

			<p class="woocommerce-form-row form-row">
				<label for="reg_billing_postcode"><?php _e('Postcode / Zip', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="text" class="input-text" name="billing_postcode" id="reg_billing_postcode" value="<?php esc_attr_e($_POST['billing_postcode']); ?>" />
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">(REQUIRED)</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
			</p>

		</div>

		<?php //do_action( 'woocommerce_register_form' ); 
		?>

		<p class="woocommerce-form-row form-row" style="margin-top: 30px">
			<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
			<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
		</p>

		<?php //do_action( 'woocommerce_register_form_end' ); 
		?>

	</form>

<?php

	return ob_get_clean();
}
add_shortcode('wc_registration_form', 'wc_registration_form_function');


function member_check_shortcode($atts, $content = null)
{
	if (is_user_logged_in() && !is_null($content) && !is_feed()) {
		return do_shortcode($content);
	}
}
add_shortcode('member', 'member_check_shortcode');

function non_member_check_shortcode($atts, $content = null)
{
	if (!is_user_logged_in() && !is_null($content) && !is_feed()) {
		return do_shortcode($content);
	}
}
add_shortcode('non_member', 'non_member_check_shortcode');

function filter_woocommerce_registration_redirect($var)
{
	return get_page_link(180);
};

add_filter('woocommerce_registration_redirect', 'filter_woocommerce_registration_redirect', 10, 1);

include(__DIR__ . '/bidder-registration-product.php');

add_action( 'wp_footer', 'woocommerce_show_coupon', 99 );
function woocommerce_show_coupon() {
echo '
<script type="text/javascript">
jQuery(document).ready(function($) {
$(\'.checkout_coupon\').show();
});
</script>
';
}


add_filter( 'gform_validation', 'gfcf_validation' );
function gfcf_validation( $validation_result ) {
	global $gfcf_fields;

	$form          = $validation_result['form'];
	$confirm_error = false;

	if ( ! isset( $gfcf_fields[ $form['id'] ] ) ) {
		return $validation_result;
	}

	foreach ( $gfcf_fields[ $form['id'] ] as $confirm_fields ) {

		$values = array();

		// loop through form fields and gather all field values for current set of confirm fields
		foreach ( $form['fields'] as $confirm_field ) {
			if ( ! in_array( $confirm_field['id'], $confirm_fields ) ) {
				continue;
			}

			$values[] = rgpost( "input_{$confirm_field['id']}" );

		}

		// filter out unique values, if greater than 1, a value was different
		if ( count( array_unique( $values ) ) <= 1 ) {
			continue;
		}

		$confirm_error = true;

		foreach ( $form['fields'] as &$field ) {
			if ( ! in_array( $field['id'], $confirm_fields ) ) {
				continue;
			}

			// fix to remove phone format instruction
			if ( RGFormsModel::get_input_type( $field ) == 'phone' ) {
				$field['phoneFormat'] = '';
			}

			$field['failed_validation']  = true;
			$field['validation_message'] = 'Your Bid Amounts Must Match';
		}
	}

	$validation_result['form']     = $form;
	$validation_result['is_valid'] = ! $validation_result['is_valid'] ? false : ! $confirm_error;

	return $validation_result;
}

function register_confirmation_fields( $form_id, $fields ) {
	global $gfcf_fields;

	if ( ! $gfcf_fields ) {
		$gfcf_fields = array();
	}

	if ( ! isset( $gfcf_fields[ $form_id ] ) ) {
		$gfcf_fields[ $form_id ] = array();
	}

	$gfcf_fields[ $form_id ][] = $fields;
}

register_confirmation_fields( 40, array( 5, 10 ) );
register_confirmation_fields( 41, array( 5, 10 ) );


//  function to prevent duplicate products
function prevent_duplicate_product( $data ) {
    // Check if product with the same title already exists
    if ( 'product' === $data['post_type'] && 'auto-draft' !== $data['post_status'] ) {
    	
    	if ( ! function_exists( 'post_exists' ) )
    	{
		    require_once( ABSPATH . 'wp-admin/includes/post.php' );
		}

        $product_id = post_exists( $data['post_title'], '', '', 'product' );

        if ( $product_id ) {
            $existing_product_status = get_post_status( $product_id );

            // If a product with the same title exists and it's not in 'auto-draft' status (published, draft, etc.)
            // then save as draft and display an error message
            if ( 'auto-draft' !== $existing_product_status ) {

                $updated_post = get_post( $data['ID'] );

                if ( $updated_post && $updated_post->post_title !== $data['post_title'] ) {
                    
                    $data['post_status'] = 'draft'; // Set the post status to draft
                    
                    add_filter( 'redirect_post_location', 'prevent_duplicate_product_redirect', 10, 2 ); // Hook to modify the redirect URL
                    
                    add_action( 'admin_notices', 'prevent_duplicate_product_notice' ); // Hook to display the error message
                }
            }
        }
    }

    return $data;
}
//add_filter( 'wp_insert_post_data', 'prevent_duplicate_product' );

function prevent_duplicate_product_redirect( $location, $post_id ) {
    remove_filter( 'redirect_post_location', 'prevent_duplicate_product_redirect', 10 );
    return add_query_arg( 'duplicate_error', 'true', $location );
}

function prevent_duplicate_product_notice() {
    if ( isset( $_GET['duplicate_error'] ) && $_GET['duplicate_error'] === 'true' ) {
        echo '<div class="error"><p>A product with the same title already exists. The product has been saved as a draft.</p></div>';
    }
}


//Featured image
/*$auctionID = get_field('auction_no');
$referenceID = get_field('reference_no');

$auctionID = $auctionID;
$referenceID = $referenceID;

$args = array(
    'post_type' => 'product',
    //'posts_per_page' => -1,
);

$products = new WP_Query($args);

if ($products->have_posts()) {
    while ($products->have_posts()) {
        $products->the_post();
        $product_id = get_the_ID();
		
		// Check if the product image has already been updated
            $is_image_updated = get_post_meta($product_id, '_image_updated', true);
			
if (!$is_image_updated) {
        // Assuming auctionID and referenceID are stored as post meta
        $auctionID = get_post_meta($product_id, 'auction_no', true);
        $referenceID = get_post_meta($product_id, 'reference_no', true);

        $product_image_url = 'https://listings.worldwideauctioneers.com/wp-content/uploads/photos/' . $auctionID . '/' . $referenceID . '/1.jpg';

        // Update the product thumbnail
        $image_id = upload_image_from_url($product_image_url, $product_id);
        set_post_thumbnail($product_id, $image_id);
       
        // Update the product image gallery
       update_post_meta($product_id, '_product_image_gallery', $image_id);
	   
	   // Mark the image as updated to avoid re-processing on the next page load
        update_post_meta($product_id, '_image_updated', true);

}
			
    }
}*/


function upload_image_from_url($product_image_url, $product_id)
{
  // Use cURL to fetch the image content
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $product_image_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $image_data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

     if ( $http_code == '200' )
     {
        $upload_dir = wp_upload_dir();

        $filename = basename( $product_image_url );
		
	    $auctionID = get_post_meta($product_id, 'auction_no', true);
        $referenceID = get_post_meta($product_id, 'reference_no', true);
		 
        // $file = $upload_dir['path'] . '/photos/' . $auctionID . '/' . $referenceID . '/1.jpg';

        file_put_contents( $product_image_url, $image_data );

        $wp_filetype = wp_check_filetype( $filename, null );

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $file, $product_id );

        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;
    }
    else
    {
        // If the image couldn't be fetched, return false
        return false;
    }
}

function wwa_photos_directory( $param ) {
	$auction_no = get_option( 'auction_no' );
	$reference_no = get_option( 'reference_no' );

    $mydir = "/photos/{$auction_no}/{$reference_no}";

    $param['path'] = $param['path'] . $mydir;
    $param['url'] = $param['url'] . $mydir;

    return $param;
}

function wwa_get_auction_id( $listings_page_url='' ) {

	$auctions = get_field( 'auctions_to_sync', 'wwa_options' );

	foreach( $auctions as $auction )
	{
		if( $auction['listings_page'] != $listings_page_url ) continue;

		return $auction['id'];
	}

}

function wwa_get_auction_name( $listings_page_url1='' ) {

	$auctionName = get_field( 'name', 'wwa_options' );

	foreach( $auctionName as $auction1 )
	{
		if( $auction1['listings_page'] != $listings_page_url1 ) continue;

		return $auction1['name'];
	}

}

function wwa_get_poducts_by_event( $event_id=false ) {

	if( ! $event_id ) return;

	$products = get_posts([
		'post_type'      => 'product',      // Post type: product
		'posts_per_page' => -1,             // Retrieve all posts
		'post_status'    => 'publish',      // Only published posts
		'meta_key'       => 'make',         // The ACF field to sort by
		'orderby'        => 'meta_value',   // Order by the value of the meta_key
		'order'          => 'ASC',          // Sort in ascending order
		'meta_query'     => [               // Meta query array
			'relation' => 'AND',            // Define the relation - In this case, it's AND
			[
				'key'     => 'auction_no',  // Your existing condition for 'auction_no'
				'value'   => $event_id,
				'compare' => '='            // Comparing for equality
			],
			[
				'key'     => 'make',        // Ensure 'make' field exists for the posts
				'compare' => 'EXISTS'       // Checking the existence of the 'make' custom field
			]
			],
		'fields' => 'ids'
	]);
	

	return $products;
}

function wwa_get_poducts_by_event_make_ascending( $event_id=false ) {

	if( ! $event_id ) return;

	$products = get_posts([
		'post_type'      => 'product',      // Post type: product
		'posts_per_page' => -1,             // Retrieve all posts
		'post_status'    => 'publish',      // Only published posts
		'meta_key'       => 'make',         // The ACF field to sort by
		'orderby'        => 'meta_value',   // Order by the value of the meta_key
		'order'          => 'ASC',          // Sort in ascending order
		'meta_query'     => [               // Meta query array
			'relation' => 'AND',            // Define the relation - In this case, it's AND
			[
				'key'     => 'auction_no',  // Your existing condition for 'auction_no'
				'value'   => $event_id,
				'compare' => '='            // Comparing for equality
			],
			[
				'key'     => 'make',        // Ensure 'make' field exists for the posts
				'compare' => 'EXISTS'       // Checking the existence of the 'make' custom field
			]
			],
		'fields' => 'ids'
	]);
	

	return $products;
}

function wwa_get_poducts_by_event_make_descending( $event_id=false ) {

	if( ! $event_id ) return;

	$products = get_posts([
		'post_type'      => 'product',      // Post type: product
		'posts_per_page' => -1,             // Retrieve all posts
		'post_status'    => 'publish',      // Only published posts
		'meta_key'       => 'make',         // The ACF field to sort by
		'orderby'        => 'meta_value',   // Order by the value of the meta_key
		'order'          => 'DESC',          // Sort in ascending order
		'meta_query'     => [               // Meta query array
			'relation' => 'AND',            // Define the relation - In this case, it's AND
			[
				'key'     => 'auction_no',  // Your existing condition for 'auction_no'
				'value'   => $event_id,
				'compare' => '='            // Comparing for equality
			],
			[
				'key'     => 'make',        // Ensure 'make' field exists for the posts
				'compare' => 'EXISTS'       // Checking the existence of the 'make' custom field
			]
			],
		'fields' => 'ids'
	]);
	

	return $products;
}

function wwa_get_poducts_by_event_year_ascending( $event_id=false ) {

	if( ! $event_id ) return;

	$products = get_posts([
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'year', // Specify the ACF field to sort by.
		'orderby'        => 'meta_value_num', // Order by the numeric value of the meta key.
		'order'          => 'ASC', // Sort in ascending order.
		'meta_query'     => [
			[
				'key'     => 'auction_no',
				'value'   => $event_id,
				'compare' => '='
			]
		],
		'fields' => 'ids'
	]);

	return $products;
}

function wwa_get_poducts_by_event_year_descending( $event_id=false ) {

	if( ! $event_id ) return;

	$products = get_posts([
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'year', // Specify the ACF field to sort by.
		'orderby'        => 'meta_value_num', // Order by the numeric value of the meta key.
		'order'          => 'DESC', // Sort in ascending order.
		'meta_query'     => [
			[
				'key'     => 'auction_no',
				'value'   => $event_id,
				'compare' => '='
			]
		],
		'fields' => 'ids'
	]);

	return $products;
}

function wwa_get_poducts_by_event_lot_ascending( $event_id=false ) {

	if( ! $event_id ) return;

	$products = get_posts([
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'lot_number', // Specify the ACF field to sort by.
		'orderby'        => 'meta_value_num', // Order by the numeric value of the meta key.
		'order'          => 'ASC', // Sort in ascending order.
		'meta_query'     => [
			[
				'key'     => 'auction_no',
				'value'   => $event_id,
				'compare' => '='
			]
		],
		'fields' => 'ids'
	]);

	return $products;
}

function wwa_get_poducts_by_event_lot_descending( $event_id=false ) {

	if( ! $event_id ) return;

	$products = get_posts([
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'lot_number', // Specify the ACF field to sort by.
		'orderby'        => 'meta_value_num', // Order by the numeric value of the meta key.
		'order'          => 'DESC', // Sort in ascending order.
		'meta_query'     => [
			[
				'key'     => 'auction_no',
				'value'   => $event_id,
				'compare' => '='
			]
		],
		'fields' => 'ids'
	]);

	return $products;
}

function wwa_get_featured_poducts_by_event( $event_id=false ) {

	if( ! $event_id ) return;

	$featuredproducts = get_posts([
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'auction_no',
		'meta_value'     => $event_id,
		'order'          => 'ASC',
		'fields'         => 'ids',
		'meta_query'     => [
			[
				'key'     => 'Featured', // The ACF field name
				'value'   => '1',        // ACF stores true as '1'
				'compare' => '=',        // Check for exact match
			],
		],
	]);

	return $featuredproducts;
}



function wwa_verify_sync_code( $code=false ) {
	$verified = false;

	if( get_user_meta( get_current_user_id(), 'sync_verification_code', true ) )
	{
		$verified = true;

		delete_user_meta( get_current_user_id(), 'sync_verification_code' );
	}

	return $verified;
}

function add_acf_sorting_option( $orderby ) {
    global $wpdb;

    $orderby['acf_field_name'] = "ACF Field Name (ASC)";
    $orderby['acf_field_name_desc'] = "ACF Field Name (DESC)";

    return $orderby;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'add_acf_catalog_ordering_args' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'add_acf_sorting_option' );
add_filter( 'woocommerce_catalog_orderby', 'add_acf_sorting_option' );

function add_acf_catalog_ordering_args( $args ) {
    global $wpdb;

    if ( 'acf_field_name' === $args['orderby'] ) {
        $args['orderby'] = 'meta_value';
        $args['order'] = 'ASC';
        $args['meta_key'] = 'acf_field_name';
    }

    if ( 'acf_field_name_desc' === $args['orderby'] ) {
        $args['orderby'] = 'meta_value';
        $args['order'] = 'DESC';
        $args['meta_key'] = 'acf_field_name';
    }

    return $args;
}

function get_acf_features_shortcode() {
    // Check if the ACF function exists to prevent errors in case the ACF plugin is not activated.
    if (function_exists('get_field')) {
        // Retrieve the 'features' custom field from the current post or page
        $features = get_field('features');

        // Check if the features field has any value
        if ($features) {
            // Split the features by " | " to create an array of individual features
            $features_array = explode(' | ', $features);

            // Initialize an output variable to store the HTML for the list
            $output = '<ul class="features-list">';

            // Loop through each feature and add it as a list item
            foreach ($features_array as $feature) {
                // Ensure that the text is safe to display
                $output .= '<li>' . esc_html($feature) . '</li>';
            }

            // Close the list HTML
            $output .= '</ul>';

            // Return the final list HTML
            return $output;
        }
    }

    // If the features field is empty or ACF is not active, return an empty string or a message
    return '';
}

// Register the shortcode with WordPress
add_shortcode('acf_features', 'get_acf_features_shortcode');

function check_acf_offered_without_reserve_shortcode() {
    // Check if the ACF function exists to prevent errors if the ACF plugin is not activated.
    if (function_exists('get_field')) {
        // Retrieve the value of the 'offered_without_reserve' custom field from the current post or page.
        $offered_without_reserve = get_field('offered_without_reserve');

        // Check if the 'offered_without_reserve' field is true (checked).
        if ($offered_without_reserve) {
            // If it is checked, return the desired div.
            return '<div class="reservation">OFFERED WITHOUT RESERVE</div>';
        }
    }

    // If the field is not checked or ACF is not active, return an empty string.
    return '';
}

// Register the shortcode with WordPress.
add_shortcode('acf_offered_without_reserve', 'check_acf_offered_without_reserve_shortcode');

function acf_offered_without_reserve_filter_shortcode() {
    // Enqueue the necessary JavaScript. (Make sure to replace 'your-script-handle' with a unique handle for your script.)
    wp_enqueue_script('your-script-handle');

    // Output the toggle switch HTML
    ob_start();
    ?>
    <div class="switch-offer">
        <label class="switch">
            <input type="checkbox" id="acf-offered-without-reserve-toggle">
            <span class="slider round"></span>
        </label>
        <span class="label-text">Offered Without Reserve</span>
    </div>
    <?php
    return ob_get_clean();
}

// Register the shortcode with WordPress.
add_shortcode('acf_owr_filter', 'acf_offered_without_reserve_filter_shortcode');

function enqueue_acf_owr_filter_script() {
    wp_enqueue_script('acf-owr-filter-js', get_template_directory_uri() . '/js/acf-owr-filter.js', array('jquery'), null, true);
    wp_localize_script('acf-owr-filter-js', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('acf-owr-filter-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_acf_owr_filter_script');

function ajax_filter_owr_products() {
    check_ajax_referer('acf-owr-filter-nonce', 'nonce');
    
    $offered_without_reserve = $_POST['offered_without_reserve'] === '1' ? true : false;
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'offered_without_reserve',
                'value'   => '1',
                'compare' => $offered_without_reserve ? '=' : '!='
            ),
        ),
    );
    
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product'); // This uses the standard WooCommerce product template part.
        }
    } else {
        echo '<p>No products found.</p>';
    }
    
    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_filter_owr_products', 'ajax_filter_owr_products');
add_action('wp_ajax_nopriv_filter_owr_products', 'ajax_filter_owr_products');

add_action('woocommerce_before_shop_loop', function () {
    if (!WOOF_REQUEST::isset('woof_before_shop_loop_done')) {
        echo do_shortcode('[woof_front_builder name="top filter"]');
    }
}, 2);


function lot_number_sorting_shortcode() {
    $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : '';

    $html = '<form action="" method="GET">';
    $html .= '<select name="sort_order" onchange="this.form.submit()">';
    $html .= '<option value="">Select Sorting</option>';
    $html .= '<option value="asc" ' . selected($sort_order, 'asc') . '>Ascending</option>';
    $html .= '<option value="desc" ' . selected($sort_order, 'desc') . '>Descending</option>';
    $html .= '</select>';
    $html .= '</form>';

    return $html;
}
add_shortcode('lot_number_sort', 'lot_number_sorting_shortcode');

function sort_products_by_lot_number($query) {
    if (!is_admin() && $query->is_main_query() && is_woocommerce()) {
        if (isset($_GET['sort_order']) && $_GET['sort_order'] != '') {
            $order = $_GET['sort_order'] == 'asc' ? 'ASC' : 'DESC';
            $query->set('meta_key', 'lot_number');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', $order);
        }
    }
}
add_action('pre_get_posts', 'sort_products_by_lot_number');

function custom_car_form_shortcode() {
    ob_start(); // Start output buffering
    ?>
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
    <form action="#" method="post" class="custom-form-container">
        <!-- Make Dropdown -->
        <div class="dropdown">
            <label for="make">Make:</label>
            <select id="make" name="make">
                <option value="">Select all</option>
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

				$sql = "SELECT DISTINCT smake FROM t_inventory WHERE ncategoryid = 1 AND smake != '' ORDER BY smake";
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
        </div>
        <!-- Model Dropdown -->
        <div class="dropdown">
            <label for="model">Model:</label>
            <select id="model" name="model">
                <option value="">Select all</option>
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

				$sql = "SELECT DISTINCT smodel FROM t_inventory WHERE ncategoryid = 1 AND smodel != '' ORDER BY smodel";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// Output data of each row
					while($row = $result->fetch_assoc()) {
						$selected = isset($_POST['model']) && $_POST['model'] == $row['smodel'] ? ' selected="selected"' : '';
						echo '<option value="' . htmlspecialchars($row['smodel']) . '"' . $selected . '>' . htmlspecialchars($row['smodel']) . '</option>';
					}
				} else {
					echo '<option value="">No models available</option>';
				}
				// Close connection
				$conn->close();
				?>
            </select>
        </div>
        <!-- Year Dropdown -->
        <div class="dropdown">
            <label for="year">Year:</label>
            <select id="year" name="year">
                <option value="">Select all</option>
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

				$sql = "SELECT DISTINCT syear FROM t_inventory WHERE ncategoryid = 1 AND syear != '' ORDER BY syear";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// Output data of each row
					while($row = $result->fetch_assoc()) {
						$selected = isset($_POST['year']) && $_POST['year'] == $row['syear'] ? ' selected="selected"' : '';
						echo '<option value="' . htmlspecialchars($row['syear']) . '"' . $selected . '>' . htmlspecialchars($row['syear']) . '</option>';
					}
				} else {
					echo '<option value="">No Year available</option>';
				}
				// Close connection
				$conn->close();
				?>
            </select>
        </div>
        <!-- Lot Number Dropdown -->
        <div class="dropdown">
            <label for="lot-number">Lot Number:</label>
            <select id="lot-number" name="lot_number">
                <option value="">Select all</option>
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

				$sql = "SELECT DISTINCT slotnumber FROM t_inventory WHERE ncategoryid = 1 AND slotnumber != '' ORDER BY slotnumber";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// Output data of each row
					while($row = $result->fetch_assoc()) {
						$selected = isset($_POST['lotnumber']) && $_POST['lotnumber'] == $row['slotnumber'] ? ' selected="selected"' : '';
						echo '<option value="' . htmlspecialchars($row['slotnumber']) . '"' . $selected . '>' . htmlspecialchars($row['slotnumber']) . '</option>';
					}
				} else {
					echo '<option value="">No Year available</option>';
				}
				// Close connection
				$conn->close();
				?>
            </select>
        </div>
        <input type="submit" value="Submit">
    </form> 
    <?php
    return ob_get_clean(); // Return the buffer contents and clear the buffer
}
add_shortcode('custom_car_form', 'custom_car_form_shortcode');

function filter_where_title_only($where, $wp_query) {
    global $wpdb;
    if ($search_term = $wp_query->get('search_title')) {
        // Using the $wpdb->prepare method for security.
        $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like($search_term) . '%');
    }
    return $where;
}
add_filter('posts_where', 'filter_where_title_only', 10, 2);


function my_custom_menu_shortcode($atts) {
    extract(shortcode_atts(array(
        'name' => '', // replace with your menu name, id, or slug
    ), $atts));

    return wp_nav_menu(array(
        'menu' => $name,
        'echo' => false
    ));
}
add_shortcode('my_custom_menu', 'my_custom_menu_shortcode');


function wwa_auto_delete_listing(){
	
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    

    $hostname = 'mysql24.ezhostingserver.com';
    $username = 'wa_myadmin';
    $password = 'W4_my@hm!N';
    $dbname = 'wa_online_db';
    $counter = 0;
    $counter2 = 0;
    $trashed_titles = [];

    require_once('wp-load.php');

    // Connect to your MySQL database
    $conn = new mysqli($hostname, $username, $password, $dbname);
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the MySQL server has gone away (timeout or server restart)
    if (!mysqli_ping($conn)) {
        die("MySQL server has gone away.");  // Halt script execution
    }
    
    // Query to select all sLotNumbers
    $sql = "SELECT sReference FROM `t_inventory` WHERE `nEventID` = '106'";
    $result = mysqli_query($conn, $sql);
    // Fetch all sLotNumbers into an array
    $database_lot_numbers = [];
    while($row = mysqli_fetch_assoc($result)) {
        $database_lot_numbers[] = $row['sReference'];
    }
    
    // Get all posts/products with ACF field 'lot_number'
    $args = [
        'post_type' => 'product', // or 'post' or your custom post type
        'posts_per_page' => -1, // Get all posts
    ];
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        // Get ACF field 'lot_number' for the post
        $lot_number = get_field('reference_no', $post->ID);
        $title = $lot_number.'-'.get_the_title();
        
        // If lot_number isn't in the database, trash the post
        if (!in_array($lot_number, $database_lot_numbers)) {
            wp_trash_post($post->ID);
            echo $title." deleted.<br>";
            //echo $title." not found.";
            $trashed_titles[] = $title;
        }
       
    }

     // Check if there are any trashed titles
     if (count($trashed_titles) > 0) {
        // Convert the array of titles into a string, each title on a new line
        $trashed_titles_string = implode("\n", $trashed_titles);

        // Set up the email details
        $to = 'justin@worldwideauctioneers.com, jetherg15@gmail.com, jether@astraapplications.com, ann@astraapplications.com, terry@worldwideauctioneers.com';  // Replace with your email
        $subject = 'Deleted Listings';
        $message = "The following listing(s) was/were deleted:\n(This is a system-genareted email.)\n" . $trashed_titles_string;
        $headers = 'From: no-reply@worldwideauctioneers.com';
        // Send the email
        if(mail($to, $subject, $message, $headers)){
            echo "Email Sent Successfully!";
        }else{
            echo "Email Sending Failed.";
        }
    }
	else{
        echo "No listing(s) deleted.";
    }
    
    mysqli_close($conn);

}

function add_jquery_to_head() {
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	

    <?php
}
add_action('wp_head', 'add_jquery_to_head', 1);





