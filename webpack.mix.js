let mix = require('laravel-mix')
let path = require('path')

mix.alias({
    'laravel-nova': path.join(__dirname, 'vendor/laravel/nova/resources/js/mixins/packages.js'),
})

mix
    .setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue()
    .copy("./node_modules/leaflet/dist/images", "dist/vendor/leaflet/dist/images")
;
