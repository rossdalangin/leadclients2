<?php
/**
 * Template Name: AI Strategy Session (Elite)
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div class="wp-block-columns are-vertically-aligned-center gp-reveal" style="gap:80px; margin-bottom:100px;">
            <div class="wp-block-column" style="flex-basis:50%;">
                <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">HIGH-STAKES AUDIT</div>
                <h1 class="text-gradient" style="margin-bottom:35px; line-height:0.9;">AI Growth Strategy Brief</h1>
                <p style="font-size:1.4rem; line-height:1.7; opacity:0.8; margin-bottom:40px;">
                    Secure a direct uplink with our specialized senior strategists to architect your bespoke 12-month business operating system.
                </p>
                <div style="display:grid; gap:25px;">
                    <div style="display:flex; gap:15px; align-items:center;">
                        <div style="width:44px; height:44px; background:var(--primary-glow); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px;">🎯</div>
                        <div style="font-weight:700;">Custom AI Readiness Assessment</div>
                    </div>
                    <div style="display:flex; gap:15px; align-items:center;">
                        <div style="width:44px; height:44px; background:var(--primary-glow); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px;">📊</div>
                        <div style="font-weight:700;">Live Demo of the Elite OS Ecosystem</div>
                    </div>
                    <div style="display:flex; gap:15px; align-items:center;">
                        <div style="width:44px; height:44px; background:var(--primary-glow); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px;">🚀</div>
                        <div style="font-weight:700;">Immediate Strategic Trajectory Roadmap</div>
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
                <div class="glass-card" style="padding:60px; border-radius:44px; border:2px solid var(--primary); box-shadow: 0 40px 80px -15px var(--primary-glow);">
                    <div style="text-align:center; margin-bottom:40px;">
                        <h3 style="margin:0;">Secure Session</h3>
                        <p style="opacity:0.6; font-size:14px; margin-top:10px;">Select an operational window below.</p>
                    </div>
                    <?php echo do_shortcode('[gp_booking_form]'); ?>
                </div>
            </div>
        </div>

        <div class="gp-reveal" style="margin-top:120px;">
            <?php echo do_shortcode('[gp_market_chart]'); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
