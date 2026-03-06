const mix = require('laravel-mix');

require('laravel-mix-polyfill');

/*const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
mix.webpackConfig({
    plugins: [new BundleAnalyzerPlugin()],
});*/

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer')
    ])
    .alias({
        '@': 'resources/js',
    })
    .polyfill({
        enabled: true,
        useBuiltIns: "usage",
        targets: {"firefox": "50", "ie": 11, 'chrome': 52, 'safari': 8, 'edge': 14}
    })
    .extract();

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}