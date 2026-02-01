import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';

/**
 * Initialize International Telephone Input on all phone fields
 * Default country is set to Syria (SY)
 */
export function initPhoneInputs() {
    // Find all phone input fields
    const phoneInputs = document.querySelectorAll('input[type="tel"], input#phone, input[name="phone"]');
    
    phoneInputs.forEach(input => {
        // Skip if already initialized
        if (input.classList.contains('iti-initialized')) {
            return;
        }
        
        // Initialize intl-tel-input
        const iti = intlTelInput(input, {
            // Set United Kingdom as the default country
            initialCountry: "gb",
            
            // Preferred countries at the top (UK, France, Germany, USA, Saudi Arabia)
            preferredCountries: ["gb", "fr", "de", "us", "sa"],
            
            // Only allow valid numbers
            strictMode: true,
            
            // Use full-width design
            containerClass: "w-100",
            
            // Automatically format as user types
            autoPlaceholder: "aggressive",
            
            // Format the number on blur
            formatOnDisplay: true,
            
            // National mode - show number without country code
            nationalMode: false,
            
            // Separate dial code in the input
            separateDialCode: true,
            
            // Show flags
            showFlags: true,
            
            // Utility script for country data
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/js/utils.js",
            
            // Custom placeholder
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                return selectedCountryPlaceholder;
            }
        });
        
        // Mark as initialized
        input.classList.add('iti-initialized');
        
        // Store the instance on the input element for later access
        input.itiInstance = iti;
        
        // Add validation on form submit
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Get the full international number
                const fullNumber = iti.getNumber();
                
                // Validate the number
                if (input.value.trim() && !iti.isValidNumber()) {
                    e.preventDefault();
                    
                    // Add error styling
                    input.classList.add('is-invalid');
                    
                    // Show error message
                    let errorMsg = input.parentElement.querySelector('.invalid-feedback');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback';
                        input.parentElement.appendChild(errorMsg);
                    }
                    
                    const errorCode = iti.getValidationError();
                    const errorMessages = {
                        0: 'رقم الهاتف غير صالح',
                        1: 'رمز الدولة غير صالح',
                        2: 'الرقم قصير جداً',
                        3: 'الرقم طويل جداً',
                        4: 'رقم الهاتف غير صالح',
                        5: 'رقم الهاتف غير صالح'
                    };
                    
                    errorMsg.textContent = errorMessages[errorCode] || 'رقم الهاتف غير صالح';
                    errorMsg.style.display = 'block';
                    
                    return false;
                } else {
                    // Remove error styling
                    input.classList.remove('is-invalid');
                    const errorMsg = input.parentElement.querySelector('.invalid-feedback');
                    if (errorMsg) {
                        errorMsg.style.display = 'none';
                    }
                    
                    // Set the full international number in the input before submitting
                    if (fullNumber) {
                        input.value = fullNumber;
                    }
                }
            });
        }
        
        // Remove error on input change
        input.addEventListener('input', function() {
            if (input.classList.contains('is-invalid')) {
                input.classList.remove('is-invalid');
                const errorMsg = input.parentElement.querySelector('.invalid-feedback');
                if (errorMsg) {
                    errorMsg.style.display = 'none';
                }
            }
        });
        
        // Handle country change
        input.addEventListener('countrychange', function() {
            console.log('Selected country:', iti.getSelectedCountryData());
        });
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPhoneInputs);
} else {
    initPhoneInputs();
}

// Re-initialize if new content is added dynamically
export function reinitPhoneInputs() {
    initPhoneInputs();
}

// Export for global access
window.initPhoneInputs = initPhoneInputs;
window.reinitPhoneInputs = reinitPhoneInputs;
