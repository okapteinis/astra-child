<?php
/**
 * Astra Child Theme functions.php
 */

// Enqueue parent Astra styles
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');
function astra_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

// Enqueue Comet Assistant
add_action('wp_enqueue_scripts', 'enqueue_comet_assistant');
function enqueue_comet_assistant() {
    // Comet JS widget
    wp_enqueue_script(
        'comet-widget',
        'https://comet-assistant.com/widget.js', // Replace with actual Comet script URL
        array(),
        '1.0.0',
        true
    );
    
    // Pass data to JS (e.g., API key, user info)
    wp_localize_script('comet-widget', 'cometConfig', array(
        'apiKey' => 'YOUR_COMET_API_KEY',
        'siteUrl' => home_url(),
        'isWooCommerce' => class_exists('WooCommerce') ? 'yes' : 'no',
    ));

    // Inline CSS for dynamic positioning
    wp_add_inline_style('astra-theme-css', '
        .comet-chat-toggle { /* Comet button styles */ }
    ');
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
    if (!get_option('astra_child_activated')) {
        echo '<div class="notice notice-info"><p>Astra Child with Comet is ready! Test on shop pages.</p></div>';
        update_option('astra_child_activated', true);
    }
}
?>
