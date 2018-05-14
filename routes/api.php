<?php

use Illuminate\Http\Request;

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

Route::middleware(['guest'])->group(function () {
  Route::post('register', 'AuthenticateController@register');
  Route::post('login', 'AuthenticateController@login');
});

Route::post('refresh-token', 'AuthenticateController@refreshToken');

Route::middleware(['jwt.auth'])->group(function () {
  //Auth	
  Route::post('logout', 'AuthenticateController@logout');
  Route::get('users', 'AuthenticateController@index');

  //Products
  Route::get('products', 'ProductController@index');
  Route::get('product/{id}', 'ProductController@show');
  Route::post('create-product', 'ProductController@store');
  Route::put('product/{id}', 'ProductController@update');
  Route::delete('product/{id}', 'ProductController@destroy');
  Route::get('filter-products/{category}', 'ProductController@filterProducts');
  Route::get('search-product/{code}', 'ProductController@searchProduct');
  Route::post('select-product', 'ProductController@selectProduct');

  //Images
  Route::post('create-image', 'ImageController@store');
  Route::delete('image/{id}', 'ImageController@destroy');

  //Packing
  Route::post('create-packing', 'PackingController@store');
  Route::put('packing/{id}', 'PackingController@update');

  //Colors
  Route::get('colors', 'ColorController@index');
  Route::post('create-color', 'ColorController@store');

  //Categories
  Route::get('categories', 'CategoryController@index');
  Route::post('create-category', 'CategoryController@store');
  Route::put('category/{id}', 'CategoryController@update');
  Route::delete('category/{id}', 'CategoryController@destroy');

  //Prices
  Route::get('prices', 'PriceController@index');
  Route::post('create-price', 'PriceController@store');
  Route::put('price/{id}', 'PriceController@update');
  Route::delete('price/{id}', 'PriceController@destroy');

  //Quotations
  Route::get('quotations', 'QuotationController@index');
  Route::post('create-quotation', 'QuotationController@store');

  //Reports
  Route::post('reporte', 'ReportesController@savePDF');
  Route::post('delete', 'ReportesController@deletePDF');
});

Route::get('/test',function () {
  $items = array(
                  ['id' => 1, 'name' => 'rojo', 'size' => ['values' => ['L','M','S']]],
                  ['id' => 2, 'name' => 'verde', 'size' => ['values' => ['X','XXL']]]
                );
  //$format = '';
  //$format = implode(',' , $items[0]['size']['values']);
  //$productsId[$value] = ['size' => 'M,L,S'];
  $data = array();
  foreach ($items as $key => $value) {
    $format = implode(',' , $value['size']['values']);
    $data[$value['id']] = ['size' => $format];
  }
  //print_r($items);
  echo json_encode($data);


});