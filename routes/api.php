<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('autenticado', 'UserController@authenticated')->middleware('auth:api');
Route::post('logout', 'UserController@logout');
Route::post('oauth/token', 'Auth\CustomAccessTokenController@issueUserToken');

Route::group(['middleware' => ['auth:api', 'acl:api']], function () {
    //Products
    Route::get('products', 'ProductController@index')->name('products.index');
    Route::get('product/{id}', 'ProductController@show')->name('products.show');
    Route::post('create-product', 'ProductController@store')->name('products.create');
    Route::put('product/{id}', 'ProductController@update')->name('products.update');
    Route::delete('product/{id}', 'ProductController@destroy')->name('products.destroy');
    Route::get('filter-products/{category}', 'ProductController@filterProducts');
    Route::get('search-product/{code}', 'ProductController@searchProduct');
    Route::post('select-product', 'ProductController@selectProduct');
    Route::get('max-products', 'ProductController@maxProducts');

    //Images
    Route::post('create-image', 'ImageController@store')->name('products.create');
    Route::delete('image/{id}', 'ImageController@destroy')->name('products.create');

    //Packing
    Route::post('create-packing', 'PackingController@store')->name('products.create');
    Route::put('packing/{id}', 'PackingController@update')->name('products.show');

    //Colors
    Route::get('colors', 'ColorController@index');
    Route::post('create-color', 'ColorController@store')->name('products.create');

    //Categories
    Route::get('categories', 'CategoryController@index')->name('categories.index');
    Route::get('list-categories', 'CategoryController@list');
    Route::get('category/{id}', 'CategoryController@show');
    Route::post('create-category', 'CategoryController@store')->name('categories.create');
    Route::put('category/{id}', 'CategoryController@update')->name('categories.update');
    Route::delete('category/{id}', 'CategoryController@destroy')->name('categories.destroy');

    //Prices
    Route::get('prices', 'PriceController@index')->name('prices.index');
    Route::get('price/{id}', 'PriceController@show')->name('prices.show');
    Route::post('create-price', 'PriceController@store')->name('prices.create');
    Route::put('price/{id}', 'PriceController@update')->name('prices.update');
    Route::delete('price/{id}', 'PriceController@destroy')->name('prices.destroy');

    //Quotations
    Route::get('quotations', 'QuotationController@index')->name('quotations.index');
    Route::get('quotation/{id}', 'QuotationController@show')->name('quotations.show');
    Route::post('create-quotation', 'QuotationController@store')->name('quotations.create');

    //Profiles
    Route::get('profiles', 'ProfileController@index')->name('profiles.index');
    Route::get('profile/{id}', 'ProfileController@show')->name('profiles.show');
    Route::post('create-profile', 'ProfileController@store')->name('profiles.create');
    Route::put('profile/{id}', 'ProfileController@update')->name('profiles.update');
    Route::delete('profile/{id}', 'ProfileController@destroy')->name('profiles.destroy');

    //Actions
    Route::get('actions', 'ActionController@index');

    //Users
    Route::get('users', 'UserController@index')->name('users.index');
    Route::get('user/{id}', 'UserController@show')->name('users.show');
    Route::post('create-user', 'UserController@store')->name('users.create');
    Route::put('user/{id}', 'UserController@update')->name('users.update');
    Route::delete('user/{id}', 'UserController@destroy')->name('users.destroy');

    //Reports
    Route::post('reporte', 'ReportesController@savePDF');
    Route::post('delete', 'ReportesController@deletePDF');
});
