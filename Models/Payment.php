<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
use Carbon\Carbon;
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
        $data['date']['start'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['start'].' 00:00:00');
        $data['date']['end'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['end'].' 23:59:59');
        //$payment = self::select();
        $payment = self::with('typename')->with('point');
        if($data['id']) $payment->where('pointId', $data['id']);
        if($data['sum']) $payment->where('sum', $data['sum']);
        if($data['num']) $payment->where('order', $data['num']);
        $payment->whereBetween('created', $data['date']);
        return $payment->get();
    }

    public function typename()
    {
        return $this->belongsTo('Modules\Hermes\Models\Paytype', 'type');
    }
    
    public function point()
    {
        return $this->belongsTo('Modules\Hermes\Models\Point', 'pointId');
    }
    
}