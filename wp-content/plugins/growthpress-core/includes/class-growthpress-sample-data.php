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
        self::generate_inventory();
        self::generate_reviews();

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
            update_post_meta($id, '_gp_proposal_status', 'Sent');
            update_post_meta($id, '_proposal_value', 12500);
            update_post_meta($id, '_gp_proposal_sent_at', current_time('mysql'));
            if (!empty($lead_ids)) {
                $lead_id = end($lead_ids);
                update_post_meta($id, '_related_lead', $lead_id);
                update_post_meta($id, '_proposal_recipient', get_post_meta($lead_id, '_lead_email', true));
            } else {
                update_post_meta($id, '_proposal_recipient', 'prospect@example.com');
            }
        }
    }

    private static function generate_transactions() {
        $methods = array('Stripe', 'PayPal', 'Bank');
        for($i=0; $i<3; $i++) {
            $id = wp_insert_post(array(
                'post_title'  => 'Transaction: Invoice #' . (100 + $i),
                'post_type'   => 'gp_transaction',
                'post_status' => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_amount', rand(500, 5000));
                update_post_meta($id, '_status', $i === 2 ? 'Pending' : 'Paid');
                update_post_meta($id, '_payment_method', $methods[$i]);
                update_post_meta($id, '_related_id', rand(1, 100));
            }
        }
    }

    private static function generate_locations() {
        $locs = array(
            'Downtown HQ' => '123 Elite Way, Business District',
            'Westside Satellite' => '456 Innovation Blvd, Tech Hub',
            'Eastside Hub' => '789 Growth Terrace, Industry Park'
        );
        foreach ($locs as $l => $addr) {
            $id = wp_insert_post(array(
                'post_title'   => $l,
                'post_content' => 'Strategic service node for the ' . explode(' ', $l)[0] . ' district.',
                'post_type'    => 'gp_location',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_serviced_zips', '90210, 90211, 90212, 10001');
                update_post_meta($id, '_location_address', $addr);
                update_post_meta($id, '_location_phone', '555-019' . rand(0,9));
                update_post_meta($id, '_location_map_url', 'https://maps.google.com/?q=' . urlencode($addr));
            }
        }
    }

    private static function generate_funnels() {
        $id = wp_insert_post(array(
            'post_title'  => 'Elite Scaling Funnel',
            'post_type'   => 'gp_funnel',
            'post_status' => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_hits_A', 1540);
            update_post_meta($id, '_hits_B', 1420);
            update_post_meta($id, '_conv_A', 310);
            update_post_meta($id, '_conv_B', 215);
            update_post_meta($id, '_url_A', home_url('/v1'));
            update_post_meta($id, '_url_B', home_url('/v2'));
        }
    }

    private static function generate_tasks($lead_ids = array()) {
        $tasks = array(
            'High-Priority Triage' => 'High',
            'Strategic Onboarding' => 'Medium',
            'Contract Review' => 'High',
            'System Calibration' => 'Low'
        );
        foreach($tasks as $title => $prio) {
            $id = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => 'Automated strategic maintenance task.',
                'post_type'    => 'gp_task',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_task_priority', $prio);
                update_post_meta($id, '_task_status', rand(0,1) ? 'Pending' : 'Completed');
                update_post_meta($id, '_task_due_date', date('Y-m-d', strtotime('+' . rand(1, 14) . ' days')));
                update_post_meta($id, '_assigned_staff', get_current_user_id());
                if (!empty($lead_ids)) {
                    update_post_meta($id, '_related_lead', $lead_ids[rand(0, count($lead_ids)-1)]);
                }
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
        $services = array(
            'Growth Strategy Audit' => '📈',
            'Neural Ecosystem Deployment' => '🤖',
            'Performance Blueprinting' => '⚡',
            'Financial Trajectory Analysis' => '💰'
        );
        foreach ($services as $s => $icon) {
            $id = wp_insert_post(array(
                'post_title'   => $s,
                'post_content' => 'Elite ' . strtolower($s) . ' for high-ticket service firms.',
                'post_type'    => 'gp_service',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_service_icon', $icon);
            }
        }
    }

    private static function generate_projects() {
        $projects = array(
            'Global Enterprise Migration' => array('+420%', '15 HRS/WK'),
            'Sustainable Infrastructure Deployment' => array('+215%', '22 HRS/WK'),
            'Neural Triage Implementation' => array('+680%', '40 HRS/WK')
        );
        foreach ($projects as $p => $data) {
            $id = wp_insert_post(array(
                'post_title'   => $p . ' (Demo User)',
                'post_content' => 'High-stakes ' . strtolower($p) . ' successfully executed.',
                'post_type'    => 'gp_project',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_growth_roi', $data[0]);
                update_post_meta($id, '_gp_efficiency_gain', $data[1]);
                update_post_meta($id, '_gp_pipeline_value', '$' . rand(1, 10) . '.2M+');
            }
        }
    }

    private static function generate_inventory() {
        $items = array('Elite Strategic Asset #1', 'High-Yield Node #2', 'Dominance District Hub');
        foreach ($items as $item) {
            $id = wp_insert_post(array(
                'post_title'   => $item,
                'post_content' => 'Premium ' . strtolower($item) . ' for ecosystem expansion.',
                'post_type'    => 'gp_property',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_price', rand(1500000, 8000000));
                update_post_meta($id, '_gp_sqft', rand(2000, 15000));
                update_post_meta($id, '_gp_lifestyle_tags', 'Modernist, Enterprise, Elite');
            }
        }
    }

    private static function generate_reviews() {
        $reviews = array(
            'The autonomous triage is 10x more efficient than our old manual process.' => 'Director of Growth',
            'Seamless client portal experience. Our enterprise partners love the transparency.' => 'Managing Partner',
            'Market dominance was achieved within 3 quarters of implementation.' => 'CEO, Nexus Corp'
        );
        $sources = array('Google', 'Trustpilot', 'Direct');
        $i = 0;
        foreach ($reviews as $content => $author) {
            $id = wp_insert_post(array(
                'post_title'   => $author,
                'post_content' => $content,
                'post_type'    => 'gp_review',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_rating', 5);
                update_post_meta($id, '_gp_client_name', $author);
                update_post_meta($id, '_gp_review_source', $sources[$i % 3]);
                $i++;
            }
        }
    }
}
