# Courier WordPress Theme & Plugin - Complete Solution

## Project Overview

This is a complete WordPress solution for a courier/logistics business featuring:
- Professional responsive theme
- Custom tracking system
- Shipping rate calculator
- Online booking and payment integration
- Customer and admin dashboards

## Directory Structure

```
courier-wp-theme/
├── style.css                 # Theme header and styling
├── functions.php             # Theme functions and hooks
├── header.php                # Header template
├── footer.php                # Footer template
├── index.php                 # Main template
├── home.php                  # Homepage template
├── page.php                  # Page template
├── single.php                # Single post template
├── archive.php               # Archive template
├── 404.php                   # 404 template
├── sidebar.php               # Sidebar template
├── searchform.php            # Search form template
├── css/
│   ├── style.css             # Main stylesheet
│   ├── responsive.css        # Responsive design
│   └── admin.css             # Admin styles
├── js/
│   ├── main.js               # Main JavaScript
│   ├── tracking.js           # Tracking functionality
│   ├── calculator.js         # Rate calculator
│   └── booking.js            # Booking system
├── images/
│   ├── logo.png
│   ├── hero-banner.jpg
│   └── icons/
├── inc/
│   ├── custom-post-types.php # Custom post types
│   ├── custom-taxonomies.php # Custom taxonomies
│   ├── widgets.php           # Custom widgets
│   └── template-tags.php     # Template tags
└── template-parts/
    ├── header/
    ├── footer/
    ├── content/
    └── navigation/

courier-wp-tracking-plugin/
├── courier-tracking.php      # Plugin main file
├── includes/
│   ├── class-tracking.php    # Tracking class
│   ├── class-calculator.php  # Calculator class
│   ├── class-booking.php     # Booking class
│   ├── class-payment.php     # Payment integration
│   └── class-dashboard.php   # Dashboard class
├── admin/
│   ├── admin-menu.php        # Admin menu setup
│   ├── admin-pages.php       # Admin pages
│   ├── tracking-management.php
│   ├── booking-management.php
│   ├── payment-management.php
│   └── css/
│       └── admin-style.css
├── public/
│   ├── shortcodes.php        # Public shortcodes
│   ├── frontend-pages.php    # Frontend pages
│   ├── css/
│   │   └── public-style.css
│   └── js/
│       ├── tracking-form.js
│       ├── calculator.js
│       ├── booking-form.js
│       └── payment.js
├── database/
│   ├── install.php           # Database installation
│   └── schema.sql            # Database schema
├── assets/
│   ├── images/
│   └── icons/
└── readme.txt                # Plugin documentation
```

## Key Features

### 1. Tracking System
- Custom tracking number input
- Real-time status updates
- Shipment history
- Email notifications
- Admin tracking management interface

### 2. Shipping Rate Calculator
- Dynamic rate calculation based on:
  - Weight
  - Distance/Zone
  - Delivery speed
  - Special handling
- Real-time quote generation
- Rate comparison

### 3. Online Booking
- Shipment booking form
- Address validation
- Service selection
- Schedule pickup/delivery
- Integration with tracking system

### 4. Payment Integration
- Stripe payment gateway
- Multiple payment methods
- Invoice generation
- Payment history
- Refund management

### 5. Dashboards
- **Customer Dashboard**: Track shipments, view history, manage bookings
- **Admin Dashboard**: Manage all shipments, users, payments, reports

## Database Schema

### Tracking Table
```sql
CREATE TABLE wp_courier_tracking (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tracking_number VARCHAR(50) UNIQUE,
  customer_id INT,
  origin_address TEXT,
  destination_address TEXT,
  weight DECIMAL(10,2),
  service_type VARCHAR(50),
  status VARCHAR(50),
  current_location TEXT,
  estimated_delivery DATE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Shipment Status History
```sql
CREATE TABLE wp_courier_status_history (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tracking_id INT,
  status VARCHAR(50),
  location TEXT,
  notes TEXT,
  timestamp TIMESTAMP
);
```

### Bookings Table
```sql
CREATE TABLE wp_courier_bookings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  customer_id INT,
  tracking_id INT,
  service_type VARCHAR(50),
  pickup_date DATE,
  delivery_date DATE,
  status VARCHAR(50),
  total_cost DECIMAL(10,2),
  created_at TIMESTAMP
);
```

### Payments Table
```sql
CREATE TABLE wp_courier_payments (
  id INT PRIMARY KEY AUTO_INCREMENT,
  booking_id INT,
  customer_id INT,
  amount DECIMAL(10,2),
  payment_method VARCHAR(50),
  stripe_transaction_id VARCHAR(100),
  status VARCHAR(50),
  created_at TIMESTAMP
);
```

## Installation Instructions

### For WordPress Installation:

1. **Upload Theme**:
   - Navigate to wp-content/themes/
   - Upload courier-wp-theme folder
   - Activate from WordPress admin

2. **Upload Plugin**:
   - Navigate to wp-content/plugins/
   - Upload courier-wp-tracking-plugin folder
   - Activate from WordPress admin

3. **Database Setup**:
   - Plugin automatically creates tables on activation
   - Configure Stripe API keys in plugin settings

4. **Configure Settings**:
   - Set up company information
   - Configure shipping zones and rates
   - Add Stripe API credentials
   - Customize email templates

## API Endpoints (for Plugin)

### Tracking API
- `GET /wp-json/courier/v1/track/{tracking_number}` - Get tracking info
- `POST /wp-json/courier/v1/track` - Create tracking record
- `PUT /wp-json/courier/v1/track/{id}` - Update tracking status

### Booking API
- `GET /wp-json/courier/v1/bookings` - List bookings
- `POST /wp-json/courier/v1/bookings` - Create booking
- `GET /wp-json/courier/v1/bookings/{id}` - Get booking details

### Calculator API
- `POST /wp-json/courier/v1/calculate-rate` - Calculate shipping rate

### Payment API
- `POST /wp-json/courier/v1/payment` - Process payment
- `GET /wp-json/courier/v1/payment/{id}` - Get payment status

## Shortcodes

### Tracking Form
```
[courier_tracking_form]
```

### Shipping Calculator
```
[courier_calculator]
```

### Booking Form
```
[courier_booking_form]
```

### Customer Dashboard
```
[courier_customer_dashboard]
```

## Admin Pages

1. **Tracking Management** - View and manage all shipments
2. **Booking Management** - View and manage bookings
3. **Payment Management** - View and manage payments
4. **Reports** - Generate shipping and revenue reports
5. **Settings** - Configure plugin options

## Security Features

- Nonce verification for forms
- User role-based access control
- Input sanitization and validation
- SQL injection prevention
- XSS protection
- CSRF token validation

## Customization

All colors, fonts, and layouts can be customized through:
- WordPress Customizer (theme options)
- Plugin settings page
- CSS files
- Template files

## Support & Documentation

Refer to individual files for detailed documentation and code comments.
