<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Number extends Model {

    protected $table = 'Number';
    protected $connection = 'hermes';
    public $timestamps = false;
	
	public static function get(){
		return self::insertGetId([]);
	}
}