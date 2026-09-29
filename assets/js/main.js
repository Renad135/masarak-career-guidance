// Language Switching
function switchLanguage(lang) {
    const siteUrl = window.siteUrl || 'http://localhost/MASARAK';
    fetch(siteUrl + '/api/switch_language.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'language=' + lang
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateInput(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function validateInput(input) {
    const value = input.value.trim();
    const type = input.type;
    const name = input.name;
    
    // Remove previous error
    const errorMsg = input.parentElement.querySelector('.error-message');
    if (errorMsg) {
        errorMsg.classList.remove('show');
    }
    input.style.borderColor = '';
    
    // Required check
    if (input.hasAttribute('required') && !value) {
        showError(input, getTranslation('field_required'));
        return false;
    }
    
    // Specific validations
    if (name === 'username' && value.length < 3) {
        showError(input, getTranslation('username_short'));
        return false;
    }
    
    if (name === 'email' && value && !isValidEmail(value)) {
        showError(input, getTranslation('invalid_email'));
        return false;
    }
    
    if (name === 'password' && value.length < 6) {
        showError(input, getTranslation('password_short'));
        return false;
    }
    
    if (name === 'confirm_password') {
        const password = form.querySelector('[name="password"]');
        if (password && value !== password.value) {
            showError(input, getTranslation('password_mismatch'));
            return false;
        }
    }
    
    if (name === 'graduation_year' && value && !/^\d{4}$/.test(value)) {
        showError(input, getTranslation('invalid_year'));
        return false;
    }
    
    if (name === 'phone' && value && value.replace(/\D/g, '').length < 10) {
        showError(input, getTranslation('invalid_phone'));
        return false;
    }
    
    if (name === 'age') {
        const age = parseInt(value);
        if (value && (age < 18 || age > 100)) {
            showError(input, getTranslation('invalid_age'));
            return false;
        }
    }
    
    return true;
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function showError(input, message) {
    input.style.borderColor = 'var(--error-color)';
    let errorMsg = input.parentElement.querySelector('.error-message');
    if (!errorMsg) {
        errorMsg = document.createElement('div');
        errorMsg.className = 'error-message';
        input.parentElement.appendChild(errorMsg);
    }
    errorMsg.textContent = message;
    errorMsg.classList.add('show');
}

function getTranslation(key) {
    // This will be populated by PHP or fetched from translations
    const translations = window.translations || {};
    const lang = document.documentElement.lang || 'ar';
    return translations[lang] && translations[lang][key] ? translations[lang][key] : key;
}

// Calculate Weighted Score
function calculateWeightedScore() {
    const qiyas = parseFloat(document.getElementById('qiyas_score')?.value) || 0;
    const tahsili = parseFloat(document.getElementById('tahsili_score')?.value) || 0;
    const gpa = parseFloat(document.getElementById('high_school_gpa')?.value) || 0;
    
    const weighted = (tahsili * 0.4) + (qiyas * 0.3) + (gpa * 0.3);
    
    const resultElement = document.getElementById('weighted_score_result');
    if (resultElement) {
        resultElement.textContent = weighted.toFixed(2);
        resultElement.style.display = 'block';
    }
    
    return weighted;
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate weighted score
    const academicInputs = ['qiyas_score', 'tahsili_score', 'high_school_gpa'];
    academicInputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', calculateWeightedScore);
        }
    });
    
    // Form submission handlers
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form.id)) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Real-time validation
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateInput(this);
        });
    });
});
