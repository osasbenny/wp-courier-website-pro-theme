<?php
/**
 * Calculator Class
 *
 * @package Courier_Tracking
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Courier_Tracking_Calculator class
 */
class Courier_Tracking_Calculator {

    /**
     * Base rates by service type
     */
    private $base_rates = array(
        'standard'  => 10,
        'express'   => 20,
        'overnight' => 35,
        'international' => 50,
    );

    /**
     * Zone rates
     */
    private $zone_rates = array(
        'local'     => 1.0,
        'regional'  => 1.5,
        'national'  => 2.0,
        'international' => 3.0,
    );

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_ajax_calculate_shipping_rate', array( $this, 'ajax_calculate_rate' ) );
        add_action( 'wp_ajax_nopriv_calculate_shipping_rate', array( $this, 'ajax_calculate_rate' ) );
    }

    /**
     * Calculate shipping rate
     */
    public function calculate_rate( $data ) {
        $weight = isset( $data['weight'] ) ? floatval( $data['weight'] ) : 0;
        $zone = isset( $data['zone'] ) ? sanitize_text_field( $data['zone'] ) : 'local';
        $service_type = isset( $data['service_type'] ) ? sanitize_text_field( $data['service_type'] ) : 'standard';
        $insurance = isset( $data['insurance'] ) ? intval( $data['insurance'] ) : 0;

        if ( ! $weight || ! $zone || ! $service_type ) {
            return false;
        }

        // Get base rate
        $base_rate = isset( $this->base_rates[ $service_type ] ) ? $this->base_rates[ $service_type ] : 10;

        // Apply zone multiplier
        $zone_multiplier = isset( $this->zone_rates[ $zone ] ) ? $this->zone_rates[ $zone ] : 1.0;
        $zone_rate = $base_rate * $zone_multiplier;

        // Calculate weight surcharge (0.5 per kg over 5kg)
        $weight_surcharge = 0;
        if ( $weight > 5 ) {
            $weight_surcharge = ( $weight - 5 ) * 0.5;
        }

        // Insurance cost (2% of subtotal)
        $subtotal = $zone_rate + $weight_surcharge;
        $insurance_cost = $insurance ? $subtotal * 0.02 : 0;

        // Apply discount if applicable
        $discount = $this->get_discount( $weight, $zone );

        // Calculate total
        $total = $subtotal + $insurance_cost - $discount;

        // Get estimated delivery
        $estimated_delivery = $this->get_estimated_delivery( $service_type, $zone );

        return array(
            'base_rate'           => $base_rate,
            'zone_rate'           => $zone_rate,
            'weight_surcharge'    => $weight_surcharge,
            'insurance_cost'      => $insurance_cost,
            'discount'            => $discount,
            'total'               => $total,
            'service_type'        => $service_type,
            'zone'                => $zone,
            'weight'              => $weight,
            'estimated_delivery'  => $estimated_delivery,
        );
    }

    /**
     * Get discount
     */
    private function get_discount( $weight, $zone ) {
        $discount = 0;

        // Bulk discount (5% for orders over 50kg)
        if ( $weight > 50 ) {
            $discount += 2.5;
        }

        // Zone discount (5% for local)
        if ( $zone === 'local' ) {
            $discount += 0.5;
        }

        return $discount;
    }

    /**
     * Get estimated delivery
     */
    private function get_estimated_delivery( $service_type, $zone ) {
        $days = 5; // Default

        switch ( $service_type ) {
            case 'express':
                $days = 2;
                break;
            case 'overnight':
                $days = 1;
                break;
            case 'standard':
                $days = $zone === 'local' ? 2 : 5;
                break;
            case 'international':
                $days = 10;
                break;
        }

        $delivery_date = date( 'Y-m-d', strtotime( "+$days days" ) );
        return $delivery_date;
    }

    /**
     * AJAX calculate rate
     */
    public function ajax_calculate_rate() {
        check_ajax_referer( 'courier_nonce', 'nonce' );

        $data = array(
            'weight'       => isset( $_POST['weight'] ) ? sanitize_text_field( $_POST['weight'] ) : 0,
            'zone'         => isset( $_POST['zone'] ) ? sanitize_text_field( $_POST['zone'] ) : 'local',
            'service_type' => isset( $_POST['service_type'] ) ? sanitize_text_field( $_POST['service_type'] ) : 'standard',
            'insurance'    => isset( $_POST['insurance'] ) ? intval( $_POST['insurance'] ) : 0,
        );

        $result = $this->calculate_rate( $data );

        if ( $result ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( array( 'message' => 'Error calculating rate' ) );
        }
    }
}
