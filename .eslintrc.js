module.exports = {
  extends: ['airbnb/base', 'prettier', 'plugin:import/errors', 'plugin:import/warnings'],
  plugins: ['prettier', 'import'],
  rules: {
    'prettier/prettier': 'error',
  },
  settings: {
    'import/resolver': {
      webpack: {
        config: `${__dirname}/webpack.config.js`,
      },
    },
  },
  env: {
    browser: true,
  },
};
