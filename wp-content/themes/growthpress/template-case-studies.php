<?php
/**
 * Template Name: Results & ROI Gallery
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:80px;" class="gp-reveal">
            <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">VERIFIED TRAJECTORIES</div>
            <h1 class="text-gradient" style="margin-bottom:30px;">
                <?php echo esc_html( get_theme_mod('gp_results_headline', 'Verified Results & ROI Profiles') ); ?>
            </h1>
            <p style="font-size:1.3rem; opacity:0.7; max-width:800px; margin:0 auto;">
                <?php echo esc_html( get_theme_mod('gp_results_subheadline', 'Visual confirmation of our precision engineering and client success trajectories.') ); ?>
            </p>
        </div>

        <?php echo do_shortcode('[gp_case_study_grid]'); ?>

        <div style="margin-top:120px; display:grid; grid-template-columns: 1fr 1fr; gap:40px;" class="gp-reveal">
            <div class="glass-card" style="padding:60px;">
                <h3 class="text-gradient" style="margin-bottom:20px;">Market Authority</h3>
                <p style="opacity:0.6;">See what our enterprise partners say about the autonomous triage and project execution velocity.</p>
                <?php echo do_shortcode('[gp_review_feed]'); ?>
            </div>
            <div class="glass-card" style="padding:60px; background:var(--secondary); color:white; border:none;">
                <h3 style="color:white; margin-bottom:20px;">Join the Elite</h3>
                <p style="opacity:0.7; margin-bottom:40px;">Ready to generate your own high-ticket ROI profile? Initialize the qualification sequence today.</p>
                <?php echo do_shortcode('[gp_lead_form]'); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
