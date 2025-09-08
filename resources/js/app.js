import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Theme management
function initTheme() {
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

// Update theme toggle icon
window.updateThemeIcon = function() {
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    
    if (themeToggleDarkIcon && themeToggleLightIcon) {
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
            themeToggleLightIcon.classList.add('hidden');
        }
    }
};

// Toggle theme function
window.toggleTheme = function() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
    
    // Update icon after theme change
    updateThemeIcon();
};

// Initialize theme on page load
initTheme();

// Sidebar state management
window.sidebarState = {
    expanded: localStorage.getItem('sidebarExpanded') === 'true',
    toggle() {
        this.expanded = !this.expanded;
        localStorage.setItem('sidebarExpanded', this.expanded);
    }
};