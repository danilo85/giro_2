import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;

// Initialize Alpine.js store before Alpine starts
Alpine.store('sidebar', {
    open: false,
    collapsed: false,
    isMobile: false,

    init() {
        this.isMobile = window.innerWidth < 768;
        this.updateState();
        this.setupResizeListener();
    },

    updateState() {
        if (this.isMobile) {
            this.open = false; // Menu mobile inicia fechado/escondido
            this.collapsed = false; // Não usa colapso em mobile
        } else {
            this.collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            this.open = true;
        }
    },

    setupResizeListener() {
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const wasMobile = this.isMobile;
                this.isMobile = window.innerWidth < 768;
                if (wasMobile !== this.isMobile) {
                    this.updateState();
                }
            }, 150);
        });
    },

    toggle() {
        console.log('Sidebar toggle chamado!');
        if (this.isMobile) {
            // Em mobile, apenas alterna entre escondido/visível
            this.open = !this.open;
        } else {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', this.collapsed);
        }
    },

    close() {
        if (this.isMobile) {
            this.open = false;
        }
    }
});

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