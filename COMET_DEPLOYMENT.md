# Comet Assistant Integration for Astra Child Theme
## Deployment & Testing Guide

### Prerequisites
- WordPress site with Astra parent theme installed and active
- Astra Child theme installed (this repository)
- WooCommerce plugin installed (for full functionality)
- Access to Hetzner VPS/YunoHost instance
- Git installed on server

### Step 1: Clone the Repository to Your Server

SSH into your Hetzner VPS and navigate to the WordPress themes directory:

```bash
cd /var/www/wordpress/wp-content/themes/
git clone https://github.com/okapteinis/astra-child.git
```

Or, if already cloned, update the theme:

```bash
cd astra-child
git pull origin master
```

### Step 2: Activate the Theme in WordPress

1. Log in to WordPress Admin Dashboard
2. Navigate to **Appearance > Themes**
3. Find "Astra Child" in the theme list
4. Click **Activate**

You should see the admin notice: "Astra Child with Comet is ready! Test on shop pages."

### Step 3: Configure Comet API Settings

#### Update the API Key

Edit `functions.php` and replace `YOUR_COMET_API_KEY` with your actual Comet API key:

```php
'apiKey' => 'YOUR_ACTUAL_API_KEY_HERE',
```

Alternatively, use WordPress options or environment variables for better security.

#### Update Comet Script URL (if needed)

If your Comet widget script is hosted at a different location, update this line in `functions.php`:

```php
'https://comet-assistant.com/widget.js', // Replace with your actual URL
```

### Step 4: Testing

#### Test 1: Shop Page

1. Navigate to your WooCommerce shop page (e.g., `supfit.lv/shop`)
2. Look for the Comet chat widget button in the bottom-right corner
3. Verify it appears as a floating circular button with smooth animations

#### Test 2: Product Page

1. Open any single product page (e.g., `supfit.lv/product/example`)
2. Verify the "💬 Comet Help" button appears below the product title
3. Click the button and verify the Comet chat window opens
4. Check browser console (F12 > Console) for any JavaScript errors

#### Test 3: Cart Functionality

1. Add products to cart
2. Navigate to cart page
3. Open the browser console and check for the message: "Comet loaded on supfit.lv"
4. Verify the postMessage events are firing correctly (check Network tab)

#### Test 4: Browser Console Debugging

Open Developer Tools (F12) and check for:
- No 403/404 errors for Comet widget scripts
- `cometConfig` object should be accessible: `console.log(cometConfig)`
- Custom JS file should load without errors

### Step 5: Verify File Structure

After pulling the code, verify the following files exist:

```
astra-child/
├── COMET_DEPLOYMENT.md    # This file
├── functions.php          # Enhanced with Comet integration hooks
├── style.css              # Updated with Comet widget CSS
├── js/
│   └── comet-custom.js   # Custom Comet event handling
└── screenshot.png
```

### Step 6: WooCommerce Hooks

The theme integrates Comet at two key points:

1. **Product Page**: Hook at `woocommerce_single_product_summary` (priority 25)
   - Adds chat button above product details

2. **Shop Loop**: Hook at `woocommerce_after_shop_loop_item`
   - Displays recommendations for each product in listings

### Customization

#### Modify Comet Button Appearance

Edit the CSS in `style.css` to customize the floating button:

```css
.comet-chat-widget {
    bottom: 20px;        /* Distance from bottom */
    right: 20px;         /* Distance from right */
    z-index: 9999;       /* Stacking order */
    border-radius: 50%;  /* Circular shape */
}
```

#### Add Custom Event Handlers

Extend `js/comet-custom.js` to add more event listeners or AJAX calls:

```javascript
window.addEventListener('message', function(event) {
    if (event.data.type === 'comet-custom-event') {
        // Handle custom Comet events
    }
});
```

### Troubleshooting

#### Comet Widget Not Appearing

1. Check that the parent Astra theme is active
2. Verify `functions.php` enqueue scripts are correct
3. Check browser console for script loading errors
4. Ensure the Comet widget script URL is accessible

#### Admin Notice Not Showing

- The notice appears once and is then cached via WordPress options
- Clear via: `wp_delete_option('astra_child_activated');` in WP CLI

#### WooCommerce Hooks Not Firing

1. Ensure WooCommerce is installed and activated
2. Check `functions.php` for `class_exists('WooCommerce')` validation
3. Verify hooks are firing in correct priority order

### Maintenance

#### Keeping the Theme Updated

Regularly pull updates from the repository:

```bash
cd /var/www/wordpress/wp-content/themes/astra-child
git pull origin master
```

#### Monitoring Comet Integration

- Check WordPress error logs in `/var/www/wordpress/wp-content/debug.log`
- Monitor browser console for JavaScript errors
- Test Comet functionality after any WordPress or plugin updates

### License & Support

- **License**: GPL v2 or later (compatible with WordPress.org)
- **Parent Theme**: Astra Child (forked from BrainStorm Force)
- **Customizations**: Comet Assistant integration for WooCommerce

### Git Commits Made

1. `Add Comet widget CSS` - Updated style.css with floating widget styles
2. `Implement Comet integration with WooCommerce hooks` - Enhanced functions.php
3. `Add custom JS file for advanced Comet logic` - Created js/comet-custom.js

---

**Last Updated**: January 2026
**Server**: Hetzner VPS with YunoHost
**Site**: supfit.lv
