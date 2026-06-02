<?php
/**
 * GrowthPress Sample Data Management - Elite v4.5 Consolidated
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Sample_Data {

    public static function generate_all_sample_data() {
        $niche = get_option('growthpress_niche', 'business');

        // 1. Generate core CPT sample data
        $lead_ids = self::generate_leads();
        $appt_ids = self::generate_appointments($lead_ids);
        $service_ids = self::generate_services();
        $proposal_ids = self::generate_proposals($lead_ids, $service_ids);
        $project_ids = self::generate_projects();
        self::generate_transactions($proposal_ids, $appt_ids);
        self::generate_locations();
        self::generate_funnels();
        self::generate_tasks($lead_ids);
        self::generate_kb();
        self::generate_inventory();
        self::generate_reviews($project_ids);

        // 2. Call niche-specific sample data
        $class_name = 'GrowthPress_' . str_replace(' ', '', ucwords(str_replace('-', ' ', $niche)));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) {
                $instance->generate_sample_data();
            }
        }

        // 3. Call Reputation sample data (already covered by generate_reviews but keeping for safety if it adds more)
        if ( class_exists('GrowthPress_Reputation') ) {
            $reputation = new GrowthPress_Reputation();
            if ( method_exists($reputation, 'generate_sample_data') ) {
                $reputation->generate_sample_data();
            }
        }

        // 4. Log the activity
        GrowthPress_Activity::log("Elite Ecosystem Sample Instantiation Completed.");
    }

    public static function remove_all_sample_data() {
        $args = array(
            'post_type'      => array('gp_lead', 'gp_appointment', 'gp_property', 'gp_review', 'gp_proposal', 'gp_transaction', 'gp_funnel', 'gp_location', 'gp_task', 'gp_kb', 'gp_project', 'gp_service', 'gp_treatment'),
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
                'content' => 'Interested in high-ticket scaling solutions for our corporate legal department. We need a system that handles multi-node triage.',
                'email' => 'john.doe@enterprise-legal.com',
                'phone' => '555-0101',
                'zip' => '90210',
                'source' => 'Neural Quiz',
                'prob' => 85,
                'stage' => 'qualified',
                'tag' => 'Enterprise',
                'sentiment' => '{"urgency": 8, "sentiment": "positive", "intent": "high"}'
            ),
            array(
                'title' => 'Jane Smith',
                'content' => 'Looking for AI automation for my dental practice group. We have 5 locations and want to automate patient follow-ups.',
                'email' => 'jane@smithdental.com',
                'phone' => '555-0202',
                'zip' => '10001',
                'source' => 'Organic Search',
                'prob' => 45,
                'stage' => 'new',
                'tag' => 'Commercial',
                'sentiment' => '{"urgency": 4, "sentiment": "neutral", "intent": "medium"}'
            ),
        );

        $ids = array();
        $staff = get_users( array( 'role__in' => array('author', 'editor', 'administrator'), 'fields' => 'ID' ) );
        $staff_id = !empty($staff) ? $staff[0] : 0;

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
                update_post_meta($id, '_lead_phone', $l['phone']);
                update_post_meta($id, '_lead_zip', $l['zip']);
                update_post_meta($id, '_lead_source', $l['source']);
                update_post_meta($id, '_gp_ai_probability', $l['prob']);
                update_post_meta($id, '_gp_ai_sentiment_json', $l['sentiment']);
                update_post_meta($id, '_gp_ai_closing_tips', "Focus on the multi-node efficiency gains.\nHighlight secure AI triage protocols.");
                update_post_meta($id, '_gp_ai_discovery_questions', "What is your current intake latency?\nHow do you handle compliance in triage?");
                update_post_meta($id, '_gp_ai_suggested_reply', "Hello " . explode(' ', $l['title'])[0] . ", I saw your inquiry about automation...");
                update_post_meta($id, '_gp_behavioral_nudge', "Based on your interest in " . $l['tag'] . " solutions, we have a specialized team ready.");
                update_post_meta($id, '_gp_nurture_sequence', "Day 1: Welcome\nDay 2: Value Proposition\nDay 3: Case Study\nDay 4: Demo Invitation\nDay 5: Final Follow-up");
                update_post_meta($id, '_assigned_staff', $staff_id);

                $notes = array(
                    array('user' => 'System AI', 'time' => current_time('mysql'), 'text' => 'Lead automatically triaged and scored.')
                );
                update_post_meta($id, '_gp_internal_notes', $notes);

                $behavior = array(
                    array('page' => 'Home', 'time' => date('Y-m-d H:i:s', strtotime('-1 hour'))),
                    array('page' => 'Services', 'time' => date('Y-m-d H:i:s', strtotime('-30 mins')))
                );
                update_post_meta($id, '_behavior_log', $behavior);

                wp_set_object_terms($id, $l['stage'], 'gp_lead_stage');
                wp_set_object_terms($id, $l['tag'], 'gp_lead_tag');
            }
        }
        return $ids;
    }

    private static function generate_appointments($lead_ids = array()) {
        $ids = array();
        $staff = get_users( array( 'role__in' => array('author', 'editor', 'administrator'), 'fields' => 'ID' ) );
        $staff_id = !empty($staff) ? $staff[0] : 0;

        $id = wp_insert_post(array(
            'post_title'  => 'Sample Strategy Session',
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish'
        ));
        if ($id) {
            $ids[] = $id;
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_appointment_date', date('Y-m-d 10:00', strtotime('+1 day')));
            update_post_meta($id, '_staff_id', $staff_id);
            update_post_meta($id, '_status', 'Confirmed');
            update_post_meta($id, '_meeting_link', 'https://zoom.us/j/123456789');
            update_post_meta($id, '_is_waiting_list', '0');
            if (!empty($lead_ids)) {
                update_post_meta($id, '_client_email', get_post_meta($lead_ids[0], '_lead_email', true));
                update_post_meta($id, '_related_lead', $lead_ids[0]);
                wp_set_object_terms($lead_ids[0], 'booked', 'gp_lead_stage');
            }
        }

        $id2 = wp_insert_post(array(
            'post_title'  => 'Priority Waiting Session',
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish'
        ));
        if ($id2) {
            $ids[] = $id2;
            update_post_meta($id2, '_gp_is_sample', '1');
            update_post_meta($id2, '_is_waiting_list', '1');
            update_post_meta($id2, '_status', 'Pending');
            update_post_meta($id2, '_client_email', 'waiting@example.com');
        }

        return $ids;
    }

    private static function generate_proposals($lead_ids = array(), $service_ids = array()) {
        $ids = array();
        $id = wp_insert_post(array(
            'post_title'   => 'Sample Growth Proposal',
            'post_content' => 'Full architectural blueprint for ecosystem dominance. Including AI Triage implementation and automated follow-up sequences.',
            'post_type'    => 'gp_proposal',
            'post_status'  => 'publish'
        ));
        if ($id) {
            $ids[] = $id;
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_proposal_status', 'Sent');
            update_post_meta($id, '_proposal_type', 'Project');
            update_post_meta($id, '_internal_approval', 'Approved');
            update_post_meta($id, '_proposal_value', 12500);
            update_post_meta($id, '_proposal_revision', 1);
            update_post_meta($id, '_proposal_expires', date('Y-m-d', strtotime('+30 days')));
            update_post_meta($id, '_is_digitally_signed', '0');
            update_post_meta($id, '_proposal_terms', "Standard service terms apply.\n50% deposit required for kickoff.");
            update_post_meta($id, '_gp_proposal_sent_at', current_time('mysql'));

            if (!empty($lead_ids)) {
                $lead_id = end($lead_ids);
                update_post_meta($id, '_related_lead', $lead_id);
                update_post_meta($id, '_proposal_recipient', get_post_meta($lead_id, '_lead_email', true));
            } else {
                update_post_meta($id, '_proposal_recipient', 'prospect@example.com');
            }

            if (!empty($service_ids)) {
                update_post_meta($id, '_related_service', $service_ids[0]);
            }
        }
        return $ids;
    }

    private static function generate_transactions($proposal_ids = array(), $appt_ids = array()) {
        $methods = array('Stripe', 'PayPal', 'Bank');
        $categories = array('Marketing', 'Operations', 'Software', 'Payroll');

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
                update_post_meta($id, '_transaction_type', $i === 2 ? 'Expense' : 'Revenue');
                update_post_meta($id, '_transaction_category', $categories[$i % 4]);
                update_post_meta($id, '_is_tax_deductible', $i === 2 ? '1' : '0');
                update_post_meta($id, '_payment_reference', 'GP-TRX-' . strtoupper(wp_generate_password(8, false)));
                update_post_meta($id, '_is_verified', $i === 2 ? '0' : '1');
                update_post_meta($id, '_audit_notes', "Automated sample transaction for system calibration.");

                if ($i === 0 && !empty($proposal_ids)) {
                    update_post_meta($id, '_related_id', $proposal_ids[0]);
                } elseif ($i === 1 && !empty($appt_ids)) {
                    update_post_meta($id, '_related_id', $appt_ids[0]);
                }
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
                'post_content' => 'Strategic service node for the ' . explode(' ', $l)[0] . ' district. Featuring high-fidelity triage systems.',
                'post_type'    => 'gp_location',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_serviced_zips', '90210, 90211, 90212, 10001, 10002');
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
            update_post_meta($id, '_url_A', home_url('/v1-control'));
            update_post_meta($id, '_url_B', home_url('/v2-challenger'));
            update_post_meta($id, '_conversion_goal', 'Lead Capture Quiz');
            update_post_meta($id, '_funnel_ad_spend', 2500);
            update_post_meta($id, '_lead_value_est', 800);
            update_post_meta($id, '_winning_strategy_note', "Variation A performs better due to lower friction in the initial value proposition.");
        }
    }

    private static function generate_tasks($lead_ids = array()) {
        $tasks = array(
            'High-Priority Triage' => 'High',
            'Strategic Onboarding' => 'Medium',
            'Contract Review' => 'High',
            'System Calibration' => 'Low'
        );
        $staff = get_users( array( 'role__in' => array('author', 'editor', 'administrator'), 'fields' => 'ID' ) );
        $staff_id = !empty($staff) ? $staff[0] : 0;

        foreach($tasks as $title => $prio) {
            $id = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => 'Automated strategic maintenance task generated by the ecosystem engine.',
                'post_type'    => 'gp_task',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_task_priority', $prio);
                update_post_meta($id, '_task_status', rand(0,1) ? 'Pending' : 'Completed');
                update_post_meta($id, '_task_due_date', date('Y-m-d', strtotime('+' . rand(1, 14) . ' days')));
                update_post_meta($id, '_assigned_staff', $staff_id);
                if (!empty($lead_ids)) {
                    update_post_meta($id, '_related_lead', $lead_ids[rand(0, count($lead_ids)-1)]);
                }
            }
        }
    }

    private static function generate_kb() {
        $id = wp_insert_post(array(
            'post_title'   => 'AI Triage Safety Protocols',
            'post_content' => 'Guidelines for maintaining high-fidelity intelligence routing. Ensure all leads are scored according to niche-specific intent metrics.',
            'post_type'    => 'gp_kb',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_kb_intel_level', 'Executive');
            update_post_meta($id, '_kb_access_control', 'Internal');
        }

        $id2 = wp_insert_post(array(
            'post_title'   => 'Client Portal User Guide',
            'post_content' => 'How to access your strategic proposals and project updates through the secure client portal.',
            'post_type'    => 'gp_kb',
            'post_status'  => 'publish'
        ));
        if ($id2) {
            update_post_meta($id2, '_gp_is_sample', '1');
            update_post_meta($id2, '_kb_intel_level', 'Basic');
            update_post_meta($id2, '_kb_access_control', 'Client');
        }
    }

    private static function generate_services() {
        $services = array(
            'Growth Strategy Audit' => '📈',
            'Neural Ecosystem Deployment' => '🤖',
            'Performance Blueprinting' => '⚡',
            'Financial Trajectory Analysis' => '💰'
        );
        $ids = array();
        foreach ($services as $s => $icon) {
            $id = wp_insert_post(array(
                'post_title'   => $s,
                'post_content' => 'Elite ' . strtolower($s) . ' for high-ticket service firms. Our specialized methodology ensures maximum ROI.',
                'post_type'    => 'gp_service',
                'post_status'  => 'publish'
            ));
            if ($id) {
                $ids[] = $id;
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_service_icon', $icon);
            }
        }
        return $ids;
    }

    private static function generate_projects() {
        $projects = array(
            'Global Enterprise Migration' => array('+420%', '15 HRS/WK', '$2.5M+'),
            'Sustainable Infrastructure Deployment' => array('+215%', '22 HRS/WK', '$1.8M+'),
            'Neural Triage Implementation' => array('+680%', '40 HRS/WK', '$3.2M+')
        );
        $ids = array();
        foreach ($projects as $p => $data) {
            $id = wp_insert_post(array(
                'post_title'   => $p . ' (Demo Case)',
                'post_content' => 'High-stakes ' . strtolower($p) . ' successfully executed. This project involved a complete overhaul of existing systems.',
                'post_type'    => 'gp_project',
                'post_status'  => 'publish'
            ));
            if ($id) {
                $ids[] = $id;
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_growth_roi', $data[0]);
                update_post_meta($id, '_gp_efficiency_gain', $data[1]);
                update_post_meta($id, '_gp_pipeline_value', $data[2]);
            }
        }
        return $ids;
    }

    private static function generate_inventory() {
        $items = array('Elite Strategic Asset #1', 'High-Yield Node #2', 'Dominance District Hub');
        foreach ($items as $item) {
            $id = wp_insert_post(array(
                'post_title'   => $item,
                'post_content' => 'Premium ' . strtolower($item) . ' for ecosystem expansion. This asset is positioned in a high-growth sector.',
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

    private static function generate_reviews($project_ids = array()) {
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

                if (!empty($project_ids)) {
                    update_post_meta($id, '_related_project', $project_ids[$i % count($project_ids)]);
                }
                $i++;
            }
        }
    }
}
