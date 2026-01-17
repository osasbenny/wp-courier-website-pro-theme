<?php
/**
 * Plugin Installer Class
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Courier_Tracking_Installer class
 */
class Courier_Tracking_Installer {

    /**
     * Install plugin
     */
    public static function install() {
        self::create_tables();
        self::create_pages();
        self::set_default_options();
    }

    /**
     * Create database tables
     */
    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Tracking table
        $tracking_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}courier_tracking (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            tracking_number VARCHAR(50) UNIQUE NOT NULL,
            customer_id BIGINT(20) UNSIGNED NOT NULL,
            origin_address TEXT NOT NULL,
            destination_address TEXT NOT NULL,
            weight DECIMAL(10,2) NOT NULL,
            service_type VARCHAR(50) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            current_location TEXT,
            estimated_delivery DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_tracking_number (tracking_number),
            INDEX idx_customer_id (customer_id),
            INDEX idx_status (status)
        ) $charset_collate;";

        // Status history table
        $history_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}courier_status_history (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            tracking_id BIGINT(20) UNSIGNED NOT NULL,
            status VARCHAR(50) NOT NULL,
            location TEXT,
            notes TEXT,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_tracking_id (tracking_id),
            FOREIGN KEY (tracking_id) REFERENCES {$wpdb->prefix}courier_tracking(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Bookings table
        $bookings_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}courier_bookings (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            customer_id BIGINT(20) UNSIGNED NOT NULL,
            tracking_id BIGINT(20) UNSIGNED,
            full_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            origin_address TEXT NOT NULL,
            destination_address TEXT NOT NULL,
            weight DECIMAL(10,2) NOT NULL,
            service_type VARCHAR(50) NOT NULL,
            pickup_date DATE NOT NULL,
            delivery_date DATE,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            total_cost DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_customer_id (customer_id),
            INDEX idx_status (status)
        ) $charset_collate;";

        // Payments table
        $payments_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}courier_payments (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            booking_id BIGINT(20) UNSIGNED NOT NULL,
            customer_id BIGINT(20) UNSIGNED NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            stripe_transaction_id VARCHAR(100),
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_booking_id (booking_id),
            INDEX idx_customer_id (customer_id),
            INDEX idx_status (status)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $tracking_table );
        dbDelta( $history_table );
        dbDelta( $bookings_table );
        dbDelta( $payments_table );
    }

    /**
     * Create plugin pages
     */
    private static function create_pages() {
        $pages = array(
            array(
                'post_title'   => 'Track Shipment',
                'post_name'    => 'track',
                'post_content' => '[courier_tracking_form]',
            ),
            array(
                'post_title'   => 'Rate Calculator',
                'post_name'    => 'calculator',
                'post_content' => '[courier_calculator]',
            ),
            array(
                'post_title'   => 'Book Shipment',
                'post_name'    => 'booking',
                'post_content' => '[courier_booking_form]',
            ),
            array(
                'post_title'   => 'My Dashboard',
                'post_name'    => 'dashboard',
                'post_content' => '[courier_customer_dashboard]',
            ),
        );

        foreach ( $pages as $page ) {
            $existing = get_page_by_path( $page['post_name'] );
            if ( ! $existing ) {
                wp_insert_post( array(
                    'post_type'    => 'page',
                    'post_title'   => $page['post_title'],
                    'post_name'    => $page['post_name'],
                    'post_content' => $page['post_content'],
                    'post_status'  => 'publish',
                ) );
            }
        }
    }

    /**
     * Set default options
     */
    private static function set_default_options() {
        if ( ! get_option( 'courier_tracking_stripe_key' ) ) {
            update_option( 'courier_tracking_stripe_key', '' );
        }
        if ( ! get_option( 'courier_tracking_stripe_secret' ) ) {
            update_option( 'courier_tracking_stripe_secret', '' );
        }
        if ( ! get_option( 'courier_tracking_base_rate' ) ) {
            update_option( 'courier_tracking_base_rate', '10' );
        }
    }
}
