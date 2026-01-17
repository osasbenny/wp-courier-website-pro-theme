<?php
/**
 * Admin Menu Setup
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add admin menu
 */
function courier_tracking_add_admin_menu() {
    add_menu_page(
        __( 'Courier Tracking', 'courier-tracking' ),
        __( 'Courier', 'courier-tracking' ),
        'manage_options',
        'courier-tracking',
        'courier_tracking_dashboard_page',
        'dashicons-location',
        30
    );

    add_submenu_page(
        'courier-tracking',
        __( 'Dashboard', 'courier-tracking' ),
        __( 'Dashboard', 'courier-tracking' ),
        'manage_options',
        'courier-tracking',
        'courier_tracking_dashboard_page'
    );

    add_submenu_page(
        'courier-tracking',
        __( 'Tracking Management', 'courier-tracking' ),
        __( 'Tracking', 'courier-tracking' ),
        'manage_options',
        'courier-tracking-management',
        'courier_tracking_management_page'
    );

    add_submenu_page(
        'courier-tracking',
        __( 'Bookings', 'courier-tracking' ),
        __( 'Bookings', 'courier-tracking' ),
        'manage_options',
        'courier-bookings',
        'courier_bookings_page'
    );

    add_submenu_page(
        'courier-tracking',
        __( 'Payments', 'courier-tracking' ),
        __( 'Payments', 'courier-tracking' ),
        'manage_options',
        'courier-payments',
        'courier_payments_page'
    );

    add_submenu_page(
        'courier-tracking',
        __( 'Reports', 'courier-tracking' ),
        __( 'Reports', 'courier-tracking' ),
        'manage_options',
        'courier-reports',
        'courier_reports_page'
    );

    add_submenu_page(
        'courier-tracking',
        __( 'Settings', 'courier-tracking' ),
        __( 'Settings', 'courier-tracking' ),
        'manage_options',
        'courier-settings',
        'courier_settings_page'
    );
}
add_action( 'admin_menu', 'courier_tracking_add_admin_menu' );

/**
 * Dashboard page
 */
