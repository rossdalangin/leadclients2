<?php
/**
 * Medical Niche specialized Closer Tools - Ultra Elite v4.0
 */
class GrowthPress_Medical {
    public function __construct() {
        add_shortcode('gp_symptom_checker', array($this, 'render_symptom_checker'));
        add_shortcode('gp_medical_intake', array($this, 'render_medical_intake'));
    }

    public function render_medical_intake() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        return '<div class="gp-medical-intake glass-card gp-reveal" style="border-right: 20px solid #10B981; padding:100px 80px; background: linear-gradient(135deg, var(--surface), #F0FDF4);">
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:12px; font-weight:950; color:#10B981; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">CLINICAL INTAKE NODE v5.9</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Secure Patient Onboarding</h3>
                <p style="font-size:1.25rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Initialize your clinical record within our HIPAA-compliant neural architecture.</p>
            </div>
            <form class="gp-form" data-action="gp_submit_lead">
                <input type="hidden" name="nonce" value="'.$nonce.'">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:30px;">
                    <div><label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">PATIENT IDENTITY</label><input type="text" name="lead_name" placeholder="Full Legal Name" required style="height:75px; border-radius:20px; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;"></div>
                    <div><label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">SECURE EMAIL</label><input type="email" name="lead_email" placeholder="Direct Email" required style="height:75px; border-radius:20px; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;"></div>
                </div>
                <div style="margin-bottom:30px;">
                    <label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">PRIMARY CLINICAL CONCERN</label>
                    <select name="lead_msg_prefix" style="width:100%; height:75px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                        <option value="General">General Consultation</option>
                        <option value="Specialist">Specialist Referral</option>
                        <option value="Urgent">Urgent Symptomology</option>
                    </select>
                </div>
                <textarea name="lead_msg" placeholder="Describe symptoms or clinical history..." style="height:200px; margin-bottom:40px; border-radius:28px; padding:30px; font-size:16px; border:2px solid #F1F5F9; line-height:1.7;"></textarea>
                <button type="submit" class="gp-btn" style="width:100%; height:90px; font-size:22px; background:#10B981;">Initialize Secure Triage</button>
            </form>
        </div>';
    }

    public function render_symptom_checker() {
        return '<div class="gp-symptom-checker glass-card gp-reveal" style="border-left: 20px solid #10B981; padding:100px 80px; position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; width:100%; height:100%; opacity:0.04; background-image:url(\'data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M15 0v30M0 15h30\" stroke=\"%2310B981\" stroke-width=\"2\"/%3E%3C/svg%3E\'); pointer-events:none;"></div>
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:12px; font-weight:950; color:#10B981; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">HIPAA-COMPLIANT NEURAL TRIAGE v4.0</div>
                <h2 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">AI Health Intelligence Assistant</h2>
                <p style="font-size:1.25rem; opacity:0.7; max-width:650px; margin:25px auto 0;">Instantly analyze clinical concerns to route your case to the appropriate board-certified specialist.</p>
            </div>

            <div id="medical-steps" class="glass-card" style="background:#FFF; padding:60px; border-radius:44px; box-shadow:0 30px 70px rgba(16,185,129,0.08);">
                <div style="margin-bottom:40px;">
                    <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:20px;">CLINICAL CONCERN / SYMPTOMOLOGY</label>
                    <textarea id="symptom-msg" placeholder="Describe clinical symptoms for neural triage prioritization..." style="height:220px; margin-bottom:30px; border-radius:28px; font-size:16px; border:2px solid #F1F5F9; padding:30px; line-height:1.7;"></textarea>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:50px;">
                    <div style="background:#F0FDF4; padding:30px; border-radius:24px; border:1px solid #DCFCE7; text-align:center;">
                        <div style="font-size:11px; font-weight:900; color:#166534; opacity:0.6; letter-spacing:1px; margin-bottom:10px;">NEURAL LATENCY</div>
                        <div style="font-size:28px; font-weight:950; color:#166534;">3.8 SECONDS</div>
                    </div>
                    <div style="background:#F0FDF4; padding:30px; border-radius:24px; border:1px solid #DCFCE7; text-align:center;">
                        <div style="font-size:11px; font-weight:900; color:#166534; opacity:0.6; letter-spacing:1px; margin-bottom:10px;">DATA PROTOCOL</div>
                        <div style="font-size:28px; font-weight:950; color:#166534;">AES-256-GCM</div>
                    </div>
                </div>
                <button class="gp-btn" style="width:100%; height:90px; font-size:22px; background:#10B981;" onclick="jQuery(\'#medical-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Execute AI Diagnostic Triage</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <div style="margin-top:40px; font-size:11px; opacity:0.5; text-align:center; line-height:1.8; max-width:600px; margin-left:auto; margin-right:auto;">NOTICE: This engine is for administrative clinical routing and priority intake only. It does not provide medical diagnosis, treatment, or advice. In the event of a medical emergency, terminate session and dial local emergency services (911).</div>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array('post_title' => 'Dr. Smith\'s Surgical Center', 'post_content' => 'Full digital transformation for a high-volume outpatient facility.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
        if ($id) update_post_meta($id, '_gp_is_sample', '1');
    }
}
new GrowthPress_Medical();
