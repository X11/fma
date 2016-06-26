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
    mix.sass('default.scss', 'public/css/default.css');
    mix.sass('green.scss', 'public/css/green.css');
    mix.scripts([
        'jquery/**/jquery.*.js',
        '**/*.js',
    ]);
    mix.copy('resources/assets/img', 'public/img');
    mix.version([
        'css/default.css',
        'css/green.css',
        'js/all.js',
    ]);
});
