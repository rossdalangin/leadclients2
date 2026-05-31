<?php
/**
 * GrowthPress Sample Data Management
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Sample_Data {

    public static function generate_all_sample_data() {
        $niche = get_option('growthpress_niche', 'business');

        // 1. Generate core CPT sample data
        $lead_ids = self::generate_leads();
        self::generate_appointments($lead_ids);
        self::generate_proposals($lead_ids);
        self::generate_transactions();
        self::generate_locations();
        self::generate_funnels();
        self::generate_tasks($lead_ids);
        self::generate_kb();
        self::generate_services();
        self::generate_projects();

        // 2. Call niche-specific sample data
        $class_name = 'GrowthPress_' . str_replace(' ', '', ucwords(str_replace('-', ' ', $niche)));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) {
                $instance->generate_sample_data();
            }
        }

        // 3. Call Reputation sample data
        $reputation = new GrowthPress_Reputation();
        $reputation->generate_sample_data();

        // 4. Log the activity
        GrowthPress_Activity::log("Sample Ecosystem Instantiation Completed.");
    }

    public static function remove_all_sample_data() {
        $args = array(
            'post_type'      => array('gp_lead', 'gp_appointment', 'gp_property', 'gp_review', 'gp_proposal', 'gp_transaction', 'gp_funnel', 'gp_location', 'gp_task', 'gp_kb', 'gp_project', 'gp_service'),
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'   => '_gp_is_sample',
                    'value' => '1',
                ),
            ),
            'post_status'    => 'any',
        );

        $sample_posts = get_posts($args);
        foreach ($sample_posts as $post) {
            wp_delete_post($post->ID, true);
        }
        GrowthPress_Activity::log("Sample Intelligence Purge Completed.");
    }

    private static function generate_leads() {
        $leads = array(
            array(
                'title' => 'John Doe',
                'content' => 'Interested in high-ticket scaling solutions for our corporate legal department.',
                'email' => 'john.doe@enterprise-legal.com',
                'zip' => '90210',
                'prob' => 85,
                'stage' => 'qualified',
                'tag' => 'Enterprise',
                'sentiment' => '{"urgency": 8, "sentiment": "positive", "intent": "high"}'
            ),
            array(
                'title' => 'Jane Smith',
                'content' => 'Looking for AI automation for my dental practice group. We have 5 locations.',
                'email' => 'jane@smithdental.com',
                'zip' => '10001',
                'prob' => 45,
                'stage' => 'new',
                'tag' => 'Commercial',
                'sentiment' => '{"urgency": 4, "sentiment": "neutral", "intent": "medium"}'
            ),
        );

        $ids = array();
        foreach ($leads as $l) {
            $id = wp_insert_post(array(
                'post_title'   => $l['title'],
                'post_content' => $l['content'],
                'post_type'    => 'gp_lead',
                'post_status'  => 'publish'
            ));
            if ($id) {
                $ids[] = $id;
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_lead_email', $l['email']);
                update_post_meta($id, '_lead_zip', $l['zip']);
                update_post_meta($id, '_gp_ai_probability', $l['prob']);
                update_post_meta($id, '_gp_ai_sentiment_json', $l['sentiment']);
                update_post_meta($id, '_gp_ai_closing_tips', "Focus on the multi-location efficiency gains.\nHighlight secure AI triage protocols.");
                update_post_meta($id, '_gp_ai_discovery_questions', "What is your current intake latency?\nHow do you handle HIPAA compliance in triage?");
                update_post_meta($id, '_gp_ai_suggested_reply', "Hello " . explode(' ', $l['title'])[0] . ", I saw your inquiry about " . (strpos($l['content'], 'legal') !== false ? 'legal' : 'dental') . " automation...");

                $notes = array(
                    array('user' => 'System AI', 'time' => current_time('mysql'), 'text' => 'Lead automatically triaged and scored.')
                );
                update_post_meta($id, '_gp_internal_notes', $notes);

                $behavior = array(
                    array('page' => 'Home', 'time' => current_time('mysql', 1)),
                    array('page' => 'Services', 'time' => current_time('mysql'))
                );
                update_post_meta($id, '_behavior_log', $behavior);

                wp_set_object_terms($id, $l['stage'], 'gp_lead_stage');
                wp_set_object_terms($id, $l['tag'], 'gp_lead_tag');
            }
        }
        return $ids;
    }

    private static function generate_appointments($lead_ids = array()) {
        $id = wp_insert_post(array(
            'post_title'  => 'Sample Strategy Session',
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_appointment_date', date('Y-m-d H:i:s', strtotime('+1 day')));
            update_post_meta($id, '_staff_id', get_current_user_id());
            if (!empty($lead_ids)) {
                update_post_meta($id, '_client_email', get_post_meta($lead_ids[0], '_lead_email', true));
                wp_set_object_terms($lead_ids[0], 'booked', 'gp_lead_stage');
            }
        }
    }

    private static function generate_proposals($lead_ids = array()) {
        $id = wp_insert_post(array(
            'post_title'   => 'Sample Growth Proposal',
            'post_content' => 'Full architectural blueprint for ecosystem dominance.',
            'post_type'    => 'gp_proposal',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_proposal_status', 'Sent');
            update_post_meta($id, '_proposal_value', 5000);
            if (!empty($lead_ids)) {
                update_post_meta($id, '_related_lead', end($lead_ids));
            }
        }
    }

    private static function generate_transactions() {
        $id = wp_insert_post(array(
            'post_title'  => 'Sample Transaction',
            'post_type'   => 'gp_transaction',
            'post_status' => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_amount', 150);
            update_post_meta($id, '_status', 'Paid');
        }
    }

    private static function generate_locations() {
        $locs = array('Downtown HQ', 'Westside Satellite', 'Eastside Hub');
        foreach ($locs as $l) {
            $id = wp_insert_post(array(
                'post_title'   => $l,
                'post_content' => 'Strategic service node for the ' . explode(' ', $l)[0] . ' district.',
                'post_type'    => 'gp_location',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_serviced_zips', '90210, 90211, 90212, 10001');
            }
        }
    }

    private static function generate_funnels() {
        $id = wp_insert_post(array(
            'post_title'  => 'Elite Conversion Funnel',
            'post_type'   => 'gp_funnel',
            'post_status' => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_hits_A', 1240);
            update_post_meta($id, '_hits_B', 1180);
        }
    }

    private static function generate_tasks($lead_ids = array()) {
        $id = wp_insert_post(array(
            'post_title'   => 'Follow up with priority leads',
            'post_content' => 'Execute high-authority closing protocol.',
            'post_type'    => 'gp_task',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            if (!empty($lead_ids)) {
                update_post_meta($id, '_related_lead', $lead_ids[0]);
            }
        }
    }

    private static function generate_kb() {
        $id = wp_insert_post(array(
            'post_title'   => 'AI Triage Safety Protocols',
            'post_content' => 'Guidelines for maintaining high-fidelity intelligence routing.',
            'post_type'    => 'gp_kb',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
        }
    }

    private static function generate_services() {
        $services = array('Growth Strategy Audit', 'Neural Ecosystem Deployment', 'Performance Blueprinting');
        foreach ($services as $s) {
            $id = wp_insert_post(array(
                'post_title'   => $s,
                'post_content' => 'Elite ' . strtolower($s) . ' for high-ticket service firms.',
                'post_type'    => 'gp_service',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
            }
        }
    }

    private static function generate_projects() {
        $projects = array('Global Enterprise Migration', 'Sustainable Infrastructure Deployment');
        foreach ($projects as $p) {
            $id = wp_insert_post(array(
                'post_title'   => $p,
                'post_content' => 'High-stakes ' . strtolower($p) . ' successfully executed.',
                'post_type'    => 'gp_project',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
            }
        }
    }
}
