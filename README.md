# Astra Child Theme for Supfit.lv

A customized child theme for the Astra WordPress theme, enhanced with WooCommerce optimizations for the Supfit fitness supplement store.

## About This Theme

This is a lightweight, flexible child theme of the [Brainstorm Force Astra Theme](https://github.com/brainstormforce/astra-child). It maintains compatibility with the parent theme while adding custom functionality for Supfit.lv's e-commerce operations.

### Key Features

- **Comet Assistant Integration**: AI-powered customer support chat widget
- **ChatGPT Atlas Optimization**: Structured data and semantic HTML for autonomous AI agent navigation
- **WooCommerce Enhancements**: Improved product pages, streamlined checkout
- **JSON-LD Schema**: Explicit product schema for search engines and AI agents
- **Accessibility**: ARIA labels and semantic HTML standards

## Branches

This repository uses a two-branch strategy:

### `nightly` (Default Branch)

**Current Development & Production**

Contains all Supfit customizations including:
- Comet Assistant widget integration
- ChatGPT Atlas-friendly enhancements (JSON-LD schema, ARIA labels, breadcrumbs)
- Custom WooCommerce hooks and styling
- Supfit-specific functionality

This is the **active deployment branch** for supfit.lv on Hetzner VPS.

**Deploy using:**
```bash
git clone https://github.com/okapteinis/astra-child.git
cd astra-child
git checkout nightly
```

### `main`

**Clean Upstream Archive**

Contains an unmodified snapshot of the original [brainstormforce/astra-child](https://github.com/brainstormforce/astra-child) code.

Purpose:
- Serves as a reference point for the original parent theme
- Allows easy comparison of changes made for Supfit
- Facilitates syncing with upstream updates

**Do not deploy this branch** - it's for archival and comparison only.

### Switching Between Branches

```bash
# Switch to production/development (nightly)
git checkout nightly

# Switch to clean upstream snapshot (main)
git checkout main
```

## Installation

### Prerequisites

- WordPress 5.0 or higher
- Astra parent theme installed and activated
- WooCommerce plugin (for full functionality)
- PHP 7.4 or higher

### Setup

1. **Clone the repository** into your themes directory:

```bash
cd /var/www/wordpress/wp-content/themes/
git clone https://github.com/okapteinis/astra-child.git
cd astra-child
git checkout nightly  # Ensure you're on the production branch
```

2. **Activate the theme** in WordPress:
   - Go to WordPress Admin → Appearance → Themes
   - Find "Astra Child"
   - Click "Activate"

3. **Configure customizations:**
   - Update API keys in `functions.php` (Comet Assistant key, etc.)
   - Review settings in `style.css` for any layout customizations

## Customizations

### Comet Assistant Integration

See [COMET_DEPLOYMENT.md](./COMET_DEPLOYMENT.md) for:
- Comet widget installation
- WooCommerce hook integration
- Testing and debugging procedures

### ChatGPT Atlas Optimization

See [ATLAS_INTEGRATION.md](./ATLAS_INTEGRATION.md) for:
- JSON-LD Product schema implementation
- ARIA labels and accessibility improvements
- Breadcrumb navigation details
- Simplified checkout flow

## File Structure

```
astra-child/
├── README.md                    # This file
├── COMET_DEPLOYMENT.md         # Comet Assistant documentation
├── ATLAS_INTEGRATION.md        # ChatGPT Atlas optimization guide
├── style.css                   # Theme header + custom CSS
├── functions.php               # PHP hooks and customizations
├── screenshot.png              # Theme screenshot
├── js/
│   └── comet-custom.js        # Custom Comet event handling
└── .gitignore                  # Git ignore rules
```

## Development Workflow

### Making Changes

1. **Always work on the `nightly` branch:**

```bash
git checkout nightly
git pull origin nightly
```

2. **Make your changes** to theme files

3. **Test locally** (staging site recommended)

4. **Commit with descriptive messages:**

```bash
git add .
git commit -m "Add feature: [description]"
```

5. **Push to nightly:**

```bash
git push origin nightly
```

6. **Deploy to production** (Hetzner VPS):

```bash
cd /path/to/wp-content/themes/astra-child
git pull origin nightly
```

### Syncing with Upstream

To fetch updates from the original Astra theme:

```bash
# Add upstream if not present
git remote add upstream https://github.com/brainstormforce/astra-child.git

# Fetch updates
git fetch upstream

# Update main branch (archive)
git checkout main
git pull upstream master

# Review changes on nightly
git checkout nightly
git diff main..nightly
```

## Git Commands Reference

### Branch Management

```bash
# List all branches
git branch -a

# Switch branch
git checkout nightly
git checkout main

# Create new branch from nightly
git checkout nightly
git checkout -b feature/my-feature
```

### Remote Management

```bash
# View all remotes
git remote -v

# Add upstream
git remote add upstream https://github.com/brainstormforce/astra-child.git

# Fetch from all remotes
git fetch --all
```

## Testing

### Before Deployment

1. **Test on staging site** (if available)
2. **Check WooCommerce functionality:**
   - Product pages display correctly
   - Add-to-cart button works
   - Checkout flow is smooth
3. **Verify Comet widget** loads and functions
4. **Inspect JSON-LD schema** (Right-click → Inspect Element, look for `<script type="application/ld+json">`)
5. **Check ARIA labels** in button elements
6. **Test breadcrumb navigation** on category and product pages

### Debugging

- Check WordPress error logs: `/var/www/wordpress/wp-content/debug.log`
- Open browser console (F12) for JavaScript errors
- Use WP CLI for theme validation:
  ```bash
  wp theme list
  wp theme test-install astra-child
  ```

## Troubleshooting

### Common Issues

**Theme not activating:**
- Verify parent Astra theme is installed
- Check WordPress version (5.0+)
- Review debug.log for errors

**Comet widget not appearing:**
- Check Comet API key in functions.php
- Verify widget script URL is accessible
- Clear browser cache

**Custom styling not applied:**
- Ensure style.css is properly enqueued
- Check CSS specificity (Astra defaults may override)
- Clear WordPress/browser caches

**Git conflicts during pull:**
```bash
# Abort current merge
git merge --abort

# Check status
git status

# Manually resolve conflicts, then
git add .
git commit -m "Resolve merge conflicts"
```

## Git Commits Reference

Recent commits to this theme:

1. ✅ Add Comet widget CSS - Initial Comet integration styling
2. ✅ Implement Comet integration with WooCommerce hooks - Core Comet functionality
3. ✅ Add custom JS file for advanced Comet logic - Event handling and AJAX
4. ✅ Add Comet Assistant deployment guide - Documentation
5. ✅ Add JSON-LD Product schema and Atlas-friendly enhancements - AI agent optimization
6. ✅ Document ChatGPT Atlas friendly theme adjustments - Comprehensive guide
7. ✅ README.md with branch strategy - This file

## License

GPL v2 or later - Compatible with WordPress and the parent Astra theme.

## Author

**Supfit.lv Development Team**
- Repository: [https://github.com/okapteinis/astra-child](https://github.com/okapteinis/astra-child)
- Parent Theme: [Brainstorm Force - Astra](https://github.com/brainstormforce/astra-child)
- Store: [https://supfit.lv](https://supfit.lv)

## Support & Contributions

For issues or suggestions:
1. Check existing documentation in this repository
2. Review [COMET_DEPLOYMENT.md](./COMET_DEPLOYMENT.md) and [ATLAS_INTEGRATION.md](./ATLAS_INTEGRATION.md)
3. Contact the development team

## Related Resources

- [Astra Theme Documentation](https://docs.brainstormforce.com/astra/)
- [WooCommerce Documentation](https://woocommerce.com/)
- [Schema.org Product](https://schema.org/Product)
- [ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [ChatGPT API Documentation](https://openai.com/)

---

**Last Updated:** January 2026  
**Maintained for:** Supfit.lv WooCommerce Store  
**Server:** Hetzner VPS with YunoHost  
**Status:** ✅ Production Ready
