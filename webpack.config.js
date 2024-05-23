// webpack.config.js

const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', '/assets/js/app.js')  
    .addStyleEntry('flaticon_styles', '/assets/scss/app.scss')  
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .autoProvidejQuery()
    .enableSassLoader()
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            // Your PostCSS plugins and configuration go here
            // For example:
            plugins: [
                require('autoprefixer'),
                // Add other PostCSS plugins as needed
            ],
        };
    })
;

module.exports = Encore.getWebpackConfig();
