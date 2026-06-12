<?php
/**
 * Template Name: Team & Specialists Hub
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:80px;" class="gp-reveal">
            <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">HUMAN CAPITAL NODES</div>
            <h1 class="text-gradient" style="margin-bottom:30px;">
                <?php echo esc_html( get_theme_mod('gp_team_headline', 'Specialized Operational Team') ); ?>
            </h1>
            <p style="font-size:1.3rem; opacity:0.7; max-width:800px; margin:0 auto;">
                <?php echo esc_html( get_theme_mod('gp_team_subheadline', 'Elite human capital nodes trained in high-stakes operational execution.') ); ?>
            </p>
        </div>

        <div class="gp-reveal">
            <?php echo do_shortcode('[gp_staff_grid]'); ?>
        </div>

        <div style="margin-top:120px;" class="gp-reveal">
            <div class="glass-card" style="background:var(--primary); color:white; border:none; padding:80px; text-align:center; border-radius:50px;">
                <h2 style="color:white; margin-bottom:20px;">Join the GrowthPress Network</h2>
                <p style="opacity:0.7; margin-bottom:40px; font-size:1.2rem;">We are always looking for elite specialists to join our global human capital infrastructure.</p>
                <a href="/contact" class="gp-btn" style="background:white; color:var(--primary) !important;">Submit Application</a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
