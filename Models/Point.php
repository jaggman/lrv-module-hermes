<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Point extends Model {

    protected $table = 'Point';
    protected $connection = 'hermes';
    protected $fillable = array('id','name');
    public $timestamps = false;
  
    public function validate()
    {
        return \Validator::make($this->toArray(),[
            'id' => ['required', 'unique:'.$this->connection.'.'.$this->table, 'numeric'],
            'name' => ['required', 'min:7'],
        ]);
    }
    
    public function __toString()
    {
        return $this->name;
    }

    public function state()
    {
        return $this->hasOne('Modules\Hermes\Models\State', 'pointId')->select(['*', \DB::connection($this->connection)->raw("TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff")])->latest('id');
        return $this->hasOne('Modules\Hermes\Models\wState', 'pointId')->select(['*', \DB::connection($this->connection)->raw("TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff")])->groupBy('pointId');
        //return $this->hasOne(\DB::connection($this->connection)->raw(State::latest('id')->toSql()), 'pointId')->select(['*', \DB::connection($this->connection)->raw("TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff")])->groupBy('pointId');
    }
}