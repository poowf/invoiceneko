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

Route::get('/', 'MainController@main')->name('main');

Route::get('/sendinv', 'MainController@testMail')->name('t.mail');


Route::get('/sinvoice', function() {
    $invoice = App\Models\Invoice::find(1);
    return new App\Mail\InvoiceMail($invoice);
});

/* Auth */
Route::get('/signin', 'AuthController@show')->name('auth.show');
Route::post('/signin', 'AuthController@process')->name('auth.process');
Route::post('/signout', 'AuthController@destroy')->name('auth.destroy');
Route::get('/forgot', 'AuthController@getForgotPassword')->name('forgot');
Route::post('/forgot', 'AuthController@postForgotPassword')->name('forgot');
Route::get('/reset/{token}', 'AuthController@getResetPassword')->name('reset');
Route::post('/reset/{token}', 'AuthController@postResetPassword')->name('reset');



/* User */
Route::get('/user/create', 'UserController@create')->name('user.create');
Route::post('/user/create', 'UserController@store')->name('user.store');

/* Company */
Route::get('/company/create', 'CompanyController@create')->name('company.create');
Route::post('/company/create', 'CompanyController@store')->name('company.store');


Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', 'MainController@dashboard')->name('dashboard');

    /* Client */
    Route::get('/clients', 'ClientController@index')->name('client.index');
    Route::get('/client/create', 'ClientController@create')->name('client.create');
    Route::post('/client/create', 'ClientController@store')->name('client.store');
    Route::get('/client/{client}', 'ClientController@show')->name('client.show');
    Route::get('/client/{client}/edit', 'ClientController@edit')->name('client.edit');
    Route::patch('/client/{client}/edit', 'ClientController@update')->name('client.update');
    Route::delete('/client/{client}/destroy', 'ClientController@destroy')->name('client.destroy');

    /* Invoice */
    Route::get('/invoices', 'InvoiceController@index')->name('invoice.index');
    Route::get('/invoice/create', 'InvoiceController@create')->name('invoice.create');
    Route::post('/invoice/create', 'InvoiceController@store')->name('invoice.store');
    Route::get('/invoice/{invoice}', 'InvoiceController@show')->name('invoice.show');
    Route::get('/invoice/{invoice}/download', 'InvoiceController@download')->name('invoice.download');
    Route::get('/invoice/{invoice}/printview', 'InvoiceController@printview')->name('invoice.printview');
    Route::get('/invoice/{invoice}/edit', 'InvoiceController@edit')->name('invoice.edit');
    Route::patch('/invoice/{invoice}/edit', 'InvoiceController@update')->name('invoice.update');
    Route::delete('/invoice/{invoice}/destroy', 'InvoiceController@destroy')->name('invoice.destroy');


    /* InvoiceItem */
    Route::delete('/invoice/item/{invoiceitem}/destroy', 'InvoiceItemController@destroy')->name('invoice.item.destroy');

});
