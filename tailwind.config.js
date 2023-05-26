/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/usernotnull/tall-toasts/config/**/*.php',
        './vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'bob-yellow-light': '#f9e78d',
                'bob-yellow': '#f7dc5c',
                'bob-blue': '#cbe8ef',
                'bob-blue-light': '#f5fafc',
                'purple': {
                    '900': '#19182C',
                    '800': '#211D33',
                    '700': '#2A223A',
                    '600': '#322641',
                    '500': '#8E63AC',
                    '400': '#A685BE',
                    '300': '#BFA8D1',
                    '200': '#D8CBE4',
                    '100': '#F1EBF6'
                },
            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/container-queries'),
    ],
}

