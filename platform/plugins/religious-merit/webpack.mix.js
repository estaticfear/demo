let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/plugins/' + directory;
const dist = 'public/vendor/core/plugins/' + directory;

mix
    // .sass(source + '/resources/assets/sass/rm.scss', dist + '/css')
    // .js(source + '/resources/assets/js/rm.js', dist + '/js')
    .js(source + '/resources/assets/js/rm-admin.js', dist + '/js')
    .react()
    .sass(source + '/resources/assets/sass/rm-public.scss', dist + '/css')
    .js(source + '/resources/assets/js/rm-public.js', dist + '/js')
    .js(source + '/resources/assets/js/modal-merit.js', dist + '/js')
    .js(source + '/resources/assets/js/modal-merit-effort.js', dist + '/js')
    .js(source + '/resources/assets/js/modal-merit-artifact.js', dist + '/js')
    .copy(source + '/public/js/rm-slick-public.min.js', dist + '/js')
    .copy(source + '/public/css/rm-slick-public.min.css', dist + '/css')
    .vue({ version: 2 })
    .copy(source + '/public/images/*', dist + '/images');

if (mix.inProduction()) {
    mix
        // .copy(dist + '/css/rm.css', source + '/public/css')
        .copy(dist + '/css/rm-public.css', source + '/public/css')
        // .copy(dist + '/js/rm.js', source + '/public/js')
        .copy(dist + '/js/rm-public.js', source + '/public/js');
}
