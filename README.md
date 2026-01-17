# Courier WordPress Theme & Plugin - Complete Solution

A professional, fully-functional WordPress solution for courier and logistics businesses featuring a custom theme and comprehensive plugin with tracking, shipping calculator, online booking, and payment integration.

## 📋 Project Structure

```
courier-wp-project/
├── wordpress-theme/
│   └── courier-wp-theme/          # Professional WordPress theme
│       ├── style.css              # Main theme stylesheet
│       ├── functions.php          # Theme functions and hooks
│       ├── header.php             # Header template
│       ├── footer.php             # Footer template
│       ├── home.php               # Homepage template
│       ├── page.php               # Page template
│       ├── index.php              # Main template
│       ├── 404.php                # 404 error template
│       ├── css/                   # Additional stylesheets
│       ├── js/                    # JavaScript files
│       │   ├── main.js            # Main functionality
│       │   ├── tracking.js        # Tracking system
│       │   ├── calculator.js      # Rate calculator
│       │   └── booking.js         # Booking system
│       ├── images/                # Theme images
│       └── inc/                   # Include files
│
├── wordpress-plugin/
│   └── courier-wp-tracking/       # Courier management plugin
│       ├── courier-tracking.php   # Plugin main file
│       ├── includes/              # Plugin classes
│       │   ├── class-installer.php
│       │   ├── class-tracking.php
│       │   ├── class-calculator.php
│       │   ├── class-booking.php
│       │   ├── class-payment.php
│       │   └── class-dashboard.php
│       ├── admin/                 # Admin functionality
│       │   ├── admin-menu.php
│       │   ├── admin-pages.php
│       │   ├── css/
│       │   └── js/
│       ├── public/                # Frontend functionality
│       │   ├── shortcodes.php
│       │   ├── frontend-pages.php
│       │   ├── css/
│       │   └── js/
│       ├── database/              # Database schema
│       ├── assets/                # Plugin assets
│       └── readme.txt             # Plugin documentation
│
├── WORDPRESS_STRUCTURE.md         # Detailed structure documentation
└── README.md                      # This file
```

## ✨ Key Features

### 1. **Custom Tracking System**
- Unique tracking number generation
- Real-time status updates
- Complete shipment history
- Location tracking
- Email notifications
- Customer-facing tracking page

### 2. **Shipping Rate Calculator**
- Dynamic rate calculation based on:
  - Weight
  - Delivery zone (local, regional, national, international)
  - Service type (standard, express, overnight, international)
  - Insurance options
- Bulk discounts
- Zone-based pricing
- Real-time quote generation

### 3. **Online Booking System**
- Easy shipment booking form
- Address validation
- Service selection
- Pickup/delivery scheduling
- Automatic tracking record creation
- Confirmation emails

### 4. **Payment Integration**
- Stripe payment gateway
- Multiple payment methods
- Secure transaction processing
- Invoice generation
- Payment history tracking
- Refund management

### 5. **Customer Dashboard**
- View all shipments
- Track orders in real-time
- Booking history
- Payment history
- Account management

### 6. **Admin Dashboard**
- Comprehensive management interface
- Shipment management
- Booking management
- Payment management
- Revenue reports
- Customer analytics
- Export functionality

## 🚀 Installation

### Prerequisites
- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

### Step 1: Upload Theme
1. Navigate to `/wp-content/themes/`
2. Upload the `courier-wp-theme` folder
3. Go to WordPress Admin > Appearance > Themes
4. Activate "Courier Pro" theme

### Step 2: Upload Plugin
1. Navigate to `/wp-content/plugins/`
2. Upload the `courier-wp-tracking` folder
3. Go to WordPress Admin > Plugins
4. Click "Activate" on "Courier Tracking & Management"

### Step 3: Configure Plugin
1. Go to WordPress Admin > Courier > Settings
2. Add your Stripe API keys:
   - Stripe Publishable Key
   - Stripe Secret Key
3. Configure base shipping rates
4. Customize email templates

### Step 4: Create Pages
The plugin automatically creates these pages on activation:
- `/track` - Tracking page
- `/calculator` - Rate calculator page
- `/booking` - Booking page
- `/dashboard` - Customer dashboard

## 📱 Shortcodes

### Tracking Form
Display the tracking form on any page:
```
[courier_tracking_form]
```

### Shipping Calculator
Display the rate calculator:
```
[courier_calculator]
```

### Booking Form
Display the booking form:
```
[courier_booking_form]
```

### Customer Dashboard
Display customer dashboard (requires login):
```
[courier_customer_dashboard]
```

## 🔌 REST API Endpoints

### Tracking
- `GET /wp-json/courier/v1/track/{tracking_number}` - Get tracking info
- `POST /wp-json/courier/v1/track` - Create tracking record

### Bookings
- `GET /wp-json/courier/v1/bookings` - List user bookings
- `POST /wp-json/courier/v1/bookings` - Create booking

### Calculator
- `POST /wp-json/courier/v1/calculate-rate` - Calculate shipping rate

### Payment
- `POST /wp-json/courier/v1/payment` - Process payment

## 💾 Database Tables

