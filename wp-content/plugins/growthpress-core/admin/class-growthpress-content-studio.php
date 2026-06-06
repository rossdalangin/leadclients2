<?php
/**
 * GrowthPress AI Content Studio - Command Center v1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Content_Studio {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_studio_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_studio_assets' ) );
        add_action( 'wp_ajax_gp_generate_content', array( $this, 'handle_generation' ) );
        add_action( 'wp_ajax_gp_sync_to_kb', array( $this, 'handle_kb_sync' ) );
    }

    public function handle_kb_sync() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $title = sanitize_text_field( $_POST['title'] );
        $content = wp_kses_post( $_POST['content'] );
        $type = sanitize_text_field( $_POST['type'] ?? 'gp_kb' );

        $types_to_sync = ($type === 'sync_all') ? array('gp_kb', 'gp_service', 'gp_project', 'gp_property', 'gp_treatment') : array($type);
        $synced_ids = array();

        foreach($types_to_sync as $node_type) {
            $post_id = wp_insert_post( array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_type'    => $node_type,
                'post_status'  => 'publish'
            ) );
            if($post_id) {
                $synced_ids[] = $post_id;
                update_post_meta($post_id, '_gp_is_sample', '1'); // For easy demo cleanup
            }
        }

        if ( ! empty($synced_ids) ) {
            GrowthPress_Activity::log( "Intelligence Asset propagated across ecosystem: $title" );
            wp_send_json_success( "Synced to " . count($synced_ids) . " system nodes." );
        }
        wp_send_json_error( "Failed to sync." );
    }

    public function add_studio_menu() {
        add_submenu_page( 'growthpress-dashboard', 'AI Content Studio', 'AI Content Studio', 'manage_options', 'growthpress-studio', array( $this, 'render_studio' ) );
    }

    public function enqueue_studio_assets( $hook ) {
        if ( 'growthpress_page_growthpress-studio' !== $hook ) return;
        wp_enqueue_script( 'growthpress-studio-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery' ), GROWTHPRESS_CORE_VERSION, true );
        wp_localize_script( 'growthpress-studio-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
    }

    public function handle_generation() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $type = sanitize_text_field($_POST['content_type']);
        $topic = sanitize_text_field($_POST['topic']);
        $tone = sanitize_text_field($_POST['tone'] ?? 'Aggressive');
        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        $context = "Tone: $tone. Industry: $niche. Focus on high-ticket conversion.";

        switch($type) {
            case 'optimize': $result = $ai->call_ai("Refine this service description: \"$topic\". $context", "Service Architect"); break;
            case 'blog': $result = $ai->call_ai("Write a 1000-word SEO blog post about \"$topic\". $context", "Content Specialist"); break;
            case 'campaign': $result = $ai->call_ai("Generate 5-day email sequence for \"$topic\". $context", "Email Marketer"); break;
            case 'market': $result = $ai->call_ai("Analyze market for \"$topic\". Identify high-stakes gaps. $context", "Market Strategist"); break;
            case 'sales': $result = $ai->call_ai("Generate discovery call talk tracks for \"$topic\". $context", "AI Sales Coach"); break;
            case 'ad': $result = $ai->call_ai("Create 3 high-converting ads for \"$topic\". $context", "Ad Copywriter"); break;
            case 'headlines': $result = $ai->call_ai("Generate 5 elite headlines for \"$topic\". $context", "CRO Expert"); break;
            case 'service': $result = $ai->call_ai("Generate a Service Line description for \"$topic\". $context", "Service Architect"); break;
            case 'project': $result = $ai->call_ai("Generate a high-ticket Case Study for \"$topic\". $context", "Success Storywriter"); break;
            case 'inventory': $result = $ai->call_ai("Generate a luxury Portfolio listing for \"$topic\". $context", "Elite Marketer"); break;
            case 'kb': $result = $ai->call_ai("Generate a technical Knowledge Base article for \"$topic\". $context", "Knowledge Specialist"); break;
            case 'treatment': $result = $ai->call_ai("Generate a specialized Clinical Treatment Protocol for \"$topic\". Include duration, complexity, and clinical outcomes. $context", "Medical Director AI"); break;
            default: $result = 'Invalid selection.';
        }

        if ( is_wp_error($result) ) wp_send_json_error($result->get_error_message());
        wp_send_json_success($result);
    }

    public function render_studio() {
        $niche = get_option('growthpress_niche', 'business');
        $prompt_library = array(
            'dental'        => array('Invisalign vs Braces', 'Emergency Dental Care', 'Smile Makeovers'),
            'law'           => array('Personal Injury Rights', 'Estate Planning 101', 'Business Litigation'),
            'solar'         => array('Federal Tax Credits', 'Battery Backup Value', 'Net Metering'),
            'contractor'    => array('Kitchen Remodel ROI', 'Outdoor Living Spaces', 'Foundation Repair')
        );
        $current_prompts = $prompt_library[$niche] ?? array('Market Dominance', 'Client Acquisition', 'Authority Building');
        ?>
        <div class="wrap growthpress-studio">
            <div class="glass-card" style="background:#fdf2f8; border-left:5px solid #db2777; margin-bottom:40px;">
                <p style="margin:0; font-size:14px; color:#9d174d;"><strong>Content Calibration Hub:</strong> Calibrate your AI's tone and preview how strategic assets will propagate across your Triple-Tier design nodes.</p>
            </div>

            <div class="studio-layout" style="display:grid; grid-template-columns: 320px 1fr 400px; gap:30px;">
                <!-- Sidebar: Config -->
                <div class="glass-card" style="padding:35px;">
                    <h3 style="margin-top:0;">Neural Calibration</h3>

                    <div style="margin:25px 0;">
                        <label style="font-weight:900; font-size:10px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">ASSET CATEGORY</label>
                        <select id="gp-content-type" style="height:50px; font-weight:700;">
                            <option value="blog">SEO Authority Post</option>
                            <option value="campaign">5-Day Nurture Sequence</option>
                            <option value="market">Market Angle of Attack</option>
                            <option value="sales">Discovery Talk Tracks</option>
                            <option value="service">Service Line Description</option>
                            <option value="project">High-Ticket Case Study</option>
                            <option value="kb">Technical KB Article</option>
                            <option value="treatment">Clinical Treatment Protocol</option>
                        </select>
                    </div>

                    <div style="margin-bottom:25px;">
                        <label style="font-weight:900; font-size:10px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">STRATEGIC TONE</label>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <button class="tone-btn active" data-tone="Aggressive">Aggressive</button>
                            <button class="tone-btn" data-tone="Empathetic">Empathetic</button>
                            <button class="tone-btn" data-tone="Technical">Technical</button>
                            <button class="tone-btn" data-tone="Elite">Elite/Luxe</button>
                        </div>
                    </div>

                    <div style="margin-bottom:30px;">
                        <label style="font-weight:900; font-size:10px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">TOPIC FOCUS</label>
                        <input type="text" id="gp-content-topic" placeholder="e.g. Lead Velocity">
                    </div>

                    <button class="button button-primary button-hero" onclick="generateContent()" style="width:100%; height:60px !important; border-radius:12px !important; font-weight:900;">INITIALIZE GENERATION</button>
                </div>

                <!-- Center: Output & Preview -->
                <div style="display:flex; flex-direction:column; gap:30px;">
                    <div class="glass-card" style="padding:0; overflow:hidden; background:#0F172A; color:white; border:none;">
                        <div style="padding:20px 30px; border-bottom:1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-size:11px; font-weight:950; opacity:0.5; letter-spacing:2px;">INTELLIGENCE STREAM</span>
                            <div style="display:flex; gap:10px;">
                                <button class="button button-small" onclick="copyStudioOutput()" style="background:rgba(255,255,255,0.1); color:white; border:none;">COPY RAW</button>
                            </div>
                        </div>
                        <div id="gp-studio-output" style="padding:40px; font-family:'JetBrains Mono', monospace; font-size:13px; line-height:1.7; height:450px; overflow-y:auto; color:rgba(255,255,255,0.9);">
                            <span style="opacity:0.2;">// Standing by for neural uplink...</span>
                        </div>
                    </div>

                    <div class="glass-card" style="padding:40px;">
                        <h3 style="margin:0 0 20px 0;">Design Node Preview</h3>
                        <div style="display:flex; gap:20px; margin-bottom:30px;">
                            <button class="design-preview-btn active" data-design="unisex">Unisex Node</button>
                            <button class="design-preview-btn" data-design="male">Male Node</button>
                            <button class="design-preview-btn" data-design="female">Female Node</button>
                        </div>
                        <div id="design-preview-area" class="preview-unisex" style="padding:40px; border-radius:20px; border:1px solid #EEE; min-height:200px;">
                            <h4 id="preview-title" style="margin-bottom:15px;">Asset Preview</h4>
                            <div id="preview-body" style="font-size:14px; opacity:0.8;">Generated content will be rendered here to verify geometric and typographic alignment.</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Asset Library -->
                <div class="glass-card" style="padding:35px;">
                    <h3 style="margin-top:0;">Ecosystem Sync</h3>
                    <p style="font-size:12px; opacity:0.5; margin-bottom:30px;">Instantly route generated intelligence to the appropriate system node.</p>

                    <div id="gp-studio-actions" style="display:grid; gap:15px;">
                        <button class="sync-btn" onclick="syncAsset('gp_kb')" style="--sync-color: #4F46E5;">Sync to Knowledge Base</button>
                        <button class="sync-btn" onclick="syncAsset('gp_service')" style="--sync-color: #0F172A;">Sync to Service Lines</button>
                        <button class="sync-btn" onclick="syncAsset('gp_project')" style="--sync-color: #10B981;">Sync to Case Studies</button>
                        <button class="sync-btn" onclick="syncAsset('gp_property')" style="--sync-color: #F59E0B;">Sync to Inventory</button>
                        <button class="sync-btn" onclick="syncAsset('gp_treatment')" style="--sync-color: #EF4444;">Sync to Treatments</button>
                        <button class="sync-btn" onclick="syncAsset('sync_all')" style="--sync-color: var(--primary); background:var(--primary-glow); border-style:dashed;">Propagate to All Nodes</button>
                    </div>

                    <hr style="margin:40px 0; opacity:0.1;">

                    <h4 style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:20px;">QUICK PROMPTS</h4>
                    <div style="display:grid; gap:10px;">
                        <?php foreach($current_prompts as $p): ?>
                            <div class="quick-prompt" onclick="jQuery('#gp-content-topic').val('<?php echo esc_js($p); ?>')">
                                <?php echo esc_html($p); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .tone-btn, .design-preview-btn {
                padding:10px; border-radius:8px; border:1px solid #E2E8F0; background:white; cursor:pointer; font-size:11px; font-weight:800; transition:0.3s;
            }
            .tone-btn.active, .design-preview-btn.active { background:var(--primary); color:white; border-color:var(--primary); }
            .sync-btn {
                height:60px; border-radius:12px; border:2px solid var(--sync-color); background:transparent; color:var(--sync-color); font-weight:900; cursor:pointer; transition:0.3s; font-size:12px;
            }
            .sync-btn:hover { background:var(--sync-color); color:white; }
            .quick-prompt { padding:15px; background:#F8FAFC; border-radius:12px; font-size:12px; font-weight:700; cursor:pointer; transition:0.3s; }
            .quick-prompt:hover { background:white; box-shadow:0 10px 20px rgba(0,0,0,0.05); transform:translateX(5px); }

            .preview-male { background:#0F172A; color:white; border-radius:8px !important; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 40px 100px rgba(0,0,0,0.5); }
            .preview-female { background:rgba(255,255,255,0.8); backdrop-filter:blur(40px); color:#4C0519; border-radius:60px !important; border: 1px solid rgba(255,255,255,0.5); box-shadow: 0 40px 100px rgba(255,100,150,0.1); }
            .preview-unisex { background:rgba(255,255,255,0.7); backdrop-filter:blur(40px); color:#111827; border-radius:30px !important; border: 1px solid rgba(0,0,0,0.05); }
        </style>

        <script>
            jQuery('.tone-btn').on('click', function() {
                jQuery('.tone-btn').removeClass('active');
                jQuery(this).addClass('active');
            });
            jQuery('.design-preview-btn').on('click', function() {
                jQuery('.design-preview-btn').removeClass('active');
                jQuery(this).addClass('active');
                jQuery('#design-preview-area').attr('class', 'preview-' + jQuery(this).data('design'));
            });
        </script>
        <?php
    }
}
new GrowthPress_Content_Studio();
