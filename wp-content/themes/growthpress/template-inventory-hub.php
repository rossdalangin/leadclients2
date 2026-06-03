<?php
/**
 * Template Name: Strategic Asset Inventory (Portfolio)
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:80px;" class="gp-reveal">
            <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">CAPITAL ALLOCATION NODES</div>
            <h1 class="text-gradient" style="margin-bottom:30px;">Portfolio Inventory</h1>
            <p style="font-size:1.3rem; opacity:0.7; max-width:800px; margin:0 auto;">
                Explore our high-yield strategic assets and off-market inventory nodes, analyzed by our neural lifestyle matcher.
            </p>
        </div>

        <div class="gp-reveal">
            <?php echo do_shortcode('[gp_inventory_grid]'); ?>
        </div>

        <div style="margin-top:120px;" class="gp-reveal">
            <div class="glass-card" style="padding:100px 60px; text-align:center; border:2px solid var(--primary); border-radius:50px;">
                <h2 class="text-gradient">Request Private Access</h2>
                <p style="margin-bottom:40px; font-size:1.2rem; opacity:0.7;">Initialize the qualification sequence to access our exclusive, off-market elite inventory nodes.</p>
                <?php echo do_shortcode('[gp_quiz_lead_form]'); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
