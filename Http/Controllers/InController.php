<?php namespace Modules\Hermes\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Hermes\Models\Entity;
use Carbon\Carbon;

class InController extends Controller {
	
        public function postIn(\Request $request)
        {
            $post = $request::all();
            //dd($post);
            $method = null;
            $params = null;
            $variables = $errors = array();
            $transaction = null;

            $method = $post['method'];
            $params = $post['params'];
            $pointId = $post['point'];

            if(!$method) {
                $errors[] = "POST variable [method] undefined";
            }

            if(!$params) {
                $errors[] = "POST variable [params] undefined";
            }

            if(!$pointId) {
                $errors[] = "POST variable [point] undefined";
            }

            if(count($errors)>0) {
                $variables['result'] = "error";
                $variables['transaction'] = null;
                $variables['message'] = array("message"=>implode(", ",$errors));
            } else {
                $variables['result'] = "200";
                $variables['message'] = array("message"=>"method=".$method.", params=".$params);
                
                $json = json_decode($params,1);
                $entity = [];
                $number = uniqid();
                $variables['transaction'] = $number;
                foreach ($json as $k=>$v) {
                    $entity[] = [
                        'method'=>$method,
                        'userId'=>0,
                        'created'=>Carbon::now('Europe/Moscow'),
                        'param'=>$k,
                        'value'=>($method == 'test' && $k == 'type') ? 'test' : $v,
                        'number'=>$number,
                        'pointId'=>$pointId,
                    ];
                }
                $variables['result'] = Entity::insert($entity) ? '200' : 'error';
                $variables['id'] = Entity::lastId();
            }
            $this->postInTest($request);
            return response()->json($variables);
        }
        public function postInTest(\Request $request)
        {
            $post = $request::all();
            //dd($post);
            $method = null;
            $params = null;
            $variables = $errors = array();
            $transaction = null;

            $method = $post['method'];
            $params = $post['params'];
            $pointId = $post['point'];

            if(!$method) {
                $errors[] = "POST variable [method] undefined";
            }

            if(!$params) {
                $errors[] = "POST variable [params] undefined";
            }

            if(!$pointId) {
                $errors[] = "POST variable [point] undefined";
            }

            if(count($errors)>0) {
                $variables['result'] = "error";
                $variables['transaction'] = null;
                $variables['message'] = array("message"=>implode(", ",$errors));
            } else {
                $variables['result'] = "200";
                $variables['message'] = array("message"=>"method=".$method.", params=".$params);
                
                $json = json_decode($params,1);
                $entity = [];
                $number = uniqid();
                $variables['transaction'] = $number;
                if($method == 'payment'){
                    $old = \Modules\Hermes\Models\Payment::select()->where(['txn'=>$json['txn']])->get();
                    if(count($old) === 0){
                        $payment = [
                            'number'=>$number,
                            'userId'=>0,
                            'created'=>Carbon::now('Europe/Moscow'),
                            'pointId'=>$pointId,
                            'type'=>$json['type'],
                            'txn'=>$json['txn'],
                            'date'=>$json['date'],
                            'sum'=>$json['sum'],
                            'order'=>$json['order'],
                        ];
                        //dd($payment);
                        $variables['id'] = \Modules\Hermes\Models\Payment::insertGetId($payment);
                    }else{
                        $variables['id'] = 0;
                    }
                    //dd(\Modules\Hermes\Models\Payment::select()->get($variables['id']));
                }elseif($method == 'incass'){
                    $json['previousDate'] = isset($json['previousDate']) ? $json['previousDate'] : null;
                    $incass = [
                        'number'=>$number,
                        'userId'=>0,
                        'created'=>Carbon::now('Europe/Moscow'),
                        'pointId'=>$pointId,
                        'currentDate'=>$json['currentDate'],
                        'previousDate'=>$json['previousDate'],
                        'sum'=>$json['sum'],
                        'banknotes'=>$json['banknotes'],
                    ];
                    unset(
                        $json['currentDate'],
                        $json['previousDate'],
                        $json['sum'],
                        $json['banknotes']
                    );
                    $incass['variables'] = json_encode($json);
                    $variables['id'] = \Modules\Hermes\Models\Incass::insertGetId($incass);
                }elseif($method == 'state'){
                    $state = [
                        'number'=>$number,
                        'state'=>$json['state'],
                        'userId'=>0,
                        'created'=>Carbon::now('Europe/Moscow'),
                        'pointId'=>$pointId,
                        'banknotes'=>$json['banknotes'],
                    ];
                    unset(
                        $json['state'],
                        $json['banknotes']
                    );
                    $state['variables'] = json_encode($json);
                    $variables['id'] = \Modules\Hermes\Models\State::insertGetId($state);
                }else{
                    //Если неведомая хрень, просто пишем в старую таблицу
                    foreach ($json as $k=>$v) {
                        $entity[] = [
                            'method'=>$method,
                            'userId'=>0,
                            'created'=>Carbon::now('Europe/Moscow'),
                            'param'=>$k,
                            'value'=>($method == 'test' && $k == 'type') ? 'test' : $v,
                            'number'=>$number,
                            'pointId'=>$pointId,
                        ];
                    }
                    $variables['result'] = Entity::insert($entity) ? '200' : 'error';
                    $variables['id'] = Entity::lastId();
                }
            }
            return response()->json($variables);
        }
}
