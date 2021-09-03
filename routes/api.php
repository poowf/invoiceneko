<?php

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Resources\InvoiceResource;

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
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::resource('recipient', \API\RecipientController::class);
Route::resource('client', \API\ClientController::class);
Route::resource('company', \API\CompanyController::class);
Route::resource('companyinvite', \API\CompanyInviteController::class);
Route::resource('companyuserrequest', \API\CompanyUserRequestController::class);
Route::resource('receipt', \API\ReceiptController::class);
Route::resource('invoice', \API\InvoiceController::class);
Route::resource('invoiceitem', \API\InvoiceItemController::class);
Route::resource('oldinvoice', \API\OldInvoiceController::class);
Route::resource('oldinvoiceitem', \API\OldInvoiceItemController::class);
Route::resource('itemtemplate', \API\ItemTemplateController::class);
Route::resource('quote', \API\QuoteController::class);
Route::resource('quoteitem', \API\QuoteItemController::class);
Route::resource('payment', \API\PaymentController::class);
Route::resource('role', \API\RoleController::class);
Route::resource('user', \API\UserController::class);
