const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');
const path = require('path');

const DEV = process.env.NODE_ENV !== 'production';
const STYLE_LOADER = process.env.STYLE_LOADER;
const sourceMap = DEV || process.env.GENERATE_SOURCEMAP !== 'false';

const styleLoaders = (loaders = []) => [
  STYLE_LOADER
    ? {
        loader: 'style-loader',
      }
    : {
        loader: MiniCssExtractPlugin.loader,
      },
  {
    loader: 'css-loader',
    options: {
      sourceMap,
      modules: 'global',
      importLoaders: loaders.length + 1,
    },
  },
  {
    loader: 'postcss-loader',
    options: {
      sourceMap,
    },
  },
  ...loaders,
];

module.exports = (/* env, args */) => {
  // const isProduction = args.mode === 'production';

  return {
    context: path.resolve(__dirname),
    entry: {
      front: './src/assets/front/index.js',
      admin: './src/assets/admin/index.js',
    },
    output: {
      filename: 'js/[name].[chunkhash:8].js',
      path: path.join(__dirname, 'public/dist'),
      publicPath: '/dist/',
      assetModuleFilename: 'media/[name].[hash:8][ext][query]',
    },
    devtool: DEV ? 'eval-cheap-module-source-map' : false,
    module: {
      strictExportPresence: true,
      rules: [
        {
          test: /\.(js|jsx|mjs)$/,
          include: [path.resolve(__dirname, 'src/assets')],
          loader: 'babel-loader',
          options: {
            cacheDirectory: true,
          },
        },
        {
          test: /\.(ts|tsx)$/,
          include: [path.resolve(__dirname, 'src/assets')],
          loader: 'ts-loader',
        },
        {
          test: /\.css$/,
          use: styleLoaders([]),
        },
        {
          test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
          type: 'asset',
          generator: {
            filename: 'img/svg/[name].[hash:8][ext]',
          },
        },
        {
          test: /\.woff2?(\?v=\d+\.\d+\.\d+)?$/,
          type: 'asset',
          generator: {
            filename: 'font/[name].[hash:8][ext]',
          },
        },
        {
          test: /\.ttf(\?.*)?$/,
          type: 'asset',
          generator: {
            filename: 'font/[name].[hash:8][ext]',
          },
        },
        {
          test: /\.(jpe?g|png|gif|eot)(\?[\s\S]+)?$/i,
          type: 'asset',
          generator: {
            filename: 'img/[name].[hash:8][ext]',
          },
        },
      ],
    },
    plugins: [
      new MiniCssExtractPlugin({ filename: 'css/[name].[chunkhash:8].css' }),
      new WebpackManifestPlugin({
        fileName: 'manifest.json',
      }),
      new CleanWebpackPlugin({
        cleanOnceBeforeBuildPatterns: ['**/*', '!.gitignore'],
      }),
    ],
    devServer: {
      contentBase: path.join(__dirname, 'public/dist'),
      disableHostCheck: true,
      port: 3060,
      publicPath: '/dist/',
    },
    watchOptions: {
      poll: 1000, // Check for changes every second
    },
    optimization: {
      moduleIds: 'deterministic',
      minimizer: [
        new TerserJSPlugin({
          terserOptions: {},
        }),
        new CssMinimizerPlugin(),
      ],
    },
    performance: {
      hints: false,
    },
    externals: {
      jquery: 'jQuery',
    },
    resolve: {
      alias: {
        '@': path.join(__dirname, 'src/assets'),
      },
      extensions: ['.js', '.jsx', '.ts', '.tsx'],
    },
    stats: {
      children: false,
      entrypoints: true,
      modules: false,
    },
  };
};
