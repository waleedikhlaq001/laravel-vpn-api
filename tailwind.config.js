module.exports = {
  content: ["./resources/js/**/*.{vue,js,jsx}","./node_modules/tw-elements/dist/js/**/*.js"],
  theme: {
    extend: {
      maxWidth: {
        '24rem': '24rem',
      },
      colors: {
        blue: {
          '50': '#f2f9fd',
          '100': '#e3f1fb',
          '200': '#c2e4f5',
          '300': '#8bceee',
          '400': '#4eb4e2',
          '500': '#279bd0',
          '600': '#1980b6',
          '700': '#15648f',
          '800': '#155577',
          '900': '#174763',
          '950': '#0f2e42',
        },
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
  darkMode: 'class',
};