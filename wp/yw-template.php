<?php
/**
 * Template Name: Youth Workshop — Landing Page
 * 
 * WordPress Page Template used for all pages of the Tamil Nadu Youth Political Workshop (TNYPW) Portal:
 * 1. Home / Landing Page (workshop / default)
 * 2. Gallery Page (gallery)
 * 3. Donate Page (donate)
 * 4. Terms of Use Page (terms)
 * 5. Contact Us Page (contact)
 * 
 * Bypasses the default Astra headers and footers to ensure the design remains 100% pixel-perfect
 * and identical to the static HTML mockups, while correctly invoking wp_head() and wp_footer()
 * to support WordPress enqueuing, script localization, and plugin hooks.
 */

// Retrieve the current page slug
$slug = get_post_field( 'post_name', get_post() );
if ( empty( $slug ) ) {
    $slug = 'workshop';
}

$page_content = '';
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        $page_content = get_the_content();
    }
}

// Generate security nonce for AJAX forms
$yw_nonce = wp_create_nonce( 'yw_workshop_nonce' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light" data-font="md">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title( '|', true, 'right' ); ?></title>
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'yw-wp-compatible' ); ?>>
  <!-- Main Wrapper -->
  <div id="yw-root">
    
    <!-- Header & Navigation -->
    <header class="yw-header">
      <nav class="yw-navbar" id="yw-main-nav">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="yw-logo" aria-label="Tamil Nadu Youth Political Workshop Home">
          <span class="ti ti-building-monument yw-logo-icon" aria-hidden="true"></span>
          <span class="yw-logo-text">TN Youth Political Workshop</span>
        </a>
        <button id="yw-nav-toggle" class="yw-nav-toggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="yw-nav-menu">
          <span class="yw-hamburger-bar"></span>
          <span class="yw-hamburger-bar"></span>
          <span class="yw-hamburger-bar"></span>
        </button>
        <ul class="yw-nav-menu" id="yw-nav-menu">
          <?php
          // Navigation items with corresponding page slugs
          $nav_items = array(
              'Home'    => 'workshop',
              'Gallery' => 'gallery',
              'Donate'  => 'donate',
              'Terms'   => 'terms',
              'Contact' => 'contact',
          );
          
          foreach ( $nav_items as $label => $item_slug ) {
              $url = ( $item_slug === 'workshop' && is_front_page() ) ? home_url( '/' ) : home_url( '/' . $item_slug . '/' );
              $is_active = false;
              if ( $slug === $item_slug ) {
                  $is_active = true;
              } elseif ( $item_slug === 'workshop' && ( is_front_page() || is_home() ) ) {
                  $is_active = true;
              }
              $active_class = $is_active ? 'yw-active' : '';
              echo '<li class="yw-nav-item"><a href="' . esc_url( $url ) . '" class="yw-nav-link ' . $active_class . '">' . esc_html( $label ) . '</a></li>';
          }
          ?>
        </ul>
      </nav>
      <!-- Accessibility and Theme Controls -->
      <div class="yw-controls-bar">
        <div class="yw-font-controls">
          <button id="yw-font-decrease" class="yw-control-btn" type="button" aria-label="Decrease font size">A-</button>
          <button id="yw-font-reset" class="yw-control-btn" type="button" aria-label="Reset font size">A</button>
          <button id="yw-font-increase" class="yw-control-btn" type="button" aria-label="Increase font size">A+</button>
        </div>
        <button id="yw-theme-toggle" class="yw-control-btn yw-theme-toggle" type="button" aria-label="Toggle theme">
          <i class="ti ti-sun yw-sun-icon" aria-hidden="true"></i>
          <i class="ti ti-moon yw-moon-icon" aria-hidden="true"></i>
        </button>
        <button id="yw-share-btn" class="yw-control-btn yw-share-btn" type="button" aria-label="Share this page">
          <i class="ti ti-share" aria-hidden="true"></i>
        </button>
      </div>
    </header>

    <!-- Main Content Area -->
    <main class="yw-main">
      <?php
      // If the WordPress page editor contains custom content, render it directly.
      // Otherwise, load the corresponding localized page template structure.
      if ( ! empty( $page_content ) ) {
          echo apply_filters( 'the_content', $page_content );
      } else {
          
          if ( $slug === 'gallery' ) {
              // ==========================================
              // GALLERY PAGE TEMPLATE
              // ==========================================
              ?>
              <section id="yw-gallery-hero" class="yw-gallery-hero">
                <div class="yw-hero-overlay"></div>
                <div class="yw-container yw-hero-content">
                  <h1 class="yw-hero-title">புகைப்படத் தொகுப்பு</h1>
                  <p class="yw-hero-subtitle">தமிழ்நாடு இளைஞர் அரசியல் பயிலரங்கத்தின் முக்கிய நிகழ்வுகள், விவாதங்கள் மற்றும் பயிற்சி வகுப்புகளின் புகைப்படக் காட்சிகள்.</p>
                </div>
              </section>
              
              <?php echo do_shortcode( '[yw_gallery]' ); ?>
              <?php
              
          } elseif ( $slug === 'donate' ) {
              // ==========================================
              // DONATE PAGE TEMPLATE
              // ==========================================
              ?>
              <section id="yw-donate-hero" class="yw-contact-hero">
                <div class="yw-hero-overlay"></div>
                <div class="yw-container yw-hero-content">
                  <h1 class="yw-hero-title">Support Our Mission</h1>
                  <p class="yw-hero-subtitle">Your generous contributions help us train and nurture the next generation of ethical and active civic leaders in Tamil Nadu.</p>
                </div>
              </section>

              <section id="yw-donate-section" class="yw-donate-section">
                <div class="yw-container yw-donate-grid">
                  
                  <!-- Column 1: Impact Information -->
                  <div class="yw-donate-info">
                    <h2 class="yw-info-title" style="font-family: var(--yw-font-serif); font-size: var(--yw-font-size-3xl); color: var(--yw-primary); margin-bottom: var(--yw-spacing-sm);">இளம் தலைமுறையினரின் அரசியல் பயணத்திற்கு கைகொடுங்கள்</h2>
                    <p class="yw-info-description" style="font-size: var(--yw-font-size-lg); color: var(--yw-text-muted); line-height: 1.6;">
                      உங்களின் சிறிய பங்களிப்பும் தமிழ்நாட்டில் ஆரோக்கியமான, நேர்மையான அரசியல் மற்றும் ஜனநாயக விழுமியங்களை வளர்க்க உதவும். தகுதியான இளைஞர்கள் மற்றும் பெண்களுக்கு இலவச பயிற்சி வழங்க ஆதரவு தாருங்கள்.
                    </p>
                    
                    <h3 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-top: var(--yw-spacing-md); margin-bottom: var(--yw-spacing-xs);">நன்கொடை எவ்வாறு பயன்படுத்தப்படுகிறது? (How your donation helps)</h3>
                    <div class="yw-impact-list" style="display: flex; flex-direction: column; gap: var(--yw-spacing-md); margin-top: var(--yw-spacing-sm);">
                      
                      <div class="yw-impact-item" style="display: flex; gap: var(--yw-spacing-md); align-items: flex-start;">
                        <div style="background-color: rgba(var(--yw-primary-rgb), 0.1); color: var(--yw-primary); padding: var(--yw-spacing-sm); border-radius: var(--yw-radius-md); display: flex; align-items: center; justify-content: center;">
                          <span class="ti ti-book" style="font-size: 1.5rem;" aria-hidden="true"></span>
                        </div>
                        <div>
                          <h4 style="font-size: var(--yw-font-size-base); font-weight: 600; color: var(--yw-text);">பயிற்சி உபகரணங்கள் (Training Kits & Materials)</h4>
                          <p style="font-size: var(--yw-font-size-sm); color: var(--yw-text-muted); margin-top: var(--yw-spacing-xs);">ஆர்வமுள்ள பங்கேற்பாளர்களுக்கு அரசியல் வரலாறு, உள்ளாட்சி சட்டங்கள் மற்றும் கொள்கை வழிகாட்டு புத்தகங்கள் இலவசமாக வழங்கப்படுகின்றன.</p>
                        </div>
                      </div>

                      <div class="yw-impact-item" style="display: flex; gap: var(--yw-spacing-md); align-items: flex-start;">
                        <div style="background-color: rgba(var(--yw-secondary-rgb), 0.1); color: var(--yw-secondary); padding: var(--yw-spacing-sm); border-radius: var(--yw-radius-md); display: flex; align-items: center; justify-content: center;">
                          <span class="ti ti-cookie" style="font-size: 1.5rem;" aria-hidden="true"></span>
                        </div>
                        <div>
                          <h4 style="font-size: var(--yw-font-size-base); font-weight: 600; color: var(--yw-text);">உணவு & தங்குமிடம் (Food & Boarding)</h4>
                          <p style="font-size: var(--yw-font-size-sm); color: var(--yw-text-muted); margin-top: var(--yw-spacing-xs);">தமிழகத்தின் பல்வேறு மாவட்டங்களில் இருந்து வரும் கிராமப்புற இளைஞர்கள் மற்றும் பெண்களுக்கு தங்குமிடம் மற்றும் சத்தான உணவு வழங்கப்படுகிறது.</p>
                        </div>
                      </div>

                      <div class="yw-impact-item" style="display: flex; gap: var(--yw-spacing-md); align-items: flex-start;">
                        <div style="background-color: rgba(var(--yw-primary-rgb), 0.1); color: var(--yw-primary); padding: var(--yw-spacing-sm); border-radius: var(--yw-radius-md); display: flex; align-items: center; justify-content: center;">
                          <span class="ti ti-device-laptop" style="font-size: 1.5rem;" aria-hidden="true"></span>
                        </div>
                        <div>
                          <h4 style="font-size: var(--yw-font-size-base); font-weight: 600; color: var(--yw-text);">டிஜிட்டல் பயிற்சி தளம் (Digital Platform)</h4>
                          <p style="font-size: var(--yw-font-size-sm); color: var(--yw-text-muted); margin-top: var(--yw-spacing-xs);">நேரடியாக பங்கேற்க இயலாத மாணவர்களுக்கான இணையவழித் தளம் மற்றும் காணொளிப் பதிவுகளைப் பராமரிக்கப் பயன்படுகிறது.</p>
                        </div>
                      </div>
                      
                    </div>
                  </div>

                  <!-- Column 2: Donation Form Card -->
                  <div class="yw-donate-form-container">
                    <div class="yw-donate-form-card">
                      <h3 style="font-size: var(--yw-font-size-2xl); font-family: var(--yw-font-serif); margin-bottom: var(--yw-spacing-xs); text-align: center; color: var(--yw-text);">நன்கொடைப் படிவம் (Donation Form)</h3>
                      <p style="font-size: var(--yw-font-size-sm); color: var(--yw-text-muted); text-align: center; margin-bottom: var(--yw-spacing-lg);">பாதுகாப்பான முறையில் உங்கள் பங்களிப்பை செலுத்துங்கள்.</p>
                      
                      <form id="yw-donate-form" class="yw-form" action="#" method="post">
                        <!-- WP AJAX Integration -->
                        <input type="hidden" name="action" value="yw_create_payment_order">
                        <input type="hidden" id="yw-payment-nonce" name="nonce" value="<?php echo esc_attr( $yw_nonce ); ?>">
                        
                        <!-- Preset Amount Buttons -->
                        <div class="yw-form-group">
                          <span class="yw-form-label" style="display: block; margin-bottom: var(--yw-spacing-sm); font-weight: 600;">தொகையைத் தேர்ந்தெடுக்கவும் (Select Amount)</span>
                          <div class="yw-donation-presets">
                            <button type="button" class="yw-donation-preset-btn" data-amount="501">₹501</button>
                            <button type="button" class="yw-donation-preset-btn" data-amount="1001">₹1001</button>
                            <button type="button" class="yw-donation-preset-btn yw-active" data-amount="2501">₹2501</button>
                            <button type="button" class="yw-donation-preset-btn" data-amount="5001">₹5001</button>
                            <button type="button" class="yw-donation-preset-btn" data-amount="10001">₹10001</button>
                          </div>
                        </div>

                        <!-- Custom Amount Input -->
                        <div class="yw-form-group">
                          <label for="yw-donation-amount" class="yw-form-label" style="font-weight: 600;">நன்கொடைத் தொகை (Donation Amount in ₹)</label>
                          <div class="yw-donation-custom-group">
                            <span class="yw-donation-currency-symbol" aria-hidden="true">₹</span>
                            <input type="number" id="yw-donation-amount" name="amount" class="yw-form-input yw-donation-custom-input" min="10" step="1" value="2501" required>
                          </div>
                        </div>

                        <!-- Donor Personal Details -->
                        <div class="yw-form-group">
                          <label for="yw-donate-name" class="yw-form-label">முழு பெயர் (Full Name)</label>
                          <input type="text" id="yw-donate-name" name="name" class="yw-form-input" placeholder="e.g. கதிர்வேல் சண்முகம்" required>
                        </div>

                        <div class="yw-form-group">
                          <label for="yw-donate-email" class="yw-form-label">மின்னஞ்சல் முகவரி (Email Address)</label>
                          <input type="email" id="yw-donate-email" name="email" class="yw-form-input" placeholder="e.g. name@example.com" required>
                        </div>

                        <div class="yw-form-group">
                          <label for="yw-donate-phone" class="yw-form-label">கைபேசி எண் (Mobile Number - Optional)</label>
                          <input type="tel" id="yw-donate-phone" name="phone" class="yw-form-input" placeholder="e.g. +91 98765 43210">
                        </div>

                        <!-- Gateway Selection & Payment Tabs -->
                        <div class="yw-form-group">
                          <label for="yw-payment-gateway" class="yw-form-label" style="font-weight: 600;">செலுத்துகை முறை (Payment Gateway)</label>
                          <input type="hidden" id="yw-payment-gateway" name="gateway" value="razorpay">
                          <div class="yw-payment-tabs" id="yw-payment-tabs">
                            <button type="button" class="yw-payment-tab-btn yw-active" data-gateway="razorpay">Razorpay</button>
                            <button type="button" class="yw-payment-tab-btn" data-gateway="payu">PayU</button>
                          </div>
                        </div>

                        <!-- Payment Descriptions -->
                        <div id="yw-tab-razorpay" class="yw-payment-tab-content yw-active">
                          <p style="font-size: var(--yw-font-size-xs); color: var(--yw-text-muted); margin-bottom: var(--yw-spacing-md); line-height: 1.5;">
                            Razorpay வாயிலாக UPI, கார்டுகள் மற்றும் நெட் பேங்கிங் மூலம் பாதுகாப்பாக செலுத்தலாம். (Pay securely via Razorpay utilizing UPI, Cards, Netbanking or Wallet).
                          </p>
                        </div>
                        <div id="yw-tab-payu" class="yw-payment-tab-content">
                          <p style="font-size: var(--yw-font-size-xs); color: var(--yw-text-muted); margin-bottom: var(--yw-spacing-md); line-height: 1.5;">
                            PayU வாயிலாக அனைத்து முக்கிய வங்கிகளின் அட்டை மற்றும் UPI முறையில் பாதுகாப்பாக செலுத்தலாம். (Pay securely via PayU payment gateway).
                          </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="yw-form-actions">
                          <button type="submit" id="yw-submit-btn" class="yw-btn yw-btn-primary yw-btn-block">நன்கொடை அளிக்க (Donate Now)</button>
                        </div>

                        <p style="font-size: var(--yw-font-size-xs); color: var(--yw-text-muted); text-align: center; margin-top: var(--yw-spacing-md); line-height: 1.4;">
                          உங்கள் தனிப்பட்ட தகவல்கள் பாதுகாப்பாக வைக்கப்படும். எங்களின் விதிமுறைகளுக்கு உட்பட்டது.
                        </p>
                      </form>
                      
                      <!-- Form Response Container -->
                      <div id="yw-form-message-container" class="yw-form-response-message yw-hidden" aria-live="polite"></div>
                    </div>
                  </div>

                </div>
              </section>
              <?php
              
          } elseif ( $slug === 'terms' ) {
              // ==========================================
              // TERMS OF USE PAGE TEMPLATE
              // ==========================================
              ?>
              <section id="yw-terms-hero" class="yw-hero-section">
                <div class="yw-container yw-hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
                  <h1 class="yw-hero-title" style="font-family: var(--yw-font-serif); font-size: var(--yw-font-size-4xl); color: var(--yw-text);">பயன்பாட்டு விதிமுறைகள்</h1>
                  <p class="yw-hero-subtitle" style="font-size: var(--yw-font-size-lg); color: var(--yw-text-muted); margin-top: var(--yw-spacing-sm);">தமிழ்நாடு இளைஞர் அரசியல் பயிலரங்கத்தின் இணையதளத்தைப் பயன்படுத்துவதற்கான விதிமுறைகள் மற்றும் நிபந்தனைகள்.</p>
                </div>
              </section>

              <section id="yw-terms-section" class="yw-terms-section" style="padding: var(--yw-spacing-xxl) 0; background-color: var(--yw-background);">
                <div class="yw-container">
                  <div class="yw-terms-card" style="background-color: var(--yw-surface); border: 1px solid var(--yw-border); border-radius: var(--yw-radius-lg); padding: var(--yw-spacing-xl); box-shadow: var(--yw-shadow-md); max-width: 900px; margin: 0 auto;">
                    
                    <div class="yw-terms-intro" style="margin-bottom: var(--yw-spacing-lg); padding-bottom: var(--yw-spacing-md); border-bottom: 1px solid var(--yw-border);">
                      <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.8;">
                        தமிழ்நாடு இளைஞர் அரசியல் பயிலரங்கத்தின் இணையதளத்திற்கு உங்களை வரவேற்கிறோம். இந்த இணையதளத்தைப் பயன்படுத்துவதன் மூலம், பின்வரும் பயன்பாட்டு விதிமுறைகளை நீங்கள் ஏற்றுக்கொள்கிறீர்கள். தயவுசெய்து இவற்றை கவனமாகப் படிக்கவும்.
                      </p>
                    </div>

                    <div class="yw-terms-content" style="display: flex; flex-direction: column; gap: var(--yw-spacing-lg);">
                      
                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-info-circle" style="color: var(--yw-primary);" aria-hidden="true"></span> 1. அறிமுகம் (Introduction)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          இந்த இணையதளம் தமிழ்நாடு இளைஞர் அரசியல் பயிலரங்கத்தினால் பராமரிக்கப்படுகிறது. எங்கள் சேவைகளையும் தகவல்களையும் அணுகுவதற்கு இந்த விதிமுறைகள் பொருந்தும். விதிமுறைகளில் உடன்பாடு இல்லாத பட்சத்தில், இந்த இணையதளத்தைப் பயன்படுத்துவதைத் தவிர்க்குமாறு கேட்டுக்கொள்கிறோம்.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-user-check" style="color: var(--yw-primary);" aria-hidden="true"></span> 2. தகுதி வரம்பு (Eligibility)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          எங்கள் பயிலரங்குகளில் பங்கேற்கவும் இணையதளம் வழியாகப் பதிவு செய்யவும் நீங்கள் 18 வயது அல்லது அதற்கு மேற்பட்டவராக இருக்க வேண்டும். இளைஞர்கள் மற்றும் பெண்களுக்கான அரசியல் விழிப்புணர்வு மற்றும் குடிமை அறிவை மேம்படுத்துவதை நோக்கமாகக் கொண்டு இந்தப் பயிற்சிகள் வடிவமைக்கப்பட்டுள்ளன.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-edit" style="color: var(--yw-primary);" aria-hidden="true"></span> 3. பதிவு மற்றும் தரவு உண்மைத்தன்மை (Registration & Accuracy)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          பயிற்சி முகாம்களுக்கு விண்ணப்பிக்கும் போது நீங்கள் வழங்கும் அனைத்து விவரங்களும் (பெயர், மின்னஞ்சல், தொலைபேசி எண் போன்றவை) உண்மையானதாகவும் துல்லியமானதாகவும் இருக்க வேண்டும். தவறான தகவல்களை வழங்குவது உங்கள் விண்ணப்பம் நிராகரிக்கப்படுவதற்குக் காரணமாக அமையலாம்.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-shield" style="color: var(--yw-primary);" aria-hidden="true"></span> 4. பயனர் நடத்தை விதிகள் (Code of Conduct)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          பங்கேற்பாளர்கள் மற்றும் இணையதளப் பயனர்கள் எந்தவொரு சட்டவிரோதமான அல்லது அவதூறு பரப்பும் செயல்களிலும் ஈடுபடக்கூடாது. இந்த தளம் முற்றிலும் நடுநிலையானது என்பதால், குறிப்பிட்ட அரசியல் கட்சிகளுக்கு ஆதரவாகவோ அல்லது எதிராகவோ வெறுப்புப் பேச்சுகளைப் பரப்புவதற்கு இதைப் பயன்படுத்தக் கூடாது.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-copyright" style="color: var(--yw-primary);" aria-hidden="true"></span> 5. அறிவுசார் சொத்துரிமை (Intellectual Property)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          இந்த இணையதளத்தில் உள்ள கட்டுரைகள், லோகோக்கள், வடிவமைப்பு, ஆடியோ, வீடியோ மற்றும் பயிற்சிப் பொருட்கள் அனைத்தும் எங்களின் அறிவுசார் சொத்துரிமை ஆகும். எங்களின் முன் அனுமதியின்றி இவற்றை வணிக நோக்கத்திற்காக நகலெடுக்கவோ அல்லது விநியோகிக்கவோ தடை செய்யப்பட்டுள்ளது.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-heart-handshake" style="color: var(--yw-primary);" aria-hidden="true"></span> 6. நன்கொடை கொள்கை (Donation Policy)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          எங்கள் தளம் மூலம் பெறப்படும் அனைத்து நன்கொடைகளும் இளைஞர்கள் மற்றும் பெண்களின் அரசியல் கல்வி மற்றும் பயிற்சிக்காக மட்டுமே பயன்படுத்தப்படும். நன்கொடைகள் அனைத்தும் இறுதித் தொகையாகும், மேலும் அவை எக்காரணம் கொண்டும் திரும்பப் பெறப்பட மாட்டாது (Non-refundable).
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-lock" style="color: var(--yw-primary);" aria-hidden="true"></span> 7. தனியுரிமை மற்றும் தரவு பாதுகாப்பு (Privacy & Data Protection)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          விண்ணப்பதாரர்களின் விவரங்கள் அனைத்தும் பாதுகாப்பாக சேமிக்கப்படும். உங்களின் தரவுகள் எக்காரணம் கொண்டும் மூன்றாம் தரப்பினருக்கு விற்கப்படவோ அல்லது பகிரப்படவோ மாட்டாது. இது பயிலரங்குத் தொடர்புகளுக்கு மட்டுமே பயன்படுத்தப்படும்.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-alert-triangle" style="color: var(--yw-primary);" aria-hidden="true"></span> 8. பொறுப்பு வரம்புகள் (Limitation of Liability)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          இணையதளத்தின் தகவல்கள் அனைத்தும் துல்லியமாக வழங்கப்படுவதை உறுதி செய்ய முயற்சிக்கிறோம். எனினும், தொழில்நுட்பத் கோளாறுகள் அல்லது வெளிப்புற இணைப்புகளால் ஏற்படும் இழப்புகளுக்கு நாங்கள் பொறுப்பேற்க மாட்டோம்.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-refresh" style="color: var(--yw-primary);" aria-hidden="true"></span> 9. விதிமுறைகளில் மாற்றங்கள் (Changes to Terms)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          விதிமுறைகளை முன்னறிவிப்பின்றி எந்த நேரத்திலும் திருத்துவதற்கோ அல்லது மாற்றுவதற்கோ எங்களுக்கு முழு அதிகாரம் உள்ளது. புதுப்பிக்கப்பட்ட விதிமுறைகள் இணையதளத்தில் வெளியிடப்பட்ட உடனேயே அமலுக்கு வரும்.
                        </p>
                      </div>

                      <div class="yw-terms-item">
                        <h2 style="font-size: var(--yw-font-size-xl); color: var(--yw-secondary); margin-bottom: var(--yw-spacing-sm); display: flex; align-items: center; gap: var(--yw-spacing-sm);">
                          <span class="ti ti-gavel" style="color: var(--yw-primary);" aria-hidden="true"></span> 10. சட்ட வரம்புகள் மற்றும் நீதிமன்ற அதிகாரம் (Governing Law)
                        </h2>
                        <p style="font-size: var(--yw-font-size-base); color: var(--yw-text-muted); line-height: 1.7;">
                          இந்த பயன்பாட்டு விதிமுறைகள் இந்தியச் சட்டங்களுக்கு உட்பட்டவை. ஏதேனும் முரண்பாடுகள் அல்லது சர்ச்சைகள் எழும்பினால், அவை சென்னை உயர்நீதிமன்ற அதிகார வரம்பிற்கு உட்பட்டு தீர்க்கப்படும்.
                        </p>
                      </div>

                    </div>

                    <div class="yw-terms-footer" style="margin-top: var(--yw-spacing-xl); padding-top: var(--yw-spacing-md); border-top: 1px solid var(--yw-border); text-align: center;">
                      <p style="font-size: var(--yw-font-size-sm); color: var(--yw-text-muted);">
                        கடைசியாக புதுப்பிக்கப்பட்ட தேதி: ஜூன் 13, 2026
                      </p>
                      <p style="font-size: var(--yw-font-size-sm); color: var(--yw-text-muted); margin-top: var(--yw-spacing-xs);">
                        உங்களுக்கு ஏதேனும் சந்தேகங்கள் இருந்தால் எங்களை <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="color: var(--yw-primary); font-weight: 600;">தொடர்பு கொள்ளவும்</a>.
                      </p>
                    </div>

                  </div>
                </div>
              </section>
              <?php
              
          } elseif ( $slug === 'contact' ) {
              // ==========================================
              // CONTACT US PAGE TEMPLATE
              // ==========================================
              ?>
              <section id="yw-contact-hero" class="yw-contact-hero">
                <div class="yw-hero-overlay"></div>
                <div class="yw-container yw-hero-content">
                  <h1 class="yw-hero-title">Get in Touch</h1>
                  <p class="yw-hero-subtitle">Have questions about the Tamil Nadu Youth Political Workshop? Reach out to our organizing team and build leadership skills today.</p>
                </div>
              </section>

              <section id="yw-contact-section" class="yw-contact-section">
                <div class="yw-container yw-contact-grid">
                  
                  <!-- Column 1: Info Cards -->
                  <div class="yw-contact-info">
                    <h2 class="yw-info-title">Contact Information</h2>
                    <p class="yw-info-subtitle">We are here to support your civic leadership journey. Connect with us through any of the channels below.</p>
                    
                    <div class="yw-info-cards">
                      <div class="yw-info-card">
                        <div class="yw-info-icon-wrapper">
                          <span class="ti ti-map-pin yw-info-icon" aria-hidden="true"></span>
                        </div>
                        <div class="yw-info-text">
                          <h3 class="yw-info-card-title">Office Address</h3>
                          <p class="yw-info-card-content">Anna Salai, Teynampet, Chennai, Tamil Nadu 600018</p>
                        </div>
                      </div>
                      
                      <div class="yw-info-card">
                        <div class="yw-info-icon-wrapper">
                          <span class="ti ti-mail yw-info-icon" aria-hidden="true"></span>
                        </div>
                        <div class="yw-info-text">
                          <h3 class="yw-info-card-title">Email Address</h3>
                          <p class="yw-info-card-content"><a href="mailto:info@tnyouthpolitics.in" class="yw-info-link">info@tnyouthpolitics.in</a></p>
                        </div>
                      </div>
                      
                      <div class="yw-info-card">
                        <div class="yw-info-icon-wrapper">
                          <span class="ti ti-phone yw-info-icon" aria-hidden="true"></span>
                        </div>
                        <div class="yw-info-text">
                          <h3 class="yw-info-card-title">Phone Number</h3>
                          <p class="yw-info-card-content"><a href="tel:+914424351234" class="yw-info-link">+91 44 2435 1234</a></p>
                        </div>
                      </div>

                      <div class="yw-info-card">
                        <div class="yw-info-icon-wrapper">
                          <span class="ti ti-clock yw-info-icon" aria-hidden="true"></span>
                        </div>
                        <div class="yw-info-text">
                          <h3 class="yw-info-card-title">Office Hours</h3>
                          <p class="yw-info-card-content">Monday - Saturday: 9:00 AM - 6:00 PM IST</p>
                        </div>
                      </div>
                    </div>

                    <div class="yw-share-box">
                      <h3 class="yw-share-box-title">Share Our Portal</h3>
                      <p class="yw-share-box-desc">Spread the word about the workshop to help empower other young leaders in Tamil Nadu.</p>
                      <button id="yw-share-btn-inline" class="yw-btn yw-btn-secondary yw-share-btn" type="button">
                        <span class="ti ti-share yw-btn-icon" aria-hidden="true"></span> Share Workshop Portal
                      </button>
                    </div>
                  </div>

                  <!-- Column 2: Form -->
                  <div class="yw-contact-form-container">
                    <h2 class="yw-form-title">Send Us a Message</h2>
                    <div class="yw-form-divider"></div>
                    
                    <form id="yw-contact-form" class="yw-form" action="#" method="post">
                      <!-- WP AJAX Compatibility -->
                      <input type="hidden" name="action" value="yw_contact_submit">
                      <input type="hidden" name="nonce" value="<?php echo esc_attr( $yw_nonce ); ?>">
                      
                      <div class="yw-form-group">
                        <label for="yw-contact-name" class="yw-form-label">Full Name</label>
                        <input type="text" id="yw-contact-name" name="name" class="yw-form-input" placeholder="e.g. Arul Selvan" required>
                      </div>
                      
                      <div class="yw-form-group">
                        <label for="yw-contact-email" class="yw-form-label">Email Address</label>
                        <input type="email" id="yw-contact-email" name="email" class="yw-form-input" placeholder="e.g. arul.selvan@example.com" required>
                      </div>
                      
                      <div class="yw-form-group">
                        <label for="yw-contact-phone" class="yw-form-label">Phone Number (Optional)</label>
                        <input type="tel" id="yw-contact-phone" name="phone" class="yw-form-input" placeholder="e.g. +91 98765 43210">
                      </div>

                      <div class="yw-form-group">
                        <label for="yw-contact-subject" class="yw-form-label">Subject</label>
                        <input type="text" id="yw-contact-subject" name="subject" class="yw-form-input" placeholder="How can we help you?" required>
                      </div>
                      
                      <div class="yw-form-group">
                        <label for="yw-contact-message" class="yw-form-label">Your Message</label>
                        <textarea id="yw-contact-message" name="message" class="yw-form-textarea" rows="5" placeholder="Type your query or feedback here..." required></textarea>
                      </div>
                      
                      <div class="yw-form-group yw-form-checkbox-group">
                        <input type="checkbox" id="yw-contact-consent" name="consent" class="yw-form-checkbox" required>
                        <label for="yw-contact-consent" class="yw-form-checkbox-label">I agree that my submitted data will be used to contact me and will be stored securely in accordance with the privacy policy.</label>
                      </div>

                      <div class="yw-form-actions">
                        <button type="submit" id="yw-submit-btn" class="yw-btn yw-btn-primary yw-btn-block">Send Message</button>
                      </div>
                    </form>
                    
                    <div id="yw-form-message-container" class="yw-form-response-message yw-hidden" aria-live="polite"></div>
                  </div>
                  
                </div>
              </section>

              <section id="yw-map-section" class="yw-map-section">
                <div class="yw-container">
                  <div class="yw-map-wrapper">
                    <div class="yw-map-overlay">
                      <span class="ti ti-map yw-map-icon" aria-hidden="true"></span>
                      <h3 class="yw-map-title">Interactive Map Mockup</h3>
                      <p class="yw-map-desc">Our headquarters are located at Teynampet, Chennai. Safe parking and public transport transit are available nearby.</p>
                      <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="yw-btn yw-btn-outline-white">Open in Google Maps</a>
                    </div>
                    <div class="yw-map-placeholder-bg"></div>
                  </div>
                </div>
              </section>
              <?php
              
          } else {
              // ==========================================
              // HOME / DEFAULT LANDING PAGE TEMPLATE
              // ==========================================
              echo do_shortcode( '[yw_hero]' );
              echo do_shortcode( '[yw_about]' );
              echo do_shortcode( '[yw_why_women]' );
              echo do_shortcode( '[yw_pathway]' );
              echo do_shortcode( '[yw_programs]' );
              echo do_shortcode( '[yw_speakers]' );
              echo do_shortcode( '[yw_testimonials]' );
              echo do_shortcode( '[yw_schedule]' );
              echo do_shortcode( '[yw_updates]' );
              echo do_shortcode( '[yw_join]' );
              
              // Include the registration section with nonce protection enjected
              ?>
              <section id="registration" class="yw-registration-section">
                <div class="yw-container yw-form-container-box">
                  <div class="yw-section-header">
                    <span class="yw-section-tagline">APPLY NOW</span>
                    <h2 class="yw-section-title">பயிற்சி முகாமில் சேர இப்போதே பதிவு செய்க</h2>
                    <div class="yw-section-divider"></div>
                  </div>
                  
                  <form id="yw-registration-form" class="yw-form">
                    <input type="hidden" name="action" value="yw_workshop_register">
                    <input type="hidden" name="nonce" value="<?php echo esc_attr( $yw_nonce ); ?>">
                    
                    <div class="yw-form-grid">
                      <div class="yw-form-group">
                        <label for="yw-field-name" class="yw-form-label">முழு பெயர்</label>
                        <input type="text" id="yw-field-name" name="name" class="yw-form-input" placeholder="e.g. Priyan Subramaniam" required>
                      </div>
                      
                      <div class="yw-form-group">
                        <label for="yw-field-email" class="yw-form-label">மின்னஞ்சல் முகவரி</label>
                        <input type="email" id="yw-field-email" name="email" class="yw-form-input" placeholder="e.g. priyan@example.com" required>
                      </div>
                      
                      <div class="yw-form-group">
                        <label for="yw-field-phone" class="yw-form-label">தொலைபேசி எண் (விருப்பத்தின் பேரில்)</label>
                        <input type="tel" id="yw-field-phone" name="phone" class="yw-form-input" placeholder="e.g. +91 9876543210">
                      </div>
                      
                      <div class="yw-form-group">
                        <label for="yw-field-interest" class="yw-form-label">விருப்பமான பயிற்சிப் பிரிவு</label>
                        <select id="yw-field-interest" name="interest" class="yw-form-select" required>
                          <option value="">-- Please select a program --</option>
                          <option value="Local Governance &amp; Panchayati Raj">Local Governance &amp; Panchayati Raj</option>
                          <option value="Public Policy Formulation">Public Policy Formulation</option>
                          <option value="Grassroots Campaigning">Grassroots Campaigning</option>
                          <option value="Digital Advocacy &amp; Communications">Digital Advocacy &amp; Communications</option>
                          <option value="Public Budgeting &amp; Municipal Finance">Public Budgeting &amp; Municipal Finance</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="yw-form-group">
                      <label for="yw-field-message" class="yw-form-label">கூடுதல் தகவல் / உங்கள் இலக்குகள்</label>
                      <textarea id="yw-field-message" name="message" class="yw-form-textarea" rows="4" placeholder="Briefly share why you want to participate and what you hope to gain..."></textarea>
                    </div>
                    
                    <div class="yw-form-actions">
                      <button type="submit" id="yw-submit-btn" class="yw-btn yw-btn-primary yw-btn-block">விண்ணப்பிக்கவும்</button>
                    </div>
                    <p class="yw-form-privacy">உங்கள் தனிப்பட்ட விவரங்களின் பாதுகாப்பு எங்களின் பொறுப்பு. இது பயிற்சி தொடர்புக்காக மட்டுமே பயன்படுத்தப்படும்.</p>
                  </form>
                  <div id="yw-form-message-container" class="yw-form-response-message yw-hidden"></div>
                </div>
              </section>
              
              <?php
              echo do_shortcode( '[yw_faq]' );
              echo do_shortcode( '[yw_social_share]' );
          }
      }
      ?>
    </main>

    <!-- Footer Section -->
    <footer id="footer" class="yw-footer-section">
      <div class="yw-container yw-footer-grid">
        <div class="yw-footer-brand">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="yw-logo yw-logo-footer" aria-label="Tamil Nadu Youth Political Workshop Home">
            <span class="ti ti-building-monument yw-logo-icon" aria-hidden="true"></span>
            <span class="yw-logo-text">TN Youth Political Workshop</span>
          </a>
          <p class="yw-footer-tagline">Building informed, active, and collaborative civic leadership among the youth of Tamil Nadu.</p>
        </div>
        
        <div class="yw-footer-nav">
          <h4 class="yw-footer-title">Navigation</h4>
          <ul class="yw-footer-links-list">
            <?php
            foreach ( $nav_items as $label => $item_slug ) {
                $url = ( $item_slug === 'workshop' && is_front_page() ) ? home_url( '/' ) : home_url( '/' . $item_slug . '/' );
                echo '<li><a href="' . esc_url( $url ) . '" class="yw-footer-link">' . esc_html( $label ) . '</a></li>';
            }
            ?>
          </ul>
        </div>
        
        <div class="yw-footer-social">
          <h4 class="yw-footer-title">Connect with Us</h4>
          <div class="yw-social-icons">
            <a href="https://x.com" class="yw-social-icon-link" aria-label="X (formerly Twitter)" target="_blank" rel="noopener"><i class="ti ti-brand-x" aria-hidden="true"></i></a>
            <a href="https://linkedin.com" class="yw-social-icon-link" aria-label="LinkedIn" target="_blank" rel="noopener"><i class="ti ti-brand-linkedin" aria-hidden="true"></i></a>
            <a href="https://facebook.com" class="yw-social-icon-link" aria-label="Facebook" target="_blank" rel="noopener"><i class="ti ti-brand-facebook" aria-hidden="true"></i></a>
            <a href="https://youtube.com" class="yw-social-icon-link" aria-label="YouTube" target="_blank" rel="noopener"><i class="ti ti-brand-youtube" aria-hidden="true"></i></a>
            <a href="https://instagram.com" class="yw-social-icon-link" aria-label="Instagram" target="_blank" rel="noopener"><i class="ti ti-brand-instagram" aria-hidden="true"></i></a>
            <a href="https://whatsapp.com" class="yw-social-icon-link" aria-label="WhatsApp" target="_blank" rel="noopener"><i class="ti ti-brand-whatsapp" aria-hidden="true"></i></a>
            <a href="https://telegram.org" class="yw-social-icon-link" aria-label="Telegram" target="_blank" rel="noopener"><i class="ti ti-brand-telegram" aria-hidden="true"></i></a>
            <a href="https://threads.net" class="yw-social-icon-link" aria-label="Threads" target="_blank" rel="noopener"><i class="ti ti-brand-threads" aria-hidden="true"></i></a>
          </div>
        </div>
      </div>
      
      <div class="yw-footer-bottom">
        <div class="yw-container">
          <p class="yw-copyright">&copy; 2026 Tamil Nadu Youth Political Workshop. All rights reserved. Non-partisan civic training platform.</p>
        </div>
      </div>
    </footer>

    <!-- Back to top button -->
    <button id="yw-back-to-top" class="yw-back-to-top yw-hidden" type="button" aria-label="Back to top">
      <i class="ti ti-chevron-up" aria-hidden="true"></i>
    </button>
    
  </div>
  <?php wp_footer(); ?>
</body>
</html>
