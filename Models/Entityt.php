<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Entityt extends Model {

    protected $table = 'Entityt';
    protected $connection = 'hermes';
    //protected $fillable = array('id','name');
    public $timestamps = false;
  
    /*public function validate()
    {
        return \Validator::make($this->toArray(),[
            'id' => ['required', 'unique:'.$this->connection.'.'.$this->table, 'numeric'],
            'name' => ['required', 'min:7'],
        ]);
    }*/
    
    public static function lastId(){
        return \DB::connection('hermes')->getPdo()->lastInsertId(null);
    }
    

}