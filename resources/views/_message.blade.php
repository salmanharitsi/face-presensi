{{-- Success Toast --}}
@if (!empty(session('success')))
    <div id="toast-success"
        class="toast-hidden z-[1000] fixed top-5 left-1/2 transform -translate-x-1/2 
               flex items-center w-full max-w-md p-4 text-white 
               bg-gradient-to-r from-green-500 via-green-600 to-green-700 
               rounded-xl shadow-2xl border border-green-400/30
               -translate-y-full opacity-0 transition-all duration-500 ease-out
               backdrop-blur-sm"
        role="alert">
        <div class="relative">
            <!-- Animated success icon with glow effect -->
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 
                        rounded-full bg-white/20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 rounded-full animate-ping"></div>
                <svg class="w-6 h-6 relative z-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                </svg>
            </div>
        </div>
        <div class="ml-4 flex-1">
            <div class="text-sm text-green-100 mt-1">{{ Session::get('success.title') }}</div>
        </div>
        <button type="button" 
                class="ml-4 -mx-1.5 -my-1.5 rounded-lg p-2 inline-flex items-center justify-center h-8 w-8 
                       text-white/80 hover:text-white hover:bg-white/20 transition-all duration-200
                       focus:ring-2 focus:ring-white/30" 
                data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
        <!-- Progress bar -->
        <div class="absolute bottom-0 left-0 h-1 bg-white/30 rounded-b-xl overflow-hidden">
            <div class="h-full bg-white/60 rounded-b-xl animate-progress" style="animation: progress 5s linear"></div>
        </div>
    </div>
@endif

{{-- Error Toast --}}
@if (!empty(session('error')))
    <div id="toast-danger"
        class="toast-hidden z-[1000] fixed top-5 left-1/2 transform -translate-x-1/2 
               flex items-center w-full max-w-md p-4 text-white 
               bg-gradient-to-r from-red-500 via-red-600 to-red-700 
               rounded-xl shadow-2xl border border-red-400/30
               -translate-y-full opacity-0 transition-all duration-500 ease-out
               backdrop-blur-sm"
        role="alert">
        <div class="relative">
            <!-- Animated error icon with pulse effect -->
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 
                        rounded-full bg-white/20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-red-300/20 rounded-full animate-pulse"></div>
                <svg class="w-6 h-6 relative z-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
            </div>
        </div>
        <div class="ml-4 flex-1">
            <div class="text-sm text-red-100 mt-1">{{ Session::get('error.title') }}</div>
        </div>
        <button type="button" 
                class="ml-4 -mx-1.5 -my-1.5 rounded-lg p-2 inline-flex items-center justify-center h-8 w-8 
                       text-white/80 hover:text-white hover:bg-white/20 transition-all duration-200
                       focus:ring-2 focus:ring-white/30" 
                data-dismiss-target="#toast-danger" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
        <!-- Progress bar -->
        <div class="absolute bottom-0 left-0 h-1 bg-white/30 rounded-b-xl overflow-hidden">
            <div class="h-full bg-white/60 rounded-b-xl animate-progress" style="animation: progress 5s linear"></div>
        </div>
    </div>
@endif

{{-- Warning Toast --}}
@if (!empty(session('warning')))
    <div id="toast-warning"
        class="toast-hidden z-[1000] fixed top-5 left-1/2 transform -translate-x-1/2 
               flex items-center w-full max-w-md p-4 text-white 
               bg-gradient-to-r from-amber-500 via-amber-600 to-orange-600 
               rounded-xl shadow-2xl border border-amber-400/30
               -translate-y-full opacity-0 transition-all duration-500 ease-out
               backdrop-blur-sm"
        role="alert">
        <div class="relative">
            <!-- Animated warning icon with bounce effect -->
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 
                        rounded-full bg-white/20 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-amber-300/20 rounded-full animate-bounce"></div>
                <svg class="w-6 h-6 relative z-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
            </div>
        </div>
        <div class="ml-4 flex-1">
            <div class="text-sm text-amber-100 mt-1">{{ Session::get('warning.title') }}</div>
        </div>
        <button type="button" 
                class="ml-4 -mx-1.5 -my-1.5 rounded-lg p-2 inline-flex items-center justify-center h-8 w-8 
                       text-white/80 hover:text-white hover:bg-white/20 transition-all duration-200
                       focus:ring-2 focus:ring-white/30" 
                data-dismiss-target="#toast-warning" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
        <!-- Progress bar -->
        <div class="absolute bottom-0 left-0 h-1 bg-white/30 rounded-b-xl overflow-hidden">
            <div class="h-full bg-white/60 rounded-b-xl animate-progress" style="animation: progress 5s linear"></div>
        </div>
    </div>
@endif

<style>
@keyframes progress {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

@keyframes slideInFromTop {
    from {
        transform: translate(-50%, -100%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
}

@keyframes slideOutToTop {
    from {
        transform: translate(-50%, 0);
        opacity: 1;
    }
    to {
        transform: translate(-50%, -100%);
        opacity: 0;
    }
}

.toast-show {
    animation: slideInFromTop 0.5s ease-out forwards;
}

.toast-hide {
    animation: slideOutToTop 0.5s ease-in forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toasts = document.querySelectorAll('.toast-hidden');

    toasts.forEach(toast => {
        // Show toast with slide down animation
        setTimeout(() => {
            toast.classList.remove('-translate-y-full', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            toast.classList.add('toast-show');
        }, 100);

        // Auto hide after 5 seconds
        setTimeout(() => {
            hideToast(toast);
        }, 5000);

        // Remove on close button click
        const closeButton = toast.querySelector('[data-dismiss-target]');
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                hideToast(toast);
            });
        }
    });

    function hideToast(toast) {
        toast.classList.remove('toast-show');
        toast.classList.add('toast-hide');
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('-translate-y-full', 'opacity-0');
        
        setTimeout(() => {
            toast.remove();
        }, 500); // Match animation duration
    }
});
</script>