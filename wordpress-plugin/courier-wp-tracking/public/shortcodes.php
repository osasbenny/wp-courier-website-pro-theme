<?php
/**
 * Public Shortcodes
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Tracking form shortcode
 */
function courier_tracking_form_shortcode() {
    ob_start();
    ?>
    <div class="courier-tracking-form">
        <form id="tracking-form" method="post">
            <div class="form-group">
                <label for="tracking-number"><?php esc_html_e( 'Tracking Number', 'courier-tracking' ); ?></label>
                <input type="text" id="tracking-number" name="tracking_number" placeholder="Enter your tracking number" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Track', 'courier-tracking' ); ?></button>
        </form>
        <div id="tracking-results"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'courier_tracking_form', 'courier_tracking_form_shortcode' );

/**
 * Calculator shortcode
 */
function courier_calculator_shortcode() {
    ob_start();
    ?>
    <div class="courier-calculator">
        <form id="calculator-form" method="post">
            <div class="form-group">
                <label for="weight"><?php esc_html_e( 'Weight (kg)', 'courier-tracking' ); ?></label>
                <input type="number" id="weight" name="weight" step="0.1" placeholder="Enter weight" required>
            </div>

            <div class="form-group">
                <label for="zone"><?php esc_html_e( 'Delivery Zone', 'courier-tracking' ); ?></label>
                <select id="zone" name="zone" required>
                    <option value="">Select Zone</option>
                    <option value="local">Local</option>
                    <option value="regional">Regional</option>
                    <option value="national">National</option>
                    <option value="international">International</option>
                </select>
            </div>

            <div class="form-group">
                <label for="service-type"><?php esc_html_e( 'Service Type', 'courier-tracking' ); ?></label>
                <select id="service-type" name="service_type" required>
                    <option value="">Select Service</option>
                    <option value="standard">Standard (5 days)</option>
                    <option value="express">Express (2 days)</option>
                    <option value="overnight">Overnight</option>
                    <option value="international">International</option>
                </select>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="insurance" name="insurance"> 
                    <?php esc_html_e( 'Add Insurance (2%)', 'courier-tracking' ); ?>
                </label>
            </div>

            <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Calculate Rate', 'courier-tracking' ); ?></button>
        </form>
        <div id="rate-result"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'courier_calculator', 'courier_calculator_shortcode' );

/**
 * Booking form shortcode
 */
function courier_booking_form_shortcode() {
    ob_start();
    ?>
    <div class="courier-booking-form">
        <form id="booking-form" method="post">
            <div class="form-group">
                <label for="full-name"><?php esc_html_e( 'Full Name', 'courier-tracking' ); ?></label>
                <input type="text" id="full-name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="email"><?php esc_html_e( 'Email', 'courier-tracking' ); ?></label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone"><?php esc_html_e( 'Phone', 'courier-tracking' ); ?></label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="origin"><?php esc_html_e( 'Origin Address', 'courier-tracking' ); ?></label>
                <textarea id="origin" name="origin" required></textarea>
            </div>

            <div class="form-group">
                <label for="destination"><?php esc_html_e( 'Destination Address', 'courier-tracking' ); ?></label>
                <textarea id="destination" name="destination" required></textarea>
            </div>

            <div class="form-group">
                <label for="weight"><?php esc_html_e( 'Weight (kg)', 'courier-tracking' ); ?></label>
                <input type="number" id="weight" name="weight" step="0.1" required>
            </div>

            <div class="form-group">
                <label for="service-type"><?php esc_html_e( 'Service Type', 'courier-tracking' ); ?></label>
                <select id="service-type" name="service_type" required>
                    <option value="">Select Service</option>
                    <option value="standard">Standard</option>
                    <option value="express">Express</option>
                    <option value="overnight">Overnight</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pickup-date"><?php esc_html_e( 'Pickup Date', 'courier-tracking' ); ?></label>
                <input type="date" id="pickup-date" name="pickup_date" required>
            </div>

            <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Book Now', 'courier-tracking' ); ?></button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'courier_booking_form', 'courier_booking_form_shortcode' );

/**
 * Customer dashboard shortcode
 */
function courier_customer_dashboard_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '<p>' . esc_html__( 'Please log in to view your dashboard.', 'courier-tracking' ) . '</p>';
    }

    ob_start();
    $customer_id = get_current_user_id();
    $dashboard = new Courier_Tracking_Dashboard();
    $stats = $dashboard->get_dashboard_stats( $customer_id );
    $booking = new Courier_Tracking_Booking();
    $bookings = $booking->get_user_bookings( $customer_id );
    ?>
    <div class="courier-customer-dashboard">
        <h2><?php esc_html_e( 'My Dashboard', 'courier-tracking' ); ?></h2>

        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?php echo intval( $stats['total_shipments'] ); ?></h3>
                <p><?php esc_html_e( 'Total Shipments', 'courier-tracking' ); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo intval( $stats['pending_shipments'] ); ?></h3>
                <p><?php esc_html_e( 'Pending', 'courier-tracking' ); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo intval( $stats['delivered'] ); ?></h3>
                <p><?php esc_html_e( 'Delivered', 'courier-tracking' ); ?></p>
            </div>
            <div class="stat-card">
                <h3>$<?php echo number_format( floatval( $stats['total_spent'] ), 2 ); ?></h3>
                <p><?php esc_html_e( 'Total Spent', 'courier-tracking' ); ?></p>
            </div>
        </div>

        <h3><?php esc_html_e( 'Recent Bookings', 'courier-tracking' ); ?></h3>
        <table class="bookings-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'ID', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'From', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'To', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Cost', 'courier-tracking' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'courier-tracking' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $bookings as $booking_item ) : ?>
                    <tr>
                        <td><?php echo intval( $booking_item->id ); ?></td>
                        <td><?php echo esc_html( substr( $booking_item->origin_address, 0, 20 ) ); ?></td>
                        <td><?php echo esc_html( substr( $booking_item->destination_address, 0, 20 ) ); ?></td>
                        <td><span class="status-badge status-<?php echo esc_attr( $booking_item->status ); ?>"><?php echo esc_html( ucfirst( $booking_item->status ) ); ?></span></td>
                        <td>$<?php echo number_format( floatval( $booking_item->total_cost ), 2 ); ?></td>
                        <td><?php echo esc_html( date( 'M d, Y', strtotime( $booking_item->created_at ) ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'courier_customer_dashboard', 'courier_customer_dashboard_shortcode' );
