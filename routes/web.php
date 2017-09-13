<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
