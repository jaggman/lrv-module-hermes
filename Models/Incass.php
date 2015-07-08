<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
use Carbon\Carbon;
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
        $data['date']['start'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['start'].' 00:00:00');
        $data['date']['end'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['end'].' 23:59:59');
        // 23:59:59
        $payment = self::with('point');
        if($data['id']) $payment->where('pointId', $data['id']);
        //if($data['sum']) $payment->where('sum', $data['sum']);
        //if($data['num']) $payment->where('order', $data['num']);
        $payment->whereBetween('created', $data['date']);
        return $payment->get();
    }

    public function point()
    {
        return $this->belongsTo('Modules\Hermes\Models\Point', 'pointId');
    }
    
    public function validate()
    {
        return \Validator::make($this->toArray(),[
            //'previousDate' => ['required', 'unique:'.$this->connection.'.'.$this->table, 'date_format:Y-m-d H:i:s'],
			/*
			 * Using this validator:
			 * https://github.com/felixkiss/uniquewith-validator
			 */
            'previousDate' => ['required', 'unique_with:'.$this->connection.'.'.$this->table.',pointId,NULL', 'date_format:Y-m-d H:i:s'],
        ]);
    }
    
}