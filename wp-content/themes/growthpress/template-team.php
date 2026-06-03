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

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:40px;" class="gp-reveal">
            <?php
            $team = get_posts(array('post_type' => 'gp_staff', 'posts_per_page' => -1));
            if($team): foreach($team as $member):
                $expertise = get_post_meta($member->ID, '_staff_expertise', true);
                $seniority = get_post_meta($member->ID, '_staff_seniority', true);
                ?>
                <div class="glass-card" style="padding:0; border-radius:40px; overflow:hidden; text-align:center;">
                    <div style="height:350px; background:var(--secondary); position:relative;">
                        <?php if(has_post_thumbnail($member->ID)): ?>
                            <?php echo get_the_post_thumbnail($member->ID, 'large', array('style'=>'width:100%; height:100%; object-fit:cover;')); ?>
                        <?php else: ?>
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:6rem;">👤</div>
                        <?php endif; ?>
                        <div style="position:absolute; bottom:20px; left:20px; background:var(--primary); color:white; padding:6px 15px; border-radius:10px; font-size:10px; font-weight:950; letter-spacing:1px;"><?php echo strtoupper($seniority); ?></div>
                    </div>
                    <div style="padding:40px;">
                        <h3 style="margin:0; font-size:24px;"><?php echo esc_html($member->post_title); ?></h3>
                        <div style="font-size:12px; font-weight:900; color:var(--primary); margin-top:10px; text-transform:uppercase; letter-spacing:1px;"><?php echo esc_html($expertise); ?></div>
                        <div style="margin-top:20px; font-size:14px; opacity:0.6; line-height:1.6;">
                            <?php echo wp_trim_words($member->post_content, 20); ?>
                        </div>
                        <a href="<?php echo get_permalink($member->ID); ?>" class="gp-btn" style="width:100%; margin-top:30px; padding:15px; font-size:11px; border-radius:15px;">VIEW DOSSIER</a>
                    </div>
                </div>
            <?php endforeach; else: echo "<p style='text-align:center; grid-column: span 3; opacity:0.4;'>Synchronizing team nodes...</p>"; endif; ?>
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
