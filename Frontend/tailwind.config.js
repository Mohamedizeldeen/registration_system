/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.html",
    "./**/*.js",
    "./assets/**/*.js",
    "./components/**/*.html",
    "./dashboards/**/*.html",
    "./SuperAdmin-dashboard/**/*.html"
  ],
  theme: {
    extend: {
      fontFamily: {
        'inter': ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
      colors: {
        'brand': {
          'primary': '#3B82F6',
          'secondary': '#8B5CF6',
          'accent': '#F59E0B',
        }
      },
      animation: {
        'float': 'float 3s ease-in-out infinite',
        'gradient': 'gradientFlow 20s ease infinite',
        'pulse-glow': 'pulseGlow 2s ease-in-out infinite alternate',
        'earth-rotate': 'earthRotate 20s linear infinite',
        'modal-slide': 'modalSlideIn 0.3s ease-out',
        'loading': 'loading 1.5s infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        gradientFlow: {
          '0%, 100%': { 'background-position': '0% 50%' },
          '50%': { 'background-position': '100% 50%' },
        },
        pulseGlow: {
          '0%': { 'box-shadow': '0 0 20px rgba(59, 130, 246, 0.4)' },
          '100%': { 'box-shadow': '0 0 30px rgba(59, 130, 246, 0.8)' },
        },
        earthRotate: {
          'from': { transform: 'rotate(0deg)' },
          'to': { transform: 'rotate(360deg)' },
        },
        loading: {
          '0%': { 'background-position': '200% 0' },
          '100%': { 'background-position': '-200% 0' },
        },
        modalSlideIn: {
          'from': { 
            opacity: '0',
            transform: 'scale(0.9) translateY(-20px)' 
          },
          'to': { 
            opacity: '1',
            transform: 'scale(1) translateY(0)' 
          },
        }
      },
      backdropBlur: {
        'xs': '2px',
        'xl': '24px',
        '2xl': '40px',
        '3xl': '64px',
      }
    },
  },
  plugins: [],
}