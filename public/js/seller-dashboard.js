// Seller Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  initializeTooltips();
  
  // Set active nav link
  setActiveNavLink();
  
  // Initialize forms
  initializeForms();
  
  // Show alerts
  showAlerts();
});

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
  tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

/**
 * Set active navigation link based on current page
 */
function setActiveNavLink() {
  const currentUrl = window.location.pathname;
  const navLinks = document.querySelectorAll('.sidebar .nav-link');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    
    if (href && currentUrl.includes(href.replace(/^[^/]*\//, ''))) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}

/**
 * Initialize form validation
 */
function initializeForms() {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
}

/**
 * Show/hide alerts with auto-dismiss
 */
function showAlerts() {
  const alerts = document.querySelectorAll('.alert');
  
  alerts.forEach(alert => {
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });
}

/**
 * Format currency
 */
function formatCurrency(value) {
  return '$' + parseFloat(value).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
}

/**
 * Format carbon value
 */
function formatCarbon(value) {
  return parseFloat(value).toFixed(2) + ' kg';
}

/**
 * Delete confirmation
 */
function confirmDelete(productName = 'this product') {
  return confirm(`Are you sure you want to delete ${productName}? This action cannot be undone.`);
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
  const toastHTML = `
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
      <div class="toast-header bg-${type} text-white">
        <strong class="mr-auto">Carbon Sphere</strong>
        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body">
        ${message}
      </div>
    </div>
  `;
  
  const toastContainer = document.querySelector('.toast-container') || document.body;
  toastContainer.insertAdjacentHTML('beforeend', toastHTML);
  
  const toastElement = toastContainer.querySelector('.toast:last-child');
  const toast = new bootstrap.Toast(toastElement);
  toast.show();
  
  // Remove element after hiding
  toastElement.addEventListener('hidden.bs.toast', function() {
    toastElement.remove();
  });
}

/**
 * Export data to CSV
 */
function exportToCSV(data, filename) {
  let csv = '';
  
  // Add headers
  const headers = Object.keys(data[0] || {});
  csv += headers.join(',') + '\n';
  
  // Add rows
  data.forEach(row => {
    const values = headers.map(header => {
      const value = row[header];
      // Escape quotes and wrap in quotes if contains comma
      return typeof value === 'string' && value.includes(',') 
        ? `"${value.replace(/"/g, '""')}"` 
        : value;
    });
    csv += values.join(',') + '\n';
  });
  
  // Create download link
  const link = document.createElement('a');
  link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
  link.download = filename || 'export.csv';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

/**
 * Print table
 */
function printTable(tableSelector, title = 'Print') {
  const printWindow = window.open('', '', 'height=600,width=800');
  const tableElement = document.querySelector(tableSelector);
  
  if (!tableElement) {
    alert('Table not found!');
    return;
  }
  
  printWindow.document.write('<html><head><title>' + title + '</title>');
  printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
  printWindow.document.write('</head><body>');
  printWindow.document.write('<h2>' + title + '</h2>');
  printWindow.document.write(tableElement.outerHTML);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}

/**
 * Validate email
 */
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

/**
 * Validate phone number
 */
function validatePhoneNumber(phone) {
  const re = /^[6-9]\d{9}$/;
  return re.test(phone);
}

/**
 * Format date
 */
function formatDate(dateString) {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleDateString('en-US', options);
}

/**
 * Debounce function for search
 */
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

/**
 * Filter table by search term
 */
function filterTable(searchId, tableId) {
  const searchInput = document.getElementById(searchId);
  const table = document.getElementById(tableId);
  
  if (!searchInput || !table) return;
  
  const debouncedFilter = debounce(function() {
    const filter = searchInput.value.toLowerCase();
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  }, 300);
  
  searchInput.addEventListener('keyup', debouncedFilter);
}

/**
 * Calculate carbon equivalents
 */
function calculateCarbonEquivalents(kgCO2) {
  return {
    carsPerMile: (kgCO2 / 0.404).toFixed(2),  // Average car: 404g CO2 per mile
    treesSeedlings: (kgCO2 / 22.15).toFixed(2),  // Tree seedling grows for 10 years
    plasticBags: (kgCO2 / 0.05).toFixed(2)  // Plastic bag: 50g CO2
  };
}

/**
 * Display carbon equivalents
 */
function displayCarbonEquivalents(value, elementId) {
  const equivalents = calculateCarbonEquivalents(parseFloat(value));
  const element = document.getElementById(elementId);
  
  if (!element) return;
  
  element.innerHTML = `
    <small class="d-block mt-2">
      <strong>This is equivalent to:</strong><br>
      🚗 ${equivalents.carsPerMile} miles of car driving<br>
      🌱 ${equivalents.treesSeedlings} tree seedlings grown for 10 years<br>
      🛍️ ${equivalents.plasticBags} plastic bags
    </small>
  `;
}

/**
 * Copy to clipboard
 */
function copyToClipboard(text, showNotification = true) {
  navigator.clipboard.writeText(text).then(() => {
    if (showNotification) {
      showToast('Copied to clipboard!', 'success');
    }
  }).catch(() => {
    showToast('Failed to copy', 'danger');
  });
}

/**
 * Format large numbers
 */
function formatNumber(num) {
  return num.toLocaleString();
}

/**
 * Get URL parameter
 */
function getUrlParameter(name) {
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
  const results = regex.exec(location.search);
  return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

/**
 * Preview image from URL
 */
function previewImage(url) {
  const container = document.getElementById('imagePreviewContainer');
  const preview = document.getElementById('imagePreview');
  
  if (!container || !preview) return;
  
  if (!url || url.trim() === '') {
    container.style.display = 'none';
    return;
  }
  
  // Create temporary image to test if URL is valid
  const img = new Image();
  img.onload = function() {
    preview.src = url;
    container.style.display = 'block';
  };
  img.onerror = function() {
    container.style.display = 'none';
  };
  img.src = url;
}

// Export functions for use in HTML
window.formatCurrency = formatCurrency;
window.formatCarbon = formatCarbon;
window.confirmDelete = confirmDelete;
window.showToast = showToast;
window.exportToCSV = exportToCSV;
window.printTable = printTable;
window.filterTable = filterTable;
window.calculateCarbonEquivalents = calculateCarbonEquivalents;
window.displayCarbonEquivalents = displayCarbonEquivalents;
window.copyToClipboard = copyToClipboard;
window.formatNumber = formatNumber;
window.getUrlParameter = getUrlParameter;
window.previewImage = previewImage;