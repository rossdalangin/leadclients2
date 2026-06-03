<?php
/**
 * Template Name: Mission & Strategy (About)
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div class="wp-block-columns are-vertically-aligned-center gp-reveal" style="gap:80px; margin-bottom:120px;">
            <div class="wp-block-column" style="flex-basis:55%;">
                <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">OPERATIONAL VISION</div>
                <h1 class="text-gradient" style="margin-bottom:40px;">
                    <?php echo esc_html( get_theme_mod('gp_about_headline', 'Engineering Market Dominance') ); ?>
                </h1>
                <div style="font-size:1.4rem; line-height:1.8; opacity:0.8; margin-bottom:50px;">
                    <?php echo nl2br( esc_html( get_theme_mod('gp_about_text', 'We are dedicated to building the worlds most advanced business growth operating systems, empowering high-ticket firms with autonomous intelligence.') ) ); ?>
                </div>
                <a href="/contact" class="gp-btn">Uplink With Our Team</a>
            </div>
            <div class="wp-block-column">
                <div class="glass-card" style="padding:10px; border-radius:40px; box-shadow: 0 50px 100px -20px var(--primary-glow);">
                    <div style="height:500px; background:var(--secondary); border-radius:30px; display:flex; align-items:center; justify-content:center;">
                        <div style="font-size:8rem;">🧠</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="gp-reveal" style="margin-top:120px;">
            <h2 class="text-gradient" style="text-align:center; margin-bottom:80px;">The Ecosystem Framework</h2>
            <div class="wp-block-columns" style="gap:40px;">
                <div class="wp-block-column glass-card" style="padding:50px; text-align:center;">
                    <div style="font-size:3rem; margin-bottom:20px;">🤖</div>
                    <h4>Neural Triage</h4>
                    <p style="opacity:0.6;">Automated qualification nodes that identify high-intent prospects with 98% accuracy.</p>
                </div>
                <div class="wp-block-column glass-card" style="padding:50px; text-align:center;">
                    <div style="font-size:3rem; margin-bottom:20px;">📊</div>
                    <h4>ROI Analytics</h4>
                    <p style="opacity:0.6;">Real-time trajectory modeling for financial ledger synchronization and pipeline equity.</p>
                </div>
                <div class="wp-block-column glass-card" style="padding:50px; text-align:center;">
                    <div style="font-size:3rem; margin-bottom:20px;">⚡</div>
                    <h4>Velocity Protocol</h4>
                    <p style="opacity:0.6;">Proprietary execution systems designed to reduce operational latency across all sectors.</p>
                </div>
            </div>
        </div>

        <div class="gp-reveal" style="margin-top:120px;">
            <?php echo do_shortcode('[gp_stats_bar]'); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
