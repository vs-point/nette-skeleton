module.exports = {
  presets: [
    [
      '@babel/preset-env',
      {
        bugfixes: true,
        corejs: { version: 3.6 },
        modules: false,
        useBuiltIns: 'usage', // 'usage', if we do not have global import, false otherwise
        // targets: { esmodules: true },
      },
    ],
    [
      '@babel/preset-react',
      {
        development: process.env.NODE_ENV === 'development',
      },
    ],
  ],
};
