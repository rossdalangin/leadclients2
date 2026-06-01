<?php
/**
 * GrowthPress Unified Proposal System
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Proposals {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_proposal_cpt' ) );
        add_action( 'wp_ajax_gp_generate_ai_proposal', array( $this, 'handle_ai_proposal_generation' ) );
        add_action( 'wp_ajax_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
        add_action( 'wp_ajax_nopriv_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_proposal_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_proposal_meta' ) );
        add_filter( 'manage_gp_proposal_posts_columns', array( $this, 'proposal_columns' ) );
        add_action( 'manage_gp_proposal_posts_custom_column', array( $this, 'proposal_column_content' ), 10, 2 );
    }

    public function proposal_columns( $cols ) {
        $cols['_status'] = 'Status';
        $cols['_val'] = 'Value';
        return $cols;
    }

    public function proposal_column_content( $col, $post_id ) {
        if ( $col === '_status' ) echo get_post_meta( $post_id, '_gp_proposal_status', true ) ?: 'Sent';
        if ( $col === '_val' ) echo '$' . number_format(get_post_meta( $post_id, '_proposal_value', true ));
    }

    public function add_proposal_meta_boxes() {
        add_meta_box( 'gp_proposal_details', 'Strategic Proposal Intel', array( $this, 'render_proposal_meta' ), 'gp_proposal', 'normal', 'high' );
    }

    public function render_proposal_meta( $post ) {
        $lead_id = get_post_meta( $post->ID, '_related_lead', true );
        $status = get_post_meta( $post->ID, '_gp_proposal_status', true ) ?: 'Sent';
        $value = get_post_meta( $post->ID, '_proposal_value', true );
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        ?>
        <table class="form-table">
            <tr>
                <th><label>Related Business Lead</label></th>
                <td>
                    <select name="gp_related_lead" style="width:100%;">
                        <option value="0">Generic / No Lead</option>
                        <?php foreach($leads as $l): ?>
                            <option value="<?php echo $l->ID; ?>" <?php selected($lead_id, $l->ID); ?>><?php echo esc_html($l->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Proposal Status</label></th>
                <td>
                    <select name="gp_proposal_status" style="width:100%;">
                        <option value="Draft" <?php selected($status, 'Draft'); ?>>Draft</option>
                        <option value="Sent" <?php selected($status, 'Sent'); ?>>Sent / Active</option>
                        <option value="Accepted" <?php selected($status, 'Accepted'); ?>>Accepted / Executed</option>
                        <option value="Declined" <?php selected($status, 'Declined'); ?>>Declined</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Strategic Value ($)</label></th>
                <td><input type="number" name="gp_proposal_value" value="<?php echo esc_attr($value); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }

    public function save_proposal_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_proposal_status'] ) ) return;
        update_post_meta( $post_id, '_related_lead', intval( $_POST['gp_related_lead'] ) );
        update_post_meta( $post_id, '_gp_proposal_status', sanitize_text_field( $_POST['gp_proposal_status'] ) );
        update_post_meta( $post_id, '_proposal_value', floatval( $_POST['gp_proposal_value'] ) );
    }

    public function register_proposal_cpt() {
        register_post_type( 'gp_proposal', array(
            'labels'      => array( 'name' => 'Proposals', 'singular_name' => 'Proposal' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-media-text',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function handle_ai_proposal_generation() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $lead_id = intval($_POST['lead_id']);
        $lead = get_post($lead_id);
        if ( ! $lead ) wp_send_json_error('Lead not found.');

        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        $proposal_content = $ai->generate_proposal($lead->post_title, "Advanced $niche Solutions", $niche);

        $proposal_id = wp_insert_post(array(
            'post_title'   => 'Proposal: ' . $lead->post_title,
            'post_content' => $proposal_content,
            'post_type'    => 'gp_proposal',
            'post_status'  => 'publish'
        ));

        update_post_meta($proposal_id, '_related_lead', $lead_id);
        update_post_meta($proposal_id, '_proposal_status', 'Sent');

        // Estimate value based on niche/probability
        $prob = get_post_meta($lead_id, '_gp_ai_probability', true) ?: 50;
        $base_values = array('law' => 5000, 'solar' => 25000, 'dental' => 3000, 'contractor' => 15000, 'accounting' => 2000);
        $est_val = $base_values[$niche] ?? 5000;
        update_post_meta($proposal_id, '_proposal_value', $est_val);

        GrowthPress_Activity::log( "AI Proposal #$proposal_id generated for " . $lead->post_title );
        wp_send_json_success( "Proposal generated! ID: $proposal_id. Value: $$est_val" );
    }

    public function handle_proposal_acceptance() {
        $proposal_id = intval($_POST['proposal_id']);
        update_post_meta($proposal_id, '_gp_proposal_status', 'Accepted');

        // Move Lead to Closed
        $lead_id = get_post_meta($proposal_id, '_related_lead', true);
        if ($lead_id) {
            wp_set_object_terms($lead_id, 'closed', 'gp_lead_stage');

            // Create Kickoff Task
            $crm = GrowthPress_CRM::get_instance();
            $crm->create_task("Project Kickoff: " . get_the_title($lead_id), "Proposal accepted. Initialize onboarding sequence.", $lead_id);

            // Create Draft Case Study
            wp_insert_post(array(
                'post_title'   => 'Case Study: ' . get_the_title($lead_id),
                'post_content' => 'Proposal accepted on ' . date('Y-m-d') . ". Summary: " . get_the_excerpt($lead_id),
                'post_type'    => 'gp_project',
                'post_status'  => 'draft'
            ));

            GrowthPress_Activity::log( "Lead #$lead_id transitioned to 'Closed' following proposal acceptance. Draft Case Study generated." );
        }

        $value = get_post_meta($proposal_id, '_proposal_value', true);
        $payments = new GrowthPress_Payments();
        $invoice_id = $payments->create_invoice($value, $proposal_id, 'proposal');

        GrowthPress_Activity::log( "Proposal #$proposal_id accepted. Invoice #$invoice_id generated." );
        wp_send_json_success(array('invoice_id' => $invoice_id));
    }

    public function get_pipeline_value() {
        $proposals = get_posts(array(
            'post_type' => 'gp_proposal',
            'posts_per_page' => -1,
            'meta_query' => array( array( 'key' => '_gp_proposal_status', 'value' => 'Sent' ) )
        ));
        $total = 0;
        foreach($proposals as $p) $total += (float)get_post_meta($p->ID, '_proposal_value', true);
        return $total;
    }
}
GrowthPress_Proposals::get_instance();