### wp_courier_tracking
Stores shipment tracking records:
- tracking_number (unique)
- customer_id
- origin_address
- destination_address
- weight
- service_type
- status
- current_location
- estimated_delivery
- timestamps

### wp_courier_status_history
Stores tracking status history:
- tracking_id
- status
- location
- notes
- timestamp

### wp_courier_bookings
Stores booking records:
- customer_id
- tracking_id
- full_name
- email
- phone
- origin_address
- destination_address
- weight
- service_type
- pickup_date
- delivery_date
- status
- total_cost
- timestamps

### wp_courier_payments
Stores payment records:
- booking_id
- customer_id
- amount
- payment_method
- stripe_transaction_id
- status
- timestamps

## 🎨 Theme Customization

### Colors
Edit the CSS variables in `style.css`:
```css
:root {
  --primary-color: #0066cc;
  --secondary-color: #004499;
  --text-color: #333;
  --border-color: #ddd;
}
```

### Fonts
Add custom fonts in `header.php`:
```html
<link href="https://fonts.googleapis.com/css2?family=YourFont" rel="stylesheet">
```

### Logo
Upload custom logo through WordPress Customizer:
- WordPress Admin > Appearance > Customize > Site Identity

## 🔒 Security Features

- Nonce verification for all forms
- User role-based access control
- Input sanitization and validation
- SQL injection prevention
- XSS protection
- CSRF token validation
- Secure password handling
- SSL/TLS support

## 📧 Email Notifications

Automatic emails sent for:
- Booking confirmation
- Payment confirmation
- Shipment status updates
- Delivery confirmation
- Customizable email templates

## 📊 Admin Features

### Dashboard
- Real-time statistics
- Recent bookings
- Revenue tracking
- Customer overview

### Tracking Management
- View all shipments
- Update tracking status
- Add status notes
- View shipment history

### Bookings Management
- View all bookings
- Update booking status
- Manage pickup/delivery dates
- Customer communication

### Payments Management
- View all payments
- Payment status tracking
- Transaction history
- Refund management

### Reports
- Revenue reports
- Shipment analytics
- Customer statistics
- Export data to CSV

## 🛠️ Configuration

### Shipping Rates
Configure in plugin settings:
- Base rate: $10
- Express surcharge: +$10
- Overnight surcharge: +$25
- Weight surcharge: $0.50/kg over 5kg
- Zone multipliers: 1.0 - 3.0x

### Service Types
- Standard (5 days)
- Express (2 days)
- Overnight (1 day)
- International (10 days)

### Delivery Zones
- Local (1.0x multiplier)
- Regional (1.5x multiplier)
- National (2.0x multiplier)
- International (3.0x multiplier)

## 🔄 Workflow

1. **Customer Books Shipment**
   - Fills booking form
   - Gets instant quote
   - Receives confirmation email

2. **Payment Processing**
   - Secure Stripe payment
   - Automatic tracking creation
   - Payment confirmation email

3. **Admin Management**
   - Confirms shipment
   - Updates tracking status
   - Manages delivery

4. **Customer Tracking**
   - Tracks shipment in real-time
   - Receives status updates
   - Gets delivery confirmation

## 🐛 Troubleshooting

### Plugin Not Activating
- Check PHP version (7.4+)
- Check WordPress version (5.0+)
- Check database permissions
- Review error logs

### Tracking Not Working
- Verify plugin is activated
- Check database tables created
- Verify nonce in forms
- Check browser console for errors

### Payment Issues
- Verify Stripe API keys
- Check SSL certificate
- Review Stripe logs
- Test with Stripe test keys

### Email Not Sending
- Check WordPress mail configuration
- Verify SMTP settings
- Check email logs
- Test with wp_mail()

## 📝 API Examples

### Get Tracking Info
```bash
curl -X GET https://yoursite.com/wp-json/courier/v1/track/CT1234567890
```

### Create Booking
```bash
curl -X POST https://yoursite.com/wp-json/courier/v1/bookings \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "origin": "123 Main St",
    "destination": "456 Oak Ave",
    "weight": 5.5,
    "service_type": "express"
  }'
```

### Calculate Rate
```bash
curl -X POST https://yoursite.com/wp-json/courier/v1/calculate-rate \
  -H "Content-Type: application/json" \
  -d '{
    "weight": 5.5,
    "zone": "national",
    "service_type": "express",
    "insurance": true
  }'
```

## 📄 License

This project is licensed under the GPL v2 or later.

## 🤝 Support

For support and documentation, refer to:
- Plugin README: `wordpress-plugin/courier-wp-tracking/readme.txt`
- Structure Guide: `WORDPRESS_STRUCTURE.md`
- Theme Functions: `wordpress-theme/courier-wp-theme/functions.php`

## 🎯 Future Enhancements

- Multi-language support
- Advanced reporting dashboard
- SMS notifications
- Mobile app integration
- Real-time GPS tracking
- Automated dispatch system
- Customer feedback system
- Integration with shipping carriers

## 📞 Contact

For inquiries and support, contact: support@example.com

---

**Version:** 1.0.0  
**Last Updated:** 2024  
**Author:** Courier Solutions
