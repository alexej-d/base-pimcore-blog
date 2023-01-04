const path = require('path')
const webpack = require('webpack')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const TerserPlugin = require('terser-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const OptimizeCSSAssetsPlugin = require('css-minimizer-webpack-plugin')
const FaviconsWebpackPlugin = require('favicons-webpack-plugin')
const HtmlWebPackPlugin = require('html-webpack-plugin')

const IS_PRODUCTION = process.env.NODE_ENV === 'production'
const LOCAL_DOMAIN = process.env.DOMAIN || 'http://localhost.test'
const STATIC_DIR = path.resolve('./public/static')
const SRC_DIR = './assets'
const SCRIPT_DIR = SRC_DIR + '/scripts'
const STYLE_DIR = SRC_DIR + '/styles'
const WEBPACK_SERVER = { host: '0.0.0.0', port: '8080' }

const entry = {
  admin: `${SCRIPT_DIR}/admin.js`,
  editmode: [`${SCRIPT_DIR}/editmode.js`, `${STYLE_DIR}/editmode.scss`],
  main: [`${SCRIPT_DIR}/main.js`, `${STYLE_DIR}/main.scss`],
}

const alias = {
  config: path.resolve(__dirname, SCRIPT_DIR, 'config'),
  helpers: path.resolve(__dirname, SCRIPT_DIR, 'helpers'),
  modules: path.resolve(__dirname, SCRIPT_DIR, 'modules'),
  extensions: path.resolve(__dirname, SCRIPT_DIR, 'extensions'),
}

const output = {
  path: STATIC_DIR,
  filename: 'scripts/[name].js',
  sourceMapFilename: 'scripts/[file].map[query]',
  chunkFilename: IS_PRODUCTION
    ? 'scripts/[name]-[contenthash].js'
    : 'scripts/[name].js',
  publicPath: IS_PRODUCTION
    ? '/static/'
    : `//${WEBPACK_SERVER.host}:${WEBPACK_SERVER.port}/static/`,
}

/** @type {import('webpack-dev-server').Configuration} */
const devServer = {
  hot: true,
  https: false,
  host: WEBPACK_SERVER.host,
  port: WEBPACK_SERVER.port,
  allowedHosts: [new URL(LOCAL_DOMAIN).hostname],
  headers: {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
    'Access-Control-Allow-Headers':
      'X-Requested-With, content-type, Authorization',
  },
  proxy: {
    context: () => true,
    target: LOCAL_DOMAIN,
    autoRewrite: true,
    changeOrigin: true,
    secure: false,
  },
  devMiddleware: {
    index: '',
  },
}

const optimization = {
  moduleIds: 'deterministic',
  minimizer: [
    new TerserPlugin({
      terserOptions: {
        sourceMap: !IS_PRODUCTION, // set to true if you want JS source maps
        mangle: {
          reserved: ['$super'],
        },
        format: {
          comments: false,
        },
      },
      extractComments: false,
    }),
    new OptimizeCSSAssetsPlugin({}),
  ],
}

const rules = [
  {
    test: /.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
    exclude: /images/,
    type: 'asset/resource',
    generator: {
      filename: 'fonts/[hash][ext]',
    },
  },
  {
    test: /\.(gif|png|jpe?g|svg|webp)$/i,
    exclude: /fonts/,
    type: 'asset/resource',
    generator: {
      filename: 'images/[hash][ext]',
    },
  },
  {
    test: /\.m?jsx?$/,
    exclude: /node_modules\/(?!(dom7|swiper)\/).*/,
    use: {
      loader: 'babel-loader',
      options: {
        cacheDirectory: true,
      },
    },
  },
  {
    test: /\.(sa|sc|c)ss$/,
    use: [
      IS_PRODUCTION ? MiniCssExtractPlugin.loader : 'style-loader',
      {
        loader: 'css-loader',
        options: { sourceMap: !IS_PRODUCTION },
      },
      {
        loader: 'postcss-loader',
        options: { sourceMap: !IS_PRODUCTION },
      },
      {
        loader: 'sass-loader',
        options: {
          sourceMap: !IS_PRODUCTION,
          sassOptions: {
            precision: 10,
            outputStyle: 'expanded',
            includePaths: ['node_modules'],
          },
        },
      },
    ],
  },
]

const plugins = [
  new webpack.DefinePlugin({
    'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV),
  }),
  ...(IS_PRODUCTION
    ? [
        new MiniCssExtractPlugin({
          filename: 'styles/[name].css',
          chunkFilename: 'styles/[id]-[contenthash].css',
        }),
        new HtmlWebPackPlugin({
          filename: path.resolve('./templates/includes/manifest.html.twig'),
          template: SRC_DIR + '/includes/manifest.ejs',
          excludeChunks: Object.keys(entry),
          inject: false,
        }),
        new FaviconsWebpackPlugin({
          logo: SRC_DIR + '/images/favicon.svg',
          inject: true,
          prefix: 'images/',
          favicons: {
            lang: 'de-DE',
            background: '#000',
            theme_color: '#000',
            appName: process.env.APP_NAME || 'Testapp',
            loadManifestWithCredentials: true,
          },
        }),
      ]
    : []),
  new BrowserSyncPlugin(
    {
      notify: false,
      host: '0.0.0.0',
      localOnly: true,
      port: 3000,
      files: ['templates/**/*.twig'],
      proxy: `${WEBPACK_SERVER.host}:${WEBPACK_SERVER.port}`,
    },
    {
      reload: false,
    }
  ),
]

/** @type {import('webpack').Configuration} */
module.exports = {
  devServer,
  entry,
  output,
  plugins,
  optimization,
  resolve: { alias, extensions: ['.js', '.mjs', '.json'] },
  module: {
    rules,
  },
  devtool: 'source-map',
}
