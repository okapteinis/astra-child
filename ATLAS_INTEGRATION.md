# ChatGPT Atlas Integration for Astra Child Theme
## Making supfit.lv Easier for AI Agents to Understand

### Overview

This document describes enhancements made to the Astra child theme specifically designed to help ChatGPT Atlas and other autonomous AI agents understand, navigate, and interact with the WooCommerce store at supfit.lv.

The improvements focus on:
- **Structured Data (Schema.org)**: Adding explicit JSON-LD markup for products
- **Semantic HTML**: Clearer ARIA labels and button text
- **Navigation**: Breadcrumbs and predictable site structure
- **Checkout Flow**: Simplified, distraction-free purchasing experience

---

## 1. JSON-LD Product Schema

### What It Does

Adds structured `schema.org` Product and Offer markup to every single product page. This makes product information machine-readable without relying on HTML parsing.

### Implementation

**Location**: `functions.php` (function `supfit_add_product_schema`)

**Hook**: `wp_head` at priority 30

**Output**: `<script type="application/ld+json">` block containing:

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Product Name",
  "image": "product-image-url",
  "description": "Product description",
  "sku": "SKU123",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "EUR",
    "price": "29.99",
    "availability": "https://schema.org/InStock",
    "url": "product-page-url"
  }
}
```

### Why Atlas Needs This

- **Direct Data Access**: Atlas can read JSON-LD without parsing HTML
- **Stock Status**: Explicit `availability` field tells agents if product is in stock
- **Pricing**: Structured price and currency prevent misinterpretation
- **Product Identity**: SKU and URL provide unique identification

### Complement to Astra Defaults

Astra theme may include basic schema markup. This JSON-LD schema is explicit and complete, ensuring consistency and reliability for AI agents.

---

## 2. ARIA Labels and Button Text Improvements

### What It Does

Clarifies button text and adds descriptive ARIA labels so Atlas agents reliably identify and interact with key actions.

### Implementation

**Location**: `functions.php`

#### Add-to-Cart Button

- **Visible Text**: "Add to cart" (short, clear)
- **ARIA Label**: "Add \"Product Name\" to your cart" (full context)
- **Filter Hook**: `woocommerce_product_add_to_cart_text` and `woocommerce_product_add_to_cart_attributes`

#### Checkout Button

- **Text**: "Place order (secure checkout)"
- **Filter Hook**: `woocommerce_order_button_text`
- **Benefit**: Removes ambiguity; agents know the button finalizes purchase

### Why Atlas Needs This

- **Unambiguous Intent**: Clear labels prevent Atlas from confusing buttons
- **Context**: ARIA labels provide full product/action context
- **Accessibility**: Better for screen readers and automation tools
- **Reliable Interactions**: Atlas can confidently click buttons with known purpose

---

## 3. Breadcrumb Navigation

### What It Does

Provides a clear hierarchical breadcrumb trail (Home > Shop > Category > Product) on every page.

### Implementation

**Location**: `functions.php` (function `supfit_simple_breadcrumb`)

**Hook**: `astra_primary_content_top`

**Output Example**:
```html
<nav class="supfit-breadcrumb" aria-label="Breadcrumb">
  <a href="https://supfit.lv/">Home</a>
  » <a href="https://supfit.lv/shop/">Shop</a>
  » <span>Protein Powders</span>
