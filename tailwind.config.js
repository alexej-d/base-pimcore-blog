// @ts-check

const toPercent = (num) => {
  const x = num * 100
  return (x % 1 !== 0 ? x.toFixed(4) : x) + '%'
}

const toRem = (num) => num / 16 + 'rem'
const toEm = (num) => num / 16 + 'em'

/** @type {import("tailwindcss/types/config").Config} */
module.exports = {
  content: [
    './editorstyles.json',
    './safelist.txt',
    './src/Document/Areabrick/**/*.php',
    './src/Twig/Extension/**/*.php',
    './assets/scripts/**/*.js',
    './templates/**/*.twig',
  ],
  theme: {
    extend: {
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
