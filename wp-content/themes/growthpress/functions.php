<?php
/**
 * GrowthPress Theme Functions - Advanced Customizer v5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

function growthpress_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );
    register_nav_menus( array( 'primary' => 'Primary Menu' ) );
}
add_action( 'after_setup_theme', 'growthpress_setup' );

/**
 * Register Customizer Settings
 */
function growthpress_customize_register( $wp_customize ) {
    // 1. Elite Branding & Geometry
    $wp_customize->add_section( 'growthpress_branding', array(
        'title' => 'Elite Branding & Geometry',
        'description' => 'Calibrate your system-wide visual personality nodes.',
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'gp_design_style', array( 'default' => 'unisex', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_design_style', array(
        'label' => 'Strategic Design Set', 'section' => 'growthpress_branding', 'type' => 'select',
        'choices' => array( 'unisex' => 'Minimalist Modern (Unisex)', 'male' => 'Bold Executive (Male Focus)', 'female' => 'Elegant Professional (Female Focus)' ),
    ) );

    $wp_customize->add_setting( 'gp_global_radius', array( 'default' => '32px', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_global_radius', array( 'label' => 'Global Corner Geometry', 'section' => 'growthpress_branding', 'type' => 'text' ) );

    $wp_customize->add_setting( 'growthpress_primary_color', array( 'default' => '#4F46E5', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'growthpress_primary_color', array( 'label' => 'Primary Brand Node', 'section' => 'growthpress_branding' ) ) );

    $wp_customize->add_setting( 'gp_elite_gradient', array( 'default' => 'linear-gradient(135deg, #1E1B4B 0%, #4F46E5 100%)', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_elite_gradient', array(
        'label' => 'Aesthetic Trajectory Gradient', 'section' => 'growthpress_branding', 'type' => 'select',
        'choices' => array(
            'linear-gradient(135deg, #1E1B4B 0%, #4F46E5 100%)' => 'Midnight Indigo (Unisex)',
            'linear-gradient(135deg, #020617 0%, #2563EB 100%)' => 'Deep Blue Executive (Male)',
            'linear-gradient(135deg, #4C0519 0%, #BE185D 100%)' => 'Royal Rose Luxe (Female)',
            'linear-gradient(135deg, #064E3B 0%, #10B981 100%)' => 'Emerald Growth (Green)'
        ),
    ) );

    $wp_customize->add_setting( 'gp_header_layout', array( 'default' => 'space-between', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_header_layout', array(
        'label' => 'Dynamic Header Architecture', 'section' => 'growthpress_branding', 'type' => 'select',
        'choices' => array(
            'space-between' => 'Logo Left / Nav Center / CTA Right',
            'center'        => 'Logo & Nav Centered (Symmetric)',
            'flex-start'    => 'Logo Left / Nav Left / CTA Right'
        ),
    ) );

    // 2. Homepage Hero Engine
    $wp_customize->add_section( 'growthpress_homepage', array( 'title' => 'Homepage Hero Engine', 'priority' => 31 ) );
    $wp_customize->add_setting( 'gp_hero_headline', array( 'default' => 'Transform Your Business with AI Intelligence', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_headline', array( 'label' => 'Hero Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_hero_subheadline', array( 'default' => 'The unified operating system for high-ticket service firms. Scale faster, automate smarter.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_subheadline', array( 'label' => 'Hero Subheadline', 'section' => 'growthpress_homepage', 'type' => 'textarea' ) );

    // 3. Strategic Services Admin
    $wp_customize->add_section( 'growthpress_services_admin', array( 'title' => 'Service Page Strategy', 'priority' => 32 ) );
    $wp_customize->add_setting( 'gp_services_headline', array( 'default' => 'Elite Service Infrastructure', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_services_headline', array( 'label' => 'Services Headline', 'section' => 'growthpress_services_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_services_subheadline', array( 'default' => 'Proprietary methodologies engineered for market dominance and high-ticket returns.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_services_subheadline', array( 'label' => 'Services Subheadline', 'section' => 'growthpress_services_admin', 'type' => 'textarea' ) );

    // 4. Case Studies & ROI Admin
    $wp_customize->add_section( 'growthpress_results_admin', array( 'title' => 'Case Study Layouts', 'priority' => 33 ) );
    $wp_customize->add_setting( 'gp_results_headline', array( 'default' => 'Verified Results & ROI Profiles', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_results_headline', array( 'label' => 'Results Headline', 'section' => 'growthpress_results_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_results_subheadline', array( 'default' => 'Visual confirmation of our precision engineering and client success trajectories.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_results_subheadline', array( 'label' => 'Results Subheadline', 'section' => 'growthpress_results_admin', 'type' => 'textarea' ) );

    // 5. Pricing & Investment Strategy
    $wp_customize->add_section( 'growthpress_pricing_admin', array( 'title' => 'Pricing Infrastructure', 'priority' => 34 ) );
    $wp_customize->add_setting( 'gp_pricing_headline', array( 'default' => 'Strategic Investment Tiers', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_pricing_headline', array( 'label' => 'Pricing Headline', 'section' => 'growthpress_pricing_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_pricing_subheadline', array( 'default' => 'Select the operational tier that aligns with your enterprise growth goals.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_pricing_subheadline', array( 'label' => 'Pricing Subheadline', 'section' => 'growthpress_pricing_admin', 'type' => 'textarea' ) );

    // 6. About & Mission Strategy
    $wp_customize->add_section( 'growthpress_about_admin', array( 'title' => 'Mission Vision Strategy', 'priority' => 35 ) );

    // 7. Team & Specialist Strategy
    $wp_customize->add_section( 'growthpress_team_admin', array( 'title' => 'Team Node Strategy', 'priority' => 36 ) );
    $wp_customize->add_setting( 'gp_team_headline', array( 'default' => 'Specialized Operational Team', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_team_headline', array( 'label' => 'Team Headline', 'section' => 'growthpress_team_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_team_subheadline', array( 'default' => 'Elite human capital nodes trained in high-stakes operational execution.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_team_subheadline', array( 'label' => 'Team Subheadline', 'section' => 'growthpress_team_admin', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'gp_about_headline', array( 'default' => 'Engineering Market Dominance', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_about_headline', array( 'label' => 'About Headline', 'section' => 'growthpress_about_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_about_text', array( 'default' => 'We are dedicated to building the worlds most advanced business growth operating systems, empowering high-ticket firms with autonomous intelligence.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'gp_about_text', array( 'label' => 'Mission Statement', 'section' => 'growthpress_about_admin', 'type' => 'textarea' ) );

    // 7. Market Authority & Social Proof
    $wp_customize->add_section( 'growthpress_authority_admin', array( 'title' => 'Market Authority Feed', 'priority' => 36 ) );
    $wp_customize->add_setting( 'gp_enable_authority_feed', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_authority_feed', array( 'label' => 'Enable Real-time Feed', 'section' => 'growthpress_authority_admin', 'type' => 'checkbox' ) );

    $wp_customize->add_setting( 'gp_authority_interval', array( 'default' => '30000', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_authority_interval', array(
        'label' => 'Feed Pulse Frequency (ms)', 'section' => 'growthpress_authority_admin', 'type' => 'select',
        'choices' => array( '15000' => 'Fast (15s)', '30000' => 'Standard (30s)', '60000' => 'Conservative (1m)' ),
    ) );

    // 8. Contact & Support Admin
    $wp_customize->add_section( 'growthpress_contact_admin', array( 'title' => 'Contact Configuration', 'priority' => 37 ) );
    $wp_customize->add_setting( 'gp_contact_headline', array( 'default' => 'Initiate Strategic Sequence', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_contact_headline', array( 'label' => 'Contact Headline', 'section' => 'growthpress_contact_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_contact_subheadline', array( 'default' => 'Uplink with our specialist team to calibrate your growth operating system.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_contact_subheadline', array( 'label' => 'Contact Subheadline', 'section' => 'growthpress_contact_admin', 'type' => 'textarea' ) );

    // 8. Conversion UI & Global CTAs
    $wp_customize->add_section( 'growthpress_conversion_admin', array( 'title' => 'Conversion UI Controls', 'priority' => 38 ) );
    $wp_customize->add_setting( 'gp_enable_sticky_cta', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_sticky_cta', array( 'label' => 'Enable Global Sticky CTA', 'section' => 'growthpress_conversion_admin', 'type' => 'checkbox' ) );

    $wp_customize->add_setting( 'gp_footer_style', array( 'default' => 'luxe', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_footer_style', array(
        'label' => 'Footer Architecture', 'section' => 'growthpress_conversion_admin', 'type' => 'select',
        'choices' => array( 'standard' => 'Standard Corporate', 'luxe' => 'High-Luxe Immersive', 'minimal' => 'Minimalist Technical' ),
    ) );

    // 9. Ecosystem Maintenance
    $wp_customize->add_section( 'growthpress_maintenance', array( 'title' => 'OS Maintenance & Sync', 'priority' => 100 ) );
    $wp_customize->add_setting( 'gp_regenerate_trigger', array( 'default' => '' ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gp_regenerate_trigger', array(
        'label' => 'Sync Ecosystem', 'section' => 'growthpress_maintenance', 'type' => 'button',
        'input_attrs' => array( 'value' => 'Apply Updates & Sync All Pages', 'class' => 'button button-primary', 'onclick' => 'if(confirm("Regenerate and template all core pages now?")){ jQuery.post(ajaxurl, {action:"gp_regenerate_pages", gp_nonce:"'.wp_create_nonce("gp_admin_nonce").'"}); }' ),
    ) ) );
}
add_action( 'customize_register', 'growthpress_customize_register' );

function growthpress_scripts() {
	wp_enqueue_style( 'growthpress-inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Lexend:wght@400;700;800;900&display=swap' );
	wp_enqueue_style( 'growthpress-style', get_stylesheet_uri() );
    wp_enqueue_style( 'growthpress-accents', get_template_directory_uri() . '/niche-accents.css' );
    wp_enqueue_style( 'growthpress-mobile-cta', get_template_directory_uri() . '/mobile-cta.css' );

    $primary = get_theme_mod( 'growthpress_primary_color', '#4F46E5' );
    $gradient = get_theme_mod( 'gp_elite_gradient', 'linear-gradient(135deg, #1E1B4B 0%, #4F46E5 100%)' );
    $radius = get_theme_mod( 'gp_global_radius', '32px' );
    wp_add_inline_style( 'growthpress-style', ":root { --primary: $primary; --elite-gradient: $gradient; --radius: $radius; }" );

    if ( defined( 'GROWTHPRESS_CORE_URL' ) ) {
	    wp_enqueue_script( 'growthpress-frontend-js', GROWTHPRESS_CORE_URL . 'assets/js/frontend.js', array('jquery'), '1.0.0', true );
	    wp_localize_script( 'growthpress-frontend-js', 'gp_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'authority_enabled' => get_theme_mod('gp_enable_authority_feed', true),
            'authority_interval' => get_theme_mod('gp_authority_interval', '30000')
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'growthpress_scripts' );

function growthpress_body_classes( $classes ) {
    $niche = get_option( 'growthpress_niche', 'business' );
    $design = get_theme_mod( 'gp_design_style', 'unisex' );
    $classes[] = 'gp-niche-' . $niche;
    $classes[] = 'gp-design-' . $design;
    return $classes;
}
add_filter( 'body_class', 'growthpress_body_classes' );

function growthpress_footer_popup() {
    if ( is_admin() || ! defined( 'GROWTHPRESS_CORE_URL' ) ) return;
    ?>
    <div id="gp-exit-popup" class="glass-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000; width:460px; text-align:center; padding: 50px;">
        <h2 class="text-gradient">Wait! Get Your Free AI Roadmap</h2>
        <p>Enter your details below to receive a custom 12-month business growth strategy powered by GPT-4.</p>
        <?php echo do_shortcode('[gp_lead_form]'); ?>
        <button onclick="jQuery('#gp-exit-popup').fadeOut()" style="margin-top:20px; border:none; background:none; cursor:pointer; color:#64748B; font-weight:700;">No thanks, I'll pass.</button>
    </div>
    <?php
}
add_action( 'wp_footer', 'growthpress_footer_popup' );
