<?php

namespace Modules\Hermes\Models;

use Eloquent as Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Entity extends Model {

    protected $table = 'Entity';
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

	public static function log($method, $point = null, $limit = 10, $offset = 0)
        {
            $entity = new self();
            $join = self::select('number')
                ->distinct('number')
                ->where('method', $method)
                ->orderBy('id', 'desc')
                ->take($limit)
                ->skip($offset);
            $binds = [
                $method,
            ];
            if($point !== null){
                $join->where('pointId', $point);
                $binds[] = $point;
            }
            $states = $entity
                ->join(
                    new \Illuminate\Database\Query\Expression(
                        '('.
                            $join
                            ->toSql()
                        .') b'
                    )
                    , $entity->table.'.number', '=', 'b.number')
                ->toSql()
                ;
            $states = \DB::connection($entity->connection)->select($states,$binds);
            return $states;
        }
        
        public static function state(){
            //$table='Entity';
            $entity = new self();
            /*$states = $entity->select('SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff FROM `'.$table.'` b INNER JOIN (SELECT MAX(`number`)`number` FROM `'.$table.'` WHERE `method` = :method  Group by `pointId`) a USING(`number`)', [
                'method'=>'state',
            ]);*/
            //dd($states);
            /*
            dd(Point::select('number')->join($entity->table, 'Point.id', '=', $entity->table.'.pointId')
                    ->groupBy('Point.id')
                    ->limit(10)
                    ->max('number')->toSql());
             */
            //$states = \DB::connection('hermes')->select('SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff FROM `'.$entity->table.'` b INNER JOIN (SELECT MAX(`number`)`number` FROM `'.$entity->table.'` WHERE `method` = :method  Group by `pointId`) a USING(`number`)', [
            $states = \DB::connection('hermes')->select('SELECT e2.*, TIME_TO_SEC(TIMEDIFF(NOW(),e2.`created`)) diff FROM `Entity` e Inner join (SELECT max(`id`) `id` FROM `Entity` WHERE `method` = :method) a USING(`id`) INNER JOIN `Entity` e2 USING(`number`)', [
                'method'=>'state',
            ]);/**/
            return $states;
        }
        
        public static function getParam($method,$limit = 10){
            $query = "DROP TABLE IF EXISTS `tmp_table`;"
                ."CREATE TABLE IF NOT EXISTS `tmp_table` AS (SELECT o2.* FROM `Entity` o2 INNER JOIN (SELECT DISTINCT `number` FROM `Entity` WHERE `method` = :method ORDER BY `id` DESC LIMIT {$limit}) a USING(`number`));"
                ."SELECT * FROM `tmp_table` o"
                ."LEFT JOIN (SELECT `number`, `value` as `sum` FROM `tmp_table` Where `param`='sum') a USING(`number`)"
                ."LEFT JOIN (SELECT `number`, `value` as `order` FROM `tmp_table` Where `param`='order') b USING(`number`)"
                ."LEFT JOIN (SELECT `number`, `value` as `date` FROM `tmp_table` Where `param`='date') c USING(`number`)"
                ."LEFT JOIN (SELECT `number`, `value` as `txn` FROM `tmp_table` Where `param`='txn') d USING(`number`)"
                ."GROUP BY o.`number` ORDER BY o.`id` DESC;";
            $params = \DB::connection('hermes')->select($query,[
                'method'=>$method,
            ]);
        }

}