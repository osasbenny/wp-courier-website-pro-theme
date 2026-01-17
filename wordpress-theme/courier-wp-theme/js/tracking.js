/**
 * Tracking functionality for Courier Pro Theme
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initializeTracking();
    });

    /**
     * Initialize tracking functionality
     */
    function initializeTracking() {
        const trackingForm = document.getElementById('tracking-form');
        if (trackingForm) {
            trackingForm.addEventListener('submit', handleTrackingSubmit);
        }
    }

    /**
     * Handle tracking form submission
     */
    function handleTrackingSubmit(e) {
        e.preventDefault();

        const trackingNumber = document.getElementById('tracking-number').value.trim();

        if (!trackingNumber) {
            showNotification('Please enter a tracking number', 'error');
            return;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Tracking...';

        // Make AJAX request
        courierAjax('get_tracking_info', { tracking_number: trackingNumber }, function(response) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;

            if (response.success) {
                displayTrackingInfo(response.data);
            } else {
                showNotification(response.message || 'Tracking number not found', 'error');
            }
        });
    }

    /**
     * Display tracking information
     */
    function displayTrackingInfo(data) {
        const resultsContainer = document.getElementById('tracking-results');

        if (!resultsContainer) {
            return;
        }

        let html = `
            <div class="tracking-info card">
                <div class="card-body">
                    <h3>Tracking Number: ${escapeHtml(data.tracking_number)}</h3>
                    <div class="tracking-details">
                        <div class="detail-row">
                            <span class="label">Status:</span>
                            <span class="value status-${data.status.toLowerCase()}">${escapeHtml(data.status)}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Current Location:</span>
                            <span class="value">${escapeHtml(data.current_location)}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Origin:</span>
                            <span class="value">${escapeHtml(data.origin_address)}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Destination:</span>
                            <span class="value">${escapeHtml(data.destination_address)}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Weight:</span>
                            <span class="value">${escapeHtml(data.weight)} kg</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Service Type:</span>
                            <span class="value">${escapeHtml(data.service_type)}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Estimated Delivery:</span>
                            <span class="value">${escapeHtml(data.estimated_delivery)}</span>
                        </div>
                    </div>

                    <div class="tracking-timeline mt-40">
                        <h4>Shipment History</h4>
                        <div class="timeline">
        `;

        if (data.history && data.history.length > 0) {
            data.history.forEach((event, index) => {
                html += `
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5>${escapeHtml(event.status)}</h5>
                            <p>${escapeHtml(event.location)}</p>
                            <small>${escapeHtml(event.timestamp)}</small>
                            ${event.notes ? `<p class="notes">${escapeHtml(event.notes)}</p>` : ''}
                        </div>
                    </div>
                `;
            });
        } else {
            html += '<p>No history available yet</p>';
        }

        html += `
                        </div>
                    </div>
                </div>
            </div>
        `;

        resultsContainer.innerHTML = html;
        resultsContainer.scrollIntoView({ behavior: 'smooth' });
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

})();
