const mix = require('laravel-mix');

if (mix.inProduction()) {
    mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .extract([
        'vue'
    ])
    .version();
} else {
    mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .extract([
        'vue'
    ]);
}

mix.disableNotifications();
