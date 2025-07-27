// Number formatting utilities
window.formatCurrency = function(value) {
    // Remove all non-digit characters
    const numericValue = value.toString().replace(/[^\d]/g, '');

    // Format with Indonesian locale
    if (numericValue === '') return '';

    return new Intl.NumberFormat('id-ID').format(parseInt(numericValue));
};

window.unformatCurrency = function(value) {
    return value.toString().replace(/[^\d]/g, '');
};

// Auto-format currency inputs
document.addEventListener('DOMContentLoaded', function() {
    // Currency input formatting
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('currency-input')) {
            const cursorPosition = e.target.selectionStart;
            const oldValue = e.target.value;
            const newValue = formatCurrency(e.target.value);

            e.target.value = newValue;

            // Restore cursor position
            const newCursorPosition = cursorPosition + (newValue.length - oldValue.length);
            e.target.setSelectionRange(newCursorPosition, newCursorPosition);
        }
    });
});
