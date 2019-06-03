<?php


namespace LotTracker;


use App\WarehouseMaster;

class Warehouse
{
static function wh(){
    return new self();
}
function getWh(){
    $wh = \App\Warehouse::get();
    $wmaster = WarehouseMaster::get();
    $wh_code =[];
    $data_found = [];

    foreach ($wh as $h){
        $wh_code[]=$h->code;
    }
    foreach ($wmaster as $wm){
        if (!in_array($wm->Code,$wh_code)){
            $data_found[] = $wm;
        }
    }

    if (count($data_found)){
     self::storeWh($data_found);
    }
    return true;
}
function storeWh($data){
   foreach ($data as $d){

       \App\Warehouse::create([
          'name' => $d['Name'],
          'code' => $d['Code']
       ]);
   }
}
}