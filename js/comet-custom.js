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
        // Example: Send current cart to Comet
        $.get('/wp-json/wc/v3/cart', function(data) {
            window.postMessage({type: 'comet-cart-update', data: data}, '*');
        });
    }
});
