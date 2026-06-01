<?php
/**
 * Single Property Template - GrowthPress Elite
 */
get_header(); ?>
<main class="site-main grainy-bg" style="padding-top:100px; padding-bottom:100px;">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="glass-card" style="padding:0; border-radius:40px; overflow:hidden; margin-bottom:60px;">
                <?php if(has_post_thumbnail()) the_post_thumbnail('full', array('style'=>'width:100%; height:auto; display:block;')); ?>
            </div>
            <div class="wp-block-columns" style="gap:50px;">
                <div class="wp-block-column" style="flex-basis:60%;">
                    <div style="display:flex; gap:20px; margin-bottom:20px; font-weight:900; font-size:13px; color:var(--primary); letter-spacing:1px;">
                        <?php if($price = get_post_meta(get_the_ID(), '_gp_price', true)): ?><span>$<?php echo number_format($price); ?></span><?php endif; ?>
                        <?php if($sqft = get_post_meta(get_the_ID(), '_gp_sqft', true)): ?><span><?php echo number_format($sqft); ?> SQFT</span><?php endif; ?>
                    </div>
                    <h1 class="text-gradient" style="margin-bottom:30px;"><?php the_title(); ?></h1>
                    <div class="entry-content" style="font-size:1.2rem; line-height:1.8; opacity:0.8;">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="wp-block-column">
                    <div class="glass-card" style="background:var(--secondary); color:white; border:none;">
                        <h3 style="color:white;">Schedule Private Viewing</h3>
                        <p style="opacity:0.6; font-size:14px;">Inquire now to receive the full off-market dossier for this property.</p>
                        <?php echo do_shortcode('[gp_lead_form]'); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
