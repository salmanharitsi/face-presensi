/** @type {import('tailwindcss').Config} */

export default {
    content: {
        relative: true,
        files: [
            "./resources/**/*.blade.php",
            "./resources/**/*.js",
            "./resources/**/*.vue",
            "./node_modules/flowbite/**/*.js",
            "./src/*.{html,js}", 
        ],
    },
    safelist: [
        "!duration-[0ms]",
        "!delay-[0ms]",
        'html.js :where([class*="taos:"]:not(.taos-init))',
    ],
    theme: {
        extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            },
            colors: {
              primary: {
                50: '#eff6ff',
                100: '#dbeafe',
                500: '#3b82f6',
                600: '#2563eb',
                700: '#1d4ed8',
                900: '#1e3a8a'
              }
            }
          }
    },
    plugins: [
        require("flowbite/plugin")({
            charts: true,
        }),
    ],
    darkMode: "class",
};