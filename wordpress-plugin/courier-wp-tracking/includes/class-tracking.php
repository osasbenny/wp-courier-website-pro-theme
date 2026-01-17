<?php
/**
 * Tracking Class
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Courier_Tracking class
 */
class Courier_Tracking {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_ajax_get_tracking_info', array( $this, 'ajax_get_tracking_info' ) );
        add_action( 'wp_ajax_nopriv_get_tracking_info', array( $this, 'ajax_get_tracking_info' ) );
    }

    /**
     * Get tracking by number
     */
    public function get_tracking_by_number( $tracking_number ) {
        global $wpdb;

        $tracking = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_tracking WHERE tracking_number = %s",
            $tracking_number
        ) );

        if ( ! $tracking ) {
            return false;
        }

        // Get status history
        $history = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_status_history WHERE tracking_id = %d ORDER BY timestamp DESC",
            $tracking->id
        ) );

        $tracking->history = $history;

        return $tracking;
    }

    /**
     * Create tracking record
     */
    public function create_tracking( $data ) {
        global $wpdb;

        $tracking_number = $this->generate_tracking_number();

        $result = $wpdb->insert(
            $wpdb->prefix . 'courier_tracking',
            array(
                'tracking_number'      => $tracking_number,
                'customer_id'          => isset( $data['customer_id'] ) ? intval( $data['customer_id'] ) : 0,
                'origin_address'       => isset( $data['origin_address'] ) ? sanitize_text_field( $data['origin_address'] ) : '',
                'destination_address'  => isset( $data['destination_address'] ) ? sanitize_text_field( $data['destination_address'] ) : '',
                'weight'               => isset( $data['weight'] ) ? floatval( $data['weight'] ) : 0,
                'service_type'         => isset( $data['service_type'] ) ? sanitize_text_field( $data['service_type'] ) : '',
                'status'               => 'pending',
                'current_location'     => isset( $data['current_location'] ) ? sanitize_text_field( $data['current_location'] ) : '',
                'estimated_delivery'   => isset( $data['estimated_delivery'] ) ? sanitize_text_field( $data['estimated_delivery'] ) : null,
            ),
            array( '%s', '%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s' )
        );

        if ( $result ) {
            return array(
                'id'                   => $wpdb->insert_id,
                'tracking_number'      => $tracking_number,
            );
        }

        return false;
    }

    /**
     * Update tracking status
     */
    public function update_tracking_status( $tracking_id, $status, $location = '', $notes = '' ) {
        global $wpdb;

        // Update tracking record
        $wpdb->update(
            $wpdb->prefix . 'courier_tracking',
            array(
                'status'            => sanitize_text_field( $status ),
                'current_location'  => sanitize_text_field( $location ),
            ),
            array( 'id' => intval( $tracking_id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );

        // Add to history
        $wpdb->insert(
            $wpdb->prefix . 'courier_status_history',
            array(
                'tracking_id' => intval( $tracking_id ),
                'status'      => sanitize_text_field( $status ),
                'location'    => sanitize_text_field( $location ),
                'notes'       => sanitize_text_field( $notes ),
            ),
            array( '%d', '%s', '%s', '%s' )
        );

        return true;
    }

    /**
     * Get all tracking records
     */
    public function get_all_tracking( $limit = 50, $offset = 0 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_tracking ORDER BY created_at DESC LIMIT %d OFFSET %d",
            intval( $limit ),
            intval( $offset )
        ) );
    }

    /**
     * Get tracking by customer
     */
    public function get_tracking_by_customer( $customer_id, $limit = 50, $offset = 0 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_tracking WHERE customer_id = %d ORDER BY created_at DESC LIMIT %d OFFSET %d",
            intval( $customer_id ),
            intval( $limit ),
            intval( $offset )
        ) );
    }

    /**
     * Generate unique tracking number
     */
    private function generate_tracking_number() {
        $prefix = 'CT';
        $timestamp = time();
        $random = wp_rand( 1000, 9999 );
        return $prefix . $timestamp . $random;
    }

    /**
     * AJAX get tracking info
     */
    public function ajax_get_tracking_info() {
        check_ajax_referer( 'courier_nonce', 'nonce' );

        $tracking_number = isset( $_POST['tracking_number'] ) ? sanitize_text_field( $_POST['tracking_number'] ) : '';

        if ( ! $tracking_number ) {
            wp_send_json_error( array( 'message' => 'Tracking number is required' ) );
        }

        $tracking = $this->get_tracking_by_number( $tracking_number );

        if ( ! $tracking ) {
            wp_send_json_error( array( 'message' => 'Tracking number not found' ) );
        }

        wp_send_json_success( $tracking );
    }
}
