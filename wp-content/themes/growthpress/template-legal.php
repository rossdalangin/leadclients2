<?php
/**
 * Template Name: Legal & Compliance
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="max-width:900px; margin:0 auto;" class="gp-reveal">
            <div class="glass-card" style="padding:100px 80px; border-top: 15px solid var(--secondary);">
                <div style="font-size:12px; font-weight:950; color:var(--secondary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">COMPLIANCE PROTOCOL</div>
                <h1 class="text-gradient" style="font-size:3.5rem; margin-bottom:50px;"><?php the_title(); ?></h1>

                <div class="entry-content" style="font-size:1.1rem; line-height:2; opacity:0.8; color:var(--text);">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>
                </div>

                <div style="margin-top:80px; padding-top:50px; border-top:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-size:11px; font-weight:800; opacity:0.4; letter-spacing:1px;">LAST REVISION: <?php the_modified_date(); ?></div>
                    <div style="display:flex; gap:20px; align-items:center;">
                        <span style="width:8px; height:8px; background:#10B981; border-radius:50%;"></span>
                        <span style="font-size:11px; font-weight:950; letter-spacing:1px;">ENCRYPTION ACTIVE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
