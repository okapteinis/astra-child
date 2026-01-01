<?php
/**
 * Astra Child Theme functions.php
 */

// Enqueue parent Astra styles
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');
function astra_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

// Helper function to get Comet configuration
function astra_child_get_comet_config() {
    return array(
        'script_url' => get_option('astra_child_comet_script_url', 'https://comet-assistant.com/widget.js'),
        'api_key' => get_option('astra_child_comet_api_key', ''), // Empty default, must be set in admin
    );
}

// Optional: Add admin settings page for Comet configuration
add_action('admin_menu', 'astra_child_comet_settings_menu');
function astra_child_comet_settings_menu() {
    add_theme_page(
        'Comet Settings',
        'Comet Settings',
        'manage_options',
        'astra-child-comet',
        'astra_child_comet_settings_page'
    );
}

function astra_child_comet_settings_page() {
    if (isset($_POST['astra_child_comet_save'])) {
        check_admin_referer('astra_child_comet_settings');
        update_option('astra_child_comet_script_url', sanitize_url($_POST['comet_script_url']));
        update_option('astra_child_comet_api_key', sanitize_text_field($_POST['comet_api_key']));
        echo '<div class="notice notice-success"><p>Comet settings saved!</p></div>';
    }

    $config = astra_child_get_comet_config();
    ?>
    <div class="wrap">
        <h1>Comet Assistant Settings</h1>
        <form method="post">
            <?php wp_nonce_field('astra_child_comet_settings'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="comet_script_url">Comet Script URL</label></th>
                    <td>
                        <input type="url" id="comet_script_url" name="comet_script_url"
                               value="<?php echo esc_attr($config['script_url']); ?>"
                               class="regular-text" />
                        <p class="description">Full URL to Comet widget JavaScript file</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="comet_api_key">Comet API Key</label></th>
                    <td>
                        <input type="text" id="comet_api_key" name="comet_api_key"
                               value="<?php echo esc_attr($config['api_key']); ?>"
                               class="regular-text" />
                        <p class="description">Your Comet Assistant API key</p>
                    </td>
                </tr>
            </table>
            <p><input type="submit" name="astra_child_comet_save" class="button-primary" value="Save Settings" /></p>
        </form>
    </div>
    <?php
}

// Enqueue Comet Assistant
add_action('wp_enqueue_scripts', 'enqueue_comet_assistant');
function enqueue_comet_assistant() {
    $config = astra_child_get_comet_config();

    // Only enqueue if API key is configured
    if (empty($config['api_key'])) {
        // Optional: show admin notice
        if (current_user_can('manage_options')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p>Comet API key not configured. <a href="' . admin_url('themes.php?page=astra-child-comet') . '">Configure now</a></p></div>';
            });
        }
        return; // Don't load widget without API key
    }

    // Comet JS widget
    wp_enqueue_script(
        'comet-widget',
        esc_url($config['script_url']),
        array(),
        '1.0.1', // Increment version
        true
    );

    // Pass data to JS (e.g., API key, user info)
    wp_localize_script('comet-widget', 'cometConfig', array(
        'apiKey' => esc_js($config['api_key']),
        'siteUrl' => esc_url(home_url()),
        'isWooCommerce' => class_exists('WooCommerce') ? 'yes' : 'no',
    ));

    // Inline CSS for dynamic positioning
    if (wp_style_is('astra-theme-css', 'enqueued')) {
        wp_add_inline_style('astra-theme-css', '
            .comet-chat-toggle { /* Comet button styles */ }
        ');
    }
}

// WooCommerce-specific Comet hooks
if (class_exists('WooCommerce')) {
    // Add Comet button to product pages
    add_action('woocommerce_single_product_summary', 'add_comet_chat_button', 25);
    function add_comet_chat_button() {
        echo '<button id="comet-toggle" class="comet-chat-widget">💬 Comet Help</button>';
        echo '<script>
            document.getElementById("comet-toggle").addEventListener("click", function() {
                // Trigger Comet open via postMessage or custom event
                window.postMessage({type: "comet-open"}, "*");
            });
        </script>';
    }

    // Comet product recommendations hook
    add_action('woocommerce_after_shop_loop_item', 'comet_recommendations');
    function comet_recommendations() {
        // AJAX call to Comet for personalized recs
        echo '<div id="comet-recs" data-product-id="' . get_the_ID() . '"></div>';
    }
}

