<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class State extends Model {

    protected $table = 'Condition';
    protected $connection = 'hermes';
    public $timestamps = false;
}