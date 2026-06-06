<?php
/**
 * Dental Niche specialized Closer Tools - Ultra Elite v4.0
 */
class GrowthPress_Dental {
    public function __construct() {
        add_action('init', array($this, 'register_cpts'));
        add_shortcode('gp_insurance_optimizer', array($this, 'render_insurance_optimizer'));
        add_shortcode('gp_smile_gallery', array($this, 'render_smile_gallery'));
        add_action('gp_niche_lead_analysis', array($this, 'analyze_dental_lead'));
        add_action('add_meta_boxes', array($this, 'add_dental_meta_boxes'));
        add_action('save_post', array($this, 'save_dental_meta'));
    }

    public function register_cpts() {
        register_post_type('gp_treatment', array(
            'labels'      => array('name' => 'Treatments', 'singular_name' => 'Treatment'),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-heart',
            'supports'    => array('title', 'editor', 'thumbnail', 'excerpt'),
            'rewrite'     => array('slug' => 'treatments')
        ));
    }

    public function add_dental_meta_boxes() {
        add_meta_box('gp_treatment_details', '🩺 Clinical Treatment Execution Protocol', array($this, 'render_treatment_meta'), 'gp_treatment', 'normal', 'high');
    }

    public function render_treatment_meta($post) {
        $duration = get_post_meta($post->ID, '_treatment_duration', true) ?: '60 mins';
        $complexity = get_post_meta($post->ID, '_treatment_complexity', true) ?: 'Standard';
        ?>
        <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #0ea5e9;">
            <p style="margin: 0; font-size: 13px; color: #0369a1;"><strong>Clinical Protocol:</strong> Define the operational parameters for this dental treatment. These values inform the 'Insurance Optimization Engine' and help set patient expectations during the triage phase.</p>
        </div>
        <table class="form-table">
            <tr>
                <th><label>Average Duration</label></th>
                <td><input type="text" name="gp_treatment_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" placeholder="e.g. 90 mins"></td>
            </tr>
            <tr>
                <th><label>Clinical Complexity</label></th>
                <td>
                    <select name="gp_treatment_complexity" style="width:100%;">
                        <option value="Routine" <?php selected($complexity, 'Routine'); ?>>Routine / Maintenance</option>
                        <option value="Standard" <?php selected($complexity, 'Standard'); ?>>Standard Clinical</option>
                        <option value="Advanced" <?php selected($complexity, 'Advanced'); ?>>Advanced Reconstructive</option>
                        <option value="Elite" <?php selected($complexity, 'Elite'); ?>>Elite Multi-Stage</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_dental_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['gp_treatment_duration'])) {
            update_post_meta($post_id, '_treatment_duration', sanitize_text_field($_POST['gp_treatment_duration']));
            update_post_meta($post_id, '_treatment_complexity', sanitize_text_field($_POST['gp_treatment_complexity']));
        }
    }

    public function analyze_dental_lead($lead_id) {
        $lead = get_post($lead_id);
        $content = strtolower($lead->post_content);
        $crm = GrowthPress_CRM::get_instance();

        if (strpos($content, 'insurance') !== false || strpos($content, 'coverage') !== false) {
            $crm->create_task("Dental Insurance Verification", "Lead inquired about coverage. Verify PPO/Elite eligibility.", $lead_id);
            wp_set_object_terms($lead_id, 'Insurance-Check', 'gp_lead_tag', true);
        }

        if (strpos($content, 'emergency') !== false || strpos($content, 'pain') !== false) {
            $crm->create_task("URGENT: Dental Triage", "Emergency inquiry detected. Immediate clinical routing required.", $lead_id);
            wp_set_object_terms($lead_id, 'Emergency', 'gp_lead_tag', true);
        }
    }

    public function render_insurance_optimizer() {
        return '<div class="gp-insurance-optimizer glass-card gp-reveal" style="padding:120px 80px; border-left: 20px solid #0EA5E9; background: linear-gradient(135deg, var(--surface), #F0F9FF);">
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:12px; font-weight:950; color:#0EA5E9; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">CLINICAL COVERAGE INTELLIGENCE v4.0</div>
                <h3 class="text-gradient" style="font-size:4rem; line-height:1.0;">Insurance Optimization Engine</h3>
                <p style="font-size:1.3rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Our neural engine instantly verifies your coverage parameters to maximize clinical benefits and eliminate financial friction.</p>
            </div>

            <div id="ins-steps" class="glass-card" style="background:#FFF; padding:80px; border-radius:50px; box-shadow:0 40px 80px rgba(0,0,0,0.04);">
                <div style="margin-bottom:50px;">
                    <label style="font-weight:950; font-size:12px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:25px;">SELECT ELITE PROVIDER NETWORK</label>
                    <select id="ins-provider" style="width:100%; height:85px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 35px; font-size:20px;">
                        <option value="Delta">Delta Dental Strategic PPO</option>
                        <option value="MetLife">MetLife Executive Elite</option>
                        <option value="Cigna">Cigna Platinum Advantage</option>
                        <option value="Other">Custom Global Enterprise Coverage</option>
                    </select>
                </div>
                <div style="background:rgba(14, 165, 233, 0.04); border:2px solid rgba(14, 165, 233, 0.08); padding:60px; border-radius:40px; text-align:center; margin-bottom:60px;">
                    <div style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:2px; margin-bottom:20px;">ESTIMATED COVERAGE INTEL</div>
                    <div class="text-gradient" style="font-size:6rem; font-weight:950; color:#0EA5E9; line-height:1; letter-spacing:-0.05em;">65% - 98%</div>
                </div>
                <button class="gp-btn" style="width:100%; height:95px; font-size:24px; background:#0EA5E9; border-radius:25px;" onclick="jQuery(\'#ins-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Execute Coverage Protocol</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
        </div>';
    }

    public function render_smile_gallery() {
        $projects = get_posts(array('post_type' => 'gp_project', 'posts_per_page' => 2));
        ob_start(); ?>
        <div class="gp-smile-gallery" style="margin-top:150px;">
            <div style="text-align:center; margin-bottom:120px;">
                <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">TRANSFORMATION ARCHIVE v4.0</div>
                <h2 class="text-gradient" style="font-size:4.5rem; line-height:0.9; letter-spacing:-0.07em;">Elite Patient Transformations</h2>
                <p style="max-width:800px; margin:30px auto 0; font-size:1.4rem; opacity:0.7;">Visual confirmation of our precision reconstructive engineering and aesthetic excellence.</p>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:80px;">
                <?php if($projects): foreach($projects as $p): ?>
                    <div class="glass-card gp-reveal" style="padding:0; border-radius:60px; overflow:hidden;">
                        <?php if(has_post_thumbnail($p->ID)): ?>
                            <?php echo get_the_post_thumbnail($p->ID, 'full', array('style'=>'width:100%; height:600px; object-fit:cover;')); ?>
                        <?php else: ?>
                            <div style="height:600px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:950; opacity:0.15; letter-spacing:3px;">INTEL: <?php echo strtoupper($p->post_title); ?></div>
                        <?php endif; ?>
                        <div style="padding:60px; text-align:center; border-top:1px solid #F1F5F9;">
                            <h4 style="margin:0; font-size:30px; font-weight:950; letter-spacing:-0.04em;"><?php echo esc_html($p->post_title); ?></h4>
                            <p style="font-size:15px; opacity:0.5; margin-top:20px; font-weight:800; letter-spacing:2px;">TIER: ELITE RESULT</p>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <p style="text-align:center; grid-column: span 2; opacity:0.4;">Awaiting clinical result synchronization...</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Sarah V. (Supreme Transformation)',
            'post_content' => 'High-authority smile reconstruction for a global leadership profile. This project involved full-mouth restoration and neural-calibrated aesthetic mapping.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+15%');
            update_post_meta($id, '_gp_ai_score', 99);
        }

        $treatments = array(
            'Invisalign Elite' => array('12 months', 'Advanced'),
            'Full Mouth Restoration' => array('4-6 months', 'Elite'),
            'Biological Periodontics' => array('90 mins', 'Standard')
        );
        foreach ($treatments as $title => $data) {
            $tid = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => 'Specialized dental treatment protocol for ' . strtolower($title) . '.',
                'post_type'    => 'gp_treatment',
                'post_status'  => 'publish'
            ));
            if ($tid) {
                update_post_meta($tid, '_gp_is_sample', '1');
                update_post_meta($tid, '_treatment_duration', $data[0]);
                update_post_meta($tid, '_treatment_complexity', $data[1]);
            }
        }
    }
}
new GrowthPress_Dental();
