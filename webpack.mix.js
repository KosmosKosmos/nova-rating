let mix = require('laravel-mix')

mix.js('resources/js/rating.js', 'dist/js');

module.exports = {
    configureWebpack: {
        devtool: 'nosources-source-map',
    }
}
