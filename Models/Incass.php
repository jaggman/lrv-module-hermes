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
}