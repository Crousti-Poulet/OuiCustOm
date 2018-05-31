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
    .addEntry('js/bootstrap3', './assets/vendor/bootstrap/js/bootstrap.js')
    // .addEntry('js/jquery.dataTables.min', './assets/vendor/datatables/js/jquery.dataTables.min.js')
    // .addEntry('js/dataTables.bootstrap.min', './assets/vendor/datatables-plugins/dataTables.bootstrap.min.js')
    // .addEntry('js/dataTables.responsive', './assets/vendor/datatables-responsive/dataTables.responsive.js')

    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/admin', './assets/css/sb-admin-2.css')
    .addStyleEntry('css/bootstrap3', './assets/vendor/bootstrap/css/bootstrap.css')
    // .addStyleEntry('css/dataTables.bootstrap', './assets/vendor/datatables-plugins/dataTables.bootstrap.css')
    // .addStyleEntry('css/dataTables.responsive', './assets/vendor/datatables-responsive/dataTables.responsive.css')
    // .createSharedEntry('common', {
    //     'public/vendor/bootstrap/bootstrap.css',
    // })

    // uncomment if you use Sass/SCSS files
     .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
