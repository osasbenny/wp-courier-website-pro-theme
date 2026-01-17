<?php
/**
 * Plugin Name: Courier Tracking & Management
 * Plugin URI: https://example.com/courier-tracking
 * Description: Complete courier management system with tracking, calculator, booking, and payment integration
 * Version: 1.0.0
 * Author: Courier Solutions
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: courier-tracking
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'COURIER_TRACKING_VERSION', '1.0.0' );
define( 'COURIER_TRACKING_PATH', plugin_dir_path( __FILE__ ) );
define( 'COURIER_TRACKING_URL', plugin_dir_url( __FILE__ ) );
define( 'COURIER_TRACKING_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load plugin text domain
 */
function courier_tracking_load_textdomain() {
    load_plugin_textdomain( 'courier-tracking', false, dirname( COURIER_TRACKING_BASENAME ) . '/languages' );
}
add_action( 'plugins_loaded', 'courier_tracking_load_textdomain' );

/**
 * Plugin activation hook
 */
function courier_tracking_activate() {
    require_once COURIER_TRACKING_PATH . 'includes/class-installer.php';
    Courier_Tracking_Installer::install();
}
register_activation_hook( __FILE__, 'courier_tracking_activate' );

/**
 * Plugin deactivation hook
 */
function courier_tracking_deactivate() {
    // Cleanup if needed
}
register_deactivation_hook( __FILE__, 'courier_tracking_deactivate' );

/**
 * Include required files
 */
require_once COURIER_TRACKING_PATH . 'includes/class-tracking.php';
require_once COURIER_TRACKING_PATH . 'includes/class-calculator.php';
require_once COURIER_TRACKING_PATH . 'includes/class-booking.php';
require_once COURIER_TRACKING_PATH . 'includes/class-payment.php';
require_once COURIER_TRACKING_PATH . 'includes/class-dashboard.php';

/**
 * Initialize plugin
 */
function courier_tracking_init() {
    // Initialize classes
    new Courier_Tracking();
    new Courier_Tracking_Calculator();
    new Courier_Tracking_Booking();
    new Courier_Tracking_Payment();
    new Courier_Tracking_Dashboard();

    // Load admin functionality
    if ( is_admin() ) {
        require_once COURIER_TRACKING_PATH . 'admin/admin-menu.php';
        require_once COURIER_TRACKING_PATH . 'admin/admin-pages.php';
    }

    // Load public functionality
    if ( ! is_admin() ) {
        require_once COURIER_TRACKING_PATH . 'public/shortcodes.php';
        require_once COURIER_TRACKING_PATH . 'public/frontend-pages.php';
    }
}
add_action( 'plugins_loaded', 'courier_tracking_init' );

/**
 * Enqueue plugin styles and scripts
 */
