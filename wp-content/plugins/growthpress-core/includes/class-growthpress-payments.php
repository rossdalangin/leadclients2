<?php
/**
 * GrowthPress Payments Engine
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Payments {

    public function __construct() {
        add_action( 'init', array( $this, 'register_payment_cpt' ) );
        add_action( 'wp_ajax_gp_process_deposit', array( $this, 'handle_deposit' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_payment_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_payment_meta' ) );
        add_filter( 'manage_gp_transaction_posts_columns', array( $this, 'transaction_columns' ) );
        add_action( 'manage_gp_transaction_posts_custom_column', array( $this, 'transaction_column_content' ), 10, 2 );
    }

    public function transaction_columns( $cols ) {
        $cols['_amt'] = 'Amount';
        $cols['_method'] = 'Method';
        $cols['_status'] = 'Status';
        return $cols;
    }

    public function transaction_column_content( $col, $post_id ) {
        if ( $col === '_amt' ) echo '$' . number_format(get_post_meta( $post_id, '_amount', true ));
        if ( $col === '_method' ) echo get_post_meta( $post_id, '_payment_method', true ) ?: 'Stripe';
        if ( $col === '_status' ) {
            $s = get_post_meta( $post_id, '_status', true ) ?: 'Pending';
            $color = ($s === 'Paid') ? '#10b981' : (($s === 'Refunded') ? '#ef4444' : '#f59e0b');
            echo "<span style='color:$color; font-weight:bold;'>$s</span>";
        }
    }

    public function add_payment_meta_boxes() {
        add_meta_box( 'gp_payment_details', 'Transaction Data Ledger', array( $this, 'render_payment_meta' ), 'gp_transaction', 'normal', 'high' );
    }

    public function render_payment_meta( $post ) {
        $amount = get_post_meta( $post->ID, '_amount', true );
        $status = get_post_meta( $post->ID, '_status', true ) ?: 'Pending';
        $method = get_post_meta( $post->ID, '_payment_method', true ) ?: 'Stripe';
        $related = get_post_meta( $post->ID, '_related_id', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label>Transaction Status</label></th>
                <td>
                    <select name="gp_payment_status" style="width:100%;">
                        <option value="Pending" <?php selected($status, 'Pending'); ?>>Pending / Unpaid</option>
                        <option value="Paid" <?php selected($status, 'Paid'); ?>>Paid / Synchronized</option>
                        <option value="Refunded" <?php selected($status, 'Refunded'); ?>>Refunded</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Ledger Amount ($)</label></th>
                <td><input type="number" name="gp_payment_amount" value="<?php echo esc_attr($amount); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Payment Method</label></th>
                <td>
                    <select name="gp_payment_method" style="width:100%;">
                        <option value="Stripe" <?php selected($method, 'Stripe'); ?>>Stripe Card</option>
                        <option value="PayPal" <?php selected($method, 'PayPal'); ?>>PayPal</option>
                        <option value="Bank" <?php selected($method, 'Bank'); ?>>Bank Transfer</option>
                        <option value="Cash" <?php selected($method, 'Cash'); ?>>Cash / Manual</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Related System Node ID</label></th>
                <td><input type="number" name="gp_related_id" value="<?php echo esc_attr($related); ?>" class="regular-text" placeholder="e.g. Appointment or Proposal ID"></td>
            </tr>
        </table>
        <?php
    }

    public function save_payment_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_payment_status'] ) ) return;
        update_post_meta( $post_id, '_status', sanitize_text_field( $_POST['gp_payment_status'] ) );
        update_post_meta( $post_id, '_amount', floatval( $_POST['gp_payment_amount'] ) );
        update_post_meta( $post_id, '_payment_method', sanitize_text_field( $_POST['gp_payment_method'] ) );
        update_post_meta( $post_id, '_related_id', intval( $_POST['gp_related_id'] ) );
    }

    public function register_payment_cpt() {
        register_post_type( 'gp_transaction', array(
            'labels'      => array( 'name' => 'Transactions', 'singular_name' => 'Transaction' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-money-alt',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function create_invoice( $amount, $related_id, $type = 'booking' ) {
        $transaction_id = wp_insert_post( array(
            'post_title'  => sprintf( 'Invoice for %s #%d', ucfirst($type), $related_id ),
            'post_type'   => 'gp_transaction',
            'post_status' => 'publish',
        ) );
        update_post_meta( $transaction_id, '_amount', $amount );
        update_post_meta( $transaction_id, '_status', 'Pending' );
        update_post_meta( $transaction_id, '_related_id', $related_id );
        return $transaction_id;
    }

    public function handle_deposit() {
        // Mock Stripe/PayPal integration logic
        $transaction_id = intval($_POST['transaction_id']);
        update_post_meta( $transaction_id, '_status', 'Paid' );
        GrowthPress_Activity::log( "Payment received for Transaction #$transaction_id" );
        wp_send_json_success('Payment confirmed.');
    }
}

new GrowthPress_Payments();
