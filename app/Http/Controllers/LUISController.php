<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use App\Models\Invoice;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Luis;

class LUISController extends Controller
{
    public function sendLUIS(Request $request)
    {
//        $client = new Client(['base_uri' => 'https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/']);
//        // Send a request to https://foo.com/api/test
//        $response = $client->request('GET', 'bfb9b80b-ecb1-4382-b463-eaed995a82f9', [
//            'query' => [
//                'subscription-key' => 'cfbff6034bf0491bbc83502bef9e55f8',
//                'staging' => 'true',
//                'timezoneOffset' => '-360',
//                'q' => $request->input('utterance')
//            ]
//        ]);
//
//        $body = json_decode($response->getBody()->getContents());
//        $intent = $body->topScoringIntent->intent;
//        $entity = $body->entities[0];
//        $entityName = preg_replace('/\s/', '', $entity->entity);
//
//        $model = '\App\Models\\' . $entity->type;
//
//        $invoice = $model::where('nice_invoice_id', $entityName)->first();
//
//        $route = route('invoice.show', [ 'company' => Unicorn::getCompanyKey(), 'invoice' => $invoice->id ]);
//
//        return response($route);

        $response = Luis::query('get invoice pwf-0001');



        return response()->json($response->entities);
    }
}
