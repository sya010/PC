import './bootstrap';

// ─── Theme Store ──────────────────────────────────────────
// Must be registered on `alpine:init` BEFORE Livewire calls Alpine.start().
// Livewire 3 bundles Alpine; this file is loaded via Vite before Livewire boots.
const darkThemes = ['dark-red', 'midnight', 'forest', 'cosmic', 'sunset', 'deep-teal', 'cherry', 'charcoal'];
const themeClassMap = {
    ocean: 'theme-ocean',
    midnight: 'theme-midnight',
    emerald: 'theme-emerald',
    forest: 'theme-forest',
    royal: 'theme-royal',
    cosmic: 'theme-cosmic',
    amber: 'theme-amber',
    sunset: 'theme-sunset',
    teal: 'theme-teal',
    'deep-teal': 'theme-deep-teal',
    rose: 'theme-rose',
    cherry: 'theme-cherry',
    slate: 'theme-slate',
    charcoal: 'theme-charcoal'
};

const themeLabelKey = (key) => key.replace('-', '_');
const themeLabels = () => window.TechBuildTranslations?.theme || {};

document.addEventListener('alpine:init', () => {
    window.Alpine.store('theme', {
        current: localStorage.siteTheme || 'light-red',
        themes: [
            { key: 'light-red',  fallback: 'Default',       swatch: '#6366f1', dark: false },
            { key: 'dark-red',   fallback: 'Dark Default',  swatch: '#6366f1', dark: true },
            { key: 'ocean',      fallback: 'Ocean Blue',    swatch: '#2563EB', dark: false },
            { key: 'midnight',   fallback: 'Midnight Blue', swatch: '#3B82F6', dark: true },
            { key: 'emerald',    fallback: 'Emerald',       swatch: '#059669', dark: false },
            { key: 'forest',     fallback: 'Forest',        swatch: '#10B981', dark: true },
            { key: 'royal',      fallback: 'Royal Purple',  swatch: '#7C3AED', dark: false },
            { key: 'cosmic',     fallback: 'Cosmic',        swatch: '#8B5CF6', dark: true },
            { key: 'amber',      fallback: 'Amber',         swatch: '#D97706', dark: false },
            { key: 'sunset',     fallback: 'Sunset',        swatch: '#F59E0B', dark: true },
            { key: 'teal',       fallback: 'Teal',          swatch: '#0D9488', dark: false },
            { key: 'deep-teal',  fallback: 'Deep Teal',     swatch: '#14B8A6', dark: true },
            { key: 'rose',       fallback: 'Rose',          swatch: '#E11D48', dark: false },
            { key: 'cherry',     fallback: 'Cherry',        swatch: '#F43F5E', dark: true },
            { key: 'slate',      fallback: 'Slate',         swatch: '#475569', dark: false },
            { key: 'charcoal',   fallback: 'Charcoal',      swatch: '#94A3B8', dark: true },
        ],
        get isDark() {
            return darkThemes.includes(this.current);
        },
        label(theme) {
            return themeLabels()[themeLabelKey(theme.key)] || theme.fallback;
        },
        setTheme(name) {
            this.current = name;
            localStorage.siteTheme = name;
            Object.values(themeClassMap).forEach(c => document.documentElement.classList.remove(c));
            if (themeClassMap[name]) {
                document.documentElement.classList.add(themeClassMap[name]);
            }
            if (darkThemes.includes(name)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        toggle() {
            this.setTheme(this.isDark ? 'light-red' : 'dark-red');
        }
    });
});
