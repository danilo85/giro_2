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

// Toggle theme function
window.toggleTheme = function() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
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