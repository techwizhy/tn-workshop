<?php
/**
 * WordPress Shortcodes for Tamil Nadu Youth Political Workshop (TNYPW) Portal
 * 
 * Defines shortcodes to dynamically render pages and sections of the workshop portal 
 * by retrieving translations and structure from content.json.
 * 
 * Shortcodes registered:
 * - [yw_hero]          - Saffron Hero Banner with Ashoka Chakra
 * - [yw_about]         - Mission and core pillars
 * - [yw_why_women]     - Gender inclusion and empowerment statistics
 * - [yw_pathway]       - 4-step timeline roadmap
 * - [yw_programs]      - Grid of workshop curriculum tracks
 * - [yw_speakers]      - Profile cards for trainers and faculty
 * - [yw_testimonials]  - Blockquotes of student reviews
 * - [yw_schedule]      - Class batch date tables and registration actions
 * - [yw_updates]       - Local news bulletins and notices
 * - [yw_join]          - Newsletter subscription signups
 * - [yw_registration]  - Application entry forms
 * - [yw_faq]           - Interactive accordion list using details/summary tags
 * - [yw_social_share]  - Shared links drawer
 * - [yw_gallery]       - Category filters grid with lightbox attachment
 * - [yw_donate]        - Payment gateway forms for donations
 * - [yw_portal]        - Combined dispatcher helper (e.g. [yw_portal section="hero"])
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Helper: Load and cache translated contents from local content.json
 */
function yw_get_content() {
    static $content = null;
    if ( $content === null ) {
        $json_file = get_stylesheet_directory() . '/content.json';
        if ( ! file_exists( $json_file ) ) {
            $json_file = get_stylesheet_directory() . '/yw-assets/content.json';
        }
        if ( file_exists( $json_file ) ) {
            $content = json_decode( file_get_contents( $json_file ), true );
        } else {
            $content = array();
        }
    }
    return $content;
}

/**
 * Helper: Map simple icon identifiers to corresponding Tabler classes
 */
function yw_get_icon_class( $icon ) {
    $icon = str_replace( 'icon-', 'ti-', $icon );
    if ( $icon === 'ti-school' ) {
        return 'ti-school';
    }
    return $icon;
}

