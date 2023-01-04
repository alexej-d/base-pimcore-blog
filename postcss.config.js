const IS_DEVELOPMENT = process.env.NODE_ENV !== 'production'

module.exports = {
  plugins: [
    require('tailwindcss'),
    require('postcss-flexbugs-fixes'),
    ...(!IS_DEVELOPMENT
      ? [
          require('postcss-sort-media-queries'),
          require('postcss-preset-env')({
            autoprefixer: {
              flexbox: 'no-2009',
              grid: 'autoplace',
            },
            stage: 3,
          }),
        ]
      : []),
  ],
}
