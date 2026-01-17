/**
 * Main JavaScript for Courier Pro Theme
 */

(function() {
    'use strict';

    // Document ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTheme();
    });

    /**
     * Initialize theme functionality
     */
    function initializeTheme() {
        setupMobileMenu();
        setupFormValidation();
        setupScrollAnimations();
    }

    /**
     * Setup mobile menu
     */
    function setupMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const primaryMenu = document.querySelector('#primary-menu');

        if (menuToggle && primaryMenu) {
            menuToggle.addEventListener('click', function() {
                primaryMenu.classList.toggle('active');
                menuToggle.classList.toggle('active');
            });
        }
    }

    /**
     * Setup form validation
     */
    function setupFormValidation() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Validate form
     */
    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('error');
                isValid = false;
            } else {
                input.classList.remove('error');
            }
        });

        return isValid;
    }

    /**
     * Setup scroll animations
     */
    function setupScrollAnimations() {
        const elements = document.querySelectorAll('.card, section');
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        elements.forEach(element => {
            observer.observe(element);
        });
    }

    /**
     * AJAX request helper
     */
    window.courierAjax = function(action, data, callback) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('nonce', courierData.nonce);

        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });

        fetch(courierData.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (callback) {
                callback(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    /**
     * Show notification
     */
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    };

})();
