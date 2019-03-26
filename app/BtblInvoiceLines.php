<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BtblInvoiceLines extends Model
{
    protected $table = '_btblInvoiceLines';
    protected $connection = 'sqlsrv2';
}
