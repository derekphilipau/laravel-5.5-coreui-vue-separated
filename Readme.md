# Laravel 5.5 + CoreUI Admin Bootstrap Template VueJS

A clean install of Laravel 5.5 with the [CoreUI Full Bootstrap Admin Template VueJS Version](https://github.com/mrholek/CoreUI-Vue) added in. 

It is the same idea as [Laravel 5.4 with CoreUI (VueJS Full Starter Template)](https://github.com/Braunson/laravel-coreui-vue), 
however in this version we're using Laravel 5.5 and instead of copying CoreUI files directly into `/resources/assets` and `assets/js`,
the main CoreUI files are all contained in `/resources/coreui` while public files go to `/public/static`

Unfortunately Bootstrap 4 has been bundled into CoreUI.  We specify font-awesome & simple-line-icons package dependencies rather than using the CoreUI bundled versions.

## Getting Started

You can either download the repository directly or follow the instructions here to make your own fresh install.

### Clean Install

#### 1. Install Laravel

    laravel new laravel-5.5-coreui-vue-separated
    cd laravel-5.5-coreui-vue-separated
    php artisan key:generate
    php artisan serve



#### 2. Add CoreUI-Vue repository

In composer.json, add a new repository:

    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "mrholek/CoreUI-Vue",
                "version": "dev-master",
                "dist": {
                    "url": "https://github.com/mrholek/CoreUI-Vue/archive/master.zip",
                    "type": "zip"
                }

            }
        }
    ],

And then require it:
    
    "require": {
        "php": ">=7.0.0",
        "fideloper/proxy": "~3.3",
        "laravel/framework": "5.5.*",
        "laravel/tinker": "~1.0",
        "mrholek/CoreUI-Vue": "dev-master"
    },

#### 3. Add Dependencies

In package.json, remove the `bootstrap-sass` line & add devDependencies:

    "bootstrap-vue": "^0.23.0",
    "chart.js": "2.6.0",
    "vue-chartjs": "2.8.2",
    "vue-router": "2.7.0",
    "font-awesome": "^4.7.0",
    "simple-line-icons": "^2.4.1"

Run `composer update` to add these packages.

#### 4. Add CoreUI files to Mix

In `webpack.mix.js`:

    // Copy over the CoreUI Assets into separate coreui directories
    var coreui_vendor = 'vendor/mrholek/CoreUI-Vue/Vue_Full_Project';
    mix.copyDirectory(coreui_vendor + '/static/img', 'public/static/img')
        .copyDirectory(coreui_vendor + '/scss', 'resources/coreui/scss')
        .copyDirectory(coreui_vendor + '/src', 'resources/coreui/src');



#### 5. Update CoreUI app

In `/resources/assets/sass/app.scss` add in our package fonts (instead of CoreUI bundled fonts).

    // Fonts
    @import "~font-awesome/scss/font-awesome"; // Font Awesome
    @import "~simple-line-icons/scss/simple-line-icons"; // Simple Line Icons

Comment out bootstrap (it's already included by CoreUI):

    // Bootstrap Comment out
    //@import "~bootstrap-sass/assets/stylesheets/bootstrap";

Add in the CoreUI bootstrap and style:

    // Bootstrap - unfortunate, but use CoreUI bootstraps.
    // TODO: replace w/ regular bootstrap
    @import "coreui/bootstrap";
    
    // CoreUI Style
    @import "../../coreui/scss/style";
    
#### 6. Add route & view for CoreUI

In `routes/web.php`, comment out the welcome route and add a coreui route:

    /*
    Route::get('/', function () {
        return view('welcome');
    });
    */
    
    Route::get('/', function () {
        return view('coreui');
    });

Add a new view in `resources/views/coreui.blade.php`:

    <!DOCTYPE html>
    <html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        <title>Laravel - CoreUI Example</title>
    
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    
        <!-- BODY options, add following classes to body to change options
    
        // Header options
        1. '.header-fixed'					- Fixed Header
    
        // Sidebar options
        1. '.sidebar-fixed'					- Fixed Sidebar
        2. '.sidebar-hidden'				- Hidden Sidebar
        3. '.sidebar-off-canvas'		- Off Canvas Sidebar
        4. '.sidebar-compact'				- Compact Sidebar Navigation (Only icons)
    
        // Aside options
        1. '.aside-menu-fixed'			- Fixed Aside Menu
        2. '.aside-menu-hidden'			- Hidden Aside Menu
        3. '.aside-menu-off-canvas'	- Off Canvas Aside Menu
    
        // Footer options
        1. '.footer-fixed'						- Fixed footer
    
        -->
    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    
    <div id="app"></div>
    <script src="{{ mix('js/app.js') }}"></script>
    </body>
    </html>

#### 7. Use the CoreUI JS

In `resources/assets/js/app.js` comment out everything and add:

    // Include CoreUI JS
    require('../../coreui/src/main.js');

#### 8. Fix Paths

Run Mix tasks, copying over CoreUI files:

    npm run dev

At this point Mix will fail, as some of the paths need to be changed.

In `/resources/coreui/scss/core/_variables.scss`, change the logo path to:

    $navbar-brand-logo:     url('/static/img/logo.png') !default;

In `resources/coreui/src/router/index.js` change all Vue paths from '@' to relative '..':

For example, `import Full from '@/containers/Full'` should become `import Full from '../containers/Full'`

In `/resources/coreui/src/App.vue`, update the import to:

    <style lang="scss">
      // Import Main styles for this application
      @import "../scss/style";
    </style>

#### 9. Use Vue-router History mode rather than Hash

To eliminate those annoying '#' symbols from the browser URL, 
in `/resource/coreui/src/router/index.js` change mode from 'hash' to 'history':

    export default new Router({
      mode: 'history', // Demo is living in GitHub.io, so required!

#### 10. Comment out the CoreUI mix copies

To eliminate overwriting these changes, comment out the copies in `webpack.mix.js`:

    /*
    // Copy over the CoreUI Assets into separate coreui directories
    var coreui_vendor = 'vendor/mrholek/CoreUI-Vue/Vue_Full_Project';
    mix.copyDirectory(coreui_vendor + '/static/img', 'public/static/img')
        .copyDirectory(coreui_vendor + '/scss', 'resources/coreui/scss')
        .copyDirectory(coreui_vendor + '/src', 'resources/coreui/src');
    */

#### 11. Run Mix and Serve

At this point, running the following should not have any errors:

    npm run dev
    php artisan serve


## Acknowledgments

* Hat tip to anyone who's code was used
* Inspiration
* etc

