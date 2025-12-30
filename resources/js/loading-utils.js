// resources/js/loading-states.js
// Comprehensive loading state management for dashboard

class LoadingStateManager {
    constructor() {
        this.activeLoaders = new Set();
    }

    // Button loading state
    setButtonLoading(buttonElement, isLoading, loadingText = 'Processing...') {
        if (isLoading) {
            // Store original content
            buttonElement.dataset.originalText = buttonElement.innerHTML;
            buttonElement.dataset.originalDisabled = buttonElement.disabled;

            // Set loading state
            buttonElement.disabled = true;
            buttonElement.classList.add('cursor-not-allowed', 'opacity-75');
            buttonElement.innerHTML = `
                <svg class="inline w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ${loadingText}
            `;

            this.activeLoaders.add(buttonElement);
        } else {
            // Restore original state
            if (buttonElement.dataset.originalText) {
                buttonElement.innerHTML = buttonElement.dataset.originalText;
                buttonElement.disabled = buttonElement.dataset.originalDisabled === 'true';
                buttonElement.classList.remove('cursor-not-allowed', 'opacity-75');

                delete buttonElement.dataset.originalText;
                delete buttonElement.dataset.originalDisabled;
            }

            this.activeLoaders.delete(buttonElement);
        }
    }

    // Form loading state
    setFormLoading(formElement, isLoading) {
        const inputs = formElement.querySelectorAll('input, select, textarea, button[type="submit"]');

        if (isLoading) {
            inputs.forEach(input => {
                input.dataset.originalDisabled = input.disabled;
                input.disabled = true;
                input.classList.add('opacity-50', 'cursor-not-allowed');
            });

            // Add loading overlay
            const overlay = document.createElement('div');
            overlay.className = 'absolute inset-0 bg-white/50 dark:bg-gray-900/50 flex items-center justify-center rounded-lg';
            overlay.innerHTML = `
                <div class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                    <svg class="w-5 h-5 animate-spin text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Processing...</span>
                </div>
            `;
            overlay.id = 'form-loading-overlay';
            formElement.style.position = 'relative';
            formElement.appendChild(overlay);

            this.activeLoaders.add(formElement);
        } else {
            inputs.forEach(input => {
                input.disabled = input.dataset.originalDisabled === 'true';
                input.classList.remove('opacity-50', 'cursor-not-allowed');
                delete input.dataset.originalDisabled;
            });

            const overlay = formElement.querySelector('#form-loading-overlay');
            if (overlay) overlay.remove();

            this.activeLoaders.delete(formElement);
        }
    }

    // Progress bar for file uploads/imports
    showProgressBar(containerId, progress = 0, message = 'Uploading...') {
        const container = document.getElementById(containerId);
        if (!container) return;

        let progressBar = container.querySelector('.progress-bar-wrapper');

        if (!progressBar) {
            progressBar = document.createElement('div');
            progressBar.className = 'progress-bar-wrapper mt-4';
            progressBar.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 progress-message">${message}</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 progress-percentage">${progress}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 overflow-hidden">
                    <div class="bg-purple-600 dark:bg-purple-500 h-2.5 rounded-full transition-all duration-300 progress-fill" style="width: ${progress}%"></div>
                </div>
            `;
            container.appendChild(progressBar);
        } else {
            // Update existing progress bar
            progressBar.querySelector('.progress-message').textContent = message;
            progressBar.querySelector('.progress-percentage').textContent = `${progress}%`;
            progressBar.querySelector('.progress-fill').style.width = `${progress}%`;
        }

        if (progress >= 100) {
            setTimeout(() => {
                progressBar.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => progressBar.remove(), 500);
            }, 1000);
        }
    }

    // Inline spinner for small loading indicators
    createInlineSpinner(size = 'small') {
        const sizes = {
            small: 'w-4 h-4',
            medium: 'w-6 h-6',
            large: 'w-8 h-8'
        };

        const spinner = document.createElement('svg');
        spinner.className = `inline animate-spin ${sizes[size]} text-current`;
        spinner.setAttribute('fill', 'none');
        spinner.setAttribute('viewBox', '0 0 24 24');
        spinner.innerHTML = `
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        `;
        return spinner;
    }

    // Clear all active loaders
    clearAll() {
        this.activeLoaders.forEach(element => {
            if (element.tagName === 'FORM') {
                this.setFormLoading(element, false);
            } else if (element.tagName === 'BUTTON') {
                this.setButtonLoading(element, false);
            }
        });
        this.activeLoaders.clear();
    }
}

// Global instance
window.loadingManager = new LoadingStateManager();

// Helper functions for easy access
window.showButtonLoading = (selector, loadingText) => {
    const button = typeof selector === 'string' ? document.querySelector(selector) : selector;
    if (button) window.loadingManager.setButtonLoading(button, true, loadingText);
};

window.hideButtonLoading = (selector) => {
    const button = typeof selector === 'string' ? document.querySelector(selector) : selector;
    if (button) window.loadingManager.setButtonLoading(button, false);
};

window.showFormLoading = (selector) => {
    const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
    if (form) window.loadingManager.setFormLoading(form, true);
};

window.hideFormLoading = (selector) => {
    const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
    if (form) window.loadingManager.setFormLoading(form, false);
};

window.updateProgress = (containerId, progress, message) => {
    window.loadingManager.showProgressBar(containerId, progress, message);
};

// Auto-cleanup on page unload
window.addEventListener('beforeunload', () => {
    window.loadingManager.clearAll();
});

// Example usage in your blade files:
/*
<button onclick="handleImport()" class="import-btn">Import Data</button>

<script>
function handleImport() {
    const btn = document.querySelector('.import-btn');
    showButtonLoading(btn, 'Importing...');

    // Simulate import with progress
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        updateProgress('import-container', progress, `Processing ${progress}%...`);

        if (progress >= 100) {
            clearInterval(interval);
            hideButtonLoading(btn);
            showToast('Import completed successfully', 'success');
        }
    }, 500);
}
</script>
*/
