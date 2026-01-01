jQuery(document).ready(function($) {
    // Listen for Comet events
    window.addEventListener('message', function(event) {
        if (event.data.type === 'comet-ready') {
            console.log('Comet loaded on supfit.lv');
            // Fetch product data for chat context
            if (cometConfig.isWooCommerce === 'yes') {
                cometInit();
            }
        }
    });

    function cometInit() {
        // Use WooCommerce fragments endpoint (no auth needed, works with sessions)
        $.get(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'), function(data) {
            // Extract cart contents from fragments response
            if (data && data.fragments) {
                window.postMessage({
                    type: 'comet-cart-update',
                    data: {
                        cart_hash: data.cart_hash,
                        fragments: data.fragments
                    }
                }, '*');
            }
        }).fail(function(xhr) {
            console.warn('Comet: Could not fetch cart data', xhr.status);
        });
    }
});
