<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Payment extends Model {

    protected $table = 'Payment';
    protected $connection = 'hermes';
    public $timestamps = false;
    
    /*
     * @data (array)[
     *   id (null),
     *   sum (null),
     *   num (null),
     *   date (array)[
     *      start (class Carbon)
     *      end (class Carbon)
     *   ]
     * ]
     */
    public static function log($data){
        $payment = self::select();
        if($data['id']) $payment->where('pointId', $data['id']);
        if($data['sum']) $payment->where('sum', $data['sum']);
        if($data['num']) $payment->where('order', $data['num']);
        $payment->whereBetween('created', $data['date']);
        return $payment->get();
    }
}