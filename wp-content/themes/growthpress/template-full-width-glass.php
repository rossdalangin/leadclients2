<?php
/**
 * Template Name: Full-Width Immersive Glass
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg">
    <div class="container-fluid" style="padding: 100px 40px;">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('gp-reveal'); ?>>
                <div class="glass-card" style="padding:120px 80px; min-height: 70vh;">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
