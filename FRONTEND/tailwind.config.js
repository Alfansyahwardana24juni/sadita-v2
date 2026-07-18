/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,njk}",
    "./*.html"
  ],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "on-tertiary-fixed-variant": "#454747",
        "secondary-container": "#dcdddd",
        "surface-container-low": "#f6f3f2",
        "tertiary-fixed-dim": "#c6c6c7",
        "on-surface": "#1b1c1c",
        "on-secondary": "#ffffff",
        "background": "#fcf9f8",
        "surface-container": "#f0eded",
        "surface-container-lowest": "#ffffff",
        "tertiary": "#2b2d2d",
        "error-container": "#ffdad6",
        "on-primary-container": "#ff907f",
        "error": "#ba1a1a",
        "secondary-fixed-dim": "#c6c6c7",
        "surface-variant": "#e4e2e1",
        "on-error-container": "#93000a",
        "secondary": "#5d5f5f",
        "secondary-fixed": "#e2e2e2",
        "on-tertiary-container": "#afafb0",
        "outline": "#8e706b",
        "surface-container-high": "#eae7e7",
        "on-tertiary-fixed": "#1a1c1c",
        "on-primary-fixed": "#410000",
        "surface-container-highest": "#e4e2e1",
        "on-primary": "#ffffff",
        "primary": "#610000",
        "tertiary-fixed": "#e2e2e2",
        "on-secondary-fixed": "#1a1c1c",
        "surface": "#fcf9f8",
        "on-secondary-fixed-variant": "#454747",
        "outline-variant": "#e3beb8",
        "surface-dim": "#dcd9d9",
        "primary-container": "#8b0000",
        "on-tertiary": "#ffffff",
        "primary-fixed": "#ffdad4",
        "surface-tint": "#b52619",
        "inverse-on-surface": "#f3f0f0",
        "tertiary-container": "#414343",
        "inverse-primary": "#ffb4a8",
        "on-background": "#1b1c1c",
        "inverse-surface": "#303030",
        "on-error": "#ffffff",
        "on-secondary-container": "#5f6161",
        "primary-fixed-dim": "#ffb4a8",
        "on-surface-variant": "#5a403c",
        "surface-bright": "#fcf9f8",
        "on-primary-fixed-variant": "#920703"
      },
      borderRadius: {
        "DEFAULT": "0.125rem",
        "lg": "0.25rem",
        "xl": "0.5rem",
        "2xl": "1rem",
        "3xl": "1.5rem",
        "full": "9999px"
      },
      spacing: {
        "container-max": "512px",
        "stack-md": "1rem",
        "margin-main": "1.25rem",
        "gutter-grid": "1rem",
        "stack-sm": "0.5rem",
        "stack-lg": "2rem"
      },
      fontFamily: {
        "body-md": ["Inter", "sans-serif"],
        "display-sm": ["Inter", "sans-serif"],
        "label-sm": ["Inter", "sans-serif"],
        "label-lg": ["Inter", "sans-serif"],
        "headline-md": ["Inter", "sans-serif"]
      },
      fontSize: {
        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
        "display-sm": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
        "label-sm": ["10px", { "lineHeight": "14px", "fontWeight": "500" }],
        "label-lg": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
        "headline-md": ["18px", { "lineHeight": "24px", "fontWeight": "600" }]
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
        'fade-in': 'fadeIn 0.5s ease-out forwards',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/container-queries'),
    function({ addUtilities }) {
      const newUtilities = {
        '.animation-delay-100': {
          'animation-delay': '100ms',
        },
        '.animation-delay-200': {
          'animation-delay': '200ms',
        },
        '.animation-delay-300': {
          'animation-delay': '300ms',
        },
      }
      addUtilities(newUtilities, ['responsive', 'hover'])
    }
  ],
}
