<?php
/**
 * Template Name: Strategic Services Hub
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg">
    <!-- Immersive Hero -->
    <section class="section-padding gp-reveal" style="background: radial-gradient(circle at center, var(--primary-glow) 0%, transparent 70%); border-bottom: 1px solid var(--border);">
        <div class="container">
            <div class="section-header">
                <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">OPERATIONAL INFRASTRUCTURE</div>
                <h1 class="text-gradient">
                    <?php echo esc_html( get_theme_mod('gp_services_headline', 'Elite Service Infrastructure') ); ?>
                </h1>
                <p style="font-size:1.5rem; opacity:0.7; max-width:850px; margin:0 auto; line-height: 1.5;">
                    <?php echo esc_html( get_theme_mod('gp_services_subheadline', 'Proprietary methodologies engineered for market dominance and high-ticket returns.') ); ?>
                </p>
            </div>

            <div style="margin-top: 60px; display: flex; justify-content: center; gap: 40px;">
                <div style="text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 950; color: var(--secondary);">14</div>
                    <div style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing: 2px;">CORE NODES</div>
                </div>
                <div style="width: 1px; background: var(--border);"></div>
                <div style="text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 950; color: var(--secondary);">+315%</div>
                    <div style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing: 2px;">AVG ROI</div>
                </div>
                <div style="width: 1px; background: var(--border);"></div>
                <div style="text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 950; color: var(--secondary);">99.9%</div>
                    <div style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing: 2px;">UPTIME</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Grid Node -->
    <section class="section-padding gp-reveal" style="animation-delay: 0.2s;">
        <div class="container">
            <?php echo do_shortcode('[gp_service_grid]'); ?>
        </div>
    </section>

    <!-- Strategic CTA Command -->
    <section class="section-padding gp-reveal" style="animation-delay: 0.4s;">
        <div class="container">
            <div class="glass-card" style="padding:120px 80px; text-align:center; position: relative; overflow: hidden; border: 1px solid var(--primary);">
                <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: var(--primary-glow); opacity: 0.2; pointer-events: none;"></div>
                <div style="position: relative; z-index: 2;">
                    <h2 class="text-gradient" style="font-size: 3.5rem; margin-bottom: 25px;">Ready to Synchronize?</h2>
                    <p style="font-size: 1.25rem; max-width: 650px; margin: 0 auto 60px; font-weight: 500;">Secure a high-stakes strategy session to determine which service node aligns with your Q3/Q4 ecosystem expansion goals.</p>
                    <div style="max-width: 800px; margin: 0 auto;">
                        <?php echo do_shortcode('[gp_booking_form]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
