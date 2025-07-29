import "./bootstrap";

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const collapseBtn = document.getElementById('collapse-btn');
    const collapseIcon = document.getElementById('collapse-icon');
    const mainContent = document.getElementById('main-content');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileCloseBtn = document.getElementById('mobile-close-btn');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const userMenuBtn = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');
    const scrollToTopBtn = document.getElementById('scrollToTop');
    const expandBtn = document.getElementById('expand-btn');

    // Buat expand button baru untuk collapsed state - PENAMBAHAN BARU
    const expandBtnFixed = document.createElement('button');
    expandBtnFixed.id = 'expand-btn-fixed';
    expandBtnFixed.className = 'expand-btn-fixed w-6 h-6 bg-primary-500 hover:bg-primary-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300';
    expandBtnFixed.innerHTML = '<i class="fas fa-chevron-right text-sm"></i>';
    document.body.appendChild(expandBtnFixed);

    let isCollapsed = false;
    let isMobileSidebarOpen = false;

    // Desktop Sidebar Collapse/Expand - DIPERBAIKI (tanpa margin manipulation)
    function toggleSidebar() {
        isCollapsed = !isCollapsed;

        if (isCollapsed) {
            sidebar.classList.remove('sidebar-expanded');
            sidebar.classList.add('sidebar-collapsed');
            collapseIcon.style.transform = 'rotate(180deg)';

            // Hide text elements
            document.querySelectorAll('.sidebar-text').forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => el.style.display = 'none', 150);
            });

            // Show fixed expand button - DIPERBARUI
            expandBtnFixed.classList.add('show');
            
            // Hide original expand button if exists
            if (expandBtn) {
                expandBtn.classList.add('hidden');
                expandBtn.classList.remove('lg:flex');
            }

            // DIHAPUS: Update main content margin - tidak diperlukan karena CSS sudah mengatur
        } else {
            sidebar.classList.remove('sidebar-collapsed');
            sidebar.classList.add('sidebar-expanded');
            collapseIcon.style.transform = 'rotate(0deg)';

            // Show text elements
            setTimeout(() => {
                document.querySelectorAll('.sidebar-text').forEach(el => {
                    el.style.display = 'block';
                    setTimeout(() => el.style.opacity = '1', 50);
                });
            }, 150);

            // Hide fixed expand button - DIPERBARUI
            expandBtnFixed.classList.remove('show');
            
            // Hide original expand button if exists
            if (expandBtn) {
                expandBtn.classList.add('hidden');
                expandBtn.classList.remove('lg:flex');
            }

            // DIHAPUS: Update main content margin - tidak diperlukan karena CSS sudah mengatur
        }
    }

    // Mobile Sidebar Toggle
    function toggleMobileSidebar() {
        isMobileSidebarOpen = !isMobileSidebarOpen;

        if (isMobileSidebarOpen) {
            mobileSidebar.classList.add('mobile-sidebar-open');
            mobileOverlay.classList.remove('hidden');
            document.body.classList.add('mobile-sidebar-active');
            
            // Prevent body scroll on mobile
            document.body.style.overflow = 'hidden';
        } else {
            mobileSidebar.classList.remove('mobile-sidebar-open');
            mobileOverlay.classList.add('hidden');
            document.body.classList.remove('mobile-sidebar-active');
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
    }

    // Close mobile sidebar
    function closeMobileSidebar() {
        if (isMobileSidebarOpen) {
            toggleMobileSidebar();
        }
    }

    // User Dropdown Toggle
    function toggleUserDropdown() {
        userDropdown.classList.toggle('hidden');
    }

    // Event Listeners
    if (collapseBtn) {
        collapseBtn.addEventListener('click', toggleSidebar);
    }

    if (expandBtn) {
        expandBtn.addEventListener('click', toggleSidebar);
    }

    // Fixed expand button event listener - PENAMBAHAN BARU
    expandBtnFixed.addEventListener('click', toggleSidebar);

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileSidebar);
    }

    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', closeMobileSidebar);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileSidebar);
    }

    if (userMenuBtn) {
        userMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleUserDropdown();
        });
    }

    // Close mobile sidebar when clicking on menu items
    const mobileMenuItems = mobileSidebar?.querySelectorAll('.menu-item');
    if (mobileMenuItems) {
        mobileMenuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Only close if it's not a submenu
                if (!this.querySelector('.submenu')) {
                    setTimeout(() => closeMobileSidebar(), 200);
                }
            });
        });
    }

    // Close user dropdown when clicking outside
    document.addEventListener('click', function () {
        if (!userDropdown.classList.contains('hidden')) {
            userDropdown.classList.add('hidden');
        }
    });

    // Prevent dropdown close when clicking inside
    if (userDropdown) {
        userDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Scroll to Top functionality
    window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
            scrollToTopBtn.classList.remove('hidden');
        } else {
            scrollToTopBtn.classList.add('hidden');
        }
    });

    scrollToTopBtn.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Handle window resize - DIPERBAIKI (tanpa margin manipulation)
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            // Desktop mode - close mobile sidebar if open
            if (isMobileSidebarOpen) {
                closeMobileSidebar();
            }
            
            // Show/hide expand button based on collapse state - DIPERBAIKI
            if (isCollapsed) {
                expandBtnFixed.classList.add('show');
            } else {
                expandBtnFixed.classList.remove('show');
            }
        } else {
            // Mobile view - DIPERBAIKI
            expandBtnFixed.classList.remove('show');
        }
    });

    // Handle escape key to close mobile sidebar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMobileSidebarOpen) {
            closeMobileSidebar();
        }
    });

    // Add smooth hover effects for menu items
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('mouseenter', function () {
            this.style.transform = 'translateX(4px)';
        });

        item.addEventListener('mouseleave', function () {
            this.style.transform = 'translateX(0)';
        });
    });

    // Add loading animation for menu items
    document.querySelectorAll('.menu-item').forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';

        setTimeout(() => {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Notification badge animation
    setInterval(() => {
        document.querySelectorAll('.notification-badge').forEach(badge => {
            badge.style.transform = 'scale(1.1)';
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
            }, 200);
        });
    }, 3000);

    // Search functionality (placeholder)
    const searchInput = document.querySelector('input[placeholder="Search anything..."]');
    if (searchInput) {
        searchInput.addEventListener('focus', function () {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.boxShadow = '0 10px 25px rgba(59, 130, 246, 0.15)';
        });

        searchInput.addEventListener('blur', function () {
            this.parentElement.style.transform = 'scale(1)';
            this.parentElement.style.boxShadow = 'none';
        });
    }

    // Add ripple effect to buttons
    function createRipple(event) {
        const button = event.currentTarget;
        const circle = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;

        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
        circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
        circle.classList.add('ripple');

        const ripple = button.getElementsByClassName('ripple')[0];
        if (ripple) {
            ripple.remove();
        }

        button.appendChild(circle);
    }

    // Apply ripple effect to buttons
    document.querySelectorAll('button, .menu-item').forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Touch support for mobile
    let touchStartY = 0;
    let touchStartX = 0;

    // Handle touch events for better mobile experience
    if (mobileSidebar) {
        mobileSidebar.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
            touchStartX = e.touches[0].clientX;
        }, { passive: true });

        // Prevent overscroll bounce on mobile safari
        mobileSidebar.addEventListener('touchmove', function(e) {
            const touch = e.touches[0];
            const deltaY = touch.clientY - touchStartY;
            const deltaX = touch.clientX - touchStartX;
            
            // Prevent horizontal scroll
            if (Math.abs(deltaX) > Math.abs(deltaY)) {
                e.preventDefault();
            }
        }, { passive: false });
    }

    // DIHAPUS: Initial setup yang mengatur margin - tidak diperlukan

    // Initialize
    console.log('Sidebar initialized successfully');
});