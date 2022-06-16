let mix = require('laravel-mix')

mix
    .setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .copy("./node_modules/leaflet/dist/images", "dist/vendor/leaflet/dist/images")
;