// ==========================================
// 1. HERO BANNER SHORTCODE
// ==========================================
add_shortcode( 'yw_hero', 'yw_hero_shortcode' );
function yw_hero_shortcode( $atts ) {
    $content = yw_get_content();
    $hero = isset( $content['hero'] ) ? $content['hero'] : array();
    
    $title       = isset( $hero['title'] ) ? $hero['title'] : 'இளம் தலைவர்களை உருவாக்குவோம், புதிய தமிழகத்தை படைப்போம்!';
    $tagline     = isset( $hero['tagline'] ) ? $hero['tagline'] : 'தமிழ்நாடு இளைஞர் அரசியல் பயிலரங்கம்';
    $subHeadline = isset( $hero['subHeadline'] ) ? $hero['subHeadline'] : 'இளைஞர் மற்றும் பெண்களின் அரசியல் பங்கேற்பை ஊக்குவிப்பதற்கும், ஆக்கபூர்வமான சமூக மாற்றத்தை உருவாக்குவதற்கும் நடத்தப்படும் நடுநிலையான அரசியல் தலைமைத்துவப் பயிற்சி.';
    $primaryCta  = isset( $hero['primaryCta'] ) ? $hero['primaryCta'] : 'பதிவு செய்க';
    $secondaryCta= isset( $hero['secondaryCta'] ) ? $hero['secondaryCta'] : 'நன்கொடை அளிக்க';

    ob_start();
    ?>
    <section id="hero" class="yw-hero-section">
      <div class="yw-hero-overlay"></div>
      <div class="yw-container yw-hero-content">
        <div class="yw-hero-badge-container">
          <!-- Ashoka Chakra inline SVG (Exactly 24 lines, 26 circles, aria-hidden="true") -->
          <svg id="yw-ashoka-chakra" class="yw-ashoka-chakra" viewBox="0 0 200 200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <g>
              <circle cx="100" cy="100" r="90" fill="none" stroke="#000080" stroke-width="4" />
              <circle cx="100" cy="100" r="8" fill="#000080" />
              <line x1="100.00" y1="100.00" x2="180.00" y2="100.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="177.27" y2="120.71" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="169.28" y2="140.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="156.57" y2="156.57" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="140.00" y2="169.28" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="120.71" y2="177.27" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="100.00" y2="180.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="79.29" y2="177.27" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="60.00" y2="169.28" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="43.43" y2="156.57" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="30.72" y2="140.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="22.73" y2="120.71" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="20.00" y2="100.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="22.73" y2="79.29" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="30.72" y2="60.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="43.43" y2="43.43" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="60.00" y2="30.72" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="79.29" y2="22.73" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="100.00" y2="20.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="120.71" y2="22.73" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="140.00" y2="30.72" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="156.57" y2="43.43" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="169.28" y2="60.00" stroke="#000080" stroke-width="2" />
              <line x1="100.00" y1="100.00" x2="177.27" y2="79.29" stroke="#000080" stroke-width="2" />
              <circle cx="183.28" cy="110.96" r="2" fill="#000080" />
              <circle cx="177.61" cy="132.15" r="2" fill="#000080" />
              <circle cx="166.65" cy="151.14" r="2" fill="#000080" />
              <circle cx="151.14" cy="166.65" r="2" fill="#000080" />
              <circle cx="132.15" cy="177.61" r="2" fill="#000080" />
              <circle cx="110.96" cy="183.28" r="2" fill="#000080" />
              <circle cx="89.04" cy="183.28" r="2" fill="#000080" />
              <circle cx="67.85" cy="177.61" r="2" fill="#000080" />
              <circle cx="48.86" cy="166.65" r="2" fill="#000080" />
              <circle cx="33.35" cy="151.14" r="2" fill="#000080" />
              <circle cx="22.39" cy="132.15" r="2" fill="#000080" />
              <circle cx="16.72" cy="110.96" r="2" fill="#000080" />
              <circle cx="16.72" cy="89.04" r="2" fill="#000080" />
              <circle cx="22.39" cy="67.85" r="2" fill="#000080" />
              <circle cx="33.35" cy="48.86" r="2" fill="#000080" />
              <circle cx="48.86" cy="33.35" r="2" fill="#000080" />
              <circle cx="67.85" cy="22.39" r="2" fill="#000080" />
              <circle cx="89.04" cy="16.72" r="2" fill="#000080" />
              <circle cx="110.96" cy="16.72" r="2" fill="#000080" />
              <circle cx="132.15" cy="22.39" r="2" fill="#000080" />
              <circle cx="151.14" cy="33.35" r="2" fill="#000080" />
              <circle cx="166.65" cy="48.86" r="2" fill="#000080" />
              <circle cx="177.61" cy="67.85" r="2" fill="#000080" />
              <circle cx="183.28" cy="89.04" r="2" fill="#000080" />
            </g>
          </svg>
        </div>
        <span class="yw-hero-tagline" style="display: block; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 15px; font-weight: 600; color: var(--yw-primary);"><?php echo esc_html( $tagline ); ?></span>
        <h1 class="yw-hero-title"><?php echo esc_html( $title ); ?></h1>
        <p class="yw-hero-subtitle"><?php echo esc_html( $subHeadline ); ?></p>
        <div class="yw-hero-actions">
          <a href="#programs" class="yw-btn yw-btn-primary"><?php echo esc_html( $primaryCta ); ?></a>
          <a href="<?php echo esc_url( home_url( '/donate' ) ); ?>" class="yw-btn yw-btn-secondary"><?php echo esc_html( $secondaryCta ); ?></a>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 2. ABOUT US SECTION SHORTCODE
// ==========================================
add_shortcode( 'yw_about', 'yw_about_shortcode' );
function yw_about_shortcode( $atts ) {
    $content = yw_get_content();
    $about = isset( $content['about'] ) ? $content['about'] : array();
    
    $title   = isset( $about['title'] ) ? $about['title'] : 'பயிலரங்கம் பற்றி';
    $mission = isset( $about['mission'] ) ? $about['mission'] : '';
    $pillars = isset( $about['pillars'] ) ? $about['pillars'] : array();
    
    ob_start();
    ?>
    <section id="about" class="yw-about-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">OUR MISSION</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        <div class="yw-about-content">
          <p class="yw-about-text">
            <?php echo esc_html( $mission ); ?>
          </p>
          <div class="yw-pillars-grid">
            <?php foreach ( $pillars as $pillar ) : 
                $icon = isset( $pillar['icon'] ) ? $pillar['icon'] : 'icon-school';
                $icon_class = yw_get_icon_class($icon);
            ?>
              <div class="yw-pillar-card">
                <div class="yw-pillar-icon-wrapper">
                  <i class="ti <?php echo esc_attr( $icon_class ); ?> yw-pillar-icon" aria-hidden="true"></i>
                </div>
                <h3 class="yw-pillar-title"><?php echo esc_html( $pillar['title'] ); ?></h3>
                <p class="yw-pillar-desc"><?php echo esc_html( $pillar['description'] ); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 3. WHY WOMEN SECTION SHORTCODE
// ==========================================
add_shortcode( 'yw_why_women', 'yw_why_women_shortcode' );
function yw_why_women_shortcode( $atts ) {
    $content = yw_get_content();
    $whyWomen = isset( $content['whyWomen'] ) ? $content['whyWomen'] : array();
    
    $title       = isset( $whyWomen['title'] ) ? $whyWomen['title'] : 'ஏன் பெண்கள்?';
    $description = isset( $whyWomen['description'] ) ? $whyWomen['description'] : '';
    
    ob_start();
    ?>
    <section id="why-women" class="yw-why-women-section">
      <div class="yw-container">
        <div class="yw-why-women-grid">
          <div class="yw-why-women-content">
            <div class="yw-section-header yw-left-align">
              <span class="yw-section-tagline">GENDER INCLUSION</span>
              <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
              <div class="yw-section-divider"></div>
            </div>
            <p class="yw-why-women-text">
              <?php echo esc_html( $description ); ?>
            </p>
            <div class="yw-why-women-stats">
              <div class="yw-stat-item">
                <span class="yw-stat-number" data-target="50">50%</span>
                <span class="yw-stat-label">உள்ளாட்சி இடஒதுக்கீடு</span>
              </div>
              <div class="yw-stat-item">
                <span class="yw-stat-number" data-target="1000">1000+</span>
                <span class="yw-stat-label">பயிற்சி பெற்ற பெண் தலைவர்கள்</span>
              </div>
            </div>
          </div>
          <div class="yw-why-women-visual">
            <div class="yw-why-women-visual-card">
              <i class="ti ti-gender-female yw-visual-icon" aria-hidden="true"></i>
              <blockquote class="yw-visual-quote">
                "முடிவெடுக்கும் அதிகாரத்தில் பெண்களின் பங்களிப்பே உண்மையான சமூக மாற்றத்தின் தொடக்கம்."
              </blockquote>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 4. TIMELINE ROADMAP SHORTCODE
// ==========================================
add_shortcode( 'yw_pathway', 'yw_pathway_shortcode' );
function yw_pathway_shortcode( $atts ) {
    $content = yw_get_content();
    $pathway = isset( $content['pathway'] ) ? $content['pathway'] : array();
    
    $title = isset( $pathway['title'] ) ? $pathway['title'] : 'பயிற்சி வழித்தடம்';
    $steps = isset( $pathway['steps'] ) ? $pathway['steps'] : array();
    
    ob_start();
    ?>
    <section id="pathway" class="yw-pathway-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">THE ROADMAP</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        <div class="yw-timeline">
          <?php foreach ( $steps as $step ) : ?>
            <div class="yw-timeline-step">
              <div class="yw-step-number"><?php echo esc_html( $step['num'] ); ?></div>
              <div class="yw-step-content">
                <h3 class="yw-step-title"><?php echo esc_html( $step['title'] ); ?></h3>
                <p class="yw-step-desc"><?php echo esc_html( $step['description'] ); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 5. WORKSHOP PROGRAMS SHORTCODE
// ==========================================
add_shortcode( 'yw_programs', 'yw_programs_shortcode' );
function yw_programs_shortcode( $atts ) {
    $content = yw_get_content();
    $programs = isset( $content['programs'] ) ? $content['programs'] : array();
    
    $title = isset( $programs['title'] ) ? $programs['title'] : 'பயிற்சி திட்டங்கள்';
    $items = isset( $programs['items'] ) ? $programs['items'] : array();
    
    // Map ids to Tabler Icons
    $icons = array(
        'civic-essentials' => 'ti-subtask',
        'campaign-strategy' => 'ti-file-text',
        'digital-advocacy' => 'ti-users',
        'policy-analysis' => 'ti-brand-twitter',
        'women-in-politics' => 'ti-chart-bar'
    );
    
    ob_start();
    ?>
    <section id="programs" class="yw-programs-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">CURRICULUM</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        <div class="yw-programs-grid">
          <?php foreach ( $items as $item ) : 
              $id = isset( $item['id'] ) ? $item['id'] : '';
              $icon_class = isset( $icons[$id] ) ? $icons[$id] : 'ti-subtask';
          ?>
            <article class="yw-program-card">
              <div class="yw-program-icon-box">
                <i class="ti <?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></i>
              </div>
              <h3 class="yw-program-title"><?php echo esc_html( $item['title'] ); ?></h3>
              <p class="yw-program-desc"><?php echo esc_html( $item['description'] ); ?></p>
              <?php if ( isset( $item['duration'] ) ) : ?>
                <div class="yw-program-meta" style="margin-top: 15px; font-size: 0.875rem; color: var(--yw-text-muted);">
                  <span><i class="ti ti-clock" aria-hidden="true"></i> <?php echo esc_html( $item['duration'] ); ?></span>
                </div>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 6. FACULTY & SPEAKERS SHORTCODE
// ==========================================
add_shortcode( 'yw_speakers', 'yw_speakers_shortcode' );
function yw_speakers_shortcode( $atts ) {
    $content = yw_get_content();
    $speakers = isset( $content['speakers'] ) ? $content['speakers'] : array();
    
    $title = isset( $speakers['title'] ) ? $speakers['title'] : 'பயிற்சியாளர்கள் & விரிவுரையாளர்கள்';
    $items = isset( $speakers['items'] ) ? $speakers['items'] : array();
    
    ob_start();
    ?>
    <section id="speakers" class="yw-speakers-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">FACULTY</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        <div class="yw-speakers-grid">
          <?php foreach ( $items as $item ) : 
              $name = isset( $item['name'] ) ? $item['name'] : '';
              $is_female = (strpos($name, 'Bharathi') !== false || strpos($name, 'Gandhimathi') !== false || strpos($name, 'Arulmozhi') !== false || strpos($name, 'Kanimozhi') !== false);
              $avatar_class = $is_female ? 'yw-female-avatar' : 'yw-male-avatar';
          ?>
            <div class="yw-speaker-card" data-name="<?php echo esc_attr( $name ); ?>">
              <div class="yw-speaker-avatar-wrapper">
                <div class="yw-speaker-avatar <?php echo esc_attr( $avatar_class ); ?>"></div>
              </div>
              <h3 class="yw-speaker-name"><?php echo esc_html( $name ); ?></h3>
              <h4 class="yw-speaker-role"><?php echo esc_html( $item['title'] ); ?></h4>
              <p class="yw-speaker-bio"><?php echo esc_html( $item['bio'] ); ?></p>
              <?php if ( isset( $item['tags'] ) && is_array( $item['tags'] ) ) : ?>
                <div class="yw-speaker-tags" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 5px;">
                  <?php foreach ( $item['tags'] as $tag ) : ?>
                    <span class="yw-badge" style="font-size: 0.75rem; background: var(--yw-primary-light); color: var(--yw-primary); padding: 2px 8px; border-radius: 4px;"><?php echo esc_html( $tag ); ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 7. ALUMNI TESTIMONIALS SHORTCODE
// ==========================================
add_shortcode( 'yw_testimonials', 'yw_testimonials_shortcode' );
function yw_testimonials_shortcode( $atts ) {
    $content = yw_get_content();
    $testimonials = isset( $content['testimonials'] ) ? $content['testimonials'] : array();
    
    $title = isset( $testimonials['title'] ) ? $testimonials['title'] : 'பங்கேற்பாளர்களின் கருத்துக்கள்';
    $items = isset( $testimonials['items'] ) ? $testimonials['items'] : array();
    
    ob_start();
    ?>
    <section id="testimonials" class="yw-testimonials-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">FEEDBACK</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        <div class="yw-testimonials-grid">
          <?php foreach ( $items as $item ) : ?>
            <blockquote class="yw-testimonial-card">
              <p class="yw-testimonial-text">"<?php echo esc_html( $item['quote'] ); ?>"</p>
              <cite class="yw-testimonial-author">
                <span class="yw-testimonial-name"><?php echo esc_html( $item['name'] ); ?></span>
                <span class="yw-testimonial-details"><?php echo esc_html( $item['role'] ); ?></span>
              </cite>
            </blockquote>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 8. BATCH SCHEDULE TABLE SHORTCODE
// ==========================================
add_shortcode( 'yw_schedule', 'yw_schedule_shortcode' );
function yw_schedule_shortcode( $atts ) {
    $content = yw_get_content();
    $schedule = isset( $content['schedule'] ) ? $content['schedule'] : array();
    
    $title = isset( $schedule['title'] ) ? $schedule['title'] : 'பயிற்சி கால அட்டவணை';
    $items = isset( $schedule['items'] ) ? $schedule['items'] : array();
    
    ob_start();
    ?>
    <section id="schedule" class="yw-schedule-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">UPCOMING BATCHES</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        
        <div class="yw-schedule-table-wrapper">
          <table class="yw-schedule-table">
            <thead>
              <tr>
                <th scope="col">பயிற்சி திட்டம்</th>
                <th scope="col">இடம்</th>
                <th scope="col">தேதி</th>
                <th scope="col">நிலை</th>
                <th scope="col">நடவடிக்கை</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ( $items as $item ) : 
                  $status = isset( $item['status'] ) ? $item['status'] : '';
                  $status_class = 'yw-badge-open';
                  
                  if ( strpos($status, 'வரையறுக்கப்பட்ட') !== false || strpos($status, 'limited') !== false ) {
                      $status_class = 'yw-badge-limited';
                      $tr_status = 'limited';
                  } elseif ( strpos($status, 'முழுமையாக') !== false || strpos($status, 'soldout') !== false || strpos($status, 'பதிவாகிவிட்டது') !== false ) {
                      $status_class = 'yw-badge-soldout';
                      $tr_status = 'soldout';
                  } else {
                      $status_class = 'yw-badge-open';
                      $tr_status = 'open';
                  }
              ?>
                <tr data-status="<?php echo esc_attr( $tr_status ); ?>">
                  <td><?php echo esc_html( $item['event'] ); ?></td>
                  <td><?php echo esc_html( $item['location'] ); ?></td>
                  <td><?php echo esc_html( $item['date'] ); ?></td>
                  <td><span class="yw-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status ); ?></span></td>
                  <td>
                    <?php if ( $tr_status === 'soldout' ) : ?>
                      <button class="yw-btn yw-btn-table yw-btn-disabled" disabled>Closed</button>
                    <?php else : ?>
                      <a href="#registration" class="yw-btn yw-btn-table yw-register-link" data-interest="<?php echo esc_attr( $item['event'] ); ?>">Register</a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 9. LOCAL UPDATES SHORTCODE
// ==========================================
add_shortcode( 'yw_updates', 'yw_updates_shortcode' );
function yw_updates_shortcode( $atts ) {
    $content = yw_get_content();
    $updates = isset( $content['updates'] ) ? $content['updates'] : array();
    
    $title = isset( $updates['title'] ) ? $updates['title'] : 'புதிய செய்திகள் & அறிவிப்புகள்';
    $items = isset( $updates['items'] ) ? $updates['items'] : array();
    
    ob_start();
    ?>
    <section id="updates" class="yw-updates-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">ANNOUNCEMENTS</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        
        <div class="yw-updates-grid">
          <?php foreach ( $items as $item ) : ?>
            <article class="yw-update-card">
              <time class="yw-update-date"><?php echo esc_html( $item['date'] ); ?></time>
              <h3 class="yw-update-title"><?php echo esc_html( $item['title'] ); ?></h3>
              <p class="yw-update-excerpt"><?php echo esc_html( $item['summary'] ); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 10. NEWSLETTER JOIN/SUBSCRIBE SHORTCODE
// ==========================================
add_shortcode( 'yw_join', 'yw_join_shortcode' );
function yw_join_shortcode( $atts ) {
    ob_start();
    ?>
    <section id="join" class="yw-join-section">
      <div class="yw-container">
        <div class="yw-join-wrapper">
          <h2 class="yw-join-title">Ready to Champion Local Leadership?</h2>
          <p class="yw-join-desc">Sign up for our newsletter to get campaign kits, governance analysis, and notifications about workshop applications in your district.</p>
          <form id="yw-join-form" class="yw-join-form">
            <div class="yw-join-input-group">
              <label for="yw-join-email" class="yw-sr-only">Email Address</label>
              <input type="email" id="yw-join-email" name="join_email" placeholder="Enter your email address" class="yw-join-input" required>
              <button type="submit" class="yw-btn yw-btn-primary">Subscribe</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 11. REGISTRATION FORM SHORTCODE
// ==========================================
add_shortcode( 'yw_registration', 'yw_registration_shortcode' );
function yw_registration_shortcode( $atts ) {
    $content = yw_get_content();
    $reg = isset( $content['registration'] ) ? $content['registration'] : array();
    
    $headline    = isset( $reg['headline'] ) ? $reg['headline'] : 'பயிற்சி முகாமில் சேர இப்போதே பதிவு செய்க';
    $fields      = isset( $reg['fields'] ) ? $reg['fields'] : array();
    $submitLabel = isset( $reg['submitLabel'] ) ? $reg['submitLabel'] : 'விண்ணப்பிக்கவும்';
    $privacyNote = isset( $reg['privacyNote'] ) ? $reg['privacyNote'] : 'உங்கள் தனிப்பட்ட விவரங்களின் பாதுகாப்பு எங்களின் பொறுப்பு.';
    
    $name_label     = isset( $fields['name'] ) ? $fields['name'] : 'Full Name';
    $email_label    = isset( $fields['email'] ) ? $fields['email'] : 'Email Address';
    $phone_label    = isset( $fields['phone'] ) ? $fields['phone'] : 'Phone Number (Optional)';
    $interest_label = isset( $fields['interest'] ) ? $fields['interest'] : 'Select Workshop Interest';
    $message_label  = isset( $fields['message'] ) ? $fields['message'] : 'Additional Message / Leadership Goals';
    
    ob_start();
    ?>
    <section id="registration" class="yw-registration-section">
      <div class="yw-container yw-form-container-box">
        <div class="yw-section-header">
          <span class="yw-section-tagline">APPLY NOW</span>
          <h2 class="yw-section-title"><?php echo esc_html( $headline ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        
        <form id="yw-registration-form" class="yw-form">
          <input type="hidden" name="action" value="yw_workshop_register">
          
          <div class="yw-form-grid">
            <div class="yw-form-group">
              <label for="yw-field-name" class="yw-form-label"><?php echo esc_html( $name_label ); ?></label>
              <input type="text" id="yw-field-name" name="name" class="yw-form-input" placeholder="e.g. Priyan Subramaniam" required>
            </div>
            
            <div class="yw-form-group">
              <label for="yw-field-email" class="yw-form-label"><?php echo esc_html( $email_label ); ?></label>
              <input type="email" id="yw-field-email" name="email" class="yw-form-input" placeholder="e.g. priyan@example.com" required>
            </div>
            
            <div class="yw-form-group">
              <label for="yw-field-phone" class="yw-form-label"><?php echo esc_html( $phone_label ); ?></label>
              <input type="tel" id="yw-field-phone" name="phone" class="yw-form-input" placeholder="e.g. +91 9876543210">
            </div>
            
            <div class="yw-form-group">
              <label for="yw-field-interest" class="yw-form-label"><?php echo esc_html( $interest_label ); ?></label>
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
            <label for="yw-field-message" class="yw-form-label"><?php echo esc_html( $message_label ); ?></label>
            <textarea id="yw-field-message" name="message" class="yw-form-textarea" rows="4" placeholder="Briefly share why you want to participate and what you hope to gain..."></textarea>
          </div>
          
          <div class="yw-form-actions">
            <button type="submit" id="yw-submit-btn" class="yw-btn yw-btn-primary yw-btn-block"><?php echo esc_html( $submitLabel ); ?></button>
          </div>
          <p class="yw-form-privacy"><?php echo esc_html( $privacyNote ); ?></p>
        </form>
        <div id="yw-form-message-container" class="yw-form-response-message yw-hidden"></div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 12. FAQ ACCORDION SHORTCODE
// ==========================================
add_shortcode( 'yw_faq', 'yw_faq_shortcode' );
function yw_faq_shortcode( $atts ) {
    $content = yw_get_content();
    $faq = isset( $content['faq'] ) ? $content['faq'] : array();
    
    $title = isset( $faq['title'] ) ? $faq['title'] : 'Frequently Asked Questions';
    $items = isset( $faq['items'] ) ? $faq['items'] : array();
    
    ob_start();
    ?>
    <section id="faq" class="yw-faq-section">
      <div class="yw-container">
        <div class="yw-section-header">
          <span class="yw-section-tagline">QUESTIONS</span>
          <h2 class="yw-section-title"><?php echo esc_html( $title ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        
        <div class="yw-faq-accordion">
          <?php foreach ( $items as $item ) : ?>
            <details class="yw-faq-item">
              <summary class="yw-faq-question"><?php echo esc_html( $item['question'] ); ?></summary>
              <div class="yw-faq-answer">
                <p><?php echo esc_html( $item['answer'] ); ?></p>
              </div>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 13. SOCIAL SHARE BUTTON SHORTCODE
// ==========================================
add_shortcode( 'yw_social_share', 'yw_social_share_shortcode' );
function yw_social_share_shortcode( $atts ) {
    $content = yw_get_content();
    $share = isset( $content['socialShare'] ) ? $content['socialShare'] : array();
    
    $title = isset( $share['title'] ) ? $share['title'] : 'சமூக ஊடகங்களில் பகிருங்கள்';
    $text  = isset( $share['text'] ) ? $share['text'] : 'ஆரோக்கியமான அரசியல் மாற்றத்தை நோக்கி இளைஞர்களை வழிநடத்தும் இந்தப் பயிலரங்கைப் பற்றி உங்கள் நண்பர்களுடன் பகிர்ந்து கொள்ளுங்கள்.';
    
    ob_start();
    ?>
    <section id="social-share" class="yw-social-share-section">
      <div class="yw-container yw-share-box">
        <h2 class="yw-share-title"><?php echo esc_html( $title ); ?></h2>
        <p class="yw-share-text"><?php echo esc_html( $text ); ?></p>
        <div class="yw-share-actions">
          <button id="yw-share-btn" class="yw-btn yw-btn-primary yw-share-btn">
            <i class="ti ti-share yw-icon" aria-hidden="true"></i> Share Page
          </button>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 14. PHOTO GALLERY SHORTCODE
// ==========================================
add_shortcode( 'yw_gallery', 'yw_gallery_shortcode' );
function yw_gallery_shortcode( $atts ) {
    $content = yw_get_content();
    $gallery = isset( $content['gallery'] ) ? $content['gallery'] : array();
    
    $items = isset( $gallery['items'] ) ? $gallery['items'] : array();
    
    ob_start();
    ?>
    <section id="yw-gallery-section" class="yw-gallery-section">
      <div class="yw-container">
        
        <!-- Category Filters -->
        <div id="yw-gallery-filters" class="yw-gallery-filters">
          <button class="yw-gallery-filter-btn yw-active" data-filter="all">அனைத்தும்</button>
          <button class="yw-gallery-filter-btn" data-filter="training">பயிற்சிகள்</button>
          <button class="yw-gallery-filter-btn" data-filter="women">பெண்கள் அரங்கம்</button>
          <button class="yw-gallery-filter-btn" data-filter="interactive">விவாதங்கள்</button>
          <button class="yw-gallery-filter-btn" data-filter="events">நிகழ்வுகள்</button>
        </div>

        <!-- Gallery Grid (12 items) -->
        <div class="yw-gallery-grid" id="yw-gallery-grid">
          <?php foreach ( $items as $item ) : 
              $cat = isset( $item['category'] ) ? $item['category'] : 'training';
              $cat_label = 'பயிற்சிகள்';
              if ( $cat === 'women' ) {
                  $cat_label = 'பெண்கள் அரங்கம்';
              } elseif ( $cat === 'interactive' ) {
                  $cat_label = 'விவாதங்கள்';
              } elseif ( $cat === 'events' ) {
                  $cat_label = 'நிகழ்வுகள்';
              }
              
              $img_url = get_stylesheet_directory_uri() . '/yw-assets/' . basename( $item['url'] );
          ?>
            <div class="yw-gallery-item" data-category="<?php echo esc_attr( $cat ); ?>" data-id="<?php echo esc_attr( $item['id'] ); ?>">
              <div class="yw-gallery-img-wrapper">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $item['caption'] ); ?>" class="yw-gallery-img">
              </div>
              <div class="yw-gallery-item-content">
                <span class="yw-gallery-item-category"><?php echo esc_html( $cat_label ); ?></span>
                <h3 class="yw-gallery-item-title"><?php echo esc_html( $item['caption'] ); ?></h3>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Lightbox Modal Container -->
    <div id="yw-lightbox" class="yw-lightbox" aria-hidden="true" role="dialog">
      <div class="yw-lightbox-content">
        <button class="yw-lightbox-close" id="yw-lightbox-close" type="button" aria-label="Close Lightbox">&times;</button>
        <img src="" alt="" class="yw-lightbox-image" id="yw-lightbox-image">
        <div class="yw-lightbox-caption" id="yw-lightbox-caption"></div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}

// ==========================================
// 15. DONATION MODULE SHORTCODE
// ==========================================
add_shortcode( 'yw_donate', 'yw_donate_shortcode' );
function yw_donate_shortcode( $atts ) {
    $content = yw_get_content();
    $don = isset( $content['donations'] ) ? $content['donations'] : array();
    
    $headline    = isset( $don['headline'] ) ? $don['headline'] : 'இளம் தலைமுறையினரின் அரசியல் பயணத்திற்கு கைகொடுங்கள்';
    $description = isset( $don['description'] ) ? $don['description'] : '';
    $amounts     = isset( $don['amounts'] ) ? $don['amounts'] : array(501, 1001, 2501, 5001, 10001);
    $submitLabel = isset( $don['submitLabel'] ) ? $don['submitLabel'] : 'நன்கொடை அளிக்கவும்';
    
    ob_start();
    ?>
    <section id="yw-donate-section" class="yw-donate-section">
      <div class="yw-container yw-form-container-box">
        <div class="yw-section-header">
          <span class="yw-section-tagline">SUPPORT US</span>
          <h2 class="yw-section-title"><?php echo esc_html( $headline ); ?></h2>
          <div class="yw-section-divider"></div>
        </div>
        
        <p class="yw-donate-description" style="text-align: center; margin-bottom: 30px; color: var(--yw-text-muted);">
          <?php echo esc_html( $description ); ?>
        </p>

        <form id="yw-donate-form" class="yw-form">
          <input type="hidden" name="action" value="yw_create_payment_order">
          
          <div class="yw-form-group">
            <label class="yw-form-label">Select Donation Amount (INR)</label>
            <div id="yw-payment-tabs" class="yw-payment-tabs" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 10px; margin-bottom: 15px;">
              <?php foreach ( $amounts as $amount ) : ?>
                <button type="button" class="yw-amount-tab yw-btn yw-btn-outline" data-amount="<?php echo esc_attr( $amount ); ?>">₹<?php echo esc_html( $amount ); ?></button>
              <?php endforeach; ?>
              <button type="button" class="yw-amount-tab yw-btn yw-btn-outline" data-amount="custom">Custom</button>
            </div>
            <input type="number" id="yw-field-amount" name="amount" class="yw-form-input" placeholder="Enter custom amount" min="10" required style="display: none; margin-top: 10px;">
          </div>

          <div class="yw-form-group">
            <label class="yw-form-label">Payment Gateway</label>
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
              <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="radio" name="gateway" value="razorpay" checked> Razorpay
              </label>
              <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="radio" name="gateway" value="payu"> PayU
              </label>
            </div>
          </div>

          <div class="yw-form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div class="yw-form-group">
              <label for="yw-donate-name" class="yw-form-label">Full Name</label>
              <input type="text" id="yw-donate-name" name="name" class="yw-form-input" placeholder="e.g. Priyan Subramaniam" required>
            </div>
            <div class="yw-form-group">
              <label for="yw-donate-email" class="yw-form-label">Email Address</label>
              <input type="email" id="yw-donate-email" name="email" class="yw-form-input" placeholder="e.g. priyan@example.com" required>
            </div>
          </div>

          <div class="yw-form-group" style="margin-bottom: 20px;">
            <label for="yw-donate-phone" class="yw-form-label">Phone Number (Optional)</label>
            <input type="tel" id="yw-donate-phone" name="phone" class="yw-form-input" placeholder="e.g. +91 9876543210">
          </div>

          <div class="yw-form-actions">
            <button type="submit" id="yw-donate-submit-btn" class="yw-btn yw-btn-primary yw-btn-block"><?php echo esc_html( $submitLabel ); ?></button>
          </div>
        </form>
        <div id="yw-donate-message-container" class="yw-form-response-message yw-hidden"></div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 16. CONTACT PAGE FORM SHORTCODE
// ==========================================
add_shortcode( 'yw_contact', 'yw_contact_shortcode' );
function yw_contact_shortcode( $atts ) {
    ob_start();
    ?>
    <section id="yw-contact-section" class="yw-contact-section" style="padding: 60px 0;">
      <div class="yw-container yw-form-container-box">
        <div class="yw-section-header">
          <span class="yw-section-tagline">GET IN TOUCH</span>
          <h2 class="yw-section-title">Contact Us</h2>
          <div class="yw-section-divider"></div>
        </div>
        
        <form id="yw-contact-form" class="yw-form">
          <input type="hidden" name="action" value="yw_contact_submit">
          
          <div class="yw-form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div class="yw-form-group">
              <label for="yw-contact-name" class="yw-form-label">Full Name</label>
              <input type="text" id="yw-contact-name" name="name" class="yw-form-input" placeholder="e.g. Priyan Subramaniam" required>
            </div>
            
            <div class="yw-form-group">
              <label for="yw-contact-email" class="yw-form-label">Email Address</label>
              <input type="email" id="yw-contact-email" name="email" class="yw-form-input" placeholder="e.g. priyan@example.com" required>
            </div>
          </div>
          
          <div class="yw-form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div class="yw-form-group">
              <label for="yw-contact-phone" class="yw-form-label">Phone Number (Optional)</label>
              <input type="tel" id="yw-contact-phone" name="phone" class="yw-form-input" placeholder="e.g. +91 9876543210">
            </div>
            
            <div class="yw-form-group">
              <label for="yw-contact-subject" class="yw-form-label">Subject</label>
              <input type="text" id="yw-contact-subject" name="subject" class="yw-form-input" placeholder="How can we help you?" required>
            </div>
          </div>
          
          <div class="yw-form-group" style="margin-bottom: 20px;">
            <label for="yw-contact-message" class="yw-form-label">Message</label>
            <textarea id="yw-contact-message" name="message" class="yw-form-textarea" rows="5" placeholder="Write your query here..." required></textarea>
          </div>
          
          <div class="yw-form-actions">
            <button type="submit" id="yw-contact-submit-btn" class="yw-btn yw-btn-primary yw-btn-block">Send Message</button>
          </div>
        </form>
        <div id="yw-contact-message-container" class="yw-form-response-message yw-hidden"></div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ==========================================
// 17. DISPATCHER SHORTCODE
// ==========================================
add_shortcode( 'yw_portal', 'yw_portal_shortcode' );
function yw_portal_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'section' => 'hero',
    ), $atts );

    switch ( $a['section'] ) {
        case 'hero':
            return yw_hero_shortcode($atts);
        case 'about':
            return yw_about_shortcode($atts);
        case 'why_women':
        case 'why-women':
            return yw_why_women_shortcode($atts);
        case 'pathway':
            return yw_pathway_shortcode($atts);
        case 'programs':
            return yw_programs_shortcode($atts);
        case 'speakers':
            return yw_speakers_shortcode($atts);
        case 'testimonials':
            return yw_testimonials_shortcode($atts);
        case 'schedule':
            return yw_schedule_shortcode($atts);
        case 'updates':
            return yw_updates_shortcode($atts);
        case 'join':
            return yw_join_shortcode($atts);
        case 'registration':
            return yw_registration_shortcode($atts);
        case 'faq':
            return yw_faq_shortcode($atts);
        case 'social_share':
        case 'social-share':
            return yw_social_share_shortcode($atts);
        case 'gallery':
            return yw_gallery_shortcode($atts);
        case 'donate':
            return yw_donate_shortcode($atts);
        case 'contact':
            return yw_contact_shortcode($atts);
        default:
            return '';
    }
}
