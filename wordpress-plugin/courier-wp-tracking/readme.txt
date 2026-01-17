=== Courier Tracking & Management ===
Contributors: Courier Solutions
Tags: courier, tracking, shipping, booking, payment, logistics
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.0
Stable tag: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Complete courier management system with tracking, calculator, booking, and payment integration.

== Description ==

Courier Tracking & Management is a comprehensive WordPress plugin designed for courier and logistics businesses. It provides:

* **Real-time Tracking**: Customers can track their shipments with live status updates
* **Shipping Calculator**: Dynamic rate calculation based on weight, zone, and service type
* **Online Booking**: Easy shipment booking with address validation
* **Payment Integration**: Stripe payment gateway integration
* **Customer Dashboard**: Customers can view their shipment history and track orders
* **Admin Dashboard**: Comprehensive admin panel for managing all shipments and payments
* **Email Notifications**: Automatic confirmation and status update emails

== Features ==

* Custom tracking system with status history
* Dynamic shipping rate calculator
* Online booking and scheduling
* Stripe payment integration
* Customer and admin dashboards
* Email notifications
* REST API endpoints
* Responsive design
* Easy customization

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Courier Settings to configure your options
4. Add Stripe API keys for payment processing
5. Use shortcodes to display tracking, calculator, and booking forms

== Usage ==

= Shortcodes =

**Tracking Form:**
`[courier_tracking_form]`

**Shipping Calculator:**
`[courier_calculator]`

**Booking Form:**
`[courier_booking_form]`

**Customer Dashboard:**
`[courier_customer_dashboard]`

== Configuration ==

1. Go to WordPress Admin > Courier Settings
2. Configure base shipping rates
3. Add Stripe API keys
4. Customize email templates
5. Set up shipping zones

== REST API ==

The plugin provides REST API endpoints for integration:

* `GET /wp-json/courier/v1/track/{tracking_number}` - Get tracking info
* `POST /wp-json/courier/v1/track` - Create tracking record
* `GET /wp-json/courier/v1/bookings` - List bookings
* `POST /wp-json/courier/v1/bookings` - Create booking
* `POST /wp-json/courier/v1/calculate-rate` - Calculate rate
* `POST /wp-json/courier/v1/payment` - Process payment

== Database Tables ==

The plugin creates the following tables:
* `wp_courier_tracking` - Shipment tracking records
* `wp_courier_status_history` - Tracking status history
* `wp_courier_bookings` - Booking records
* `wp_courier_payments` - Payment records

== Security ==

* Nonce verification for all forms
* User role-based access control
* Input sanitization and validation
* SQL injection prevention
* XSS protection
* CSRF token validation

== Support ==

For support, please visit https://example.com/support

== Changelog ==

= 1.0.0 =
* Initial release
* Tracking system
* Shipping calculator
* Booking system
* Payment integration
* Customer and admin dashboards

== License ==

This plugin is licensed under the GPL v2 or later.
