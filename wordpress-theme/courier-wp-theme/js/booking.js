/**
 * Booking functionality for Courier Pro Theme
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initializeBooking();
    });

    /**
     * Initialize booking functionality
     */
    function initializeBooking() {
        const bookingForm = document.getElementById('booking-form');
        if (bookingForm) {
            bookingForm.addEventListener('submit', handleBookingSubmit);
        }

        // Pre-fill service type if provided in URL
        const urlParams = new URLSearchParams(window.location.search);
        const service = urlParams.get('service');
        if (service) {
            const serviceSelect = document.getElementById('service-type');
            if (serviceSelect) {
                serviceSelect.value = service;
            }
        }
    }

    /**
     * Handle booking form submission
     */
    function handleBookingSubmit(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        // Validate required fields
        if (!data.full_name || !data.email || !data.phone || !data.origin || !data.destination || !data.service_type) {
            showNotification('Please fill in all required fields', 'error');
            return;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';

        // Make AJAX request
        courierAjax('create_booking', data, function(response) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;

            if (response.success) {
                showNotification('Booking created successfully!', 'success');
                // Redirect to payment page
                setTimeout(() => {
                    window.location.href = `/payment/?booking_id=${response.data.booking_id}`;
                }, 1500);
            } else {
                showNotification(response.message || 'Error creating booking', 'error');
            }
        });
    }

    /**
     * Validate email
     */
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Validate phone
     */
    function validatePhone(phone) {
        const re = /^[\d\s\-\+\(\)]+$/;
        return re.test(phone) && phone.replace(/\D/g, '').length >= 10;
    }

})();
