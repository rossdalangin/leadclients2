<?php
/**
 * Template Name: Intelligence Repository (KB Hub)
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:80px;" class="gp-reveal">
            <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">TECHNICAL REPOSITORY</div>
            <h1 class="text-gradient" style="margin-bottom:30px;">Intelligence Base</h1>
            <p style="font-size:1.3rem; opacity:0.7; max-width:800px; margin:0 auto;">
                Search our neural-indexed knowledge base for technical specifications, strategic blueprints, and ecosystem documentation.
            </p>
        </div>

        <div class="gp-reveal" style="margin-bottom:80px;">
            <?php echo do_shortcode('[gp_kb_search]'); ?>
        </div>

        <div class="gp-reveal">
            <?php echo do_shortcode('[gp_kb_grid]'); ?>
        </div>

        <div style="margin-top:120px;" class="gp-reveal">
            <div class="glass-card" style="background:var(--secondary); color:white; border:none; padding:80px; text-align:center; border-radius:44px;">
                <h2 style="color:white; margin-bottom:20px;">Still Calibrating?</h2>
                <p style="opacity:0.7; margin-bottom:40px; font-size:1.2rem;">Our AI assistant is available 24/7 to provide contextual guidance based on our entire technical repository.</p>
                <button onclick="jQuery('#gp-chat-launcher').click()" class="gp-btn" style="height:70px; padding:0 60px; font-size:18px;">Initiate Assistant Uplink</button>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
