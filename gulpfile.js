var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


elixir(function(mix) {
    mix.styles([
        './public/css/bootstrap.css',
        './public/css/custome.css'
    ], './public/css/separate/main.css')
        .styles([
            './public/css/404.css'
        ], './public/css/separate/404.css')

    // minify main js
    mix.scripts([
        './public/js/jquery.js',
        './public/js/bootstrap.js'
    ], './public/js/separate/main.js')
        .scripts([
            './public/js/404.js'
        ], './public/js/separate/404.js')

        // combining details js
        .combine([
            './public/js/jquery.js',
            './public/js/bootstrap.js',
            './public/js/404.js'
        ], './public/js/separate/main.js')
        .version([
            'public/css/separate/main.css',
            'public/js/separate/main.js',
            'public/css/separate/404.css',
            'public/js/separate/404.js'
        ])

});