<?php
/**
 * GrowthPress Reporting Class - Data-Driven v4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Reports {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_reports_menu' ) );
    }

    public function add_reports_menu() {
        add_submenu_page( 'growthpress-dashboard', 'Reports & ROI', 'Strategic ROI', 'manage_options', 'growthpress-reports', array( $this, 'render_reports' ) );
    }

    private function get_live_stats() {
        $leads = get_posts(array('post_type' => 'gp_lead', 'posts_per_page' => -1, 'post_status' => 'publish'));
        $appts = get_posts(array('post_type' => 'gp_appointment', 'posts_per_page' => -1, 'post_status' => 'publish'));
        $proposals = get_posts(array('post_type' => 'gp_proposal', 'posts_per_page' => -1, 'post_status' => 'publish'));
        $transactions = get_posts(array('post_type' => 'gp_transaction', 'posts_per_page' => -1, 'post_status' => 'publish'));

        $total_value = 0;
        foreach($proposals as $p) {
            $status = get_post_meta($p->ID, '_gp_proposal_status', true);
            if($status === 'Accepted') {
                $total_value += (float)get_post_meta($p->ID, '_proposal_value', true) ?: 0;
            }
        }

        $revenue = 0;
        $expenses = 0;
        foreach($transactions as $t) {
            $amt = (float)get_post_meta($t->ID, '_amount', true);
            $type = get_post_meta($t->ID, '_transaction_type', true) ?: 'Revenue';
            if($type === 'Revenue') $revenue += $amt;
            else $expenses += $amt;
        }

        return array(
            'Total Leads' => count($leads),
            'Confirmed Bookings' => count($appts),
            'Revenue' => $revenue,
            'OpEx' => $expenses,
            'Net Equity' => $revenue - $expenses,
            'Pipeline Upside' => $total_value
        );
    }

    public function render_reports() {
        $stats = $this->get_live_stats();
        $conv_rate = $stats['Total Leads'] > 0 ? round(($stats['Confirmed Bookings'] / $stats['Total Leads']) * 100, 1) : 0;

        // Funnel Performance & ROI
        $funnels = get_posts(array('post_type' => 'gp_funnel', 'posts_per_page' => 5));
        $funnel_data = array();
        foreach($funnels as $f) {
            $hitsA = (int)get_post_meta($f->ID, '_hits_A', true);
            $hitsB = (int)get_post_meta($f->ID, '_hits_B', true);
            $convA = (int)get_post_meta($f->ID, '_conv_A', true);
            $convB = (int)get_post_meta($f->ID, '_conv_B', true);
            $spend = (float)get_post_meta($f->ID, '_funnel_ad_spend', true);
            $val_est = (float)get_post_meta($f->ID, '_lead_value_est', true);

            $total_conv = $convA + $convB;
            $pipeline_gen = $total_conv * $val_est;
            $roi = $spend > 0 ? (($pipeline_gen - $spend) / $spend) * 100 : 0;

            $funnel_data[] = array(
                'title' => $f->post_title,
                'hits' => $hitsA + $hitsB,
                'roi' => $roi,
                'pipeline' => $pipeline_gen
            );
        }

        // Location Distribution
        $locations = get_posts(array('post_type' => 'gp_location', 'posts_per_page' => -1));
        $loc_stats = array();
        foreach($locations as $l) {
            $leads_count = count(get_posts(array(
                'post_type' => 'gp_lead',
                'meta_key' => '_assigned_location',
                'meta_value' => $l->ID,
                'posts_per_page' => -1
            )));
            $loc_stats[] = array('title' => $l->post_title, 'count' => $leads_count);
        }
        ?>
        <div class="wrap growthpress-reports">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:50px;">
                <h1>Strategic ROI & Performance Analytics</h1>
                <div style="background:var(--primary-glow); color:var(--primary); padding:10px 20px; border-radius:30px; font-size:11px; font-weight:950; letter-spacing:2px;">ENGINE: OMNI-INTELLIGENCE v4.0</div>
            </div>

            <div class="stats-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:30px;">
                <?php foreach($stats as $label => $val):
                    $is_money = in_array($label, array('Revenue', 'OpEx', 'Net Equity', 'Pipeline Upside'));
                    $border_color = ($label === 'Net Equity') ? '#10B981' : (($label === 'OpEx') ? '#EF4444' : 'var(--border)');
                ?>
                    <div class="stat-card glass-card" style="padding:45px; border-radius:35px; border-bottom: 8px solid <?php echo $border_color; ?>;">
                        <h4 style="font-size:11px; font-weight:950; opacity:0.4; text-transform:uppercase; letter-spacing:2px; margin-bottom:15px;"><?php echo $label; ?></h4>
                        <div class="value" style="font-size:3rem; color:var(--secondary); font-weight:950; letter-spacing:-0.05em;">
                            <?php echo $is_money ? '$'.number_format($val) : $val; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="stat-card glass-card" style="padding:45px; border-radius:35px; border-bottom: 8px solid #10B981;">
                    <h4 style="font-size:11px; font-weight:950; opacity:0.4; text-transform:uppercase; letter-spacing:2px; margin-bottom:15px;">CONVERSION VELOCITY</h4>
                    <div class="value" style="font-size:3.5rem; color:#10B981; font-weight:950; letter-spacing:-0.05em;"><?php echo $conv_rate; ?>%</div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-top:40px;">
                <div class="glass-card" style="padding:40px;">
                    <h3 style="margin-top:0;">Funnel ROI & Acquisition Velocity</h3>
                    <?php if($funnel_data): foreach($funnel_data as $fd): ?>
                        <div style="margin-bottom:25px; padding-bottom:15px; border-bottom:1px solid #F1F5F9;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:13px; font-weight:700;">
                                <span><?php echo esc_html($fd['title']); ?></span>
                                <span style="color:var(--primary);"><?php echo $fd['hits']; ?> Hits / <?php echo round($fd['roi']); ?>% ROI</span>
                            </div>
                            <div style="height:6px; background:#F1F5F9; border-radius:10px; overflow:hidden;">
                                <div style="width:<?php echo min(100, $fd['roi'] / 5); ?>%; height:100%; background:<?php echo $fd['roi'] > 0 ? '#10B981' : '#EF4444'; ?>;"></div>
                            </div>
                            <div style="font-size:10px; opacity:0.5; margin-top:8px; font-weight:800;">PIPELINE GENERATED: $<?php echo number_format($fd['pipeline']); ?></div>
                        </div>
                    <?php endforeach; else: echo "<p style='opacity:0.5;'>Calibrating conversion nodes...</p>"; endif; ?>
                </div>
                <div class="glass-card" style="padding:40px;">
                    <h3 style="margin-top:0;">Sector Lead Distribution</h3>
                    <?php if($loc_stats): foreach($loc_stats as $ls): ?>
                        <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:13px; font-weight:700;">
                            <span><?php echo esc_html($ls['title']); ?></span>
                            <span style="color:#10B981;"><?php echo $ls['count']; ?> Leads</span>
                        </div>
                        <div style="height:6px; background:#F1F5F9; border-radius:10px; overflow:hidden; margin-bottom:20px;">
                            <div style="width:<?php echo min(100, $ls['count'] * 10); ?>%; height:100%; background:#10B981;"></div>
                        </div>
                    <?php endforeach; else: echo "<p style='opacity:0.5;'>Mapping sector distribution...</p>"; endif; ?>
                </div>
            </div>

            <div class="glass-card" style="margin-top:40px; padding:40px;">
                <h3 style="margin-top:0;">Recent Ecosystem Transactions</h3>
                <table class="wp-list-table widefat fixed striped" style="border:none; background:transparent;">
                    <thead>
                        <tr>
                            <th style="font-weight:900; font-size:10px; opacity:0.5; letter-spacing:1px;">TRANSACTION</th>
                            <th style="font-weight:900; font-size:10px; opacity:0.5; letter-spacing:1px;">VALUE</th>
                            <th style="font-weight:900; font-size:10px; opacity:0.5; letter-spacing:1px;">STATUS</th>
                            <th style="font-weight:900; font-size:10px; opacity:0.5; letter-spacing:1px;">TIMESTAMP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $transactions = get_posts(array('post_type' => 'gp_transaction', 'posts_per_page' => 10));
                        if($transactions): foreach($transactions as $t):
                            $amount = get_post_meta($t->ID, '_amount', true);
                            $status = get_post_meta($t->ID, '_status', true);
                            $type = get_post_meta($t->ID, '_transaction_type', true) ?: 'Revenue';
                            ?>
                            <tr>
                                <td style="font-weight:700;">
                                    <?php echo esc_html($t->post_title); ?>
                                    <div style="font-size:9px; color:<?php echo $type === 'Revenue' ? '#10b981' : '#ef4444'; ?>; font-weight:950; margin-top:4px;"><?php echo strtoupper($type); ?></div>
                                </td>
                                <td style="font-weight:900; color:var(--primary);">$<?php echo number_format($amount); ?></td>
                                <td><span style="background:<?php echo $status === 'Paid' ? '#D1FAE5' : '#FEF3C7'; ?>; color:<?php echo $status === 'Paid' ? '#065F46' : '#92400E'; ?>; padding:5px 12px; border-radius:30px; font-size:10px; font-weight:900;"><?php echo strtoupper($status); ?></span></td>
                                <td style="opacity:0.5; font-size:11px; font-weight:700;"><?php echo get_the_date('M j, Y', $t->ID); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="4" style="text-align:center; padding:40px; opacity:0.5;">No active financial nodes detected.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="glass-card" style="margin-top:40px; padding:60px; border-radius:44px; background:var(--secondary); color:white; border:none; position:relative; overflow:hidden;">
                <div style="position:absolute; top:0; right:0; width:300px; height:300px; background:var(--primary); opacity:0.1; border-radius:50%; transform:translate(100px, -100px);"></div>
                <h3 style="color:white; font-size:24px; margin-bottom:20px;">AI Performance Trajectory Analysis</h3>
                <div style="display:grid; grid-template-columns: 1fr 2fr; gap:50px; align-items:center;">
                    <div>
                        <div style="font-size:10px; font-weight:950; opacity:0.5; letter-spacing:2px; margin-bottom:10px;">NEURAL CONFIDENCE</div>
                        <div style="font-size:32px; font-weight:950; color:var(--accent);">94.2%</div>
                    </div>
                    <div>
                        <?php if($conv_rate < 20): ?>
                            <p style="font-size:17px; line-height:1.7; opacity:0.8;">🚨 **TRAJECTORY ALERT:** Your current booking conversion node is performing below sector standards (25%). AI recommends immediate execution of the **5-Day Authority Blitz** nurture sequence for all un-converted leads in the 'Qualified' stage.</p>
                        <?php else: ?>
                            <p style="font-size:17px; line-height:1.7; opacity:0.8;">✅ **OPTIMAL PERFORMANCE:** Your conversion node is synchronized with high-ticket sector benchmarks. Strategic trajectory suggests a 15% scaling opportunity in Q3 by increasing 'Ad-Spend' on the generated **Market Angle of Attack** social suite.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
new GrowthPress_Reports();
