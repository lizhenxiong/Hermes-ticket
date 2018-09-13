var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .setManifestKeyPrefix('build/')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('global', './assets/css/global.scss')
    .enableSassLoader()
    .enableVueLoader()
    .configureBabel(function(babelConfig) {
        babelConfig.presets.push('es2015');
    })
;

module.exports = Encore.getWebpackConfig();
