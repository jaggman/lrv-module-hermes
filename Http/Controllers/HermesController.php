<?php namespace Modules\Hermes\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Hermes\Models\Point;
use Modules\Hermes\Models\Entityt;
use Modules\Hermes\Models\Entity;
use Modules\Hermes\Models\Payment;
use Modules\Hermes\Models\Incass;
use Modules\Hermes\Models\Query;
use Carbon\Carbon;

class HermesController extends Controller {
	
        public function postIn(\Request $request)
        {
            Query::insert(['query'=>json_encode($request::all())]);
            return;
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
                //dd($json);
                $entity = [];
                //$created = new \DateTime();
                $number = uniqid();
                foreach ($json as $k=>$v) {
                    $entity[] = [
                        'method'=>$method,
                        'userId'=>0,
                        //'created'=>$created,
                        'created'=>Carbon::now('Europe/Moscow'),
                        'param'=>$k,
                        'value'=>$method == 'test' ? 'test' : $v,
                        'number'=>$number,
                        'pointId'=>$pointId,
                    ];
                }
                $variables['transaction'] = $number;
                //dd($entity);
                $d = Entity::insert($entity);
                //dd($d);
                $variables['id'] = Entity::lastId();
            }
            return response()->json($variables);
            //return json_encode( $variables );
            //echo json_encode( $variables );
        }
        
        private function data()
        {
            /*
            $table='Entity';
            $states = \DB::connection('hermes')->select('SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff FROM `'.$table.'` b INNER JOIN (SELECT MAX(`number`)`number` FROM `'.$table.'` WHERE `method` = :method  Group by `pointId`) a USING(`number`)', [
                'method'=>'state',
            ]);*/
            $states = Entity::state();
            $state = [];
            foreach($states as $sts){
                $state[$sts->pointId][$sts->param] = $sts->value;
                $state[$sts->pointId]['created'] = $sts->created;
                $state[$sts->pointId]['diff'] = $sts->diff;
            }
            return $state;
        }
        public function getIndex()
	{
            return view('hermes::index', [
                'point'=>Point::with('state')->get(),
            ]);
	}
        public function postIndex()
        {
            return view('hermes::indexPost', [
                'point'=>Point::with('state')->get(),
            ]);
        }
        
        public function getTerminal(\Request $request)
	{
            //$post = $request::all();
            $id = $request::get('id');
            dd($request);
            $id = $id == 'null' ? false : $id;
            $states = Entity::log('payment',$id);
            //$states = $this->states('payment',$id);
            $incasss = Entity::log('incass',$id);
            //$incasss = $this->states('incass',$id);
            
            $state = [];
            foreach($states as $sts){
                $state[$sts->number]['point'] = $sts->pointId;
                $state[$sts->number]['created'] = $sts->created;
                $state[$sts->number][$sts->param] = $sts->value;
            }
            //$state = Entity::getParam('payment');
            $incass = [];
            foreach($incasss as $inca){
                $incass[$inca->number]['point'] = $inca->pointId;
                $incass[$inca->number]['created'] = $inca->created;
                $incass[$inca->number]['params'][$inca->param] = $inca->value;
            }
            $points = Point::all();
            $point = [];
            foreach($points as $poin){
                $point[$poin->id] = $poin->name;
            }
            return view('hermes::terminal', [
                'state'=>$state, 
                'incass'=>$incass,
                'point'=>$point,
            ]);
	}
        
        public function getPayment(\Request $request){
            $id = $request::get('id');
            $sum = $request::get('sum');
            $num = $request::get('num');
            $data['id'] = $id == "" ? null : $id;
            $data['sum'] = $sum == "" ? null : $sum;
            $data['num'] = $num == "" ? null : $num;
            $data['date'] = $request::get('date');
            if(!isset($data['date'])){
                $data['date']['start'] = date("Y-m-d");
                $data['date']['end'] = date("Y-m-d");
            }
            $states = Payment::log($data);
            return view('hermes::payment', [
                //'state'=>$state, 
                'state'=>$states, 
                'data'=>$data,
            ]);            
        }
        
        public function getPoints()
        {
            return view('hermes::points', [
                //'states'=>$this->data(),
                'points'=>Point::with('state')->get(),
            ]);
        }
        
        public function getIncass(\Request $request)
        {
            $id = $request::get('id');
            $data['id'] = $id == "" ? null : $id;
            $data['date'] = $request::get('date');
            if(!isset($data['date'])){
                $data['date']['start'] = date("Y-m-d");
                $data['date']['end'] = date("Y-m-d");
            }
            $states = Incass::log($data);
            return view('hermes::incass', [
                'incass'=>$states, 
                //'point'=>$point,
                'data'=>$data,
            ]);            
            
        }

        public function getLibTerm()
        {
            $point = Point::all();
            return view('hermes::libTerm',[
                'point' => $point,
            ]);
        }
        
        public function postLibTerm(\Request $request)
        {
            $input = $request::all();
            $point = new Point($input);
            $validation = $point->validate();
            if ($validation->fails()) {
              // проверка не пройдена.
                return \Redirect::to('hermes/lib-term')->withInput()->withErrors($validation);
            }
            $point->save();
            return \Redirect::to('hermes/lib-term');
        }
        
}