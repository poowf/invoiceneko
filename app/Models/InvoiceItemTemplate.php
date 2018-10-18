<?php

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class InvoiceItemTemplate extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'invoice_item_templates';

}
