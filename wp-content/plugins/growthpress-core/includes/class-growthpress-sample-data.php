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
        self::generate_leads();
        self::generate_appointments();
        self::generate_proposals();
        self::generate_transactions();

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
    }

    private static function generate_leads() {
        $leads = array(
            array('title' => 'John Doe', 'content' => 'Interested in high-ticket scaling solutions.'),
            array('title' => 'Jane Smith', 'content' => 'Looking for AI automation for my service business.'),
        );

        foreach ($leads as $l) {
            $id = wp_insert_post(array(
                'post_title'   => $l['title'],
                'post_content' => $l['content'],
                'post_type'    => 'gp_lead',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_lead_email', sanitize_title($l['title']) . '@example.com');
            }
        }
    }

    private static function generate_appointments() {
        $id = wp_insert_post(array(
            'post_title'  => 'Sample Strategy Session',
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_appointment_date', date('Y-m-d H:i:s', strtotime('+1 day')));
        }
    }

    private static function generate_proposals() {
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
}
