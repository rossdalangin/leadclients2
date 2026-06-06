<?php
/**
 * GrowthPress Shortcode Reference Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Shortcode_Ref {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_shortcode_menu' ) );
    }

    public function add_shortcode_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Shortcode Library',
            'Shortcodes',
            'manage_options',
            'growthpress-shortcodes',
            array( $this, 'render_shortcodes_page' )
        );
    }

    public function render_shortcodes_page() {
        $shortcodes = array(
            array(
                'tag'   => '[gp_lead_form]',
                'title' => 'Standard Lead Capture',
                'desc'  => 'Displays the primary high-converting lead form with AI spam filtering.',
                'usage' => 'Place on Landing Pages or your Contact page.',
                'example' => '[gp_lead_form]'
            ),
            array(
                'tag'   => '[gp_quiz_lead_form]',
                'title' => 'Conversational AI Quiz',
                'desc'  => 'A multi-step quiz to qualify high-ticket leads before capture.',
                'usage' => 'Best used on the Homepage Hero or a dedicated Triage page.',
                'example' => '[gp_quiz_lead_form]'
            ),
            array(
                'tag'   => '[gp_booking_form]',
                'title' => 'Appointment Scheduling',
                'desc'  => 'Displays the staff-aware calendar for booking discovery calls.',
                'usage' => 'Place on Services pages or after a lead is captured.',
                'example' => '[gp_booking_form]'
            ),
            array(
                'tag'   => '[gp_solar_calculator]',
                'title' => 'Solar ROI Estimator',
                'desc'  => 'Interactive calculator for solar niches with AI energy consulting.',
                'usage' => 'Specific to Solar niche companies.',
                'example' => '[gp_solar_calculator]'
            ),
            array(
                'tag'   => '[gp_solar_financing]',
                'title' => 'Solar Financing Form',
                'desc'  => 'Secure inquiry form for $0-down solar financing eligibility.',
                'usage' => 'Place on Solar pricing or financing pages.',
                'example' => '[gp_solar_financing]'
            ),
            array(
                'tag'   => '[gp_insurance_optimizer]',
                'title' => 'Dental Insurance Optimizer',
                'desc'  => 'Frontend tool for patients to check insurance coverage and maximize benefits.',
                'usage' => 'Place on Dental intake or pricing pages.',
                'example' => '[gp_insurance_optimizer]'
            ),
            array(
                'tag'   => '[gp_contractor_estimator]',
                'title' => 'Construction Cost Estimator',
                'desc'  => 'Precision labor and material calculator for renovation niches.',
                'usage' => 'Specific to Contractor/Roofing niches.',
                'example' => '[gp_contractor_estimator]'
            ),
            array(
                'tag'   => '[gp_urgency_banner]',
                'title' => 'Dynamic Urgency Alert',
                'desc'  => 'Displays a niche-specific alert banner (e.g. Emergency Dental available).',
                'usage' => 'Place at the very top of your site or hero sections.',
                'example' => '[gp_urgency_banner]'
            ),
            array(
                'tag'   => '[gp_kb_grid]',
                'title' => 'Knowledge Base Grid',
                'desc'  => 'Displays a high-fidelity grid of technical KB articles.',
                'usage' => 'Place on documentation or support pages.',
                'example' => '[gp_kb_grid]'
            ),
            array(
                'tag'   => '[gp_case_study_grid]',
                'title' => 'Success Story Grid',
                'desc'  => 'Displays a grid of high-ticket Case Studies and Projects.',
                'usage' => 'Place on Portfolio or Results pages.',
                'example' => '[gp_case_study_grid]'
            ),
            array(
                'tag'   => '[gp_service_grid]',
                'title' => 'Service Lines Grid',
                'desc'  => 'Displays a grid of your elite service offerings.',
                'usage' => 'Place on the Services overview page.',
                'example' => '[gp_service_grid]'
            ),
            array(
                'tag'   => '[gp_inventory_grid]',
                'title' => 'Portfolio Inventory Grid',
                'desc'  => 'Displays a luxury grid of inventory items (e.g. Properties).',
                'usage' => 'Specific to Real Estate or Asset-heavy niches.',
                'example' => '[gp_inventory_grid]'
            ),
            array(
                'tag'   => '[gp_location_grid]',
                'title' => 'Location Network Grid',
                'desc'  => 'Displays a grid of all physical service locations.',
                'usage' => 'Place on the Locations or About page.',
                'example' => '[gp_location_grid]'
            ),
            array(
                'tag'   => '[gp_funnel_grid]',
                'title' => 'Active Funnels Overview',
                'desc'  => 'Displays a grid of active conversion funnels.',
                'usage' => 'Mainly for internal landing page management.',
                'example' => '[gp_funnel_grid]'
            ),
            array(
                'tag'   => '[gp_medical_intake]',
                'title' => 'Clinical Intake Node',
                'desc'  => 'HIPAA-compliant patient onboarding form with neural triage.',
                'usage' => 'Specific to Medical niche.',
                'example' => '[gp_medical_intake]'
            ),
            array(
                'tag'   => '[gp_tax_audit]',
                'title' => 'Fiscal Strategy Audit',
                'desc'  => 'Secure corporate tax optimization intake form.',
                'usage' => 'Specific to Accounting niche.',
                'example' => '[gp_tax_audit]'
            ),
            array(
                'tag'   => '[gp_law_conflict_check]',
                'title' => 'Litigation Clearance Node',
                'desc'  => 'Secure conflict of interest verification form.',
                'usage' => 'Specific to Law niche.',
                'example' => '[gp_law_conflict_check]'
            ),
            array(
                'tag'   => '[gp_ai_faq]',
                'title' => 'AI FAQ Assistant',
                'desc'  => 'Context-aware AI chatbot that answers questions based on your KB.',
                'usage' => 'Place on documentation, support, or service pages.',
                'example' => '[gp_ai_faq]'
            ),
            array(
                'tag'   => '[gp_staff_grid]',
                'title' => 'Specialist Team Grid',
                'desc'  => 'Displays all active specialists with performance radar metrics.',
                'usage' => 'Place on Team or About pages.',
                'example' => '[gp_staff_grid]'
            ),
            array(
                'tag'   => '[gp_treatment_grid]',
                'title' => 'Clinical Protocols Grid',
                'desc'  => 'Displays specialized treatment protocols for medical/dental niches.',
                'usage' => 'Place on Services or Clinical hubs.',
                'example' => '[gp_treatment_grid]'
            ),
            array(
                'tag'   => '[gp_market_chart]',
                'title' => 'Strategic ROI Chart',
                'desc'  => 'Interactive Chart.js visualization of market trajectory and ROI.',
                'usage' => 'Best on Strategy or Results pages.',
                'example' => '[gp_market_chart]'
            ),
            array(
                'tag'   => '[gp_trust_badges]',
                'title' => 'Elite Authority Logos',
                'desc'  => 'Displays high-authority trust badges (Forbes, Bloomberg, etc.).',
                'usage' => 'Place in footers or below hero sections.',
                'example' => '[gp_trust_badges]'
            ),
            array(
                'tag'   => '[gp_stats_bar]',
                'title' => 'Financial Performance Bar',
                'desc'  => 'Displays impressive system-wide performance statistics.',
                'usage' => 'Place on About or Results pages.',
                'example' => '[gp_stats_bar]'
            ),
            array(
                'tag'   => '[gp_client_portal]',
                'title' => 'Secure Client Portal',
                'desc'  => 'The main entry point for clients to manage projects and assets.',
                'usage' => 'Place on a dedicated /portal page.',
                'example' => '[gp_client_portal]'
            ),
            array(
                'tag'   => '[gp_coaching_assistant]',
                'title' => 'Scaling Roadmap Engine',
                'desc'  => 'AI-powered tool to identify bottlenecks and generate 12-month roadmaps.',
                'usage' => 'Specific to Coaching niche.',
                'example' => '[gp_coaching_assistant]'
            ),
            array(
                'tag'   => '[gp_smile_gallery]',
                'title' => 'Clinical Result Gallery',
                'desc'  => 'High-fidelity grid of before/after clinical transformations.',
                'usage' => 'Specific to Dental niche.',
                'example' => '[gp_smile_gallery]'
            ),
            array(
                'tag'   => '[gp_roofing_estimator]',
                'title' => 'Structural Audit Estimator',
                'desc'  => 'Drone-aware cost estimator for roofing and structural projects.',
                'usage' => 'Specific to Roofing niche.',
                'example' => '[gp_roofing_estimator]'
            ),
            array(
                'tag'   => '[gp_consulting_audit]',
                'title' => 'Strategic Efficiency Audit',
                'desc'  => 'High-stakes operational audit intake for enterprise firms.',
                'usage' => 'Specific to Consulting niche.',
                'example' => '[gp_consulting_audit]'
            ),
            array(
                'tag'   => '[gp_symptom_checker]',
                'title' => 'AI Health Intelligence',
                'desc'  => 'Neural triage tool for clinical symptom mapping.',
                'usage' => 'Specific to Medical niche.',
                'example' => '[gp_symptom_checker]'
            ),
            array(
                'tag'   => '[gp_tax_estimator]',
                'title' => 'Wealth Preservation Engine',
                'desc'  => 'AI tax savings estimator for corporate and family office niches.',
                'usage' => 'Specific to Accounting niche.',
                'example' => '[gp_tax_estimator]'
            ),
            array(
                'tag'   => '[gp_location_switcher]',
                'title' => 'Regional Node Switcher',
                'desc'  => 'Allows visitors to select their nearest strategic service hub.',
                'usage' => 'Place in sidebars or footers.',
                'example' => '[gp_location_switcher]'
            ),
            array(
                'tag'   => '[gp_kb_search]',
                'title' => 'Intelligence Search Hub',
                'desc'  => 'AI-powered search interface for the technical Knowledge Base.',
                'usage' => 'Place on KB Hub or support pages.',
                'example' => '[gp_kb_search]'
            )
        );
        ?>
        <div class="wrap growthpress-shortcodes">
            <h1>GrowthPress Shortcode Library</h1>
            <p class="description">Use these shortcodes to deploy the Business OS features anywhere on your site.</p>

            <div class="shortcode-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap:20px; margin-top:20px;">
                <?php foreach($shortcodes as $s): ?>
                    <div class="glass-card">
                        <code style="font-size:18px; color:#2563EB;"><?php echo $s['tag']; ?></code>
                        <h3 style="margin-top:10px;"><?php echo $s['title']; ?></h3>
                        <p><?php echo $s['desc']; ?></p>
                        <div style="background:#f8fafc; padding:10px; border-radius:8px; font-size:12px;">
                            <strong>Instructions:</strong> <?php echo $s['usage']; ?><br>
                            <strong>Sample Data:</strong> Automatically populated based on Niche.
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

new GrowthPress_Shortcode_Ref();
