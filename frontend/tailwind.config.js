/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: ["class"],
    content: [
    "./assets/**/*.{js,jsx}",
  ],
  theme: {
  	extend: {
  		container: {
  			center: true,
  			padding: '2rem',
  			screens: {
  				'2xl': '1400px'
  			}
  		},
  		borderRadius: {
  			lg: 'var(--radius)',
  			md: 'calc(var(--radius) - 2px)',
  			sm: 'calc(var(--radius) - 4px)'
  		},
  		colors: {}
  	}
  },
  plugins: [require("tailwindcss-animate")],
};