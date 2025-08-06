/* Modo oscuro corregido para AdminLTE - Vista PC */
(function() {
    'use strict';

    const THEME_KEY = 'theme-preference';
    const DARK_THEME = 'dark';
    const LIGHT_THEME = 'light';

    function getStoredTheme() {
        return localStorage.getItem(THEME_KEY);
    }

    function setStoredTheme(theme) {
        localStorage.setItem(THEME_KEY, theme);
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        document.body.setAttribute('data-theme', theme);
        
        // Actualizar todos los botones de cambio de tema
        const themeToggles = document.querySelectorAll('.theme-toggle, #theme-toggle');
        themeToggles.forEach(toggle => {
            const icon = toggle.querySelector('i');
            if (icon) {
                if (theme === DARK_THEME) {
                    icon.className = 'fas fa-sun';
                    toggle.setAttribute('title', 'Cambiar a modo claro');
                } else {
                    icon.className = 'fas fa-moon';
                    toggle.setAttribute('title', 'Cambiar a modo oscuro');
                }
            }
        });
    }

    function toggleTheme() {
        const currentTheme = getStoredTheme() || LIGHT_THEME;
        const newTheme = currentTheme === DARK_THEME ? LIGHT_THEME : DARK_THEME;
        applyTheme(newTheme);
        setStoredTheme(newTheme);
    }

    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        const storedTheme = getStoredTheme();
        const theme = storedTheme || LIGHT_THEME;
        applyTheme(theme);

        // Agregar event listeners a todos los botones de cambio de tema
        const themeToggles = document.querySelectorAll('.theme-toggle, #theme-toggle');
        themeToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleTheme();
            });
        });
    }

    init();
})();