var elixir = require('laravel-elixir');
elixir(function(mix) {
    mix.styles([
        './node_modules/bootstrap/dist/css/bootstrap.css',
        './web/assets/css/AdminLTE.css',
        './node_modules/admin-lte/dist/css/skins/skin-green-light.css',
        './node_modules/font-awesome/css/font-awesome.css',
    ], 'web/assets/css/vendor.css');
    // mix.styles([
    //     './resources/assets/css/adminui.css',
    // ], 'web/assets/css/adminui.css');
    mix.scripts([
        './node_modules/jquery/dist/jquery.js',
        './node_modules/bootstrap/dist/js/bootstrap.js',
        './node_modules/admin-lte/dist/js/app.js',
    ], 'web/assets/js/vendor.js');
    // mix.scripts([
    //     './resources/assets/js/adminui.js',
    // ], 'web/assets/js/adminui.js');
    mix.copy('./node_modules/font-awesome/fonts', 'web/assets/fonts');
    // mix.copy('./resources/assets/js/pages', 'web/assets/js/pages');
});