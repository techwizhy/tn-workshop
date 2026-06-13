# 🇮🇳 Tamil Nadu Youth Political Workshop (TNYPW) Portal
## WordPress Integration & Deployment Guide

This guide details the steps required to integrate the **Tamil Nadu Youth Political Workshop** HTML5/CSS3/JS website into a WordPress website utilizing the **Astra** theme (or Astra Child Theme).

---

## 📂 Deployment File Structure

When copying files into your WordPress installation, ensure the following structure is maintained within your active Astra child theme directory:

```text
wp-content/themes/astra-child/
├── yw-template.php          # WordPress Page Template for Workshop pages
├── yw-shortcode.php         # Shortcode registration for dynamic widgets
├── yw-ajax-handler.php      # AJAX endpoint for Registration & Donation
├── functions.php            # Active theme functions (include/require hookups)
└── yw-assets/
    ├── style.css            # Stylesheet containing TNYPW custom styles
    ├── main.js              # JavaScript for animations, navigation & form handling
    └── ashoka-chakra.svg    # Inline SVG asset for the Hero section
```

---

## 🛠️ Step-by-Step Integration Guide

### Step 1: Astra Child Theme Setup
For any theme modification, it is critical to use a **Child Theme** so updates to the parent Astra theme do not overwrite your customizations.
1. If not already active, download and install the Astra child theme template from the official Astra website.
2. Activate the Astra Child theme in your WordPress dashboard under **Appearance > Themes**.

### Step 2: Copy Assets
1. Create a folder named `yw-assets` in the root of your child theme directory: `/wp-content/themes/astra-child/yw-assets/`.
2. Copy the following files into this folder:
   - `style.css`
   - `main.js`
   - `assets/ashoka-chakra.svg` (copy to `yw-assets/ashoka-chakra.svg`)

### Step 3: Register Custom Templates & Shortcodes
Add the following code snippet to the bottom of your child theme's `functions.php` file (`/wp-content/themes/astra-child/functions.php`) to integrate the custom templates, shortcodes, and AJAX handlers:

```php
<?php
// Enqueue TNYPW Styles and Scripts
function yw_enqueue_workshop_assets() {
    if (is_page_template('yw-template.php')) {
        // Enqueue Google Fonts (Outfit and Playfair Display)
        wp_enqueue_style('yw-google-fonts', 'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap', array(), null);
        
        // Enqueue Tabler Icons
        wp_enqueue_style('yw-tabler-icons', 'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css', array(), null);

        // Enqueue Custom Styles
        wp_enqueue_style('yw-custom-styles', get_stylesheet_directory_uri() . '/yw-assets/style.css', array(), '1.0.0');

        // Enqueue Custom JavaScript
        wp_enqueue_script('yw-custom-js', get_stylesheet_directory_uri() . '/yw-assets/main.js', array('jquery'), '1.0.0', true);

        // Localize Script for AJAX calls (handles WordPress dynamic AJAX URL)
        wp_localize_script('yw-custom-js', 'ywAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('yw_workshop_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'yw_enqueue_workshop_assets');

// Include TNYPW Shortcodes
require_once get_stylesheet_directory() . '/yw-shortcode.php';

// Include TNYPW AJAX Handlers
require_once get_stylesheet_directory() . '/yw-ajax-handler.php';
```

### Step 4: Configure the AJAX Handler (`yw-ajax-handler.php`)
The file `yw-ajax-handler.php` handles backend submissions for registration forms and donation requests.
- **Registration Submission**: Validates user inputs, saves registration details into a custom database table or WordPress post meta, and sends a confirmation email.
- **Donation Processing**: Generates payment orders via Razorpay/PayU APIs and handles backend verification webhooks.

> [!IMPORTANT]
> Open `yw-ajax-handler.php` and enter your payment gateway credentials (Razorpay Key ID/Secret or PayU Merchant Key/Salt) in the designated configuration section:
> ```php
> define('YW_RAZORPAY_KEY_ID', 'YOUR_KEY_ID');
> define('YW_RAZORPAY_KEY_SECRET', 'YOUR_KEY_SECRET');
> ```

### Step 5: Create WordPress Pages
You must create 5 WordPress pages. In your WordPress admin dashboard, navigate to **Pages > Add New**:

| Title | URL Slug | Page Template Setting (Page Attributes) | Description |
|---|---|---|---|
| Tamil Nadu Youth Political Workshop | `workshop` (or `/`) | **Tamil Nadu Youth Political Workshop Template** | 🏠 The main landing page |
| Gallery | `gallery` | **Tamil Nadu Youth Political Workshop Template** | 🖼️ Grid of 12 gallery items with filters |
| Donate | `donate` | **Tamil Nadu Youth Political Workshop Template** | 💛 Donation page with custom amounts & payment gateway |
| Terms of Use | `terms` | **Tamil Nadu Youth Political Workshop Template** | 📄 Site terms and conditions |
| Contact Us | `contact` | **Tamil Nadu Youth Political Workshop Template** | 📞 Contact information and enquiry form |

To set the template:
1. On the Page Editor screen, look at the sidebar under **Page Attributes**.
2. Select **Tamil Nadu Youth Political Workshop Template** from the **Template** dropdown.
3. Publish the pages.

---

## 💳 Payment Gateway Integration (Razorpay / PayU)

The donation page integrates Razorpay and PayU SDKs dynamically using the AJAX handler.

### Flow:
1. **Initiate Payment**: User selects or inputs a donation amount (`[501, 1001, 2501, 5001, 10001]`) and clicks submit.
2. **Order Creation**: Frontend calls `yw_create_payment_order` AJAX endpoint in `yw-ajax-handler.php` which requests an Order ID from the gateway API.
3. **Checkout Script**: The gateway checkout popup appears on the frontend.
4. **Verification**: Upon successful payment, the gateway triggers a callback verified by `yw_verify_payment` webhook to confirm transaction security.

---

## 🔒 Security Best Practices
- **Data Validation & Sanitization**: Ensure all custom inputs are sanitized with `sanitize_text_field()` and `sanitize_email()`.
- **Nonce Verification**: Every backend request is secured with a WordPress security nonce `yw_workshop_nonce` created during localize script.
- **Database Safety**: Prepared statements (`$wpdb->prepare()`) are enforced in database operations.
