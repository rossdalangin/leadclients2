<?php
/**
 * Accounting Niche specialized Closer Tools - Ultra Elite v4.5
 */
class GrowthPress_Accounting {
    public function __construct() {
        add_shortcode('gp_tax_estimator', array($this, 'render_tax_estimator'));
        add_shortcode('gp_tax_audit', array($this, 'render_tax_audit'));
    }

    public function render_tax_audit() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        return '<div class="gp-tax-audit glass-card gp-reveal" style="border-left: 20px solid #7C3AED; padding:100px 80px; background: linear-gradient(135deg, var(--surface), #F5F3FF);">
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:12px; font-weight:950; color:#7C3AED; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">FISCAL INTELLIGENCE NODE v5.9</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Secure Tax Strategy Audit</h3>
                <p style="font-size:1.25rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Submit your corporate profile for a proprietary AI tax optimization analysis.</p>
            </div>
            <form class="gp-form" data-action="gp_submit_lead">
                <input type="hidden" name="nonce" value="'.$nonce.'">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:30px;">
                    <div><label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">ENTITY IDENTITY</label><input type="text" name="lead_name" placeholder="Business or Legal Name" required style="height:75px; border-radius:20px; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;"></div>
                    <div><label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">SECURE CHANNEL</label><input type="email" name="lead_email" placeholder="Direct Email" required style="height:75px; border-radius:20px; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;"></div>
                </div>
                <div style="margin-bottom:30px;">
                    <label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">ANNUAL REVENUE TIER</label>
                    <select name="lead_msg_prefix" style="width:100%; height:75px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                        <option value="Tier1">$250k - $1M</option>
                        <option value="Tier2">$1M - $5M</option>
                        <option value="Tier3">$5M - $20M</option>
                        <option value="Tier4">$20M+</option>
                    </select>
                </div>
                <textarea name="lead_msg" placeholder="Describe current fiscal challenges or optimization goals..." style="height:200px; margin-bottom:40px; border-radius:28px; padding:30px; font-size:16px; border:2px solid #F1F5F9; line-height:1.7;"></textarea>
                <button type="submit" class="gp-btn" style="width:100%; height:90px; font-size:22px; background:#7C3AED;">Initialize Fiscal Audit</button>
            </form>
        </div>';
    }

    public function render_tax_estimator() {
        return '<div class="gp-tax-estimator glass-card gp-reveal" style="border-right: 20px solid #7C3AED; padding:120px 80px; background: linear-gradient(135deg, var(--surface), #F5F3FF);">
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:12px; font-weight:950; color:#7C3AED; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">WEALTH PRESERVATION ENGINE v4.5</div>
                <h3 class="text-gradient" style="font-size:4rem; line-height:1.0;">AI Tax Optimization Estimator</h3>
                <p style="font-size:1.3rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Determine your potential tax optimization nodes and capital preservation benefits based on your current corporate profile.</p>
            </div>

            <div id="tax-steps" class="glass-card" style="background:#FFF; padding:60px; border-radius:44px; box-shadow:0 30px 60px rgba(0,0,0,0.03);">
                <div style="margin-bottom:40px;">
                    <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:20px;">ESTIMATED ANNUAL REVENUE</label>
                    <select id="rev-select" style="width:100%; height:85px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 35px; font-size:20px;">
                        <option value="18000">$250k - $750k</option>
                        <option value="65000">$750k - $3M</option>
                        <option value="215000">$3M - $15M</option>
                        <option value="480000">$15M+</option>
                    </select>
                </div>
                <div style="background:rgba(124, 58, 237, 0.05); border:2px solid rgba(124, 58, 237, 0.1); padding:60px; border-radius:40px; text-align:center; margin-bottom:60px;">
                    <div style="font-size:12px; font-weight:950; color:#7C3AED; opacity:0.6; letter-spacing:2px; margin-bottom:15px;">ESTIMATED SAVINGS POTENTIAL</div>
                    <div class="text-gradient" style="font-size:6rem; font-weight:950; color:#7C3AED; line-height:1; letter-spacing:-0.05em;">$<span id="tax-savings">18,000</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:95px; font-size:24px; background:#7C3AED; border-radius:25px;" onclick="jQuery(\'#tax-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Secure High-Stakes Financial Audit</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <script>
                jQuery("#rev-select").on("change", function() {
                    jQuery("#tax-savings").text(parseInt(jQuery(this).val()).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Fortune 500 Fiscal Audit',
            'post_content' => 'Complete corporate tax restructuring and offshore capital optimization for a multi-national entity.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+42%');
            update_post_meta($id, '_gp_pipeline_value', '$12.5M');
        }

        $sid = wp_insert_post(array(
            'post_title'   => 'Corporate Tax Shielding',
            'post_content' => 'Elite fiscal strategy for asset protection and multi-jurisdictional tax optimization.',
            'post_type'    => 'gp_service',
            'post_status'  => 'publish'
        ));
        if ($sid) {
            update_post_meta($sid, '_gp_is_sample', '1');
            update_post_meta($sid, '_gp_service_icon', '🛡️');
        }
    }
}
new GrowthPress_Accounting();
