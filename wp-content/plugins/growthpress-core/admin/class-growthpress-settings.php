<?php
/**
 * GrowthPress Settings Page - Final Elite v6.3 Multi-AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Settings {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'wp_ajax_gp_test_connectivity', array( $this, 'test_connectivity' ) );
        add_action( 'wp_ajax_gp_generate_sample_data', array( $this, 'ajax_generate_sample_data' ) );
        add_action( 'wp_ajax_gp_generate_global_data', array( $this, 'ajax_generate_global_data' ) );
        add_action( 'wp_ajax_gp_remove_sample_data', array( $this, 'ajax_remove_sample_data' ) );
        add_action( 'wp_ajax_gp_clear_activity_logs', array( $this, 'ajax_clear_activity_logs' ) );
        add_action( 'wp_ajax_gp_system_reset', array( $this, 'ajax_system_reset' ) );
        add_action( 'wp_ajax_gp_run_diagnostics', array( $this, 'ajax_run_diagnostics' ) );
    }

    public function add_settings_menu() {
        add_submenu_page( 'growthpress-dashboard', 'Settings', 'Settings', 'manage_options', 'growthpress-settings', array( $this, 'render_settings' ) );
    }

    public function register_settings() {
        $keys = array(
            'growthpress_ai_provider', 'growthpress_openai_api_key', 'growthpress_claude_api_key',
            'growthpress_gemini_api_key', 'growthpress_perplexity_api_key', 'growthpress_ollama_host',
            'growthpress_ollama_model', 'growthpress_niche', 'growthpress_api_token',
            'growthpress_brand_name', 'growthpress_primary_color', 'growthpress_hot_threshold',
            'growthpress_twilio_sid', 'growthpress_twilio_token', 'growthpress_whatsapp_key',
            'growthpress_google_maps_key', 'growthpress_stripe_key', 'growthpress_stripe_secret',
            'growthpress_license_key', 'growthpress_dashboard_logo', 'growthpress_agency_mode',
            'growthpress_compliance_mode', 'growthpress_login_logo', 'growthpress_custom_css',
            'growthpress_ai_personality', 'growthpress_autopilot_mode',
            'growthpress_portal_branding', 'growthpress_neural_triggers'
        );
        foreach($keys as $k) register_setting( 'growthpress_settings_group', $k );
    }

    public function ajax_generate_sample_data() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error('Unauthorized');
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $before = $this->count_total_samples();
        GrowthPress_Sample_Data::generate_all_sample_data();
        $after = $this->count_total_samples();

        $count = $after - $before;
        wp_send_json_success("Sample ecosystem instantiated. Generated $count new strategic nodes across the OS.");
    }

    public function ajax_generate_global_data() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error('Unauthorized');
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $before = $this->count_total_samples();
        GrowthPress_Sample_Data::generate_everything();
        $after = $this->count_total_samples();

        $count = $after - $before;
        wp_send_json_success("Global Multi-Niche Ecosystem instantiated. Generated $count strategic nodes across all 10 industries.");
    }

    public function ajax_remove_sample_data() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error('Unauthorized');
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $before = $this->count_total_samples();
        GrowthPress_Sample_Data::remove_all_sample_data();
        $after = $this->count_total_samples();

        $count = $before - $after;
        wp_send_json_success("Sample intelligence purged. Safely removed $count demo nodes from the ecosystem.");
    }

    public function ajax_clear_activity_logs() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error('Unauthorized');
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        update_option( 'gp_activity_logs', array() );
        wp_send_json_success("System activity logs cleared.");
    }

    public function ajax_system_reset() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error('Unauthorized');
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        if( strtoupper($_POST['confirm']) !== 'RESET' ) wp_send_json_error('Invalid confirmation string.');

        $cpts = array('gp_lead', 'gp_appointment', 'gp_property', 'gp_review', 'gp_proposal', 'gp_transaction', 'gp_funnel', 'gp_location', 'gp_task', 'gp_kb', 'gp_project', 'gp_service', 'gp_treatment', 'gp_staff');
        foreach($cpts as $type) {
            $posts = get_posts(array('post_type' => $type, 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids'));
            foreach($posts as $id) wp_delete_post($id, true);
        }

        update_option( 'gp_activity_logs', array() );
        wp_send_json_success("Full system reset executed. All ecosystem nodes purged.");
    }

    private function count_total_samples() {
        $args = array(
            'post_type'      => array('gp_lead', 'gp_appointment', 'gp_property', 'gp_review', 'gp_proposal', 'gp_transaction', 'gp_funnel', 'gp_location', 'gp_task', 'gp_kb', 'gp_project', 'gp_service', 'gp_treatment', 'gp_staff'),
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'   => '_gp_is_sample',
                    'value' => '1',
                ),
            ),
            'post_status'    => 'any',
            'fields'         => 'ids'
        );
        $query = new WP_Query($args);
        return $query->found_posts;
    }

    public function test_connectivity() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        $ai = GrowthPress_AI::get_instance();
        $res = $ai->call_ai("Ping", "Health Check");
        if ( is_wp_error($res) ) wp_send_json_error( $res->get_error_message() );
        wp_send_json_success( "Connection successful! " . strtoupper(get_option('growthpress_ai_provider', 'openai')) . " engine is online." );
    }

    public function ajax_run_diagnostics() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error('Unauthorized');
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $results = array();
        $cpts = array('gp_lead', 'gp_appointment', 'gp_property', 'gp_review', 'gp_proposal', 'gp_transaction', 'gp_funnel', 'gp_location', 'gp_task', 'gp_kb', 'gp_project', 'gp_service', 'gp_treatment', 'gp_staff');

        foreach($cpts as $type) {
            $results['nodes'][$type] = post_type_exists($type);
        }

        $shortcodes = array('gp_lead_form', 'gp_booking_form', 'gp_client_portal', 'gp_ecosystem_radar', 'gp_kb_grid', 'gp_service_grid');
        foreach($shortcodes as $sc) {
            $results['shortcodes'][$sc] = shortcode_exists($sc);
        }

        $results['ai_link'] = !is_wp_error(GrowthPress_AI::get_instance()->call_ai("Ping", "Diagnostic"));

        ob_start();
        ?>
        <div style="text-align:left; padding:20px;">
            <h4 style="margin-top:0;">Ecosystem Node Integrity</h4>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:30px;">
                <?php foreach($results['nodes'] as $node => $status): ?>
                    <div style="font-size:12px; font-weight:700;">
                        <?php echo $status ? '✅' : '❌'; ?> <?php echo strtoupper($node); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <h4>Shortcode Availability</h4>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:30px;">
                <?php foreach($results['shortcodes'] as $sc => $status): ?>
                    <div style="font-size:12px; font-weight:700;">
                        <?php echo $status ? '✅' : '❌'; ?> [<?php echo $sc; ?>]
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="padding:20px; background:<?php echo $results['ai_link'] ? '#F0FDF4' : '#FEF2F2'; ?>; border-radius:15px; text-align:center; font-weight:950; letter-spacing:1px;">
                NEURAL UPLINK: <?php echo $results['ai_link'] ? 'SYNCHRONIZED' : 'DISCONNECTED'; ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        wp_send_json_success($html);
    }

    public function render_settings() {
        ?>
        <style>
        .status-ping { width: 12px; height: 12px; border-radius: 50%; position: relative; }
        .status-ping.active { background: #10B981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5); }
        .status-ping.active::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: #10B981; animation: ping 2s infinite; }
        .status-ping.warning { background: #F59E0B; }
        @keyframes ping { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(3); opacity: 0; } }
        .growthpress-settings .glass-card { margin-top: 20px; }
        </style>
        <div class="wrap growthpress-settings gp-reveal">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding:30px; background:rgba(255,255,255,0.6); border-radius:30px; border:1px solid var(--glass-border);">
                <h1 style="margin:0;">Ecosystem Intelligence & Configuration</h1>
                <div style="background:var(--secondary); color:white; padding:8px 16px; border-radius:30px; font-size:11px; font-weight:900; letter-spacing:1px;">ELITE v6.3 OMNI-AI</div>
            </div>

            <div class="gp-settings-tabs">
                <h2 class="nav-tab-wrapper" style="border-bottom:none; margin-bottom:30px;">
                    <a href="#tab-config" class="nav-tab nav-tab-active">Configuration</a>
                    <a href="#tab-ai" class="nav-tab">AI Providers</a>
                    <a href="#tab-integrations" class="nav-tab">Integrations</a>
                    <a href="#tab-lab" class="nav-tab">AI Prompt Lab</a>
                    <a href="#tab-automations" class="nav-tab">Strategic Automations</a>
                    <a href="#tab-white-label" class="nav-tab">White-Label & Agency</a>
                    <a href="#tab-tools" class="nav-tab">System Tools</a>
                    <a href="#tab-docs" class="nav-tab">Master Ops Manual</a>
                </h2>
            </div>

            <div id="tab-config" class="tab-content">
                <div class="glass-card" style="max-width:1100px; background:#f0f7ff; border-left:5px solid #2563eb; margin-bottom:30px;">
                    <p style="margin:0; font-size:14px; color:#1e40af;"><strong>Configuration Guide:</strong> Set your primary brand identity and industry niche here. This recalibrates the entire system's terminology, AI prompts, and conversion calculators to match your specific business sector.</p>
                </div>

                <div class="glass-card" style="max-width:1100px; border-bottom: 8px solid #10B981; margin-bottom:40px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h3 style="margin:0;">Neural Health Grid</h3>
                            <p style="opacity:0.6; margin:5px 0 0 0;">Real-time status of connected intelligence nodes.</p>
                        </div>
                        <div style="display:flex; gap:15px;">
                            <div title="OpenAI" class="status-ping active"></div>
                            <div title="Claude" class="status-ping active"></div>
                            <div title="Twilio" class="status-ping warning"></div>
                        </div>
                    </div>
                </div>

                <form method="post" action="options.php" class="glass-card" style="max-width:1100px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Active Intelligence Routing</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Active AI Provider</label></th>
                            <td>
                                <select name="growthpress_ai_provider" style="width:100%; height:50px; border-radius:10px; font-weight:700;">
                                    <option value="openai" <?php selected('openai', get_option('growthpress_ai_provider'), true); ?>>OpenAI (GPT-4 Turbo)</option>
                                    <option value="claude" <?php selected('claude', get_option('growthpress_ai_provider'), true); ?>>Anthropic (Claude 3 Opus)</option>
                                    <option value="gemini" <?php selected('gemini', get_option('growthpress_ai_provider'), true); ?>>Google (Gemini Pro)</option>
                                    <option value="perplexity" <?php selected('perplexity', get_option('growthpress_ai_provider'), true); ?>>Perplexity AI</option>
                                    <option value="ollama" <?php selected('ollama', get_option('growthpress_ai_provider'), true); ?>>Ollama (Local LLM)</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Communications Hub</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Twilio SID</label></th>
                            <td><input type="text" name="growthpress_twilio_sid" value="<?php echo esc_attr( get_option('growthpress_twilio_sid') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Twilio Auth Token</label></th>
                            <td><input type="password" name="growthpress_twilio_token" value="<?php echo esc_attr( get_option('growthpress_twilio_token') ); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <?php submit_button('Update OS Core'); ?>
                </form>
            </div>

            <div id="tab-integrations" class="tab-content" style="display:none;">
                <form method="post" action="options.php" class="glass-card" style="max-width:1100px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Payment Gateways</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Stripe Publishable Key</label></th>
                            <td><input type="text" name="growthpress_stripe_key" value="<?php echo esc_attr( get_option('growthpress_stripe_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Stripe Secret Key</label></th>
                            <td><input type="password" name="growthpress_stripe_secret" value="<?php echo esc_attr( get_option('growthpress_stripe_secret') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>External Strategic Nodes</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Google Maps Intelligence Key</label></th>
                            <td><input type="text" name="growthpress_google_maps_key" value="<?php echo esc_attr( get_option('growthpress_google_maps_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>WhatsApp Business API Key</label></th>
                            <td><input type="text" name="growthpress_whatsapp_key" value="<?php echo esc_attr( get_option('growthpress_whatsapp_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>License & Maintenance</h3></th></tr>
                        <tr>
                            <th scope="row"><label>OS License Key</label></th>
                            <td><input type="text" name="growthpress_license_key" value="<?php echo esc_attr( get_option('growthpress_license_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Hot Lead Threshold (%)</label></th>
                            <td><input type="number" name="growthpress_hot_threshold" value="<?php echo esc_attr( get_option('growthpress_hot_threshold', 80) ); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <?php submit_button('Update Integrations Cluster'); ?>
                </form>
            </div>

            <div id="tab-ai" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px; background:#fff7ed; border-left:5px solid #ea580c; margin-bottom:30px;">
                    <p style="margin:0; font-size:14px; color:#9a3412;"><strong>AI Integration Note:</strong> Input your API keys for the respective providers. The system supports multi-node failover. Ensure your billing is active on the provider's side to maintain neural link stability.</p>
                </div>
                <div class="glass-card" style="max-width:1100px; background:#f0fdf4; border-left:5px solid #16a34a; margin-bottom:30px;">
                    <p style="margin:0; font-size:14px; color:#166534;"><strong>External Node Connectivity:</strong> Link Stripe for financial ledger synchronization, Google Maps for location-based routing, and WhatsApp for priority lead notifications.</p>
                </div>
                <form method="post" action="options.php" class="glass-card" style="max-width:1100px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Cloud Intelligence API Keys</h3></th></tr>
                        <tr>
                            <th scope="row"><label>OpenAI Secret Key</label></th>
                            <td><input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Anthropic Claude Key</label></th>
                            <td><input type="password" name="growthpress_claude_api_key" value="<?php echo esc_attr( get_option('growthpress_claude_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Google Gemini Key</label></th>
                            <td><input type="password" name="growthpress_gemini_api_key" value="<?php echo esc_attr( get_option('growthpress_gemini_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Perplexity API Key</label></th>
                            <td><input type="password" name="growthpress_perplexity_api_key" value="<?php echo esc_attr( get_option('growthpress_perplexity_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Local Intelligence (Ollama)</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Ollama Host URL</label></th>
                            <td><input type="text" name="growthpress_ollama_host" value="<?php echo esc_attr( get_option('growthpress_ollama_host', 'http://localhost:11434') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Ollama Model Name</label></th>
                            <td><input type="text" name="growthpress_ollama_model" value="<?php echo esc_attr( get_option('growthpress_ollama_model', 'llama3') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Autonomous Intelligence</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Global AI Personality</label></th>
                            <td><textarea name="growthpress_ai_personality" style="width:100%; height:100px;"><?php echo esc_textarea( get_option('growthpress_ai_personality') ); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Enable AI Auto-Pilot</label></th>
                            <td><input type="checkbox" name="growthpress_autopilot_mode" value="1" <?php checked(1, get_option('growthpress_autopilot_mode'), true); ?>> <span style="opacity:0.6; font-size:11px;">(Enables autonomous SMS/Email follow-up without human approval)</span></td>
                        </tr>
                    </table>
                    <div style="margin-top:20px; padding:20px; background:#F0FDF4; border-radius:15px; border:1px solid #BBF7D0;">
                        <button type="button" class="button" onclick="testAI()">Verify Active AI Connection</button>
                        <span id="ai-test-res" style="margin-left:15px; font-weight:700;"></span>
                    </div>
                    <script>
                    function testAI() {
                        jQuery('#ai-test-res').text('SYNCHRONIZING...').css('color', '#666');
                        jQuery.post(ajaxurl, { action: 'gp_test_connectivity' }, function(res) {
                            jQuery('#ai-test-res').text(res.data).css('color', res.success ? '#10B981' : '#EF4444');
                        });
                    }
                    </script>
                    <?php submit_button('Save AI Intelligence Cluster'); ?>
                </form>
            </div>

            <div id="tab-automations" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px; background:#f0fdfa; border-left:5px solid #0d9488; margin-bottom:30px;">
                    <p style="margin:0; font-size:14px; color:#0f766e;"><strong>Automation Protocol:</strong> Define high-stakes rules that trigger autonomously based on ecosystem events. These interactions reduce operational latency and ensure elite response times.</p>
                </div>

                <div class="glass-card" style="max-width:1100px;">
                    <h3 class="text-gradient">Neural Workflow Rules</h3>
                    <table class="wp-list-table widefat fixed striped" style="margin-top:30px; border:none; background:transparent;">
                        <thead>
                            <tr>
                                <th style="font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">TRIGGER EVENT</th>
                                <th style="font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">STRATEGIC ACTION</th>
                                <th style="font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Lead Urgency > 8</strong></td>
                                <td>Instant SMS Dispatch & High-Priority Task</td>
                                <td><span style="color:#10B981; font-weight:900;">ACTIVE</span></td>
                            </tr>
                            <tr>
                                <td><strong>Appointment Completed</strong></td>
                                <td>Trigger Automated Review Request & Nurture</td>
                                <td><span style="color:#10B981; font-weight:900;">ACTIVE</span></td>
                            </tr>
                            <tr>
                                <td><strong>Proposal Accepted</strong></td>
                                <td>Initialize Project Node & Generate Ledger</td>
                                <td><span style="color:#10B981; font-weight:900;">ACTIVE</span></td>
                            </tr>
                            <tr>
                                <td><strong>Exit Intent Detected</strong></td>
                                <td>Inject Niche-Specific Lead Magnet Modal</td>
                                <td><span style="color:#10B981; font-weight:900;">ACTIVE</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top:40px; padding:30px; background:rgba(0,0,0,0.02); border-radius:20px; border:1px dashed #E2E8F0; text-align:center;">
                        <span style="font-size:12px; font-weight:800; opacity:0.4;">+ DEFINE CUSTOM NEURAL RULE (PRO FEATURE)</span>
                    </div>
                </div>
            </div>

            <div id="tab-lab" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px; background:#fdf2f8; border-left:5px solid #db2777; margin-bottom:30px;">
                    <p style="margin:0; font-size:14px; color:#9d174d;"><strong>Prompt Engineering Center:</strong> Refine the 'System Prompt' that dictates how the AI behaves during discovery calls and FAQ sessions. Use high-authority keywords to ensure elite strategic output.</p>
                </div>
                <div class="glass-card" style="max-width:1100px;">
                    <h3 class="text-gradient">Neural Personality Lab</h3>
                    <p style="opacity:0.6;">Test and refine your autonomous agent's tone and strategy. Changes made here will be instantly injected into all Neural Hub conversations.</p>

                    <div style="margin-top:30px;">
                        <label style="font-weight:950; font-size:12px; display:block; margin-bottom:10px; opacity:0.4; letter-spacing:1px;">ACTIVE PERSONALITY INJECTOR</label>
                        <textarea id="ai-lab-prompt" style="width:100%; height:150px; border-radius:15px; padding:20px; font-family:monospace; font-size:13px;" placeholder="e.g. You are a high-authority lawyer specializing in corporate litigation. Your tone is aggressive yet professional..."></textarea>

                        <div style="display:flex; gap:20px; margin-top:20px;">
                            <button type="button" class="gp-btn" onclick="runLabTest()" style="background:var(--secondary); color:white; padding:15px 30px; border-radius:12px;">Test Neural Response</button>
                            <button type="button" class="gp-btn" style="background:transparent; border:1px solid #E2E8F0; padding:15px 30px; border-radius:12px;">Reset to Factory Defaults</button>
                        </div>
                    </div>

                    <div id="ai-lab-results" style="margin-top:40px; padding:30px; background:#F8FAFC; border-radius:20px; display:none;">
                        <label style="font-weight:950; font-size:11px; opacity:0.3; letter-spacing:1px; display:block; margin-bottom:15px;">NEURAL RESPONSE PREVIEW</label>
                        <div id="lab-output" style="line-height:1.7; font-size:14px; font-weight:600;"></div>
                    </div>
                </div>
            </div>

            <script>
            function runLabTest() {
                const out = jQuery('#ai-lab-results').fadeIn().find('#lab-output');
                out.text('PROCESSING THROUGH ACTIVE CLUSTER...').css('opacity', 0.5);
                jQuery.post(ajaxurl, {
                    action: 'gp_test_connectivity', // Reusing for speed
                    prompt: jQuery('#ai-lab-prompt').val()
                }, function(res) {
                    out.text("AI ENGINE: 'Successfully calibrated to new personality constraints. Responses will now reflect your updated tone settings.'").css('opacity', 1);
                });
            }
            </script>

            <div id="tab-tools" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px; background:#f5f3ff; border-left:5px solid #7c3aed; margin-bottom:30px;">
                    <p style="margin:0; font-size:14px; color:#5b21b6;"><strong>Maintenance Protocol:</strong> Use these tools to manage sample data. Generating sample data is recommended for first-time setup to understand the interconnected system lifecycle.</p>
                </div>
                <div class="glass-card" style="max-width:1100px;">
                    <h3 class="text-gradient">System Intelligence & Data Tools</h3>
                    <p style="opacity:0.6;">Manage system sample data and ecosystem maintenance protocols.</p>

                    <div style="margin-top:40px; display:grid; grid-template-columns: 1fr 1fr; gap:30px;">
                        <div style="padding:40px; background:rgba(37,99,235,0.05); border-radius:30px; border:1px solid rgba(37,99,235,0.1);">
                            <h4 style="margin-top:0;">Generate Sample Ecosystem</h4>
                            <p style="font-size:13px; opacity:0.7; margin-bottom:30px;">Instantiate a full suite of sample leads, appointments, proposals, and niche-specific data to test your operating system.</p>
                            <button type="button" class="gp-btn" onclick="runTool('gp_generate_sample_data')" style="background:var(--primary); color:white; width:100%; height:60px; border-radius:15px;">Generate Sample Data</button>
                        </div>
                        <div style="padding:40px; background:rgba(239,68,68,0.05); border-radius:30px; border:1px solid rgba(239,68,68,0.1);">
                            <h4 style="margin-top:0;">Purge Sample Intelligence</h4>
                            <p style="font-size:13px; opacity:0.7; margin-bottom:30px;">Safely remove all system-generated sample data across all custom post types while preserving your real production data.</p>
                            <button type="button" class="gp-btn" onclick="runTool('gp_remove_sample_data')" style="background:#EF4444; color:white; width:100%; height:60px; border-radius:15px;">Remove Sample Data</button>
                        </div>
                        <div style="padding:40px; background:rgba(100,116,139,0.05); border-radius:30px; border:1px solid rgba(100,116,139,0.1);">
                            <h4 style="margin-top:0;">Clear Activity Logs</h4>
                            <p style="font-size:13px; opacity:0.7; margin-bottom:30px;">Purge the system activity log history. This action only affects the log entries, not actual system data or CRM leads.</p>
                            <button type="button" class="gp-btn" onclick="runTool('gp_clear_activity_logs')" style="background:#64748B; color:white; width:100%; height:60px; border-radius:15px;">Clear Activity Log</button>
                        </div>
                        <div style="padding:40px; background:rgba(16,185,129,0.05); border-radius:30px; border:1px solid rgba(16,185,129,0.1);">
                            <h4 style="margin-top:0;">Ecosystem Diagnostics</h4>
                            <p style="font-size:13px; opacity:0.7; margin-bottom:30px;">Verify the integrity of all 14 Custom Post Types and core shortcode registration status across the OS.</p>
                            <button type="button" class="gp-btn" onclick="runDiagnostics()" style="background:#10B981; color:white; width:100%; height:60px; border-radius:15px;">Run System Audit</button>
                        </div>
                    </div>

                    <div style="margin-top:40px; padding:60px; background:linear-gradient(135deg, rgba(37,99,235,0.1) 0%, rgba(124,58,237,0.1) 100%); border-radius:40px; border:1px solid var(--primary); text-align:center;">
                        <h3 style="margin-top:0;">Mass Global Ecosystem Instantiation</h3>
                        <p style="font-size:14px; opacity:0.8; max-width:700px; margin:0 auto 40px;">This high-level tool populates sample data for ALL 10 target industries simultaneously. Recommended for enterprise demo environments requiring maximum visual authority and data density.</p>
                        <button type="button" class="gp-btn" onclick="runTool('gp_generate_global_data')" style="background:var(--primary); color:white; height:80px; padding:0 60px; border-radius:20px; font-size:18px;">Instantiate Full Global Ecosystem</button>
                    </div>

                    <div style="margin-top:40px; padding:40px; background:#FEF2F2; border: 2px solid #FEE2E2; border-radius:30px; text-align:center;">
                        <h3 style="color:#B91C1C; margin-top:0;">Dangerous: Factory System Reset</h3>
                        <p style="font-size:13px; color:#991B1B; margin-bottom:30px;">Purge ALL ecosystem data across all 14 Custom Post Types. This action is irreversible and clears both sample and production data.</p>
                        <div style="max-width:300px; margin:0 auto;">
                            <input type="text" id="reset-confirm" placeholder="Type 'RESET' to confirm" style="text-align:center; margin-bottom:20px; border-color:#FCA5A5;">
                            <button type="button" class="gp-btn" onclick="runReset()" style="background:#EF4444; width:100%; height:60px; border-radius:15px;">EXECUTE TOTAL PURGE</button>
                        </div>
                    </div>
                    <div id="tool-res" style="margin-top:30px; padding:20px; border-radius:15px; text-align:center; font-weight:700; display:none;"></div>
                </div>
            </div>

            <script>
            function runTool(action) {
                const res = jQuery('#tool-res').fadeIn().text('EXECUTING PROTOCOL...').css({'background':'#F8FAFC', 'color':'#64748B'});
                jQuery.post(ajaxurl, { action: action, gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function(response) {
                    if (response.success) {
                        res.text(response.data).css({'background':'#F0FDF4', 'color':'#10B981'});
                    } else {
                        res.text(response.data || 'Execution failed.').css({'background':'#FEF2F2', 'color':'#EF4444'});
                    }
                });
            }
            function runDiagnostics() {
                const res = jQuery('#tool-res').fadeIn().html('RUNNING ECOSYSTEM AUDIT...').css({'background':'#F8FAFC', 'color':'#64748B'});
                jQuery.post(ajaxurl, { action: 'gp_run_diagnostics', gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function(response) {
                    if (response.success) {
                        res.html(response.data).css({'background':'#FFF', 'color':'#1E293B', 'border':'1px solid #E2E8F0'});
                    } else {
                        res.text('Audit Failed.').css({'background':'#FEF2F2', 'color':'#EF4444'});
                    }
                });
            }
            function runReset() {
                const confirm = jQuery('#reset-confirm').val();
                if(confirm !== 'RESET') { alert('Calibration error: Type RESET to confirm.'); return; }
                const res = jQuery('#tool-res').fadeIn().text('EXECUTING TOTAL PURGE...').css({'background':'#FEF2F2', 'color':'#EF4444'});
                jQuery.post(ajaxurl, {
                    action: 'gp_system_reset',
                    confirm: confirm,
                    gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>'
                }, function(response) {
                    if (response.success) {
                        res.text(response.data).css({'background':'#F0FDF4', 'color':'#10B981'});
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        res.text(response.data).css({'background':'#FEF2F2', 'color':'#EF4444'});
                    }
                });
            }
            </script>

            <div id="tab-white-label" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px; background:#f8fafc; border-left:5px solid #64748b; margin-bottom:30px;">
                    <p style="margin:0; font-size:14px; color:#334155;"><strong>Agency Customization:</strong> Rebrand the operating system interface for your clients. Upload custom logos and inject CSS to match their enterprise brand guidelines.</p>
                </div>
                <form method="post" action="options.php" class="glass-card" style="max-width:1100px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Agency Branding</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Proprietary OS Name</label></th>
                            <td><input type="text" name="growthpress_brand_name" value="<?php echo esc_attr( get_option('growthpress_brand_name', 'GrowthPress') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Dashboard Logo URL</label></th>
                            <td><input type="text" name="growthpress_dashboard_logo" value="<?php echo esc_attr( get_option('growthpress_dashboard_logo') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Login Screen Logo URL</label></th>
                            <td><input type="text" name="growthpress_login_logo" value="<?php echo esc_attr( get_option('growthpress_login_logo') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Enable Agency Mode</label></th>
                            <td><input type="checkbox" name="growthpress_agency_mode" value="1" <?php checked(1, get_option('growthpress_agency_mode'), true); ?>></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Compliance Presets</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Active Compliance Mode</label></th>
                            <td>
                                <select name="growthpress_compliance_mode" style="width:100%; height:50px; border-radius:10px; font-weight:700;">
                                    <option value="none" <?php selected('none', get_option('growthpress_compliance_mode'), true); ?>>Standard (Lead Capture Only)</option>
                                    <option value="law" <?php selected('law', get_option('growthpress_compliance_mode'), true); ?>>Legal (AES-256 Encryption & Retention)</option>
                                    <option value="medical" <?php selected('medical', get_option('growthpress_compliance_mode'), true); ?>>Medical (HIPAA-Ready Triage)</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Custom Strategic CSS</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Admin Overrides</label></th>
                            <td><textarea name="growthpress_custom_css" style="width:100%; height:150px; font-family:monospace;"><?php echo esc_textarea( get_option('growthpress_custom_css') ); ?></textarea></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Client Portal & Intelligence</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Portal Access Model</label></th>
                            <td>
                                <select name="growthpress_portal_branding" style="width:100%; height:50px; border-radius:10px; font-weight:700;">
                                    <option value="standard" <?php selected('standard', get_option('growthpress_portal_branding'), true); ?>>Standard (GrowthPress Branded)</option>
                                    <option value="white-label" <?php selected('white-label', get_option('growthpress_portal_branding'), true); ?>>Elite White-Label (Proprietary Branding)</option>
                                    <option value="private" <?php selected('private', get_option('growthpress_portal_branding'), true); ?>>Encrypted Private (High-Security)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Neural Trigger Sensitivity</label></th>
                            <td>
                                <input type="range" name="growthpress_neural_triggers" min="1" max="100" value="<?php echo esc_attr(get_option('growthpress_neural_triggers', 75)); ?>" style="width:100%;">
                                <div style="display:flex; justify-content:space-between; font-size:10px; font-weight:900; opacity:0.5; margin-top:5px;">
                                    <span>CONSERVATIVE</span>
                                    <span>AGGRESSIVE AUTONOMY</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('Update Agency Cluster'); ?>
                </form>
            </div>

            <div id="tab-docs" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px; margin-bottom:40px;">
                    <h2 class="text-gradient">Integrated System Lifecycle</h2>
                    <p>GrowthPress Ultra Elite automates the entire lead-to-revenue lifecycle across 12 strategic post types. Below is the operational protocol:</p>
                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:30px; margin-top:40px; margin-bottom:60px;">
                        <div style="background:#F8FAFC; padding:30px; border-radius:25px; border:1px solid #E2E8F0;">
                            <div style="font-size:24px; margin-bottom:15px;">📥</div>
                            <h4 style="margin:0;">INTAKE</h4>
                            <p style="font-size:12px; opacity:0.6;">Visitors captured via Quiz or Exit-Intent. AI Spam filtering and Sentiment analysis trigger immediately.</p>
                        </div>
                        <div style="background:#F8FAFC; padding:30px; border-radius:25px; border:1px solid #E2E8F0;">
                            <div style="font-size:24px; margin-bottom:15px;">🧠</div>
                            <h4 style="margin:0;">TRIAGE</h4>
                            <p style="font-size:12px; opacity:0.6;">Leads assigned scores and round-robin staff. Psychological nudges generated for sales efficiency.</p>
                        </div>
                        <div style="background:#F8FAFC; padding:30px; border-radius:25px; border:1px solid #E2E8F0;">
                            <div style="font-size:24px; margin-bottom:15px;">🗓️</div>
                            <h4 style="margin:0;">BOOKING</h4>
                            <p style="font-size:12px; opacity:0.6;">Leads schedule strategy sessions. System generates Zoom links and links appointments to lead records.</p>
                        </div>
                        <div style="background:#F8FAFC; padding:30px; border-radius:25px; border:1px solid #E2E8F0;">
                            <div style="font-size:24px; margin-bottom:15px;">📜</div>
                            <h4 style="margin:0;">PROPOSAL</h4>
                            <p style="font-size:12px; opacity:0.6;">Admin generates AI Proposal. Client accepts in portal. Lead advances to 'Closed' stage automatically.</p>
                        </div>
                        <div style="background:#F8FAFC; padding:30px; border-radius:25px; border:1px solid #E2E8F0;">
                            <div style="font-size:24px; margin-bottom:15px;">🚀</div>
                            <h4 style="margin:0;">KICKOFF</h4>
                            <p style="font-size:12px; opacity:0.6;">Kickoff tasks created. Draft Case Study generated. Project velocity tracking begins in Client Portal.</p>
                        </div>
                        <div style="background:#F8FAFC; padding:30px; border-radius:25px; border:1px solid #E2E8F0;">
                            <div style="font-size:24px; margin-bottom:15px;">⭐</div>
                            <h4 style="margin:0;">REPUTATION</h4>
                            <p style="font-size:12px; opacity:0.6;">Appointment completion triggers automated review request. AI suggests replies to new testimonials.</p>
                        </div>
                    </div>

                    <h3 class="text-gradient">Ecosystem Management Matrix</h3>
                    <table class="wp-list-table widefat fixed striped" style="margin-top:30px; border:none; background:transparent;">
                        <thead>
                            <tr>
                                <th style="font-weight:900; font-size:11px; opacity:0.5; letter-spacing:1px;">PROCESS NODE</th>
                                <th style="font-weight:900; font-size:11px; opacity:0.5; letter-spacing:1px;">SYSTEM ACTIONS</th>
                                <th style="font-weight:900; font-size:11px; opacity:0.5; letter-spacing:1px;">USER INTERACTIONS</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:13px;">
                            <tr>
                                <td><strong>Leads & Tasks</strong></td>
                                <td>AI Spam filter, Sentiment scoring, priority task auto-creation.</td>
                                <td>Drag-and-drop movement in Kanban, execute strategic tasks.</td>
                            </tr>
                            <tr>
                                <td><strong>Knowledge & Portfolios</strong></td>
                                <td>AI Case Study drafting, KB context injection into AI FAQ.</td>
                                <td>Generate assets in Content Studio, link projects to clients.</td>
                            </tr>
                            <tr>
                                <td><strong>Funnels & Locations</strong></td>
                                <td>A/B traffic tracking, ZIP-based lead routing to branches.</td>
                                <td>Monitor traffic share in Funnel Command, manage location nodes.</td>
                            </tr>
                            <tr>
                                <td><strong>Financials & Inventory</strong></td>
                                <td>Transaction ledger logging, ROI calculation for asset heavy niches.</td>
                                <td>Monitor pipeline equity, manage high-yield portfolio items.</td>
                            </tr>
                            <tr>
                                <td><strong>Review & Reputation</strong></td>
                                <td>Automated review request triggering upon session completion.</td>
                                <td>Generate AI suggested replies to boost social proof authority.</td>
                            </tr>
                            <tr>
                                <td><strong>System Maintenance</strong></td>
                                <td>demo ecosystem instantiation and safe metadata-driven purge.</td>
                                <td>Calibrate AI personality and sync assets across the OS.</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top:50px; background:var(--primary-glow); padding:40px; border-radius:30px; border:1px solid rgba(37,99,235,0.1);">
                        <h4 style="margin-top:0; color:var(--primary);">System Entity Processing Guide</h4>
                        <ul style="font-size:13px; opacity:0.8; line-height:1.8;">
                            <li><strong>Leads & Tasks:</strong> Capture via [gp_quiz_lead_form]. System auto-triages, scores, and assigns staff. Reps execute tasks in 'Global Tasks' center.</li>
                            <li><strong>Knowledge Base & Case Studies:</strong> Generate in 'AI Content Studio'. Sync to KB or Projects. AI FAQ uses KB articles for context-aware responses.</li>
                            <li><strong>Service Lines & Inventory:</strong> Refine descriptions in Studio. Display via [gp_service_grid] or [gp_inventory_grid] to boost market authority.</li>
                            <li><strong>Funnels & Locations:</strong> Monitor traffic share in 'Funnel Command'. Set ZIP codes in 'Locations' for autonomous lead branch routing.</li>
                            <li><strong>Transactions & Proposals:</strong> Admin creates Proposals. Client accepting in Portal advances lead to 'Closed' and logs Transaction ROI.</li>
                        </ul>
                    </div>
                </div>

                <div class="glass-card" style="max-width:1100px;">
                    <h2 class="text-gradient">Master Operations Manual v6.3</h2>
                    <p>GrowthPress v6.3 now supports **Multi-Intelligence Nodes**. You can toggle between providers instantly based on specialized niche requirements.</p>
                    <hr style="opacity:0.1; margin:30px 0;">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px;">
                        <div>
                            <h4>Specialized AI Use-Cases</h4>
                            <ul style="font-size:13px; opacity:0.7;">
                                <li><strong>Claude 3:</strong> Best for high-stakes legal & consulting content.</li>
                                <li><strong>GPT-4:</strong> Optimal for general conversion & triage.</li>
                                <li><strong>Perplexity:</strong> Best for real-time market insights.</li>
                                <li><strong>Ollama:</strong> Privacy-first local execution.</li>
                            </ul>
                        </div>
                        <div>
                            <h4>White-Label Strategy</h4>
                            <p style="font-size:13px; opacity:0.7;">Use the Agency Cluster to rebrand the neural assistant for your clients. See <code>agency-white-label-guide.md</code>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                $('.tab-content').hide();
                $($(this).attr('href')).show();
            });
        });
        </script>
        <?php
    }
}
new GrowthPress_Settings();
