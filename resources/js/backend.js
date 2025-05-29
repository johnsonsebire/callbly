// Backend-specific JavaScript for admin dashboard
import './bootstrap';
import '../css/backend.css';

// Load original template scripts first
const loadTemplateScripts = () => {
    return new Promise((resolve) => {
        // Load the original plugins bundle if not already loaded
        if (!window.KTUtil) {
            const script = document.createElement('script');
            script.src = '/assets/plugins/global/plugins.bundle.js';
            script.onload = resolve;
            script.onerror = resolve; // Continue even if fails
            document.head.appendChild(script);
        } else {
            resolve();
        }
    });
};

// Lazy load heavy libraries only when needed
let chartsLoaded = false;
let dataTablesLoaded = false;

function loadCharts() {
    if (!chartsLoaded) {
        return import('chart.js').then((Chart) => {
            chartsLoaded = true;
            return Chart;
        }).catch(error => {
            console.warn('Failed to load Chart.js:', error);
            return null;
        });
    }
    return Promise.resolve(null);
}

function loadDataTables() {
    if (!dataTablesLoaded) {
        return import('datatables.net-bs5').then(() => {
            dataTablesLoaded = true;
            return window.DataTable || window.jQuery;
        }).catch(error => {
            console.warn('Failed to load DataTables:', error);
            return null;
        });
    }
    return Promise.resolve(null);
}

// Initialize backend features
document.addEventListener('DOMContentLoaded', function() {
    // Load template scripts first, then initialize custom features
    loadTemplateScripts().then(() => {
        // Initialize template functionality if available
        if (window.KTUtil) {
            // Template's utility functions are now available
            // Initialize any template components that need manual initialization
            if (window.KTMenu) {
                KTMenu.createInstances();
            }
            if (window.KTDrawer) {
                KTDrawer.createInstances();
            }
            if (window.KTSticky) {
                KTSticky.createInstances();
            }
        }
        
        // Mobile sidebar toggle
        const sidebarMobileToggle = document.getElementById('kt_app_sidebar_mobile_toggle');
        if (sidebarMobileToggle) {
            sidebarMobileToggle.addEventListener('click', function() {
                const mobileSidebarElement = document.getElementById('kt_app_sidebar_mobile');
                if (mobileSidebarElement) {
                    import('bootstrap').then((bootstrap) => {
                        const mobileSidebar = new bootstrap.Offcanvas(mobileSidebarElement);
                        mobileSidebar.show();
                    }).catch(error => {
                        console.warn('Failed to load Bootstrap offcanvas:', error);
                    });
                }
            });
        }
        
        // Logo switching for backend pages
        const headerLogo = document.querySelector('.app-header-logo');
        if (headerLogo) {
            let ticking = false;
            
            function updateLogo() {
                if (window.scrollY > 50) {
                    headerLogo.classList.add('scrolled');
                } else {
                    headerLogo.classList.remove('scrolled');
                }
                ticking = false;
            }
            
            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateLogo);
                    ticking = true;
                }
            }
            
            window.addEventListener('scroll', requestTick, { passive: true });
            updateLogo(); // Initial check
        }
        
        // Lazy load DataTables when table containers are visible
        const tableContainers = document.querySelectorAll('[data-datatable]');
        if (tableContainers.length > 0) {
            const tableObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        loadDataTables().then((DataTable) => {
                            if (DataTable) {
                                // Initialize DataTable
                                const table = entry.target.querySelector('table');
                                if (table && !table.classList.contains('dataTable')) {
                                    new DataTable(table, {
                                        responsive: true,
                                        pageLength: 25,
                                        language: {
                                            search: "Search:",
                                            lengthMenu: "Show _MENU_ entries",
                                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                            paginate: {
                                                first: "First",
                                                last: "Last",
                                                next: "Next",
                                                previous: "Previous"
                                            }
                                        }
                                    });
                                }
                            }
                        });
                        tableObserver.unobserve(entry.target);
                    }
                });
            });
            
            tableContainers.forEach(container => {
                tableObserver.observe(container);
            });
        }
        
        // Lazy load charts when chart containers are visible
        const chartContainers = document.querySelectorAll('[data-chart]');
        if (chartContainers.length > 0) {
            const chartObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        loadCharts().then((Chart) => {
                            if (Chart) {
                                const canvas = entry.target.querySelector('canvas');
                                if (canvas && !canvas.hasAttribute('data-chart-initialized')) {
                                    // Initialize chart based on data attributes
                                    const chartType = entry.target.dataset.chartType || 'line';
                                    const chartData = JSON.parse(entry.target.dataset.chartData || '{}');
                                    
                                    new Chart.Chart(canvas, {
                                        type: chartType,
                                        data: chartData,
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    position: 'bottom'
                                                }
                                            }
                                        }
                                    });
                                    
                                    canvas.setAttribute('data-chart-initialized', 'true');
                                }
                            }
                        });
                        chartObserver.unobserve(entry.target);
                    }
                });
            });
            
            chartContainers.forEach(container => {
                chartObserver.observe(container);
            });
        }
        
        // Form validation enhancements
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Focus on first invalid field
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
                form.classList.add('was-validated');
            });
        });
        
        // Auto-save draft functionality
        const autosaveForms = document.querySelectorAll('form[data-autosave]');
        autosaveForms.forEach(form => {
            let saveTimeout;
            const formId = form.dataset.autosave;
            
            // Load saved data
            const savedData = localStorage.getItem(`autosave_${formId}`);
            if (savedData) {
                try {
                    const data = JSON.parse(savedData);
                    Object.keys(data).forEach(key => {
                        const field = form.querySelector(`[name="${key}"]`);
                        if (field) {
                            field.value = data[key];
                        }
                    });
                } catch (e) {
                    console.warn('Failed to load autosave data:', e);
                }
            }
            
            // Save on input
            form.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    const formData = new FormData(form);
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }
                    localStorage.setItem(`autosave_${formId}`, JSON.stringify(data));
                }, 1000);
            });
            
            // Clear on successful submit
            form.addEventListener('submit', function() {
                localStorage.removeItem(`autosave_${formId}`);
            });
        });
    });
});

// Performance monitoring
window.addEventListener('load', function() {
    // Log performance metrics in development
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('127.0.0.1')) {
        setTimeout(() => {
            if (window.performance && window.performance.timing) {
                const perfData = window.performance.timing;
                const loadTime = perfData.loadEventEnd - perfData.navigationStart;
                console.log(`Page load time: ${loadTime}ms`);
                
                if (loadTime > 3000) {
                    console.warn('Page load time is high. Consider optimizing assets.');
                }
            }
        }, 0);
    }
});