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
        add_shortcode( 'gp_ecosystem_radar', array( $this, 'render_ecosystem_radar' ) );
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

    public function render_ecosystem_radar() {
        $cpts = array(
            'Leads' => 'gp_lead', 'Bookings' => 'gp_appointment', 'Equity' => 'gp_proposal',
            'Revenue' => 'gp_transaction', 'Locations' => 'gp_location', 'Funnels' => 'gp_funnel',
            'Tasks' => 'gp_task', 'KB' => 'gp_kb', 'Services' => 'gp_service',
            'Cases' => 'gp_project', 'Reviews' => 'gp_review', 'Inventory' => 'gp_property',
            'Clinical' => 'gp_treatment', 'Specialists' => 'gp_staff'
        );
        $counts = array();
        foreach($cpts as $label => $type) $counts[$label] = wp_count_posts($type)->publish;

        ob_start(); ?>
        <div class="gp-ecosystem-radar glass-card gp-reveal" style="padding:60px; text-align:center; margin:40px 0;">
            <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">SYSTEM AUTHORITY MATRIX</div>
            <h3 class="text-gradient" style="font-size:2.5rem; margin:0 0 40px 0;">Ecosystem Authority Radar</h3>
            <div style="max-width:600px; margin:0 auto;">
                <canvas id="ecosystemRadarFrontend" height="400"></canvas>
            </div>
            <script>
            jQuery(document).ready(function($) {
                const ctx = document.getElementById('ecosystemRadarFrontend').getContext('2d');
                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: <?php echo json_encode(array_keys($counts)); ?>,
                        datasets: [{
                            label: 'Node Authority',
                            data: <?php echo json_encode(array_values($counts)); ?>,
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: '#4F46E5',
                            pointBackgroundColor: '#4F46E5',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        scales: {
                            r: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                pointLabels: { font: { weight: 'bold', size: 10 } }
                            }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            });
            </script>
        </div>
        <?php
        return ob_get_clean();
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
        <style>
            .gp-grid-item {
                transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.15);
            }
            .gp-grid-item:hover {
                transform: translateY(-10px);
                box-shadow: 0 30px 60px -12px rgba(50, 50, 93, 0.25), 0 18px 36px -18px rgba(0, 0, 0, 0.3);
                border-color: var(--primary);
            }
            .gp-grid-item .gp-intel-badge {
                transition: all 0.3s ease;
            }
            .gp-grid-item:hover .gp-intel-badge {
                transform: scale(1.1);
                box-shadow: 0 0 15px var(--primary-glow);
            }
        </style>
        <div class="gp-content-grid-wrapper" style="margin: 80px 0;">
            <div style="text-align: center; margin-bottom: 60px;">
                <div style="font-size: 11px; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 4px; margin-bottom: 15px;">ECOSYSTEM NODES</div>
                <h2 class="text-gradient" style="font-size: 3.5rem; margin: 0; line-height: 1.1;"><?php echo esc_html( $title ); ?></h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 40px;">
                <?php foreach ( $posts as $p ) : ?>
                    <div class="glass-card gp-grid-item gp-reveal" style="padding: 45px; border-radius: 40px; display: flex; flex-direction: column; position: relative; overflow: hidden; background: rgba(255, 255, 255, 0.65);">

                        <?php if ( $type === 'gp_project' ) :
                            $roi = get_post_meta($p->ID, '_gp_growth_roi', true);
                            if ($roi) : ?>
                                <div class="gp-intel-badge" style="position: absolute; top: 25px; right: 25px; background: var(--primary); color: white; padding: 6px 16px; border-radius: 30px; font-size: 10px; font-weight: 950; z-index: 10; letter-spacing: 1px;"><?php echo esc_html($roi); ?> ROI</div>
                            <?php endif;
                        endif; ?>

                        <?php if ( has_post_thumbnail( $p->ID ) ) : ?>
                            <div style="margin: -45px -45px 35px -45px; height: 240px; overflow: hidden; border-radius: 0; position: relative;">
                                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 60%, rgba(255,255,255,0.8)); z-index: 1;"></div>
                                <?php echo get_the_post_thumbnail( $p->ID, 'medium_large', array( 'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <div style="display: flex; flex-direction: column; flex: 1;">
                            <h3 style="margin: 0 0 15px 0; font-size: 24px; font-weight: 950; letter-spacing: -0.02em; line-height: 1.2;"><?php echo esc_html( $p->post_title ); ?></h3>

                            <?php if ( $type === 'gp_staff' ) :
                                $exp = get_post_meta($p->ID, '_staff_expertise', true);
                                if ($exp) : ?>
                                    <div style="font-size: 11px; font-weight: 950; color: var(--primary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;"><?php echo esc_html($exp); ?></div>
                                <?php endif;
                            endif; ?>

                            <div style="font-size: 15px; opacity: 0.7; line-height: 1.8; margin-bottom: 30px; flex: 1; font-weight: 500;">
                                <?php echo wp_trim_words( $p->post_content, 22 ); ?>
                            </div>

                            <?php if ( $type === 'gp_project' ) :
                                $val = get_post_meta($p->ID, '_gp_pipeline_value', true);
                                if ($val) : ?>
                                    <div style="margin-bottom: 30px; padding: 20px; background: rgba(79, 70, 229, 0.04); border-radius: 20px; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(79, 70, 229, 0.1);">
                                        <span style="font-size: 10px; font-weight: 950; opacity: 0.5; letter-spacing: 1px;">PIPELINE EQUITY</span>
                                        <span style="font-size: 16px; font-weight: 950; color: var(--primary);"><?php echo esc_html($val); ?></span>
                                    </div>
                                <?php endif;
                            endif; ?>

                            <a href="<?php echo get_permalink( $p->ID ); ?>" class="gp-btn" style="text-align: center; padding: 18px; font-size: 13px; border-radius: 18px; background: var(--secondary); color: white !important; font-weight: 800; letter-spacing: 0.5px;">EXPLORE INTEL NODE</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
new GrowthPress_Display();
