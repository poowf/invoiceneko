<?php

namespace App\Http\Controllers;

use App\Models\NekoNotification;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    public function pixel($notificationId)
    {
        //Do not fail even if id is not found, this is so that even if the id is missing, the transparent pixel will still display properly in the email.
        $notification = NekoNotification::find($notificationId);

        ignore_user_abort(true);

        // turn off gzip compression
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', 1);
        }

        ini_set('zlib.output_compression', 0);

        // turn on output buffering if necessary
        if (ob_get_level() == 0) {
            ob_start();
        }

        // removing any content encoding like gzip etc.
        header('Content-encoding: none', true);

        //check to ses if request is a POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // the GIF should not be POSTed to, so do nothing...
            echo ' ';
        } else {

            // return 1x1 pixel transparent gif
            header('Content-type: image/gif');
            // needed to avoid cache time on browser side
            header('Content-Length: 42');
            header('Cache-Control: private, no-cache, no-cache=Set-Cookie, proxy-revalidate');
            header('Expires: Wed, 11 Jan 2000 12:59:00 GMT');
            header('Last-Modified: Wed, 11 Jan 2006 12:59:00 GMT');
            header('Pragma: no-cache');

            echo sprintf('%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%', 71, 73, 70, 56, 57, 97, 1, 0, 1, 0, 128, 255, 0, 192, 192, 192, 0, 0, 0, 33, 249, 4, 1, 0, 0, 0, 0, 44, 0, 0, 0, 0, 1, 0, 1, 0, 0, 2, 2, 68, 1, 0, 59);
        }

        // flush all output buffers. No reason to make the user wait for OWA.
        ob_flush();
        flush();
        ob_end_flush();

        // Can retrieve more info from server parameter
        // Log::info($_SERVER);
        if (! $notification->read_at && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $notification->read_at = Carbon::now();
            $notification->save();
        }
    }
}
