/**
 * Theme System for GSC Risk Dashboard
 * Handles dynamic CSS variables and localStorage
 */

const themes = {
    forest: {
        '--sidebar': '#13212E',
        '--sidebar-hover': '#1E3140',
        '--primary': '#2F7A68',
        '--secondary': '#56C5A8',
        '--background': '#F5F7FA'
    },
    ocean: {
        '--sidebar': '#0F172A',
        '--sidebar-hover': '#1E293B',
        '--primary': '#0284C7',
        '--secondary': '#38BDF8',
        '--background': '#F0F9FF'
    },
    purple: {
        '--sidebar': '#1E1B4B',
        '--sidebar-hover': '#312E81',
        '--primary': '#7C3AED',
        '--secondary': '#A78BFA',
        '--background': '#F5F3FF'
    },
    sunset: {
        '--sidebar': '#431407',
        '--sidebar-hover': '#7C2D12',
        '--primary': '#EA580C',
        '--secondary': '#FB923C',
        '--background': '#FFF7ED'
    },
    emerald: {
        '--sidebar': '#064E3B',
        '--sidebar-hover': '#065F46',
        '--primary': '#059669',
        '--secondary': '#34D399',
        '--background': '#ECFDF5'
    },
    midnight: {
        '--sidebar': '#000000',
        '--sidebar-hover': '#1F2937',
        '--primary': '#4F46E5',
        '--secondary': '#818CF8',
        '--background': '#111827',
        '--card': '#1F2937',
        '--text': '#F9FAFB',
        '--text-muted': '#9CA3AF',
        '--border': '#374151'
    }
};

function applyTheme(themeName) {
    const root = document.documentElement;
    const theme = themes[themeName] || themes.forest;
    
    // Apply CSS Variables
    for (const [key, value] of Object.entries(theme)) {
        root.style.setProperty(key, value);
    }
    
    // Special handling for dark/midnight background colors
    if (themeName === 'midnight') {
        root.style.setProperty('--card', '#1F2937');
        root.style.setProperty('--text', '#F9FAFB');
        root.style.setProperty('--border', '#374151');
    } else {
        root.style.setProperty('--card', '#FFFFFF');
        root.style.setProperty('--text', '#1F2937');
        root.style.setProperty('--border', '#E5E7EB');
    }

    // Save preference
    localStorage.setItem('gsc_theme', themeName);
    document.documentElement.setAttribute('data-theme', themeName);
}

document.addEventListener('DOMContentLoaded', () => {
    // Load saved theme
    const savedTheme = localStorage.getItem('gsc_theme') || 'forest';
    applyTheme(savedTheme);

    // Attach click listeners to theme buttons
    const themeButtons = document.querySelectorAll('.theme-btn');
    themeButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const theme = btn.getAttribute('data-theme');
            if(theme) applyTheme(theme);
        });
    });
});
