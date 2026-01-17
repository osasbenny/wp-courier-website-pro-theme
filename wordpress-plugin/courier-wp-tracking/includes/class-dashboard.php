<?php
/**
 * Dashboard Class
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Courier_Tracking_Dashboard class
 */
class Courier_Tracking_Dashboard {

    /**
     * Constructor
     */
    public function __construct() {
        // Dashboard functionality
    }

    /**
     * Get dashboard stats
     */
    public function get_dashboard_stats( $customer_id = null ) {
        global $wpdb;

        if ( ! $customer_id ) {
            $customer_id = get_current_user_id();
        }

        $stats = array(
            'total_shipments'   => 0,
            'pending_shipments' => 0,
            'delivered'         => 0,
            'total_spent'       => 0,
        );

        // Total shipments
        $stats['total_shipments'] = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}courier_bookings WHERE customer_id = %d",
            $customer_id
        ) );

        // Pending shipments
        $stats['pending_shipments'] = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}courier_bookings WHERE customer_id = %d AND status IN ('pending', 'confirmed')",
            $customer_id
        ) );

        // Delivered
        $stats['delivered'] = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}courier_bookings WHERE customer_id = %d AND status = 'delivered'",
            $customer_id
        ) );

        // Total spent
        $stats['total_spent'] = $wpdb->get_var( $wpdb->prepare(
            "SELECT SUM(total_cost) FROM {$wpdb->prefix}courier_bookings WHERE customer_id = %d",
            $customer_id
        ) ) ?? 0;

        return $stats;
    }

    /**
     * Get admin dashboard stats
     */
    public function get_admin_stats() {
        global $wpdb;

        $stats = array(
            'total_shipments'   => 0,
            'pending_shipments' => 0,
            'delivered'         => 0,
            'total_revenue'     => 0,
            'total_customers'   => 0,
        );

        // Total shipments
        $stats['total_shipments'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}courier_bookings"
        );

        // Pending shipments
        $stats['pending_shipments'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}courier_bookings WHERE status IN ('pending', 'confirmed')"
        );

        // Delivered
        $stats['delivered'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}courier_bookings WHERE status = 'delivered'"
        );

        // Total revenue
        $stats['total_revenue'] = $wpdb->get_var(
            "SELECT SUM(total_cost) FROM {$wpdb->prefix}courier_bookings"
        ) ?? 0;

        // Total customers
        $stats['total_customers'] = $wpdb->get_var(
            "SELECT COUNT(DISTINCT customer_id) FROM {$wpdb->prefix}courier_bookings"
        );

        return $stats;
    }

    /**
     * Get recent bookings
     */
    public function get_recent_bookings( $limit = 10 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_bookings ORDER BY created_at DESC LIMIT %d",
            intval( $limit )
        ) );
    }

    /**
     * Get recent tracking updates
     */
    public function get_recent_tracking_updates( $limit = 10 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_status_history ORDER BY timestamp DESC LIMIT %d",
            intval( $limit )
        ) );
    }

    /**
     * Get revenue by month
     */
    public function get_revenue_by_month( $months = 12 ) {
        global $wpdb;

        $results = $wpdb->get_results( $wpdb->prepare(
            "SELECT DATE_FORMAT(created_at, '%%Y-%%m') as month, SUM(total_cost) as revenue
             FROM {$wpdb->prefix}courier_bookings
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL %d MONTH)
             GROUP BY DATE_FORMAT(created_at, '%%Y-%%m')
             ORDER BY month ASC",
            intval( $months )
        ) );

        return $results;
    }

    /**
     * Get shipment status distribution
     */
    public function get_shipment_status_distribution() {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT status, COUNT(*) as count
             FROM {$wpdb->prefix}courier_bookings
             GROUP BY status"
        );
    }

    /**
     * Get top customers
     */
    public function get_top_customers( $limit = 10 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT customer_id, COUNT(*) as shipment_count, SUM(total_cost) as total_spent
             FROM {$wpdb->prefix}courier_bookings
             GROUP BY customer_id
             ORDER BY total_spent DESC
             LIMIT %d",
            intval( $limit )
        ) );
    }

    /**
     * Export data to CSV
     */
    public function export_bookings_csv() {
        global $wpdb;

        $bookings = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}courier_bookings ORDER BY created_at DESC"
        );

        $filename = 'bookings_' . date( 'Y-m-d_H-i-s' ) . '.csv';
        $file = fopen( 'php://output', 'w' );

        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        // Header row
        fputcsv( $file, array(
            'ID',
            'Customer ID',
            'Full Name',
            'Email',
            'Phone',
            'Origin',
            'Destination',
            'Weight',
            'Service Type',
            'Status',
            'Total Cost',
            'Created At',
        ) );

        // Data rows
        foreach ( $bookings as $booking ) {
            fputcsv( $file, array(
                $booking->id,
                $booking->customer_id,
                $booking->full_name,
                $booking->email,
                $booking->phone,
                $booking->origin_address,
                $booking->destination_address,
                $booking->weight,
                $booking->service_type,
                $booking->status,
                $booking->total_cost,
                $booking->created_at,
            ) );
        }

        fclose( $file );
        exit;
    }
}
