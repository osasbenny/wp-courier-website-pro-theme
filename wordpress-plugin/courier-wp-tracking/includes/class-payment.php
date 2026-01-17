<?php
/**
 * Payment Class
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Courier_Tracking_Payment class
 */
class Courier_Tracking_Payment {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_ajax_process_payment', array( $this, 'ajax_process_payment' ) );
        add_action( 'wp_ajax_nopriv_process_payment', array( $this, 'ajax_process_payment' ) );
    }

    /**
     * Process payment
     */
    public function process_payment( $data ) {
        global $wpdb;

        $booking_id = isset( $data['booking_id'] ) ? intval( $data['booking_id'] ) : 0;
        $payment_method = isset( $data['payment_method'] ) ? sanitize_text_field( $data['payment_method'] ) : 'stripe';
        $stripe_token = isset( $data['stripe_token'] ) ? sanitize_text_field( $data['stripe_token'] ) : '';

        if ( ! $booking_id ) {
            return false;
        }

        // Get booking
        $booking = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_bookings WHERE id = %d",
            $booking_id
        ) );

        if ( ! $booking ) {
            return false;
        }

        // Process payment based on method
        if ( $payment_method === 'stripe' && $stripe_token ) {
            $transaction_id = $this->process_stripe_payment( $stripe_token, $booking->total_cost );
            if ( ! $transaction_id ) {
                return false;
            }
        } else {
            $transaction_id = 'manual_' . time();
        }

        // Create payment record
        $result = $wpdb->insert(
            $wpdb->prefix . 'courier_payments',
            array(
                'booking_id'            => $booking_id,
                'customer_id'           => $booking->customer_id,
                'amount'                => $booking->total_cost,
                'payment_method'        => $payment_method,
                'stripe_transaction_id' => $transaction_id,
                'status'                => 'completed',
            ),
            array( '%d', '%d', '%f', '%s', '%s', '%s' )
        );

        if ( $result ) {
            // Update booking status
            $wpdb->update(
                $wpdb->prefix . 'courier_bookings',
                array( 'status' => 'confirmed' ),
                array( 'id' => $booking_id ),
                array( '%s' ),
                array( '%d' )
            );

            // Create tracking record
            $tracking = new Courier_Tracking();
            $tracking_result = $tracking->create_tracking( array(
                'customer_id'           => $booking->customer_id,
                'origin_address'        => $booking->origin_address,
                'destination_address'   => $booking->destination_address,
                'weight'                => $booking->weight,
                'service_type'          => $booking->service_type,
                'current_location'      => 'Processing',
            ) );

            if ( $tracking_result ) {
                // Update booking with tracking ID
                $wpdb->update(
                    $wpdb->prefix . 'courier_bookings',
                    array( 'tracking_id' => $tracking_result['id'] ),
                    array( 'id' => $booking_id ),
                    array( '%d' ),
                    array( '%d' )
                );
            }

            // Send payment confirmation email
            $this->send_payment_confirmation( $booking_id, $transaction_id );

            return array(
                'payment_id'      => $wpdb->insert_id,
                'transaction_id'  => $transaction_id,
                'tracking_number' => isset( $tracking_result['tracking_number'] ) ? $tracking_result['tracking_number'] : '',
            );
        }

        return false;
    }

    /**
     * Process Stripe payment
     */
    private function process_stripe_payment( $token, $amount ) {
        // This would integrate with Stripe API
        // For now, return a mock transaction ID
        // In production, use Stripe PHP library
        return 'stripe_' . wp_generate_uuid4();
    }

    /**
     * Get payment by ID
     */
    public function get_payment( $payment_id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_payments WHERE id = %d",
            intval( $payment_id )
        ) );
    }

    /**
     * Get payments by booking
     */
    public function get_payments_by_booking( $booking_id ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_payments WHERE booking_id = %d ORDER BY created_at DESC",
            intval( $booking_id )
        ) );
    }

    /**
     * Get payments by customer
     */
    public function get_payments_by_customer( $customer_id, $limit = 50, $offset = 0 ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}courier_payments WHERE customer_id = %d ORDER BY created_at DESC LIMIT %d OFFSET %d",
            intval( $customer_id ),
            intval( $limit ),
            intval( $offset )
        ) );
    }

    /**
     * Send payment confirmation email
     */
    private function send_payment_confirmation( $booking_id, $transaction_id ) {
        $booking = new Courier_Tracking_Booking();
        $booking_data = $booking->get_booking( $booking_id );

        if ( ! $booking_data ) {
            return false;
        }

        $to = $booking_data->email;
        $subject = 'Payment Confirmation - ' . get_bloginfo( 'name' );

        $message = "
            <h2>Payment Confirmation</h2>
            <p>Thank you for your payment!</p>
            <h3>Payment Details:</h3>
            <ul>
                <li><strong>Transaction ID:</strong> {$transaction_id}</li>
                <li><strong>Amount:</strong> \${$booking_data->total_cost}</li>
                <li><strong>Status:</strong> Completed</li>
            </ul>
            <p>Your shipment will be picked up on {$booking_data->pickup_date}.</p>
        ";

        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        return wp_mail( $to, $subject, $message, $headers );
    }

    /**
     * AJAX process payment
     */
    public function ajax_process_payment() {
        check_ajax_referer( 'courier_nonce', 'nonce' );

        $data = array(
            'booking_id'    => isset( $_POST['booking_id'] ) ? intval( $_POST['booking_id'] ) : 0,
            'payment_method' => isset( $_POST['payment_method'] ) ? sanitize_text_field( $_POST['payment_method'] ) : 'stripe',
            'stripe_token'  => isset( $_POST['stripe_token'] ) ? sanitize_text_field( $_POST['stripe_token'] ) : '',
        );

        $result = $this->process_payment( $data );

        if ( $result ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( array( 'message' => 'Error processing payment' ) );
        }
    }
}
