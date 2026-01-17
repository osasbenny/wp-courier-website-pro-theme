/**
 * Shipping calculator functionality for Courier Pro Theme
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initializeCalculator();
    });

    /**
     * Initialize calculator functionality
     */
    function initializeCalculator() {
        const calculatorForm = document.getElementById('calculator-form');
        if (calculatorForm) {
            // Add event listeners to form inputs
            const inputs = calculatorForm.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('change', calculateRate);
                input.addEventListener('keyup', calculateRate);
            });
        }
    }

    /**
     * Calculate shipping rate
     */
    function calculateRate() {
        const weight = document.getElementById('weight')?.value || 0;
        const zone = document.getElementById('zone')?.value || '';
        const serviceType = document.getElementById('service-type')?.value || '';
        const insurance = document.getElementById('insurance')?.checked ? 1 : 0;

        if (!weight || !zone || !serviceType) {
            document.getElementById('rate-result').innerHTML = '';
            return;
        }

        // Show loading state
        const resultContainer = document.getElementById('rate-result');
        resultContainer.innerHTML = '<p>Calculating...</p>';

        // Make AJAX request
        courierAjax('calculate_shipping_rate', {
            weight: weight,
            zone: zone,
            service_type: serviceType,
            insurance: insurance
        }, function(response) {
            if (response.success) {
                displayRateResult(response.data);
            } else {
                resultContainer.innerHTML = `<p class="error">${escapeHtml(response.message)}</p>`;
            }
        });
    }

    /**
     * Display rate result
     */
    function displayRateResult(data) {
        const resultContainer = document.getElementById('rate-result');

        let html = `
            <div class="rate-result card">
                <div class="card-body">
                    <h4>Shipping Quote</h4>
                    <div class="rate-details">
                        <div class="detail-row">
                            <span class="label">Base Rate:</span>
                            <span class="value">$${parseFloat(data.base_rate).toFixed(2)}</span>
                        </div>
        `;

        if (data.weight_surcharge > 0) {
            html += `
                        <div class="detail-row">
                            <span class="label">Weight Surcharge:</span>
                            <span class="value">$${parseFloat(data.weight_surcharge).toFixed(2)}</span>
                        </div>
            `;
        }

        if (data.insurance_cost > 0) {
            html += `
                        <div class="detail-row">
                            <span class="label">Insurance:</span>
                            <span class="value">$${parseFloat(data.insurance_cost).toFixed(2)}</span>
                        </div>
            `;
        }

        if (data.discount > 0) {
            html += `
                        <div class="detail-row">
                            <span class="label">Discount:</span>
                            <span class="value">-$${parseFloat(data.discount).toFixed(2)}</span>
                        </div>
            `;
        }

        html += `
                        <div class="detail-row total">
                            <span class="label">Total:</span>
                            <span class="value">$${parseFloat(data.total).toFixed(2)}</span>
                        </div>
                    </div>

                    <div class="rate-info mt-20">
                        <p><strong>Estimated Delivery:</strong> ${escapeHtml(data.estimated_delivery)}</p>
                        <p><strong>Service Type:</strong> ${escapeHtml(data.service_type)}</p>
                    </div>

                    <button class="btn btn-primary mt-20" onclick="bookWithRate('${escapeHtml(data.service_type)}', ${parseFloat(data.total)})">
                        Book Now
                    </button>
                </div>
            </div>
        `;

        resultContainer.innerHTML = html;
    }

    /**
     * Book with rate
     */
    window.bookWithRate = function(serviceType, rate) {
        // Redirect to booking page with pre-filled data
        window.location.href = `/booking/?service=${encodeURIComponent(serviceType)}&rate=${rate}`;
    };

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

})();
