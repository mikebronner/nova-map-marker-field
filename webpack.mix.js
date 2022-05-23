let mix = require('laravel-mix')
let path = require('path')

require('./nova.mix')

mix
    .setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue()
    .copy("./node_modules/leaflet/dist/images", "dist/vendor/leaflet/dist/images")
    .alias({
        '@': path.join(__dirname, 'resources/js/'),
    })
    .nova('{{ name }}')
