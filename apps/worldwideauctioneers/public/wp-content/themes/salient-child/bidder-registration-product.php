<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
add_action('admin_menu', 'register_my_custom_submenu_page');

function register_my_custom_submenu_page()
{
    add_submenu_page('woocommerce', 'Bidder Registration Settings', 'Bidder Registration Settings', 'manage_options', 'bidder-registration-settings', 'hma_bidder_registration_settings');
}

function hma_bidder_registration_settings()
{
    if (isset($_POST['security']) && $_POST['security'] == wp_create_nonce(wp_salt())) {
        update_option('bidder_registration_settings', $_POST["bidder_registration_settings"]);
        echo '<script> window.location = "' . admin_url('/admin.php?page=bidder-registration-settings') . '"; </script>';
    }
    $bidder_registration_settings  = get_option('bidder_registration_settings');
?>
    <form method="post">
        <input type="hidden" name="security" value="<?php echo wp_create_nonce(wp_salt()) ?>">
        <div class="wrap">
            <h1>Bidder Registration Settings</h1>
            <table class="form-table">
                <tr valign="top">
                    <th>Registration Product</th>
                    <td>
                        <select name="bidder_registration_settings[registration_product]" style="width: 100%">
                            <option value="">Select Product</option>
                            <?php
                            $product_query = new WP_Query([
                                'post_type' => 'product',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                            ]);
                            foreach ($product_query->posts as $post) {
                                echo '<option ' . ($bidder_registration_settings['registration_product'] == $post->ID ? "selected='selected'" : '') . ' value="' . $post->ID . '" >' . $post->post_title . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <td></td>
                    <td><button type="submit" class="button button-primary">Update</button></td>
                </tr>
            </table>
        </div>
    </form>
<?php
}

add_filter('gform_confirmation_28', 'hma_gform_confirmation_28', 10, 4);
function hma_gform_confirmation_28($confirmation, $form, $entry, $ajax)
{
    $_SESSION['bidder_data_entry'] = $entry;


    if (!WC()->cart->is_empty()) {
        WC()->cart->empty_cart();
    }
    $bidder_registration_settings  = get_option('bidder_registration_settings');
    WC()->cart->add_to_cart($bidder_registration_settings['registration_product']);
    $checkout_page_id = wc_get_page_id('checkout');
    $confirmation = array('redirect' => get_permalink($checkout_page_id));

    return $confirmation;
}
add_action('wp_head', 'hma_wp_head');
function hma_wp_head()
{
    if (isset($_GET['test']) && $_GET['test'] == true) {
        echo '<pre>';
        print_r($_SESSION['bidder_data_entry']);
        echo '</pre>';
    }
}
add_filter('woocommerce_add_cart_item_data', 'hma_woocommerce_add_cart_item_data', 10, 3); /* step 3 Add custom cart item data */
function hma_woocommerce_add_cart_item_data($cart_item_data, $product_id, $variation_id)
{

    $entry = $_SESSION['bidder_data_entry'];


    if (isset($_SESSION['bidder_data_entry']) && !empty($_SESSION['bidder_data_entry'])) {
        $data = [
            'gform_entry_id' => $entry['id'],
            // 'first_name' => $entry['1.3'],
            // 'last_name' => $entry['1.6'],
            // 'email' => $entry['2'],
            // 'auction_type' => $entry['282'],
            // 'bidding_type' => $entry['112'],
            // 'phone_number' => $entry['105'],
            // 'email_secondry' => $entry['285'],
            // 'date_of_birth' => $entry['9'],
            // 'dealer_number' => $entry['5'],
            // 'resale_tax_id' => $entry['6'],
            // 'state_license' => $entry['7'],
            // 'how_are_you_paying' => $entry['109'],
            // 'signature' => $entry['32'],
            // 'like_ww_call' => $entry['16'],
            'bidd_final_price' => $entry['261'],
        ];
        foreach ($data as $key => $value) {
            $cart_item_data[$key] = sanitize_text_field($value);
        }
    }
    return $cart_item_data;
}
add_filter('woocommerce_get_item_data',  'hma_woocommerce_get_item_data', 10, 2); /* step 4 Display custom item data in the cart */
function hma_woocommerce_get_item_data($item_data, $cart_item_data)
{

    $data = [
        [
            'label' => 'G Form Entry ID',
            'key' => 'gform_entry_id',
        ],
        // [
        //     'label' => 'First name',
        //     'key' => 'first_name',
        // ],
        // [
        //     'label' => 'Last name',
        //     'key' => 'last_name',
        // ],
        // [
        //     'label' => 'Email',
        //     'key' => 'email',
        // ],
        // [
        //     'label' => 'Auction Type',
        //     'key' => 'auction_type',
        // ],
        // [
        //     'label' => 'Bidding Type',
        //     'key' => 'bidding_type',
        // ],
        // [
        //     'label' => 'Phone Number',
        //     'key' => 'phone_number',
        // ],
        // [
        //     'label' => 'Secondry Email',
        //     'key' => 'email_secondry',
        // ],
        // [
        //     'label' => 'Date of Birth',
        //     'key' => 'date_of_birth',
        // ],
        // [
        //     'label' => 'Dealer Number',
        //     'key' => 'dealer_number',
        // ],
        // [
        //     'label' => 'Resale Tax ID',
        //     'key' => 'resale_tax_id',
        // ],
        // [
        //     'label' => 'State License',
        //     'key' => 'state_license',
        // ],
        // [
        //     'label' => 'How Are You Paying',
        //     'key' => 'how_are_you_paying',
        // ],
        // [
        //     'label' => 'Signature (Type in Full Name)',
        //     'key' => 'signature',
        // ],
        // [
        //     'label' => 'Would you like a worldwide representative to call',
        //     'key' => 'like_ww_call',
        // ],
    ];
    foreach ($data as $input) {
        if (isset($cart_item_data[$input['key']])) {
            $item_data[] = array(
                'key' => __($input['label'], 'woocommerce'),
                'value' => wc_clean($cart_item_data[$input['key']])
            );
        }
    }

    return $item_data;
}
add_filter('woocommerce_get_cart_item_from_session', 'hma_woocommerce_get_cart_item_from_session', 1, 3);
function hma_woocommerce_get_cart_item_from_session($item, $values, $key)
{

    $data = [
        [
            'label' => 'G Form Entry ID',
            'key' => 'gform_entry_id',
        ],
        // [
        //     'label' => 'First name',
        //     'key' => 'first_name',
        // ],
        // [
        //     'label' => 'Last name',
        //     'key' => 'last_name',
        // ],
        // [
        //     'label' => 'Email',
        //     'key' => 'email',
        // ],
        // [
        //     'label' => 'Auction Type',
        //     'key' => 'auction_type',
        // ],
        // [
        //     'label' => 'Bidding Type',
        //     'key' => 'bidding_type',
        // ],
        // [
        //     'label' => 'Phone Number',
        //     'key' => 'phone_number',
        // ],
        // [
        //     'label' => 'Secondry Email',
        //     'key' => 'email_secondry',
        // ],
        // [
        //     'label' => 'Date of Birth',
        //     'key' => 'date_of_birth',
        // ],
        // [
        //     'label' => 'Dealer Number',
        //     'key' => 'dealer_number',
        // ],
        // [
        //     'label' => 'Resale Tax ID',
        //     'key' => 'resale_tax_id',
        // ],
        // [
        //     'label' => 'State License',
        //     'key' => 'state_license',
        // ],
        // [
        //     'label' => 'How Are You Paying',
        //     'key' => 'how_are_you_paying',
        // ],
        // [
        //     'label' => 'Signature (Type in Full Name)',
        //     'key' => 'signature',
        // ],
        // [
        //     'label' => 'Would you like a worldwide representative to call',
        //     'key' => 'like_ww_call',
        // ],
    ];
    foreach ($data as $input) {
        $item[$input['key']] = $values[$input['key']];
    }
    return $item;
}
add_action('woocommerce_add_order_item_meta', 'hma_woocommerce_add_order_item_meta', 1, 2);
function hma_woocommerce_add_order_item_meta($item_id, $values)
{
    $data = [
        [
            'label' => 'G Form Entry ID',
            'key' => 'gform_entry_id',
        ],
        // [
        //     'label' => 'First name',
        //     'key' => 'first_name',
        // ],
        // [
        //     'label' => 'Last name',
        //     'key' => 'last_name',
        // ],
        // [
        //     'label' => 'Email',
        //     'key' => 'email',
        // ],
        // [
        //     'label' => 'Auction Type',
        //     'key' => 'auction_type',
        // ],
        // [
        //     'label' => 'Bidding Type',
        //     'key' => 'bidding_type',
        // ],
        // [
        //     'label' => 'Phone Number',
        //     'key' => 'phone_number',
        // ],
        // [
        //     'label' => 'Secondry Email',
        //     'key' => 'email_secondry',
        // ],
        // [
        //     'label' => 'Date of Birth',
        //     'key' => 'date_of_birth',
        // ],
        // [
        //     'label' => 'Dealer Number',
        //     'key' => 'dealer_number',
        // ],
        // [
        //     'label' => 'Resale Tax ID',
        //     'key' => 'resale_tax_id',
        // ],
        // [
        //     'label' => 'State License',
        //     'key' => 'state_license',
        // ],
        // [
        //     'label' => 'How Are You Paying',
        //     'key' => 'how_are_you_paying',
        // ],
        // [
        //     'label' => 'Signature (Type in Full Name)',
        //     'key' => 'signature',
        // ],
        // [
        //     'label' => 'Would you like a worldwide representative to call',
        //     'key' => 'like_ww_call',
        // ],
    ];

    foreach ($data as $input) {


        if (!empty($values[$input['key']])) {
            wc_add_order_item_meta($item_id, $input['label'], $values[$input['key']]);
        }
    }
}

add_action('woocommerce_before_calculate_totals', 'hma_woocommerce_before_calculate_totals', 99); /* chane price of product */

function hma_woocommerce_before_calculate_totals($cart_object)
{



    $cart_items = $cart_object->cart_contents;
    if (!empty($cart_items)) {
        foreach ($cart_items as $key => $value) {

            if (isset($value['bidd_final_price'])) {
                $value['data']->set_price($value['bidd_final_price']);
            }
        }
    }
}

// add_filter('woocommerce_add_to_cart_redirect', 'hma_woocommerce_add_to_cart_redirect');
// function hma_woocommerce_add_to_cart_redirect($url)
// {
//     $bidder_registration_settings  = get_option('bidder_registration_settings');


//     global $woocommerce;
//     $items = $woocommerce->cart->get_cart();

//     foreach ($items as $item => $values) {
//         if ($values['data']->get_id() == $bidder_registration_settings['registration_product']) {
//             return wc_get_checkout_url();
//         }
//     }
// }
