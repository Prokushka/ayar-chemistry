import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            screens:{
               'xs': '340px',
               'md': '540px',
               'lg': '768px',
               'xl': "1180px"
            },
            fontFamily: {
                lobster: ["Lobster"],
                rubick: ['Rubik', ...defaultTheme.fontFamily.sans],
                jost: ["Jost", "sans-serif"],
                Roboto: ["Roboto", "sans-serif"],
                lato: ["Lato", "sans-serif"],
                baskerville: ["Libre Baskerville", "serif"],
                comic: ["Comic Relief ", "system-ui"]
            },
            keyframes: {
                moveY: {
                        '0%': { transform: 'translateY(0)' },
                        '100%': { transform: 'translateY(10px)' },
                    },
                moveRight: {
                    '0%': { transform: 'translateX(-50px)' },
                    '100%': { transform: 'translateX(0px)' },
                },
                rollRight: {
                    '0%': { transform: 'translateX(-180px) rotate(-120deg)' },
                    '100%': { transform: 'translateX(0px)' },
                },
                rollLeft: {
                    '0%': { transform: 'translateX(180px) rotate(120deg)' },
                    '100%': { transform: 'translateX(0px)' },
                },
                upSize:{
                   '0%': {transform: 'scale(0.4)'},
                   '100%': {transform: 'scale(1)'}
                },
                roulet: {
                    '0%': { transform: 'translateX(34%)' },
                    '25%': {transform: 'translateX(34%)'},
                    '33%': { transform: 'translateX(-0.3%)', paused: true },
                    '58%': { transform: 'translateX(-0.3%)', paused: false },
                    '66%': { transform: 'translateX(-34.5%)', paused: true },
                    '100%': { transform: 'translateX(-34.5%)', paused: false },

                },
                animatePhoneMoreRoulet:{
                     '0%': { transform: 'translateX(36%)' },
                     '25%': {transform: 'translateX(36%)'},
                     '33%': { transform: 'translateX(1%)', paused: true },
                     '58%': { transform: 'translateX(1%)', paused: false },
                     '66%': { transform: 'translateX(-35%)', paused: true },
                     '100%': { transform: 'translateX(-35%)', paused: false },


                 },
                animatePhoneRoulet:{
                    '0%': { transform: 'translateX(35%)' },
                    '25%': {transform: 'translateX(35%)'},
                    '33%': { transform: 'translateX(-1%)', paused: true },
                    '58%': { transform: 'translateX(-1%)', paused: false },
                    '66%': { transform: 'translateX(-38%)', paused: true },
                    '100%': { transform: 'translateX(-38%)', paused: false },


                },

                text: {
                    '0%': { transform: 'translateY(-5px) scale(0.9)' },
                    '100%': { transform: 'translateY(0px) scale(1)' },
                },
                slideLeft:{
                    '0%': { transform: 'translateX(5rem)' },
                    '100%': { transform: 'translateX(0px)' },
                },
                slideRight:{
                    '0%': { transform: 'translateX(-5rem)' },
                    '100%': { transform: 'translateX(0px)' },
                }
            },
            animation: {
                'move-y': 'moveY 0.7s ease-out 1 forwards',
                'roll-right': 'rollRight 2s ease-out 1 forwards',
                'roll-left': 'rollLeft 2s ease-out 1 forwards',
                'roulet': 'roulet 15s ease-out infinite alternate',
                'roulet-phone': 'animatePhoneRoulet 15s ease-out infinite alternate',
                'roulet-phone-more': 'animatePhoneMoreRoulet 15s ease-out infinite alternate',
                'up-size': 'upSize 1.2s ease-out 1 forwards',
                'slide-left': 'slideLeft 1.2s ease-out 1 forwards',
                'slide-right': 'slideRight 1.2s ease-out 1 forwards',
                'text-up': 'text 1.2s ease-out 1 forwards',
            },

        },
    },

    plugins: [forms],
};
