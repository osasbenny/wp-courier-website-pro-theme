<?php
/**
 * Booking Class
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Courier_Tracking_Booking class
 */
class Courier_Tracking_Booking {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_ajax_create_booking', array( $this, 'ajax_create_booking' ) );
        add_action( 'wp_ajax_nopriv_create_booking', array( $this, 'ajax_create_booking' ) );
    }

    /**
     * Create booking
     */
    public function create_booking( $data ) {
        global $wpdb;

        $customer_id = isset( $data['customer_id'] ) ? intval( $data['customer_id'] ) : get_current_user_id();

        $result = $wpdb->insert(
            $wpdb->prefix . 'courier_bookings',
            array(
                'customer_id'           => $customer_id,
                'full_name'             => isset( $data['full_name'] ) ? sanitize_text_field( $data['full_name'] ) : '',
                'email'                 => isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '',
                'phone'                 => isset( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : '',
                'origin_address'        => isset( $data['origin'] ) ? sanitize_text_field( $data['origin'] ) : '',
                'destination_address'   => isset( $data['destination'] ) ? sanitize_text_field( $data['destination'] ) : '',
                'weight'                => isset( $data['weight'] ) ? floatval( $data['weight'] ) : 0,
                'service_type'          => isset( $data['service_type'] ) ? sanitize_text_field( $data['service_type'] ) : '',
                'pickup_date'           => isset( $data['pickup_date'] ) ? sanitize_text_field( $data['pickup_date'] ) : date( 'Y-m-d' ),
                'delivery_date'         => isset( $data['delivery_date'] ) ? sanitize_text_field( $data['delivery_date'] ) : null,
                'status'                => 'pending',
                'total_cost'            => isset( $data['total_cost'] ) ? floatval( $data['total_cost'] ) : 0,
            ),
            array( '%d', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%f' )
        );

        if ( $result ) {
            $booking_id = $wpdb->insert_id;

            // Send confirmation email
            $this->send_booking_confirmation( $booking_id );

            return array(
                'booking_id' => $booking_id,
            );
        }

        return false;
    }

    /**
     * Get user bookings
     */
    public function get_user_bookings( $customer_id, $limit = 50, $offset = 0 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_bookings WHERE customer_id = %d ORDER BY created_at DESC LIMIT %d OFFSET %d",
            intval( $customer_id ),
            intval( $limit ),
            intval( $offset )
        ) );
    }

    /**
     * Get booking by ID
     */
    public function get_booking( $booking_id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_bookings WHERE id = %d",
            intval( $booking_id )
        ) );
    }

    /**
     * Update booking status
     */
    public function update_booking_status( $booking_id, $status ) {
        global $wpdb;

        return $wpdb->update(
            $wpdb->prefix . 'courier_bookings',
            array( 'status' => sanitize_text_field( $status ) ),
            array( 'id' => intval( $booking_id ) ),
            array( '%s' ),
            array( '%d' )
        );
    }

    /**
     * Send booking confirmation email
     */
    private function send_booking_confirmation( $booking_id ) {
        $booking = $this->get_booking( $booking_id );

        if ( ! $booking ) {
            return false;
        }

        $to = $booking->email;
        $subject = 'Booking Confirmation - ' . get_bloginfo( 'name' );

        $message = "
            <h2>Booking Confirmation</h2>
            <p>Thank you for your booking!</p>
            <h3>Booking Details:</h3>
            <ul>
                <li><strong>Booking ID:</strong> {$booking->id}</li>
                <li><strong>Name:</strong> {$booking->full_name}</li>
                <li><strong>Service:</strong> {$booking->service_type}</li>
                <li><strong>From:</strong> {$booking->origin_address}</li>
                <li><strong>To:</strong> {$booking->destination_address}</li>
                <li><strong>Weight:</strong> {$booking->weight} kg</li>
                <li><strong>Pickup Date:</strong> {$booking->pickup_date}</li>
                <li><strong>Total Cost:</strong> \${$booking->total_cost}</li>
            </ul>
            <p>We will contact you shortly to confirm the pickup.</p>
        ";

        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        return wp_mail( $to, $subject, $message, $headers );
    }

    /**
     * AJAX create booking
     */
    public function ajax_create_booking() {
        check_ajax_referer( 'courier_nonce', 'nonce' );

        $data = array(
            'customer_id'   => get_current_user_id(),
            'full_name'     => isset( $_POST['full_name'] ) ? sanitize_text_field( $_POST['full_name'] ) : '',
            'email'         => isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '',
            'phone'         => isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '',
            'origin'        => isset( $_POST['origin'] ) ? sanitize_text_field( $_POST['origin'] ) : '',
            'destination'   => isset( $_POST['destination'] ) ? sanitize_text_field( $_POST['destination'] ) : '',
            'weight'        => isset( $_POST['weight'] ) ? floatval( $_POST['weight'] ) : 0,
            'service_type'  => isset( $_POST['service_type'] ) ? sanitize_text_field( $_POST['service_type'] ) : '',
            'pickup_date'   => isset( $_POST['pickup_date'] ) ? sanitize_text_field( $_POST['pickup_date'] ) : date( 'Y-m-d' ),
            'total_cost'    => isset( $_POST['total_cost'] ) ? floatval( $_POST['total_cost'] ) : 0,
        );

        $result = $this->create_booking( $data );

        if ( $result ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( array( 'message' => 'Error creating booking' ) );
        }
    }
}
