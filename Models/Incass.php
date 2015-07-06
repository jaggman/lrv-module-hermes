<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Incass extends Model {

    protected $table = 'Incass';
    protected $connection = 'hermes';
    public $timestamps = false;

    public static function log($data){
        $payment = self::select();
        if($data['id']) $payment->where('pointId', $data['id']);
        //if($data['sum']) $payment->where('sum', $data['sum']);
        //if($data['num']) $payment->where('order', $data['num']);
        $payment->whereBetween('created', $data['date']);
        return $payment->get();
    }
}