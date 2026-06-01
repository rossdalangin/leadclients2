<?php
/**
 * GrowthPress Funnel Management Class - A/B Testing Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Funnels {

    public function __construct() {
        add_action( 'init', array( $this, 'register_funnel_cpt' ) );
        add_shortcode( 'gp_funnel_step', array( $this, 'render_funnel_step' ) );
        add_action( 'wp_ajax_gp_track_funnel', array( $this, 'handle_tracking' ) );
        add_action( 'wp_ajax_nopriv_gp_track_funnel', array( $this, 'handle_tracking' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_funnel_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_funnel_meta' ) );
        add_filter( 'manage_gp_funnel_posts_columns', array( $this, 'funnel_columns' ) );
        add_action( 'manage_gp_funnel_posts_custom_column', array( $this, 'funnel_column_content' ), 10, 2 );
    }

    public function funnel_columns( $cols ) {
        $cols['_hits'] = 'Total Traffic';
        return $cols;
    }

    public function funnel_column_content( $col, $post_id ) {
        if ( $col === '_hits' ) {
            $a = (int)get_post_meta($post_id, '_hits_A', true);
            $b = (int)get_post_meta($post_id, '_hits_B', true);
            echo ($a + $b) . ' Hits';
        }
    }

    public function add_funnel_meta_boxes() {
        add_meta_box( 'gp_funnel_details', 'A/B Analytics Metrics', array( $this, 'render_funnel_meta' ), 'gp_funnel', 'normal', 'high' );
    }

    public function render_funnel_meta( $post ) {
        $hitsA = get_post_meta( $post->ID, '_hits_A', true ) ?: 0;
        $hitsB = get_post_meta( $post->ID, '_hits_B', true ) ?: 0;
        ?>
        <table class="form-table">
            <tr>
                <th><label>Variation A Traffic Hits</label></th>
                <td><input type="number" name="gp_hits_a" value="<?php echo esc_attr($hitsA); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Variation B Traffic Hits</label></th>
                <td><input type="number" name="gp_hits_b" value="<?php echo esc_attr($hitsB); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }

    public function save_funnel_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_hits_a'] ) ) return;
        update_post_meta( $post_id, '_hits_A', intval( $_POST['gp_hits_a'] ) );
        update_post_meta( $post_id, '_hits_B', intval( $_POST['gp_hits_b'] ) );
    }

    public function register_funnel_cpt() {
        register_post_type( 'gp_funnel', array(
            'labels'      => array( 'name' => 'Funnels', 'singular_name' => 'Funnel' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-filter',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function render_funnel_step( $atts ) {
        $a = shortcode_atts( array( 'id' => 0, 'variation' => 'A' ), $atts );
        if ( ! $a['id'] ) return '';

        ob_start(); ?>
        <script>
        jQuery(document).ready(function($) {
            $.post(gp_ajax.ajaxurl, {
                action: 'gp_track_funnel',
                funnel_id: <?php echo intval($a['id']); ?>,
                variation: '<?php echo esc_js($a['variation']); ?>'
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_tracking() {
        $id = intval($_POST['funnel_id']);
        $var = sanitize_text_field($_POST['variation']);
        $this->track_variation_hit($id, $var);
        wp_send_json_success();
    }

    public function track_variation_hit( $funnel_id, $variation = 'A' ) {
        $hits = get_post_meta( $funnel_id, "_hits_$variation", true ) ?: 0;
        update_post_meta( $funnel_id, "_hits_$variation", ++$hits );
    }

    public function get_performance( $funnel_id ) {
        return array(
            'A' => get_post_meta( $funnel_id, '_hits_A', true ) ?: 0,
            'B' => get_post_meta( $funnel_id, '_hits_B', true ) ?: 0,
        );
    }
}

new GrowthPress_Funnels();
