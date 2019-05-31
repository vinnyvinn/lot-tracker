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
           'ITEM CODE' => 'I-01',
           'BATCH / SERIAL NO' => 'B-01',
           'QTY' => 50,
           'EXPIRY' => date('d/m/Y')

       ];

      return collect($data);
}
    public function sampleOp()
    {
        $data []= [
            'PO' => 'Opening Balance('.date('d/m/Y H:i:s').')',
            'ITEM CODE' => 'I-01',
            'BATCH / SERIAL NO' => 'B-01'
        ];

        return collect($data);
    }
}
