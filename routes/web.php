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

Route::get('/invoice/view', 'InvoiceController@showwithtoken')->name('invoice.token');
Route::get('/quote/view', 'QuoteController@showwithtoken')->name('quote.token');

Route::group(['middleware' => ['guest']], function() {
    /* Auth */
    Route::get('/', 'MainController@main')->name('main');
    Route::get('/signin', 'AuthController@show')->name('auth.show');
    Route::post('/signin', 'AuthController@process')->name('auth.process');
    Route::get('/forgot', 'ForgotPasswordController@show')->name('forgot');
    Route::post('/forgot', 'ForgotPasswordController@process')->name('forgot');
    Route::get('/reset/{token}', 'ResetPasswordController@show')->name('reset');
    Route::post('/reset/{token}', 'ResetPasswordController@process')->name('reset');

    Route::get('/start', 'MainController@start')->name('start');

    /* User */
    Route::get('/user/create', 'UserController@create')->name('user.create');
    Route::post('/user/create', 'UserController@store')->name('user.store');

    /* Company */
    Route::get('/company/create', 'CompanyController@create')->name('company.create');
    Route::post('/company/create', 'CompanyController@store')->name('company.store');
    Route::get('/company/check', 'CompanyController@show_check')->name('company.show_check');
    Route::post('/company/check', 'CompanyController@check')->name('company.check');

    Route::get('/company/requests/create', 'CompanyUserRequestController@create')->name('company.requests.create');
    Route::post('/company/requests/create', 'CompanyUserRequestController@store')->name('company.requests.store');

});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/user/multifactor/backup', 'UserController@multifactor_backup')->name('user.multifactor.backup');
    Route::post('/user/multifactor/backup', 'UserController@multifactor_backup_validate')->name('user.multifactor.backup_validate');
    Route::post('/signout', 'AuthController@destroy')->name('auth.destroy');
});

