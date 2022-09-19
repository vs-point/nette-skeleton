/* eslint-disable global-require, import/no-extraneous-dependencies */

module.exports = () => ({
  plugins: [
    require('autoprefixer'),
    require('postcss-flexbugs-fixes'),
    require('postcss-preset-env')({
      features: {
        'nesting-rules': true,
      },
    }),
  ],
});
