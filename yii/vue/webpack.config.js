// Модуль nodejs для работы с путями
const path = require('path');
// Модуль для .vue файлов
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
  mode: 'development',
  entry: './src/main.js',
  output: {
    filename: 'app.js',
    path: path.resolve(__dirname, '../web/js/vue'), // path.resolve превращает аргументы в путь
  },
  //  Интсрукции по обработки файлов
  module: {
    rules: [
      {
        test: /\.vue$/, // regex
        loader: 'vue-loader' // Любой .vue файл обрабатывается vue-loader
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader'
        }
      },
      {
        // Обработка css для использования <style> в vue
        test: /\.css$/,
        use: ['style-loader', 'css-loader']
      }
    ]
  },
  // Правила поиска импортов
  resolve: {
    alias: {
      vue$: 'vue/dist/vue.esm.js'
    },
    extensions: ['*', '.js', '.vue'] // Позволяют писать импорты удобнее
  },
  plugins: [
    // Модуль для обработки Vue
    new VueLoaderPlugin()
  ]
};
