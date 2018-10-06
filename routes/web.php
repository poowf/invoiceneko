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
Route::get('/invoice/view', 'InvoiceController@showwithtoken')->name('invoice.token');

Route::group(['middleware' => ['guest']], function() {
    /* Auth */
    Route::get('/signin', 'AuthController@show')->name('auth.show');
    Route::post('/signin', 'AuthController@process')->name('auth.process');
    Route::get('/forgot', 'ForgotPasswordController@show')->name('forgot');
    Route::post('/forgot', 'ForgotPasswordController@process')->name('forgot');
    Route::get('/reset/{token}', 'ResetPasswordController@show')->name('reset');
    Route::post('/reset/{token}', 'ResetPasswordController@process')->name('reset');

    /* User */
    Route::get('/user/create', 'UserController@create')->name('user.create');
    Route::post('/user/create', 'UserController@store')->name('user.store');

    /* Company */
    Route::get('/company/create', 'CompanyController@create')->name('company.create');
    Route::post('/company/create', 'CompanyController@store')->name('company.store');
});

Route::group(['middleware' => ['auth']], function() {
    Route::post('/signout', 'AuthController@destroy')->name('auth.destroy');

    Route::get('/errors/nocompany', 'MainController@nocompany')->name('nocompany');
    Route::get('/dashboard', 'MainController@dashboard')->name('dashboard');

    /* User */
    Route::get('/user/edit', 'UserController@edit')->name('user.edit');
    Route::patch('/user/edit', 'UserController@update')->name('user.update');

    /* Company */
    Route::get('/company/edit', 'CompanyController@edit')->name('company.edit');
    Route::patch('/company/edit', 'CompanyController@update')->name('company.update');

    /* CompanyAddress */
    Route::get('/company/address/edit', 'CompanyAddressController@edit')->name('company.address.edit');
    Route::patch('/company/address/edit', 'CompanyAddressController@update')->name('company.address.update');

    Route::get('/company/settings/edit', 'CompanySettingsController@edit')->name('company.settings.edit');
    Route::patch('/company/settings/edit', 'CompanySettingsController@update')->name('company.settings.update');

    Route::group(['middleware' => ['hascompany']], function() {

        /* Migration */
        Route::get('/migration/', 'DataMigrationController@create')->name('migration.create');
        Route::post('/migration/import/contact', 'DataMigrationController@storecontact')->name('migration.import.contact');
        Route::post('/migration/import/invoice', 'DataMigrationController@storeinvoice')->name('migration.import.invoice');
        Route::post('/migration/import/payment', 'DataMigrationController@storepayment')->name('migration.import.payment');

        /* Clients */
        Route::get('/clients', 'ClientController@index')->name('client.index');
        Route::get('/client/create', 'ClientController@create')->name('client.create');
        Route::get('/client/{client}/invoicecreate', 'ClientController@invoicecreate')->name('client.invoice.create');
        Route::post('/client/create', 'ClientController@store')->name('client.store');
        Route::get('/client/{client}', 'ClientController@show')->name('client.show');
        Route::get('/client/{client}/edit', 'ClientController@edit')->name('client.edit');
        Route::patch('/client/{client}/edit', 'ClientController@update')->name('client.update');
        Route::delete('/client/{client}/destroy', 'ClientController@destroy')->name('client.destroy');

        /* Invoice */
        Route::get('/invoices', 'InvoiceController@index')->name('invoice.index');
        Route::get('/invoice/create', 'InvoiceController@create')->name('invoice.create');
        Route::post('/invoice/create', 'InvoiceController@store')->name('invoice.store');
        Route::get('/invoice/{invoice}', 'InvoiceController@show')->name('invoice.show')->middleware('can:view,invoice');
        Route::get('/invoice/{invoice}/download', 'InvoiceController@download')->name('invoice.download')->middleware('can:view,invoice');
        Route::get('/invoice/{invoice}/printview', 'InvoiceController@printview')->name('invoice.printview')->middleware('can:view,invoice');
        Route::get('/invoice/{invoice}/edit', 'InvoiceController@edit')->name('invoice.edit')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/edit', 'InvoiceController@update')->name('invoice.update')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/archive', 'InvoiceController@archive')->name('invoice.archive')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/writeoff', 'InvoiceController@writeoff')->name('invoice.writeoff')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/share', 'InvoiceController@share')->name('invoice.share')->middleware('can:view,invoice');
        Route::delete('/invoice/{invoice}/destroy', 'InvoiceController@destroy')->name('invoice.destroy')->middleware('can:delete,invoice');

        Route::get('/invoice/adhoc/create', 'InvoiceController@adhoccreate')->name('invoice.adhoc.create');

        /* Quotes */
        Route::get('/quotes', 'QuoteController@index')->name('quotes.index');

        /* OldInvoice */
        Route::get('/oldinvoice/{oldinvoice}', 'OldInvoiceController@show')->name('invoice.old.show')->middleware('can:view,oldinvoice');
        Route::get('/oldinvoice/{oldinvoice}/download', 'OldInvoiceController@download')->name('invoice.old.download')->middleware('can:view,oldinvoice');
        Route::get('/oldinvoice/{oldinvoice}/printview', 'OldInvoiceController@printview')->name('invoice.old.printview')->middleware('can:view,oldinvoice');

        /* Invoice History */
        Route::get('/invoice/{invoice}/history', 'InvoiceController@history')->name('invoice.history.show')->middleware('can:view,invoice');

        /* InvoiceItem */
        Route::delete('/invoice/item/{invoiceitem}/destroy', 'InvoiceItemController@destroy')->name('invoice.item.destroy')->middleware('can:delete,invoiceitem');

        /* Payment */
        Route::get('/payments', 'PaymentController@index')->name('payment.index');
        Route::get('/invoice/{invoice}/payment/create', 'PaymentController@create')->name('payment.create')->middleware('can:update,invoice');
        Route::post('/invoice/{invoice}/payment/create', 'PaymentController@store')->name('payment.store')->middleware('can:update,invoice');
        Route::get('/payment/create', 'PaymentController@createsolo')->name('payment.createsolo');
        Route::post('/payment/create', 'PaymentController@storesolo')->name('payment.storesolo');
        Route::get('/payment/{payment}', 'PaymentController@show')->name('payment.show')->middleware('can:update,payment');
        Route::get('/payment/{payment}/edit', 'PaymentController@edit')->name('payment.edit')->middleware('can:update,payment');
        Route::patch('/payment/{payment}/edit', 'PaymentController@update')->name('payment.update')->middleware('can:update,payment');
        Route::delete('/payment/{payment}/destroy', 'PaymentController@destroy')->name('payment.destroy')->middleware('can:delete,payment');
    });
});