// Admin notice for activation
add_action('admin_notices', 'astra_child_admin_notice');
function astra_child_admin_notice() {
    // Show notice once, then hide for 30 days
    if (false === get_transient('astra_child_welcome_notice_dismissed')) {
        $screen = get_current_screen();
        // Only show on themes page for relevance
        if ($screen && $screen->id === 'themes') {
            ?>
            <div class="notice notice-info is-dismissible">
                <p><strong>Astra Child with Comet is ready!</strong> Test Comet on shop pages.
                   <a href="<?php echo admin_url('themes.php?page=astra-child-comet'); ?>">Configure Comet settings</a>
                </p>
            </div>
            <?php
            // Auto-dismiss after showing once (transient expires in 30 days)
            set_transient('astra_child_welcome_notice_dismissed', true, 30 * DAY_IN_SECONDS);
        }
    }
}

// Cleanup transient on theme switch
add_action('switch_theme', 'astra_child_cleanup_on_deactivation');
function astra_child_cleanup_on_deactivation() {
    delete_transient('astra_child_welcome_notice_dismissed');
    // Optional: cleanup options too
    delete_option('astra_child_comet_api_key');
    delete_option('astra_child_comet_script_url');
}
?>


// ===== ChatGPT Atlas Friendly Enhancements =====

// Output additional JSON-LD Product schema for single products
add_action( 'wp_head', 'supfit_add_product_schema', 30 );
function supfit_add_product_schema() {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) {
        return;
    }

    global $product;
    if ( ! $product instanceof WC_Product ) {
        return;
    }

    $schema = array(
        '@context'  => 'https://schema.org',
        '@type'     => 'Product',
        'name'      => get_the_title(),
        'image'     => wp_get_attachment_url( $product->get_image_id() ),
        'description' => wp_strip_all_tags( get_the_excerpt(), true ),
        'sku'       => $product->get_sku(),
        'offers'    => array(
            '@type'         => 'Offer',
            'priceCurrency' => get_woocommerce_currency(),
            'price'         => $product->get_price(),
            'availability'  => $product->is_in_stock()
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock',
            'url'           => get_permalink(),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}

// Improve ARIA labels for WooCommerce buttons
add_filter( 'woocommerce_product_add_to_cart_text', 'supfit_add_to_cart_text', 10, 2 );
function supfit_add_to_cart_text( $text, $product ) {
    return __( 'Add to cart', 'astra-child' );
}

add_filter( 'woocommerce_product_add_to_cart_attributes', 'supfit_add_to_cart_attributes', 10, 2 );
function supfit_add_to_cart_attributes( $attributes, $product ) {
    $label = sprintf(
        __( 'Add "%s" to your cart', 'astra-child' ),
        $product->get_name()
    );
    $attributes['aria-label'] = esc_attr( $label );
    return $attributes;
}

// Clarify checkout button label for Atlas
add_filter( 'woocommerce_order_button_text', function( $text ) {
    return __( 'Place order (secure checkout)', 'astra-child' );
} );

// Simple breadcrumb hook before main content
add_action( 'astra_primary_content_top', 'supfit_simple_breadcrumb' );
function supfit_simple_breadcrumb() {
    if ( is_front_page() ) {
        return;
    }

    echo '<nav class="supfit-breadcrumb" aria-label="Breadcrumb">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">Home</a>';

    if ( is_shop() ) {
        echo ' » <span>Shop</span>';
    } elseif ( is_product_category() ) {
        echo ' » <span>' . single_term_title( '', false ) . '</span>';
    } elseif ( is_product() ) {
        echo ' » <a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">Shop</a>';
        $terms = wc_get_product_terms( get_the_ID(), 'product_cat' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo ' » <span>' . esc_html( $terms[0]->name ) . '</span>';
        }
    }

    echo '</nav>';
}

// Optional: Disable distractions on checkout for clearer Atlas automation
add_action( 'wp', 'supfit_reduce_checkout_distractions' );
function supfit_reduce_checkout_distractions() {
    if ( is_checkout() ) {
        // Example: dequeue marketing popup scripts or banners here.
        // wp_dequeue_script( 'some-popup-handle' );
    }
}
