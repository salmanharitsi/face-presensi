class PaginationHelper {
    constructor(config = {}) {
        this.config = {
            tableSelector: '#dataTable',
            paginationSelector: '#paginationWrapper',
            loadingClass: 'loading',
            baseUrl: window.location.pathname,
            onBeforeLoad: null,
            onAfterLoad: null,
            ...config
        };
        
        this.init();
    }

    init() {
        this.bindPaginationEvents();
        this.bindPerPageEvents();
    }

    /**
     * Fetch data with pagination
     * @param {number} page - Page number
     * @param {Object} filters - Additional filters
     */
    async fetchData(page = 1, filters = {}) {
        try {
            // Call before load callback
            if (this.config.onBeforeLoad) {
                this.config.onBeforeLoad();
            }

            this.showLoading();

            // Build URL with parameters
            const url = new URL(this.config.baseUrl, window.location.origin);
            url.searchParams.set('page', page);
            
            // Add filters to URL
            Object.entries(filters).forEach(([key, value]) => {
                if (value) {
                    url.searchParams.set(key, value);
                }
            });

            // Add per_page if set
            const perPageSelector = document.querySelector('.per-page-selector');
            if (perPageSelector && perPageSelector.value) {
                url.searchParams.set('per_page', perPageSelector.value);
            }

            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            // Update table content
            const tableContainer = document.querySelector(this.config.tableSelector);
            if (tableContainer && data.html) {
                tableContainer.innerHTML = data.html;
            }

            this.hideLoading();
            this.bindPaginationEvents();
            this.bindPerPageEvents();

            // Call after load callback
            if (this.config.onAfterLoad) {
                this.config.onAfterLoad(data);
            }

            return data;

        } catch (error) {
            console.error('Pagination fetch error:', error);
            this.hideLoading();
            this.showError('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
    }

    /**
     * Bind pagination link events
     */
    bindPaginationEvents() {
        document.querySelectorAll('.pagination-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = link.getAttribute('data-page');
                if (page) {
                    this.fetchData(parseInt(page), this.getCurrentFilters());
                }
            });
        });
    }

    /**
     * Bind per-page selector events
     */
    bindPerPageEvents() {
        const perPageSelector = document.querySelector('.per-page-selector');
        if (perPageSelector) {
            perPageSelector.addEventListener('change', () => {
                this.fetchData(1, this.getCurrentFilters());
            });
        }
    }

    /**
     * Get current filter values
     * Override this method for specific implementations
     */
    getCurrentFilters() {
        return {};
    }

    /**
     * Show loading state
     */
    showLoading() {
        const tableContainer = document.querySelector(this.config.tableSelector);
        if (tableContainer) {
            tableContainer.style.opacity = '0.6';
            tableContainer.style.pointerEvents = 'none';
            tableContainer.classList.add(this.config.loadingClass);
        }
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        const tableContainer = document.querySelector(this.config.tableSelector);
        if (tableContainer) {
            tableContainer.style.opacity = '1';
            tableContainer.style.pointerEvents = 'auto';
            tableContainer.classList.remove(this.config.loadingClass);
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        // Create error notification
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        errorDiv.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>${message}</span>
                <button class="ml-2 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(errorDiv);
        
        // Animate in
        setTimeout(() => {
            errorDiv.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            errorDiv.style.transform = 'translateX(full)';
            setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
    }

    /**
     * Update URL without page reload
     */
    updateUrl(params) {
        const url = new URL(window.location);
        Object.entries(params).forEach(([key, value]) => {
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });
        window.history.pushState({}, '', url);
    }
}

// Specific implementation for Attendance (Presensi) page
class AttendancePagination extends PaginationHelper {
    constructor(config = {}) {
        super({
            tableSelector: '#presensiTable',
            ...config
        });
    }

    getCurrentFilters() {
        return {
            status: document.getElementById('filterStatus')?.value || '',
            tanggal: document.getElementById('searchTanggal')?.value || ''
        };
    }
}

// Usage examples for different pages:

// 1. For Attendance/Presensi page
// const attendancePagination = new AttendancePagination({
//     onBeforeLoad: () => console.log('Loading attendance data...'),
//     onAfterLoad: (data) => console.log('Attendance data loaded:', data)
// });

// 2. For Students page
// class StudentsPagination extends PaginationHelper {
//     constructor(config = {}) {
//         super({
//             tableSelector: '#studentsTable',
//             ...config
//         });
//     }
//     
//     getCurrentFilters() {
//         return {
//             search: document.getElementById('searchName')?.value || '',
//             class: document.getElementById('filterClass')?.value || '',
//             status: document.getElementById('filterStatus')?.value || ''
//         };
//     }
// }

// 3. For Generic table
// const genericPagination = new PaginationHelper({
//     tableSelector: '#myTable',
//     getCurrentFilters: () => ({
//         search: document.getElementById('search').value,
//         filter: document.getElementById('filter').value
//     })
// });

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { PaginationHelper, AttendancePagination };
}

// Global usage
window.PaginationHelper = PaginationHelper;
window.AttendancePagination = AttendancePagination;