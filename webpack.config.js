var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.scss.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/admin', './assets/js/sb-admin-2.js')

    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/admin', './assets/css/sb-admin-2.css')
    // .createSharedEntry('common', {
    //     'public/vendor/bootstrap/bootstrap.css',
    // })

    // uncomment if you use Sass/SCSS files
     .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