function courier_tracking_dashboard_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'courier-tracking' ) );
    }

    $dashboard = new Courier_Tracking_Dashboard();
    $stats = $dashboard->get_admin_stats();
    $recent_bookings = $dashboard->get_recent_bookings( 5 );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Courier Dashboard', 'courier-tracking' ); ?></h1>

        <div class="dashboard-stats">
            <div class="stat-box">
                <h3><?php echo intval( $stats['total_shipments'] ); ?></h3>
                <p><?php esc_html_e( 'Total Shipments', 'courier-tracking' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo intval( $stats['pending_shipments'] ); ?></h3>
                <p><?php esc_html_e( 'Pending', 'courier-tracking' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo intval( $stats['delivered'] ); ?></h3>
                <p><?php esc_html_e( 'Delivered', 'courier-tracking' ); ?></p>
            </div>
            <div class="stat-box">
                <h3>$<?php echo number_format( floatval( $stats['total_revenue'] ), 2 ); ?></h3>
                <p><?php esc_html_e( 'Total Revenue', 'courier-tracking' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo intval( $stats['total_customers'] ); ?></h3>
                <p><?php esc_html_e( 'Total Customers', 'courier-tracking' ); ?></p>
            </div>
        </div>

        <h2><?php esc_html_e( 'Recent Bookings', 'courier-tracking' ); ?></h2>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'ID', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Customer', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Service', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Cost', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'courier-tracking' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $recent_bookings as $booking ) : ?>
                    <tr>
                        <td><?php echo intval( $booking->id ); ?></td>
                        <td><?php echo esc_html( $booking->full_name ); ?></td>
                        <td><?php echo esc_html( $booking->service_type ); ?></td>
                        <td><?php echo esc_html( ucfirst( $booking->status ) ); ?></td>
                        <td>$<?php echo number_format( floatval( $booking->total_cost ), 2 ); ?></td>
                        <td><?php echo esc_html( date( 'M d, Y', strtotime( $booking->created_at ) ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Tracking management page
 */
function courier_tracking_management_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'courier-tracking' ) );
    }

    $tracking = new Courier_Tracking();
    $all_tracking = $tracking->get_all_tracking( 50 );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Tracking Management', 'courier-tracking' ); ?></h1>

        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Tracking Number', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Origin', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Destination', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Location', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Actions', 'courier-tracking' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $all_tracking as $track ) : ?>
                    <tr>
                        <td><?php echo esc_html( $track->tracking_number ); ?></td>
                        <td><?php echo esc_html( substr( $track->origin_address, 0, 30 ) ); ?></td>
                        <td><?php echo esc_html( substr( $track->destination_address, 0, 30 ) ); ?></td>
                        <td><?php echo esc_html( ucfirst( $track->status ) ); ?></td>
                        <td><?php echo esc_html( $track->current_location ); ?></td>
                        <td>
                            <a href="#" class="button"><?php esc_html_e( 'Edit', 'courier-tracking' ); ?></a>
                            <a href="#" class="button button-link-delete"><?php esc_html_e( 'Delete', 'courier-tracking' ); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Bookings page
 */
function courier_bookings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'courier-tracking' ) );
    }

    $dashboard = new Courier_Tracking_Dashboard();
    $bookings = $dashboard->get_recent_bookings( 100 );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Bookings', 'courier-tracking' ); ?></h1>

        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'ID', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Customer', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Email', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Service', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Cost', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'courier-tracking' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $bookings as $booking ) : ?>
                    <tr>
                        <td><?php echo intval( $booking->id ); ?></td>
                        <td><?php echo esc_html( $booking->full_name ); ?></td>
                        <td><?php echo esc_html( $booking->email ); ?></td>
                        <td><?php echo esc_html( $booking->service_type ); ?></td>
                        <td><?php echo esc_html( ucfirst( $booking->status ) ); ?></td>
                        <td>$<?php echo number_format( floatval( $booking->total_cost ), 2 ); ?></td>
                        <td><?php echo esc_html( date( 'M d, Y', strtotime( $booking->created_at ) ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Payments page
 */
function courier_payments_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'courier-tracking' ) );
    }

    global $wpdb;
    $payments = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}courier_payments ORDER BY created_at DESC LIMIT 100" );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Payments', 'courier-tracking' ); ?></h1>

        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'ID', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Booking ID', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Amount', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Method', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'courier-tracking' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $payments as $payment ) : ?>
                    <tr>
                        <td><?php echo intval( $payment->id ); ?></td>
                        <td><?php echo intval( $payment->booking_id ); ?></td>
                        <td>$<?php echo number_format( floatval( $payment->amount ), 2 ); ?></td>
                        <td><?php echo esc_html( ucfirst( $payment->payment_method ) ); ?></td>
                        <td><?php echo esc_html( ucfirst( $payment->status ) ); ?></td>
                        <td><?php echo esc_html( date( 'M d, Y', strtotime( $payment->created_at ) ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Reports page
 */
function courier_reports_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'courier-tracking' ) );
    }

    $dashboard = new Courier_Tracking_Dashboard();
    $stats = $dashboard->get_admin_stats();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Reports', 'courier-tracking' ); ?></h1>

        <div class="postbox">
            <h2><?php esc_html_e( 'Overview', 'courier-tracking' ); ?></h2>
            <div class="inside">
                <p>
                    <strong><?php esc_html_e( 'Total Shipments:', 'courier-tracking' ); ?></strong>
                    <?php echo intval( $stats['total_shipments'] ); ?>
                </p>
                <p>
                    <strong><?php esc_html_e( 'Total Revenue:', 'courier-tracking' ); ?></strong>
                    $<?php echo number_format( floatval( $stats['total_revenue'] ), 2 ); ?>
                </p>
                <p>
                    <strong><?php esc_html_e( 'Total Customers:', 'courier-tracking' ); ?></strong>
                    <?php echo intval( $stats['total_customers'] ); ?>
                </p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Settings page
 */
function courier_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'courier-tracking' ) );
    }

    if ( isset( $_POST['submit'] ) ) {
        check_admin_referer( 'courier_settings_nonce' );
        update_option( 'courier_tracking_stripe_key', sanitize_text_field( $_POST['stripe_key'] ?? '' ) );
        update_option( 'courier_tracking_stripe_secret', sanitize_text_field( $_POST['stripe_secret'] ?? '' ) );
        update_option( 'courier_tracking_base_rate', floatval( $_POST['base_rate'] ?? 10 ) );
        echo '<div class="updated"><p>' . esc_html__( 'Settings saved.', 'courier-tracking' ) . '</p></div>';
    }

    $stripe_key = get_option( 'courier_tracking_stripe_key', '' );
    $stripe_secret = get_option( 'courier_tracking_stripe_secret', '' );
    $base_rate = get_option( 'courier_tracking_base_rate', '10' );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Courier Settings', 'courier-tracking' ); ?></h1>

        <form method="post">
            <?php wp_nonce_field( 'courier_settings_nonce' ); ?>

            <table class="form-table">
                <tr>
                    <th><label for="stripe_key"><?php esc_html_e( 'Stripe Publishable Key', 'courier-tracking' ); ?></label></th>
                    <td>
                        <input type="text" name="stripe_key" id="stripe_key" value="<?php echo esc_attr( $stripe_key ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th><label for="stripe_secret"><?php esc_html_e( 'Stripe Secret Key', 'courier-tracking' ); ?></label></th>
                    <td>
                        <input type="password" name="stripe_secret" id="stripe_secret" value="<?php echo esc_attr( $stripe_secret ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th><label for="base_rate"><?php esc_html_e( 'Base Shipping Rate ($)', 'courier-tracking' ); ?></label></th>
                    <td>
                        <input type="number" name="base_rate" id="base_rate" value="<?php echo esc_attr( $base_rate ); ?>" step="0.01" class="regular-text">
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
