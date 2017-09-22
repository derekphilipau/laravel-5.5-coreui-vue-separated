# Laravel 5.5 + CoreUI Admin Bootstrap Template VueJS

A clean install of Laravel 5.5 with the [CoreUI Full Bootstrap Admin Template VueJS Version](https://github.com/mrholek/CoreUI-Vue) added in. 

Based on [Laravel 5.4 with CoreUI (VueJS Full Starter Template)](https://github.com/Braunson/laravel-coreui-vue), however in this version we're using Laravel 5.5 and instead of copying CoreUI files directly into `/resources/assets` and `assets/js`,
the main CoreUI files are all contained in `/resources/coreui` while public files go to `/public/static`

We also specify Bootstrap 4, font-awesome and simple-line-icons package dependencies rather than using the CoreUI bundled versions.

## Contents
- [Getting Started](#getting-started)
	- [Install from repository](#install-from-repository)
    - [Step-by-step Clean Install](#step-by-step-clean-install)
        - [1. Install Laravel](#1-install-laravel)
        - [2. Add CoreUI-Vue repository](#2-add-coreui-vue-repository)
        - [3. Add Dependencies](#3-add-dependencies)
        - [4. Add CoreUI files to Mix](#4-add-coreui-files-to-mix)
        - [5. Update CoreUI app](#5-update-coreui-app)
        - [6. Change bootstrap import](#6-change-bootstrap-import)
        - [7. Add route & view for CoreUI](#7-add-route--view-for-coreui)
        - [8. Use the CoreUI JS](#8-use-the-coreui-js)
        - [9. Fix Paths](#9-fix-paths)

## Getting Started

You can either download the repository directly or follow the instructions here to make your own fresh install.

### Install from repository

Download & unpack the files, navigate to the directory and run:

    composer install

After it has completed, run:

    npm install
    
Copy the example .env file:
    
    cp .env.example .env

Generate an application key:

    php artisan key:generate

Run Mix tasks:

    npm run dev

View the website:

    php artisan serve


### Step-by-step Clean Install

#### 1. Install Laravel

    laravel new laravel-5.5-coreui-vue-separated
    cd laravel-5.5-coreui-vue-separated
    php artisan key:generate
    php artisan serve

At this point you should be able to see the familiar Laravel welcome screen in your browser.
For more information, see [Laravel 5.5 Installation](https://laravel.com/docs/5.5/installation)

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

Run `composer update` to add these packages.  At this point the CoreUI files should be in `vendor/mrholek/CoreUI-Vue/`

#### 3. Add Dependencies

In package.json, remove the `bootstrap-sass` line & add devDependencies:

    "bootstrap": "^4.0.0-beta",
    "bootstrap-vue": "^0.23.0",
    "chart.js": "2.6.0",
    "vue-chartjs": "2.8.2",
    "vue-router": "2.7.0",
    "font-awesome": "^4.7.0",
    "simple-line-icons": "^2.4.1"

Run `npm update`

#### 4. Add CoreUI files to Mix

In `webpack.mix.js`:

    // Copy over the CoreUI Assets into separate coreui directories
    var coreui_vendor = 'vendor/mrholek/CoreUI-Vue/Vue_Full_Project';
    mix.copyDirectory(coreui_vendor + '/static/img', 'public/static/img')
        .copyDirectory(coreui_vendor + '/scss', 'resources/coreui/scss')
        .copyDirectory(coreui_vendor + '/src', 'resources/coreui/src');
        
    mix.js('resources/assets/js/app.js', 'public/js')
       .sass('resources/assets/sass/app.scss', 'public/css');    

#### 5. Update CoreUI app

In `/resources/assets/sass/app.scss` comment out bootstrap and add in our package fonts (instead of CoreUI bundled fonts).

    // Bootstrap
    // Commented out as CoreUI already contains bootstrap
    //@import "~bootstrap-sass/assets/stylesheets/bootstrap";

    // Fonts
    @import "~font-awesome/scss/font-awesome"; // Font Awesome
    @import "~simple-line-icons/scss/simple-line-icons"; // Simple Line Icons
    
    // CoreUI Style
    @import "../../coreui/scss/style";


#### 6. Change bootstrap import

In `resources/coreui/scss/style.scss` change the bundled CoreUI bootstrap to our external one:

    // Import Bootstrap source files
    //@import "bootstrap/bootstrap";
    
    // If you want you can import bootstrap scss files directly from node_modules or bower_components.
    // To do this please remove @import "bootstrap/bootstrap"; and uncomment proper line.
    
    // Import Bootstrap source files from node_modules
    @import "node_modules/bootstrap/scss/bootstrap";

#### 7. Add route & view for CoreUI

In `routes/web.php`, comment out the welcome route and add a coreui route.  Because we will be using vue-router's "history" mode (step 10), we configure a route to catch all URL's.

    /*
    Route::get('/', function () {
        return view('welcome');
    });
    */
    
    /*
     * Getting started using Vue's Vue-router for single page apps
     * Matt Stauffer
     * https://mattstauffer.co/blog/getting-started-using-vues-vue-router-for-single-page-apps/
     *
     * If you're using this app within a Laravel app, instead of configuring nginx or Apache
     * to handle hashbang-less push state, you could configure your Laravel app to handle it;
     * just set up a capture route that grabs all valid URLs and passes them the view that's
     * outputting your Vue code.
     *
     */
    Route::get('/{vue_capture?}', function () {
        return view('coreui');
    })->where('vue_capture', '[\/\w\.-]*');


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

#### 8. Use the CoreUI JS

In `resources/assets/js/app.js` comment out everything and add:

    /**
     * First we will load all of this project's JavaScript dependencies which
     * includes Vue and other libraries. It is a great starting point when
     * building robust, powerful web applications using Vue and Laravel.
     */
    /*
    require('./bootstrap');
    
    window.Vue = require('vue');
    */
    /**
     * Next, we will create a fresh Vue application instance and attach it to
     * the page. Then, you may begin adding components to this application
     * or customize the JavaScript scaffolding to fit your unique needs.
     */
    /*
    Vue.component('example', require('./components/Example.vue'));
    
    const app = new Vue({
        el: '#app'
    });
    */
    
    require('../../coreui/src/main.js');

#### 9. Fix Paths

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

#### 10. Use Vue-router History mode rather than Hash

To eliminate those annoying '#' symbols from the browser URL, 
in `/resource/coreui/src/router/index.js` change mode from 'hash' to 'history':

    export default new Router({
      mode: 'history', // Demo is living in GitHub.io, so required!

More information: [HTML5 History Mode](https://router.vuejs.org/en/essentials/history-mode.html)

#### 11. Comment out the CoreUI mix copies

To eliminate overwriting these changes, comment out the copies in `webpack.mix.js`:

    /*
    // Copy over the CoreUI Assets into separate coreui directories
    var coreui_vendor = 'vendor/mrholek/CoreUI-Vue/Vue_Full_Project';
    mix.copyDirectory(coreui_vendor + '/static/img', 'public/static/img')
        .copyDirectory(coreui_vendor + '/scss', 'resources/coreui/scss')
        .copyDirectory(coreui_vendor + '/src', 'resources/coreui/src');
    */

#### 12. Run Mix and Serve

At this point, running the following should not have any errors:

    npm run dev
    php artisan serve


## Acknowledgments

* Based on: [Laravel 5.4 with CoreUI (VueJS Full Starter Template)](https://github.com/Braunson/laravel-coreui-vue)