</nav>
```

### Navigation Coverage

- **Homepage**: Breadcrumb hidden (no trail needed)
- **Shop Page**: "Home > Shop"
- **Category Page**: "Home > Shop > [Category Name]"
- **Product Page**: "Home > Shop > [Category] > [Product]"

### Why Atlas Needs This

- **Site Structure**: Clear navigation helps Atlas understand site hierarchy
- **Backtracking**: Agents can navigate back to categories/shop
- **Context**: Breadcrumbs provide page context without reading full content
- **Link Following**: Breadcrumbs offer explicit navigation paths

---

## 4. Simplified Checkout Flow

### What It Does

Ensures checkout and cart pages have a predictable, distraction-free flow optimized for automated agents.

### Implementation

**Location**: `functions.php` (function `supfit_reduce_checkout_distractions`)

**Hook**: `wp` action

**Capabilities**:

```php
if ( is_checkout() ) {
    // Optionally dequeue marketing popups, banners, etc.
    // wp_dequeue_script( 'some-popup-handle' );
}
```

### Checkout Best Practices

1. **No Pop-ups During Checkout**: Popups interrupt Atlas workflows
2. **Linear Form Flow**: Single-page or multi-step (not AJAX surprises)
3. **Visible Buttons**: Add-to-cart and Place Order buttons must be accessible
4. **Error Messages**: Clear, readable feedback if checkout fails
5. **Progress Indication**: Show checkout step (1/3, 2/3, etc.) if multi-step

### Why Atlas Needs This

- **Predictable Flow**: Fewer surprises = more reliable automation
- **No JavaScript Traps**: Modals or AJAX interactions can break automation
- **Form Parsing**: Clear form fields without obstructions
- **Success Confirmation**: Order confirmation page must be obvious

---

## 5. Semantic HTML & Accessibility

### Best Practices in Place

1. **ARIA Labels**: All interactive elements have descriptive ARIA labels
2. **Navigation Landmarks**: `<nav>` tags for breadcrumbs
3. **Semantic Buttons**: Use `<button>` not `<a>` for actions
4. **Form Labels**: All form inputs have associated `<label>` elements
5. **Heading Hierarchy**: Proper H1 > H2 > H3 structure

### For Product Pages

- Product name: H1
- Category/SKU: Clear text or `<span>` with proper context
- Price: Structured (not embedded in text)
- Description: Plain, readable text
- Add-to-cart: `<button>` with ARIA label

### For Cart & Checkout

- Cart heading: H1
- Item list: `<table>` or `<ul>` with clear item names/prices
- Totals: Separate, labeled section
- Checkout button: Clear, prominent `<button>`

---

## Why This Helps ChatGPT Atlas

### 1. Autonomous Navigation
Atlas can follow breadcrumbs and links to explore product categories and find items without human guidance.

### 2. Product Understanding
JSON-LD schema provides structured data that Atlas can parse directly, avoiding OCR errors or misinterpretation.

### 3. Reliable Interactions
Clear ARIA labels and button text mean Atlas knows exactly what will happen when it clicks.

### 4. Predictable Checkout
Simplified checkout flow means Atlas can follow a linear path from product selection to order confirmation without unexpected obstacles.

### 5. Consistent Intent Recognition
Semantic HTML and breadcrumbs reinforce the page context, helping Atlas understand its current location and available actions.

---

## Implementation Checklist

- [x] JSON-LD Product schema added to single product pages
- [x] ARIA labels added to Add-to-Cart and Checkout buttons
- [x] Breadcrumb navigation added to relevant pages
- [x] Checkout distraction filters in place
- [x] Semantic HTML standards maintained
- [ ] Test with ChatGPT Atlas (in progress)
- [ ] Monitor Atlas interactions and refine as needed

---

## Testing with Atlas

### Manual Testing Steps

1. **Product Page Inspection**
   - Right-click > Inspect Element
   - Look for `<script type="application/ld+json">` block
   - Verify all fields: name, image, price, SKU, availability

2. **Button Label Verification**
   - Inspect Add-to-Cart button: should have `aria-label="Add \"[Product]\" to your cart"`
   - Inspect Checkout button: should display "Place order (secure checkout)"

3. **Breadcrumb Navigation**
   - Verify breadcrumbs appear on Shop, Category, and Product pages
   - Check that breadcrumb links are clickable and functional
   - Confirm breadcrumb structure matches site hierarchy

4. **Checkout Flow**
   - Add a product to cart
   - Navigate to cart page (check breadcrumb)
   - Proceed to checkout (check button text)
   - Verify no pop-ups or modals interrupt the flow

### Debugging

- **JSON-LD Not Appearing?**: Check `wp_head` hook fires before closing `</head>` tag
- **ARIA Labels Not Showing?**: Verify WooCommerce version supports these filters
- **Breadcrumbs Missing?**: Check if `astra_primary_content_top` hook is called in your Astra child theme
- **Checkout Broken?**: Review `supfit_reduce_checkout_distractions` function; may need theme-specific customization

---

## Future Enhancements

1. **Review Schema**: Add `AggregateRating`, `reviewCount`, customer reviews
2. **FAQ Schema**: FAQ page with structured Q&A for product guidance
3. **Breadcrumb Schema**: JSON-LD breadcrumb markup (redundant with HTML, but explicit for agents)
4. **Cart Schema**: Structure cart items and totals in JSON-LD
5. **Analytics**: Track Atlas bot activity to refine interaction patterns

---

## References

- [Schema.org Product](https://schema.org/Product)
- [Schema.org Offer](https://schema.org/Offer)
- [ARIA Authoring Practices Guide](https://www.w3.org/WAI/ARIA/apg/)
- [WooCommerce Filters](https://woocommerce.com/)
- [ChatGPT Atlas Documentation](https://openai.com/)

---

**Last Updated**: January 2026
**Theme**: Astra Child (supfit.lv)
**Purpose**: ChatGPT Atlas Autonomous Agent Support