function courier_tracking_enqueue_assets() {
    // Admin styles and scripts
    if ( is_admin() ) {
        wp_enqueue_style( 'courier-tracking-admin', COURIER_TRACKING_URL . 'admin/css/admin-style.css', array(), COURIER_TRACKING_VERSION );
        wp_enqueue_script( 'courier-tracking-admin', COURIER_TRACKING_URL . 'admin/js/admin-script.js', array( 'jquery' ), COURIER_TRACKING_VERSION, true );
    }

    // Frontend styles and scripts
    wp_enqueue_style( 'courier-tracking-public', COURIER_TRACKING_URL . 'public/css/public-style.css', array(), COURIER_TRACKING_VERSION );
    wp_enqueue_script( 'courier-tracking-public', COURIER_TRACKING_URL . 'public/js/public-script.js', array( 'jquery' ), COURIER_TRACKING_VERSION, true );

    // Localize script
    wp_localize_script( 'courier-tracking-public', 'courierTrackingData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'courier_tracking_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'courier_tracking_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'courier_tracking_enqueue_assets' );

/**
 * Register REST API endpoints
 */
function courier_tracking_register_rest_routes() {
    // Tracking endpoints
    register_rest_route( 'courier/v1', '/track/(?P<tracking_number>[\w-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'courier_tracking_get_tracking',
        'permission_callback' => '__return_true',
    ) );

    register_rest_route( 'courier/v1', '/track', array(
        'methods'             => 'POST',
        'callback'            => 'courier_tracking_create_tracking',
        'permission_callback' => 'courier_tracking_check_admin',
    ) );

    // Booking endpoints
    register_rest_route( 'courier/v1', '/bookings', array(
        'methods'             => 'GET',
        'callback'            => 'courier_tracking_get_bookings',
        'permission_callback' => 'courier_tracking_check_user',
    ) );

    register_rest_route( 'courier/v1', '/bookings', array(
        'methods'             => 'POST',
        'callback'            => 'courier_tracking_create_booking_rest',
        'permission_callback' => '__return_true',
    ) );

    // Calculator endpoint
    register_rest_route( 'courier/v1', '/calculate-rate', array(
        'methods'             => 'POST',
        'callback'            => 'courier_tracking_calculate_rate_rest',
        'permission_callback' => '__return_true',
    ) );

    // Payment endpoint
    register_rest_route( 'courier/v1', '/payment', array(
        'methods'             => 'POST',
        'callback'            => 'courier_tracking_process_payment_rest',
        'permission_callback' => '__return_true',
    ) );
}
add_action( 'rest_api_init', 'courier_tracking_register_rest_routes' );

/**
 * Check if user is admin
 */
function courier_tracking_check_admin() {
    return current_user_can( 'manage_options' );
}

/**
 * Check if user is logged in
 */
function courier_tracking_check_user() {
    return is_user_logged_in();
}

/**
 * Get tracking info REST callback
 */
function courier_tracking_get_tracking( $request ) {
    $tracking_number = $request->get_param( 'tracking_number' );
    $tracking = new Courier_Tracking();
    $result = $tracking->get_tracking_by_number( $tracking_number );

    if ( $result ) {
        return rest_ensure_response( array(
            'success' => true,
            'data'    => $result,
        ) );
    }

    return rest_ensure_response( array(
        'success' => false,
        'message' => 'Tracking number not found',
    ) );
}

/**
 * Create tracking REST callback
 */
function courier_tracking_create_tracking( $request ) {
    $params = $request->get_json_params();
    $tracking = new Courier_Tracking();
    $result = $tracking->create_tracking( $params );

    if ( $result ) {
        return rest_ensure_response( array(
            'success' => true,
            'data'    => $result,
        ) );
    }

    return rest_ensure_response( array(
        'success' => false,
        'message' => 'Error creating tracking',
    ) );
}

/**
 * Get bookings REST callback
 */
function courier_tracking_get_bookings( $request ) {
    $booking = new Courier_Tracking_Booking();
    $results = $booking->get_user_bookings( get_current_user_id() );

    return rest_ensure_response( array(
        'success' => true,
        'data'    => $results,
    ) );
}

/**
 * Create booking REST callback
 */
function courier_tracking_create_booking_rest( $request ) {
    $params = $request->get_json_params();
    $booking = new Courier_Tracking_Booking();
    $result = $booking->create_booking( $params );

    if ( $result ) {
        return rest_ensure_response( array(
            'success' => true,
            'data'    => $result,
        ) );
    }

    return rest_ensure_response( array(
        'success' => false,
        'message' => 'Error creating booking',
    ) );
}

/**
 * Calculate rate REST callback
 */
function courier_tracking_calculate_rate_rest( $request ) {
    $params = $request->get_json_params();
    $calculator = new Courier_Tracking_Calculator();
    $result = $calculator->calculate_rate( $params );

    if ( $result ) {
        return rest_ensure_response( array(
            'success' => true,
            'data'    => $result,
        ) );
    }

    return rest_ensure_response( array(
        'success' => false,
        'message' => 'Error calculating rate',
    ) );
}

/**
 * Process payment REST callback
 */
function courier_tracking_process_payment_rest( $request ) {
    $params = $request->get_json_params();
    $payment = new Courier_Tracking_Payment();
    $result = $payment->process_payment( $params );

    if ( $result ) {
        return rest_ensure_response( array(
            'success' => true,
            'data'    => $result,
        ) );
    }

    return rest_ensure_response( array(
        'success' => false,
        'message' => 'Error processing payment',
    ) );
}
