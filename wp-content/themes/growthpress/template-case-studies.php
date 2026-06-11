<?php
/**
 * Template Name: Results & ROI Gallery
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg">
    <!-- Immersive Header -->
    <section class="section-padding gp-reveal" style="border-bottom: 1px solid var(--border);">
        <div class="container">
            <div class="section-header">
                <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">VERIFIED TRAJECTORIES</div>
                <h1 class="text-gradient">
                    <?php echo esc_html( get_theme_mod('gp_results_headline', 'Verified Results & ROI Profiles') ); ?>
                </h1>
                <p style="font-size:1.5rem; opacity:0.7; max-width:850px; margin:0 auto; line-height: 1.5; font-weight: 500;">
                    <?php echo esc_html( get_theme_mod('gp_results_subheadline', 'Visual confirmation of our precision engineering and client success trajectories.') ); ?>
                </p>
            </div>

            <!-- ROI Radar Integration -->
            <div style="margin-top: 80px;">
                <?php echo do_shortcode('[gp_market_chart]'); ?>
            </div>
        </div>
    </section>

    <!-- Case Study Grid -->
    <section class="section-padding gp-reveal" style="animation-delay: 0.2s;">
        <div class="container">
            <?php echo do_shortcode('[gp_case_study_grid]'); ?>
        </div>
    </section>

    <!-- Reputation & Authority Wall -->
    <section class="section-padding gp-reveal" style="animation-delay: 0.4s; background: rgba(var(--surface-rgb), 0.3);">
        <div class="container">
            <div class="grid-2">
                <div class="glass-card" style="padding:80px 60px;">
                    <div style="font-size:10px; font-weight:950; color:var(--primary); letter-spacing:3px; margin-bottom:20px; text-transform: uppercase;">Market Authority</div>
                    <h3 class="text-gradient" style="font-size: 2.5rem; margin-bottom:30px;">Peer-Verified Success</h3>
                    <p style="opacity:0.6; font-size: 1.1rem; margin-bottom: 50px;">See what our enterprise partners say about the autonomous triage and project execution velocity.</p>
                    <?php echo do_shortcode('[gp_review_feed]'); ?>
                </div>
                <div class="glass-card" style="padding:80px 60px; background:var(--secondary); color:white; border:none; box-shadow: var(--shadow-xl);">
                    <div style="font-size:10px; font-weight:950; color:var(--accent); letter-spacing:3px; margin-bottom:20px; text-transform: uppercase;">Join the Elite</div>
                    <h3 style="color:white; font-size: 2.5rem; margin-bottom:30px;">Initialize Your Profile</h3>
                    <p style="opacity:0.7; font-size: 1.1rem; margin-bottom:60px;">Ready to generate your own high-ticket ROI profile? Initialize the qualification sequence and secure your position.</p>
                    <?php echo do_shortcode('[gp_lead_form]'); ?>

                    <div style="margin-top: 60px; padding-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 11px; font-weight: 950; letter-spacing: 1px; opacity: 0.5;">STATUS: WAITING LIST ACTIVE</div>
                        <div style="font-size: 11px; font-weight: 950; letter-spacing: 1px; color: var(--accent);">AVAILABILITY: 12%</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
