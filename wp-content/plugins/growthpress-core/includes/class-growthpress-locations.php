<?php
/**
 * GrowthPress Multi-Location Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Locations {

    public function __construct() {
        add_action( 'init', array( $this, 'register_location_cpt' ) );
        add_action( 'gp_lead_captured', array( $this, 'route_lead_by_location' ) );
        add_shortcode( 'gp_location_switcher', array( $this, 'render_location_switcher' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_location_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_location_meta' ) );
        add_filter( 'manage_gp_location_posts_columns', array( $this, 'location_columns' ) );
        add_action( 'manage_gp_location_posts_custom_column', array( $this, 'location_column_content' ), 10, 2 );
    }

    public function location_columns( $cols ) {
        $cols['_zips'] = 'Serviced ZIPs';
        return $cols;
    }

    public function location_column_content( $col, $post_id ) {
        if ( $col === '_zips' ) echo get_post_meta( $post_id, '_serviced_zips', true ) ?: '-';
    }

    public function add_location_meta_boxes() {
        add_meta_box( 'gp_location_details', 'Regional Node Configuration', array( $this, 'render_location_meta' ), 'gp_location', 'normal', 'high' );
    }

    public function render_location_meta( $post ) {
        $zips = get_post_meta( $post->ID, '_serviced_zips', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label>Serviced ZIP Codes</label></th>
                <td>
                    <textarea name="gp_serviced_zips" style="width:100%; height:100px;" placeholder="90210, 90211, 90212..."><?php echo esc_textarea($zips); ?></textarea>
                    <p class="description">Comma-separated list of ZIP codes for autonomous lead routing.</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_location_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_serviced_zips'] ) ) return;
        update_post_meta( $post_id, '_serviced_zips', sanitize_text_field( $_POST['gp_serviced_zips'] ) );
    }

    public function register_location_cpt() {
        register_post_type( 'gp_location', array(
            'labels'      => array( 'name' => 'Locations', 'singular_name' => 'Location' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-location',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function get_locations() {
        return get_posts( array( 'post_type' => 'gp_location', 'posts_per_page' => -1 ) );
    }

    public function render_location_switcher() {
        $locations = $this->get_locations();
        if ( empty($locations) ) return '';

        ob_start(); ?>
        <div class="gp-location-selector glass-card">
            <select onchange="window.location.href=this.value">
                <option>Select Nearest Location...</option>
                <?php foreach($locations as $loc): ?>
                    <option value="<?php echo get_permalink($loc->ID); ?>"><?php echo esc_html($loc->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
        return ob_get_clean();
    }

    public function route_lead_by_location( $lead_id ) {
        $zip = get_post_meta( $lead_id, '_lead_zip', true );
        if ( ! $zip ) return;

        $locations = $this->get_locations();
        foreach ( $locations as $loc ) {
            $serviced_zips = get_post_meta( $loc->ID, '_serviced_zips', true );
            if ( strpos( $serviced_zips, $zip ) !== false ) {
                update_post_meta( $lead_id, '_assigned_location', $loc->ID );
                GrowthPress_Activity::log( "Lead #$lead_id routed to location: " . $loc->post_title );
                break;
            }
        }
    }
}

new GrowthPress_Locations();
