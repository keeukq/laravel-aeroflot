const mix = require('laravel-mix');

// JS: берём resources/js/app.js, кладём готовый файл в public/js
mix.js('resources/js/app.js', 'public/js');

// SCSS: берём resources/scss/app.scss, кладём готовый файл в public/css
mix.sass('resources/scss/app.scss', 'public/css', {
    sassOptions: {
        quietDeps: true,                                   // не ругаться на код внутри Bootstrap
        silenceDeprecations: ['import', 'global-builtin', 'color-functions'],
    },
});
