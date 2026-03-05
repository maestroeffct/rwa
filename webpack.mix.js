const mix = require('laravel-mix');
let fs = require('fs');
let path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


// Recursive function to get all files and directories
let getFilesAndFolders = function (dir) {
    let results = [];
    fs.readdirSync(dir).forEach(file => {
        let fullPath = path.join(dir, file);
        let stats = fs.statSync(fullPath);

        if (stats.isFile()) {
            results.push(fullPath); // Add files directly
        } else if (stats.isDirectory()) {
            // Recursively process subdirectories
            let subFiles = getFilesAndFolders(fullPath);
            results = results.concat(subFiles);
        }
    });
    return results;
};

/*
 |--------------------------------------------------------------------------
 | Custom Mix Asset
 |--------------------------------------------------------------------------
 | All .js and .css files are automatically compiled
 | and you don't need to manually add the file to the mix.
 |
 */

mix.options({
    processCssUrls: false
});


const assetsFiles = [
    // Js
    {resource: 'resources/js/design_1/parts', output: 'public/assets/design_1/js/parts/'},
    {resource: 'resources/js/design_1/panel', output: 'public/assets/design_1/js/panel/'},
    {resource: 'resources/js/design_1/landing_builder', output: 'public/assets/design_1/landing_builder/js/'},
    {resource: 'resources/js/admin/parts', output: 'public/assets/admin/js/parts/'},

    // Sass
    {resource: 'resources/sass/design_1/parts', output: 'public/assets/design_1/css/parts/'},
    {resource: 'resources/sass/design_1/landing_builder/components', output: 'public/assets/design_1/landing_builder/components/'},
]

for (const assetFile of assetsFiles) {
    const baseResourcePath = assetFile.resource;
    const baseOutputPath = assetFile.output;

    // Get all files and folders
    getFilesAndFolders(baseResourcePath).forEach(filepath => {
        let relativePath = path.relative(baseResourcePath, filepath); // Relative path from base folder
        let outputPath = path.join(baseOutputPath, path.dirname(relativePath)); // Match folder structure in output

        // Compile `.scss` or `.js` files
        if (filepath.endsWith('.js')) {
            const minName = path.basename(filepath).replace('.js', '.min.js');
            mix.js(filepath, path.join(outputPath, minName));
        } else if (filepath.endsWith('.scss')) {
            const minName = path.basename(filepath).replace('.scss', '.min.css');
            mix.sass(filepath, path.join(outputPath, minName));
        }
    });
}

// Common Files
mix
    .js('resources/js/design_1/app.js', 'public/assets/design_1/js/app.min.js')

    // Admin js
    .js('resources/js/admin/admin.js', 'public/assets/admin/js/admin.min.js')

    // scss
    .sass('resources/sass/admin/app.scss', 'public/assets/admin/css/extra.min.css')
    .sass('resources/sass/design_1/app.scss', 'public/assets/design_1/css/app.min.css')
    .sass('resources/sass/design_1/panel.scss', 'public/assets/design_1/css/panel.min.css')
    .sass('resources/sass/design_1/rtl-app.scss', 'public/assets/design_1/css/rtl-app.min.css')
    .sass('resources/sass/design_1/landing_builder.scss', 'public/assets/design_1/landing_builder/app.min.css')
    .sass('resources/sass/design_1/landing_builder/front.scss', 'public/assets/design_1/landing_builder/front.min.css')
;

