<?php
/**
 * GrowthPress Frontend Display Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Display {

    public function __construct() {
        add_shortcode( 'gp_kb_grid', array( $this, 'render_kb_grid' ) );
        add_shortcode( 'gp_case_study_grid', array( $this, 'render_case_study_grid' ) );
        add_shortcode( 'gp_staff_grid', array( $this, 'render_staff_grid' ) );
        add_shortcode( 'gp_service_grid', array( $this, 'render_service_grid' ) );
        add_shortcode( 'gp_inventory_grid', array( $this, 'render_inventory_grid' ) );
        add_shortcode( 'gp_location_grid', array( $this, 'render_location_grid' ) );
        add_shortcode( 'gp_funnel_grid', array( $this, 'render_funnel_grid' ) );
        add_shortcode( 'gp_treatment_grid', array( $this, 'render_treatment_grid' ) );
        add_shortcode( 'gp_market_chart', array( $this, 'render_market_chart' ) );
        add_shortcode( 'gp_kb_search', array( $this, 'render_kb_search' ) );
        add_shortcode( 'gp_treatment_search', array( $this, 'render_treatment_search' ) );
        add_action( 'wp_ajax_gp_kb_ai_search', array( $this, 'handle_ai_search' ) );
        add_action( 'wp_ajax_nopriv_gp_kb_ai_search', array( $this, 'handle_ai_search' ) );
    }

    public function render_kb_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_kb', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Knowledge Base' );
    }

    public function render_case_study_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_project', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Success Stories', 'gp_project' );
    }

    public function render_staff_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_staff', 'posts_per_page' => -1 ) );
        return $this->render_grid( $posts, 'Strategic Team Nodes', 'gp_staff' );
    }

    public function render_service_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_service', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Service Lines' );
    }

    public function render_inventory_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_property', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Portfolio Inventory' );
    }

    public function render_location_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_location', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Service Locations' );
    }

    public function render_funnel_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_funnel', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Conversion Funnels' );
    }

    public function render_treatment_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_treatment', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Clinical Protocols' );
    }

    public function render_market_chart() {
        ob_start(); ?>
        <div class="gp-market-chart glass-card" style="padding:60px; margin:40px 0;">
            <h3 class="text-gradient" style="margin-bottom:30px;">Strategic Sector Trajectory</h3>
            <canvas id="gpMarketChart" height="200"></canvas>
            <script>
            jQuery(document).ready(function($) {
                const ctx = document.getElementById('gpMarketChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                        datasets: [{
                            label: 'Market Authority %',
                            data: [65, 78, 82, 94],
                            borderColor: '#4F46E5',
                            tension: 0.4,
                            fill: true,
                            backgroundColor: 'rgba(79, 70, 229, 0.05)'
                        }]
                    },
                    options: { plugins: { legend: { display: false } } }
                });
            });
            </script>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_treatment_search() {
        ob_start(); ?>
        <div class="gp-treatment-ai-search glass-card" style="padding:60px; max-width:800px; margin:40px auto; border-top: 10px solid var(--primary);">
            <h3 class="text-gradient" style="text-align:center; font-size:2.5rem; margin-bottom:30px;">Clinical Intel Search</h3>
            <div style="position:relative;">
                <input type="text" id="gp-treat-query" placeholder="Search specialized clinical protocols..." style="width:100%; height:70px; border-radius:18px; border:2px solid var(--border); padding:0 30px; font-size:16px;">
                <button onclick="runTreatSearch()" class="gp-btn" style="position:absolute; right:10px; top:10px; height:50px; border-radius:12px; padding:0 25px;">SCAN</button>
            </div>
            <div id="treat-ai-results" style="margin-top:40px; display:none;">
                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:20px;">NEURAL PROTOCOL RECOMMENDATION</div>
                <div id="treat-ai-output" style="line-height:1.7; font-size:15px; background:#F0F9FF; padding:30px; border-radius:20px; border:1px solid #BAE6FD; color:#0369A1;"></div>
            </div>
        </div>
        <script>
        function runTreatSearch() {
            const q = jQuery('#gp-treat-query').val();
            const out = jQuery('#treat-ai-results').fadeIn().find('#treat-ai-output');
            out.text('CONSULTING CLINICAL REPOSITORY...').css('opacity', 0.5);
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_kb_ai_search', query: q }, function(res) {
                out.html(res.data).css('opacity', 1);
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function render_kb_search() {
        ob_start(); ?>
        <div class="gp-kb-ai-search glass-card" style="padding:60px; max-width:800px; margin:40px auto;">
            <h3 class="text-gradient" style="text-align:center; font-size:2.5rem; margin-bottom:30px;">Intelligence Search</h3>
            <div style="position:relative;">
                <input type="text" id="gp-kb-query" placeholder="Ask a technical or strategic question..." style="width:100%; height:70px; border-radius:18px; border:2px solid var(--border); padding:0 30px; font-size:16px;">
                <button onclick="runKBSearch()" class="gp-btn" style="position:absolute; right:10px; top:10px; height:50px; border-radius:12px; padding:0 25px;">SCAN</button>
            </div>
            <div id="kb-ai-results" style="margin-top:40px; display:none;">
                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:20px;">NEURAL INFERENCE RESULT</div>
                <div id="kb-ai-output" style="line-height:1.7; font-size:15px; background:#F8FAFC; padding:30px; border-radius:20px; border:1px solid #E2E8F0;"></div>
            </div>
        </div>
        <script>
        function runKBSearch() {
            const q = jQuery('#gp-kb-query').val();
            const out = jQuery('#kb-ai-results').fadeIn().find('#kb-ai-output');
            out.text('CONSULTING INTERNAL KNOWLEDGE BASE...').css('opacity', 0.5);
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_kb_ai_search', query: q }, function(res) {
                out.html(res.data).css('opacity', 1);
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_ai_search() {
        $query = sanitize_text_field($_POST['query']);
        $kb_posts = get_posts(array('post_type' => 'gp_kb', 's' => $query, 'posts_per_page' => 3));
        $context = "";
        foreach($kb_posts as $post) $context .= $post->post_title . ": " . strip_tags($post->post_content) . "\n";

        $ai = GrowthPress_AI::get_instance();
        $prompt = "Based on this internal knowledge base data: \n$context\n\n Answer the visitor's question: \"$query\". Provide a professional, system-specific response.";
        $response = $ai->call_ai($prompt, "GrowthPress Knowledge Specialist");

        if ( is_wp_error($response) ) wp_send_json_error();
        wp_send_json_success(nl2br($response));
    }

    private function render_grid( $posts, $title, $type = '' ) {
        if ( empty( $posts ) ) return '';
        ob_start(); ?>
        <div class="gp-content-grid-wrapper" style="margin: 60px 0;">
            <h2 class="text-gradient" style="font-size: 2.5rem; margin-bottom: 40px; text-align: center;"><?php echo esc_html( $title ); ?></h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <?php foreach ( $posts as $p ) : ?>
                    <div class="glass-card gp-reveal" style="padding: 40px; border-radius: 30px; display: flex; flex-direction: column; position: relative; overflow: hidden;">

                        <?php if ( $type === 'gp_project' ) :
                            $roi = get_post_meta($p->ID, '_gp_growth_roi', true);
                            if ($roi) : ?>
                                <div style="position: absolute; top: 20px; right: 20px; background: var(--primary); color: white; padding: 5px 12px; border-radius: 10px; font-size: 10px; font-weight: 950; z-index: 10;"><?php echo esc_html($roi); ?> ROI</div>
                            <?php endif;
                        endif; ?>

                        <?php if ( has_post_thumbnail( $p->ID ) ) : ?>
                            <div style="margin: -40px -40px 30px -40px; height: 200px; overflow: hidden; border-radius: 30px 30px 0 0;">
                                <?php echo get_the_post_thumbnail( $p->ID, 'medium_large', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 900;"><?php echo esc_html( $p->post_title ); ?></h3>

                        <?php if ( $type === 'gp_staff' ) :
                            $exp = get_post_meta($p->ID, '_staff_expertise', true);
                            if ($exp) : ?>
                                <div style="font-size: 10px; font-weight: 950; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;"><?php echo esc_html($exp); ?></div>
                            <?php endif;
                        endif; ?>

                        <div style="font-size: 14px; opacity: 0.7; line-height: 1.6; margin-bottom: 25px; flex: 1;">
                            <?php echo wp_trim_words( $p->post_content, 20 ); ?>
                        </div>

                        <?php if ( $type === 'gp_project' ) :
                            $val = get_post_meta($p->ID, '_gp_pipeline_value', true);
                            if ($val) : ?>
                                <div style="margin-bottom: 20px; padding: 15px; background: rgba(0,0,0,0.03); border-radius: 15px; display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 10px; font-weight: 950; opacity: 0.5;">PIPELINE VALUE</span>
                                    <span style="font-size: 14px; font-weight: 950; color: var(--secondary);"><?php echo esc_html($val); ?></span>
                                </div>
                            <?php endif;
                        endif; ?>

                        <a href="<?php echo get_permalink( $p->ID ); ?>" class="gp-btn" style="text-align: center; padding: 12px; font-size: 12px; border-radius: 12px; background: var(--secondary); color: white !important;">EXPLORE INTEL</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
new GrowthPress_Display();