Route::group(['middleware' => ['auth', '2fa']], function() {
    Route::post('/multifactor/validate', 'AuthController@multifactor_validate')->name('auth.multifactor.validate');
    Route::get('/errors/nocompany', 'MainController@nocompany')->name('nocompany');

    /* User */
    Route::get('/user/edit', 'UserController@edit')->name('user.edit');
    Route::patch('/user/edit', 'UserController@update')->name('user.update');
    Route::get('/user/security', 'UserController@security')->name('user.security');
    Route::post('/user/multifactor/start', 'UserController@multifactor_start')->name('user.multifactor.start');
    Route::get('/user/multifactor/create', 'UserController@multifactor_create')->name('user.multifactor.create');
    Route::post('/user/multifactor/create', 'UserController@multifactor_store')->name('user.multifactor.store');
    Route::post('/user/multifactor/regenerate_codes', 'UserController@multifactor_regenerate_codes')->name('user.multifactor.regenerate_codes');
    Route::delete('/user/multifactor/destroy', 'UserController@multifactor_destroy')->name('user.multifactor.destroy');

    /* Company */
    Route::get('/company/show', 'CompanyController@show')->name('company.show');
    Route::get('/company/edit', 'CompanyController@edit')->name('company.edit')->middleware('can:owner,App\Models\Company');
    Route::patch('/company/edit', 'CompanyController@update')->name('company.update')->middleware('can:owner,App\Models\Company');

    Route::group(['middleware' => ['hascompany']], function() {
        Route::get('/dashboard', 'MainController@dashboard')->name('dashboard');

        //TODO:Middleware check to ensure that the user logged in is an owner and that the user retrieved is a member of the company
        Route::get('/user/{user}/retrieve', 'UserController@retrieve')->name('user.retrieve');
        /* Company */
        Route::get('/company/owner/edit', 'CompanyController@edit_owner')->name('company.owner.edit')->middleware('can:owner,App\Models\Company');
        Route::patch('/company/owner/edit', 'CompanyController@update_owner')->name('company.owner.update')->middleware('can:owner,App\Models\Company');
        Route::get('/company/users', 'CompanyUserController@index')->name('company.users.index')->middleware('can:owner,App\Models\Company');
        Route::get('/company/users/create', 'CompanyUserController@create')->name('company.users.create')->middleware('can:owner,App\Models\Company');
        Route::post('/company/users/create', 'CompanyUserController@store')->name('company.users.store')->middleware('can:owner,App\Models\Company');
        Route::get('/company/users/{user}/edit', 'CompanyUserController@edit')->name('company.users.edit')->middleware('can:owner,App\Models\Company');
        Route::patch('/company/users/{user}/edit', 'CompanyUserController@update')->name('company.users.update')->middleware('can:owner,App\Models\Company');
        Route::delete('/company/users/{user}/destroy', 'CompanyUserController@destroy')->name('company.users.destroy')->middleware('can:owner,App\Models\Company');

        /* CompanyAddress */
        Route::get('/company/address/edit', 'CompanyAddressController@edit')->name('company.address.edit')->middleware('can:owner,App\Models\Company');
        Route::patch('/company/address/edit', 'CompanyAddressController@update')->name('company.address.update')->middleware('can:owner,App\Models\Company');

        /* CompanySettings */
        Route::get('/company/settings/edit', 'CompanySettingsController@edit')->name('company.settings.edit')->middleware('can:owner,App\Models\Company');
        Route::patch('/company/settings/edit', 'CompanySettingsController@update')->name('company.settings.update')->middleware('can:owner,App\Models\Company');

        Route::get('/company/requests', 'CompanyUserRequestController@index')->name('company.requests.index');
        Route::post('/company/requests/{companyuserrequest}/approve', 'CompanyUserRequestController@approve')->name('company.requests.approve');
        Route::post('/company/requests/{companyuserrequest}/reject', 'CompanyUserRequestController@reject')->name('company.requests.reject');

        /* Migration */
        Route::get('/migration/', 'DataMigrationController@create')->name('migration.create');
        Route::post('/migration/import/contact', 'DataMigrationController@storecontact')->name('migration.import.contact');
        Route::post('/migration/import/invoice', 'DataMigrationController@storeinvoice')->name('migration.import.invoice');
        Route::post('/migration/import/payment', 'DataMigrationController@storepayment')->name('migration.import.payment');

        /* Clients */
        Route::get('/clients', 'ClientController@index')->name('client.index');
        Route::get('/client/create', 'ClientController@create')->name('client.create');
        Route::get('/client/{client}/invoicecreate', 'ClientController@invoicecreate')->name('client.invoice.create')->middleware('can:update,client');
        Route::post('/client/create', 'ClientController@store')->name('client.store');
        Route::get('/client/{client}', 'ClientController@show')->name('client.show')->middleware('can:view,client');
        Route::get('/client/{client}/edit', 'ClientController@edit')->name('client.edit')->middleware('can:update,client');
        Route::patch('/client/{client}/edit', 'ClientController@update')->name('client.update')->middleware('can:update,client');
        Route::delete('/client/{client}/destroy', 'ClientController@destroy')->name('client.destroy')->middleware('can:delete,client');

        /* Invoice */
        Route::get('/invoices', 'InvoiceController@index')->name('invoice.index');
        Route::get('/invoices/archived', 'InvoiceController@index_archived')->name('invoice.index.archived');
        Route::get('/invoice/create', 'InvoiceController@create')->name('invoice.create');
        Route::post('/invoice/create', 'InvoiceController@store')->name('invoice.store');
        Route::get('/invoice/{invoice}', 'InvoiceController@show')->name('invoice.show')->middleware('can:view,invoice');
        Route::post('/invoice/{invoice}/duplicate', 'InvoiceController@duplicate')->name('invoice.duplicate')->middleware('can:update,invoice');
        Route::post('/invoice/{invoice}/convert', 'InvoiceController@convertToQuote')->name('invoice.convert')->middleware('can:view,invoice');
        Route::get('/invoice/{invoice}/download', 'InvoiceController@download')->name('invoice.download')->middleware('can:view,invoice');
        Route::get('/invoice/{invoice}/printview', 'InvoiceController@printview')->name('invoice.printview')->middleware('can:view,invoice');
        Route::get('/invoice/{invoice}/edit', 'InvoiceController@edit')->name('invoice.edit')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/edit', 'InvoiceController@update')->name('invoice.update')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/archive', 'InvoiceController@archive')->name('invoice.archive')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/writeoff', 'InvoiceController@writeoff')->name('invoice.writeoff')->middleware('can:update,invoice');
        Route::patch('/invoice/{invoice}/share', 'InvoiceController@share')->name('invoice.share')->middleware('can:view,invoice');
        Route::post('/invoice/{invoice}/send', 'InvoiceController@sendnotification')->name('invoice.send')->middleware('can:view,invoice');
        Route::delete('/invoice/{invoice}/destroy', 'InvoiceController@destroy')->name('invoice.destroy')->middleware('can:delete,invoice');
        Route::get('/invoice/{invoice}/siblings/check', 'InvoiceController@checkSiblings')->name('invoice.siblings.check')->middleware('can:update,invoice');

        Route::get('/invoice/adhoc/create', 'InvoiceController@adhoccreate')->name('invoice.adhoc.create');

        /* Quotes */
        Route::get('/quotes', 'QuoteController@index')->name('quote.index');
        Route::get('/quotes/archived', 'QuoteController@index_archived')->name('quote.index.archived');
        Route::get('/quote/create', 'QuoteController@create')->name('quote.create');
        Route::post('/quote/create', 'QuoteController@store')->name('quote.store');
        Route::get('/quote/{quote}', 'QuoteController@show')->name('quote.show')->middleware('can:view,quote');
        Route::post('/quote/{quote}/duplicate', 'QuoteController@duplicate')->name('quote.duplicate')->middleware('can:update,quote');
        Route::post('/quote/{quote}/convert', 'QuoteController@convertToInvoice')->name('quote.convert')->middleware('can:view,quote');
        Route::get('/quote/{quote}/download', 'QuoteController@download')->name('quote.download')->middleware('can:view,quote');
        Route::get('/quote/{quote}/printview', 'QuoteController@printview')->name('quote.printview')->middleware('can:view,quote');
        Route::get('/quote/{quote}/edit', 'QuoteController@edit')->name('quote.edit')->middleware('can:update,quote');
        Route::patch('/quote/{quote}/edit', 'QuoteController@update')->name('quote.update')->middleware('can:update,quote');
        Route::patch('/quote/{quote}/archive', 'QuoteController@archive')->name('quote.archive')->middleware('can:update,quote');
        Route::patch('/quote/{quote}/writeoff', 'QuoteController@writeoff')->name('quote.writeoff')->middleware('can:update,quote');
        Route::patch('/quote/{quote}/share', 'QuoteController@share')->name('quote.share')->middleware('can:view,quote');
        Route::delete('/quote/{quote}/destroy', 'QuoteController@destroy')->name('quote.destroy')->middleware('can:delete,quote');

        /* OldInvoice */
        Route::get('/oldinvoice/{oldinvoice}', 'OldInvoiceController@show')->name('invoice.old.show')->middleware('can:view,oldinvoice');
        Route::get('/oldinvoice/{oldinvoice}/download', 'OldInvoiceController@download')->name('invoice.old.download')->middleware('can:view,oldinvoice');
        Route::get('/oldinvoice/{oldinvoice}/printview', 'OldInvoiceController@printview')->name('invoice.old.printview')->middleware('can:view,oldinvoice');

        /* Invoice History */
        Route::get('/invoice/{invoice}/history', 'InvoiceController@history')->name('invoice.history.show')->middleware('can:view,invoice');

        /* InvoiceItem */
        Route::delete('/invoice/item/{invoiceitem}/destroy', 'InvoiceItemController@destroy')->name('invoice.item.destroy')->middleware('can:delete,invoiceitem');

        /* ItemTemplate */
        Route::get('/itemtemplates', 'ItemTemplateController@index')->name('itemtemplate.index');
        Route::get('/itemtemplate/create', 'ItemTemplateController@create')->name('itemtemplate.create');
        Route::post('/itemtemplate/create', 'ItemTemplateController@store')->name('itemtemplate.store');
        Route::get('/itemtemplate/{itemtemplate}', 'ItemTemplateController@show')->name('itemtemplate.show')->middleware('can:view,itemtemplate');
        Route::get('/itemtemplate/{itemtemplate}/retrieve', 'ItemTemplateController@retrieve')->name('itemtemplate.retrieve')->middleware('can:view,itemtemplate');
        Route::post('/itemtemplate/{itemtemplate}/duplicate', 'ItemTemplateController@duplicate')->name('itemtemplate.duplicate')->middleware('can:update,itemtemplate');
        Route::get('/itemtemplate/{itemtemplate}/download', 'ItemTemplateController@download')->name('itemtemplate.download')->middleware('can:view,itemtemplate');
        Route::get('/itemtemplate/{itemtemplate}/edit', 'ItemTemplateController@edit')->name('itemtemplate.edit')->middleware('can:update,itemtemplate');
        Route::patch('/itemtemplate/{itemtemplate}/edit', 'ItemTemplateController@update')->name('itemtemplate.update')->middleware('can:update,itemtemplate');
        Route::delete('/itemtemplate/{itemtemplate}/destroy', 'ItemTemplateController@destroy')->name('itemtemplate.destroy')->middleware('can:delete,itemtemplate');

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
