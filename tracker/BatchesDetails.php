<?php
/**
 * Created by PhpStorm.
 * User: vinnyvinny
 * Date: 3/23/19
 * Time: 12:58 PM
 */

namespace LotTracker;


use Carbon\Carbon;

class BatchesDetails
{
static function init(){
    return new self();
}

    public function sampleData()
    {
      $data []= [
            'PO' => 'P001',
           'ITEM' => 'I-01',
           'BATCH' => 'B-01',
           'QTY' => '5000',
           'EXPIRY' => Carbon::parse(Carbon::now())->format('d/m/Y')

       ];

      return collect($data);
}
}
