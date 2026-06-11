<?php
/**
 * Template Name: Strategic Services Hub
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:80px;" class="gp-reveal">
            <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">OPERATIONAL INFRASTRUCTURE</div>
            <h1 class="text-gradient" style="margin-bottom:30px;">
                <?php echo esc_html( get_theme_mod('gp_services_headline', 'Elite Service Infrastructure') ); ?>
            </h1>
            <p style="font-size:1.3rem; opacity:0.7; max-width:800px; margin:0 auto;">
                <?php echo esc_html( get_theme_mod('gp_services_subheadline', 'Proprietary methodologies engineered for market dominance and high-ticket returns.') ); ?>
            </p>
        </div>

        <?php echo do_shortcode('[gp_service_grid]'); ?>

        <div style="margin-top:120px;" class="gp-reveal">
            <div class="glass-card" style="padding:100px 60px; text-align:center; border:2px solid var(--primary);">
                <h2 class="text-gradient">Ready to Scale?</h2>
                <p style="margin-bottom:50px;">Secure a strategy session to determine which service node aligns with your Q3/Q4 growth targets.</p>
                <?php echo do_shortcode('[gp_booking_form]'); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
