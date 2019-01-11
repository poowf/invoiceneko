<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Log;

class DataMigrationController extends Controller
{
    public function create(Company $company)
    {
        return view('pages.migration.create');
    }

    public function storecontact(Request $request, Company $company)
    {
        $file = $request->file('contactimport');

        //$file = public_path('/Contacts.csv');

        $errorscollection = new Collection();

        Excel::load($file, function($reader) use ($errorscollection) {
            // ->all() is a wrapper for ->get() and will work the same
            $results = $reader->all();

            foreach ($results as $row) {
                try {
                    $client = Client::query();
                    $companyname = (is_null($row->company_name) ? $row->display_name : $row->company_name);
                    if (!$client->duplicatecheck($companyname)->first()) {
                        $client = new Client();
                        $client->companyname = $companyname;
                        $client->phone = $row->phone;
                        $client->website = $row->website;
                        $client->nickname = $row->display_name;
                        $client->street = $row->billing_address . $row->billing_street2;
                        $client->postalcode = $row->billing_code;
                        $client->country = $row->billing_country;
                        $client->crn = (is_null($row->customfield_value1) ? null : $row->customfield_value1);
                        $client->contactsalutation = $row->salutation;
                        $client->contactfirstname = $row->first_name;
                        $client->contactlastname = $row->last_name;
                        $client->contactgender = '';
                        $client->contactemail = $row->emailid;
                        $client->contactphone = $row->phone;
                        $client->company_id = auth()->user()->company_id;
                        $client->save();
                    } else {
                        $errorscollection->push($row->emailid . ' could not be imported');
                    }
                } catch (\Exception $e) {
                    $errorscollection->push($row->emailid . ' could not be imported');
                    continue;
                    // All other exceptions
                    Log::info('Caught Exception la');
                }
            }
        });

        return redirect()->route('migration.create')->with(compact('errorscollection'));
    }

    public function storeinvoice(Request $request, Company $company)
    {
        $file = $request->file('invoiceimport');

        //$file = public_path('/Invoice.csv');

        $errorscollection = new Collection();

        Excel::load($file, function($reader) use ($errorscollection) {
            // ->all() is a wrapper for ->get() and will work the same
            $results = $reader->all();

            //dd($results);

            foreach ($results as $row) {
                try {
                    //Need to check if Invoice has already been created and if so, merge the items in.

                    $auth_companyid = auth()->user()->company_id;

                    if ($invoice = Invoice::where('nice_invoice_id', $row->invoice_number)->where('company_id', $auth_companyid)->first()) {
                        self::createInvoiceItem($row->item_name, $row->item_desc, $row->item_price, $row->quantity, $invoice->id);
                    } else {
                        $companyname = $row->company_name;

                        $client = Client::where('companyname', 'LIKE', '%' . $companyname . '%')->where('company_id', $auth_companyid)->first();

                        $invoice = new Invoice();
                        $invoice->nice_invoice_id = $row->invoice_number;
                        $invoice->date = $row->invoice_date;
                        $invoice->duedate = $row->due_date;
                        $invoice->netdays = $row->payment_terms;
                        $invoice->client_id = $client->id;
                        $invoice->company_id = $auth_companyid;

                        switch ($row->invoice_status) {
                            case 'Open':
                                $invoice->status = Invoice::STATUS_OPEN;
                                break;
                            case 'Overdue':
                                $invoice->status = Invoice::STATUS_OVERDUE;
                                break;
                            case 'Closed':
                                $invoice->status = Invoice::STATUS_CLOSED;
                                break;
                            case 'Draft':
                                $invoice->status = Invoice::STATUS_DRAFT;
                                break;
                            case 'Void':
                                $invoice->status = Invoice::STATUS_VOID;
                                break;
                            default:
                                $invoice->status = Invoice::STATUS_OPEN;
                                break;
                        }

                        $invoice->save();

                        self::createInvoiceItem($row->item_name, $row->item_desc, $row->item_price, $row->quantity, $invoice->id);
                    }

                    $invoice->setInvoiceTotal();
                } catch (Exception $e) {
                    $errorscollection->push($row->invoice_number . ' could not be imported');
                    continue;
                    // All other exceptions
                    Log::info('Caught Exception la');
                }
            }
        });

        return redirect()->route('migration.create')->with(compact('errorscollection'));
    }

    public function createInvoiceItem($name, $description, $price, $quantity, $invoiceid)
    {
        $invoiceitem = InvoiceItem::query();
        $price = number_format($price, 3, '.', '');
        $quantity = intval($quantity);
        if (!$invoiceitem->duplicatecheck($price, $quantity, $invoiceid)->first()) {
            $invitem = new InvoiceItem();
            $invitem->name = (is_null($name) ? 'Item' : $name);
            $invitem->description = $description;
            $invitem->price = $price;
            $invitem->quantity = $quantity;
            $invitem->invoice_id = $invoiceid;
            $invitem->save();
        }
    }

    public function storepayment(Request $request, Company $company)
    {
        $file = $request->file('paymentimport');

        //$file = public_path('/Customer_Payment.csv');

        $errorscollection = new Collection();

        Excel::load($file, function($reader) use ($errorscollection) {
            // ->all() is a wrapper for ->get() and will work the same
            $results = $reader->all();

            //dd($results);

            foreach ($results as $row) {
                try {
                    $auth_companyid = auth()->user()->company_id;

                    $invoice = Invoice::where('nice_invoice_id', $row->invoice_number)->where('company_id', $auth_companyid)->first();

                    $payment = Payment::query();

                    $amount = number_format($row->amount, 3, '.', '');

                    if (!$payment->duplicatecheck($amount, $row->date, $invoice->id, $invoice->getClient()->id, $auth_companyid)->first()) {
                        $payment = new Payment();
                        $payment->amount = $amount;
                        $payment->receiveddate = $row->date;
                        $payment->notes = $row->description . ' ' . $row->reference_number;
                        $payment->mode = $row->mode;
                        $payment->invoice_id = $invoice->id;
                        $payment->client_id = $invoice->getClient()->id;
                        $payment->company_id = $auth_companyid;
                        $payment->save();
                    } else {
                        $errorscollection->push($row->invoice_number . ', ' . $row->date . ', $' . $row->amount . ' could not be imported');
                    }
                } catch (\Exception $e) {
                    $errorscollection->push($row->invoice_number . ', ' . $row->date . ', $' . $row->amount . ' could not be imported');
                    continue;
                    // All other exceptions
                    Log::info('Caught Exception la');
                }
            }
        });

        return redirect()->route('migration.create')->with(compact('errorscollection'));
    }
}
