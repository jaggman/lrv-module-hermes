<?php namespace Modules\Hermes\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Hermes\Models\Point;
use Modules\Hermes\Models\Entityt;
use Modules\Hermes\Models\Entity;
use Carbon\Carbon;

class HermesController extends Controller {
	
        public function postIn(\Request $request)
        {
            $post = $request::all();
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
                //$created = new \DateTime();
                $number = uniqid();
                foreach ($json as $k=>$v) {
                    $entity[] = [
                        'method'=>$method,
                        'userId'=>0,
                        //'created'=>$created,
                        'param'=>$k,
                        'value'=>$v,
                        'number'=>$number,
                        'pointId'=>$pointId,
                    ];
                }
                $variables['transaction'] = $number;
                Entityt::insert($entity);
                $variables['id'] = Entityt::lastId();
            }
            return json_encode( $variables );
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
                'state'=>$this->data(),
                'point'=>Point::all(),
            ]);
	}
        public function postIndex()
        {
            return view('hermes::indexPost', [
                'state'=>$this->data(),
                'point'=>Point::all(),
            ]);
        }
        
	/*
        private function states($method, $point = null, $limit = 10, $offset = 0)
        {
            $table = 'Entity';
            $join = \DB::
                            connection('hermes')->
                            table($table)
                            ->select('number')
                            ->distinct('number')
                            ->where('method', $method)
                            ->orderBy('created', 'desc')
                            ->take($limit)
                            ->skip($offset);
            $binds = [
                $method,
            ];
            if($point !== null){
                $join->where('pointId', $point);
                $binds[] = $point;
            }
            $states = \DB::
                connection('hermes')->
                table($table.' as a')
                ->join(
                    new \Illuminate\Database\Query\Expression(
                        '('.
                            $join
                            ->toSql()
                        .') b'
                    )
                    , 'a.number', '=', 'b.number')
                ->toSql()
                ;
            $states = \DB::connection('hermes')->select($states,$binds);
            return $states;
        }
        */
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
                $data['date']['start'] = date("Y-m-d 00:00:00");
                $data['date']['end'] = date("Y-m-d 23:59:59");
            }
            //$data['date']['start'] = new \DateTime($data['date']['start']);
            //$data['date']['end'] = new \DateTime($data['date']['end']);
            //$data['date']['start'] = new Carbon(strtotime($data['date']['start']));
            //$data['date']['end'] = new Carbon(strtotime($data['date']['end']));
            $data['date']['start'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['start']);
            $data['date']['end'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['end']);

            $points = Point::all();
            $point = [];
            foreach($points as $poin){
                $point[$poin->id] = $poin->name;
            }
            
            $states = Entity::logg('payment',$data);
            $state = [];
            foreach($states as $sts){
                $state[$sts->number]['method'] = $sts->method;
                $state[$sts->number]['point'] = $sts->pointId;
                $state[$sts->number]['created'] = $sts->created;
                $state[$sts->number][$sts->param] = $sts->value;
            }
            return view('hermes::payment', [
                'state'=>$state, 
                'point'=>$point,
                'data'=>$data,
            ]);            
        }
        
        public function getPoints()
        {
            return view('hermes::points', [
                'states'=>$this->data(),
                'points'=>Point::all(),
            ]);
        }
        
        public function getIncass(\Request $request)
        {
            $id = $request::get('id');
            $data['id'] = $id == "" ? null : $id;
            $data['date'] = $request::get('date');
            $data['sum'] = null;
            $data['num'] = null;
            if(!isset($data['date'])){
                $data['date']['start'] = date("Y-m-d 00:00:00");
                $data['date']['end'] = date("Y-m-d 23:59:59");
            }
            //$data['date']['start'] = Carbon::instance(new \DateTime($data['date']['start']));
            //$data['date']['end'] = Carbon::instance(new \DateTime($data['date']['end']));
            //$data['date']['start'] = new Carbon(strtotime($data['date']['start']));
            //$data['date']['end'] = new Carbon(strtotime($data['date']['end']));
            $data['date']['start'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['start']);
            $data['date']['end'] = Carbon::createFromFormat('Y-m-d H:i:s',$data['date']['end']);
            
            $states = Entity::logg('incass',$data);
            $state = [];
            foreach($states as $sts){
                $state[$sts->number]['created'] = $sts->created;
                if(!isset($state[$sts->number]['arr'])) $state[$sts->number]['arr'] = [];
                $state[$sts->number]['point'] = $sts->pointId;
                $state[$sts->number]['number'] = $sts->number;
                switch($sts->param){
                    case 'currentDate';
                       $state[$sts->number]['date'] = $sts->value;
                       break;
                    case 'banknotes':
                        $state[$sts->number]['banknotes'] = $sts->value;
                        break;
                    case 'sum':
                        $state[$sts->number]['sum'] = $sts->value;
                        break;
                    case 'previousDate':
                        break;
                    case 'order': break;
                    case 'date': break;
                    case 'txn': break;
                    default:
                        $state[$sts->number]['arr'][$sts->param] = $sts->value;
                }
            }
            
            $points = Point::all();
            $point = [];
            foreach($points as $poin){
                $point[$poin->id] = $poin->name;
            }
            
            return view('hermes::incass', [
                'incass'=>$state, 
                'point'=>$point,
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
        
	public function index()
	{
            $table='Entity';
            //$states = \DB::connection('hermes')->select('SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff FROM `'.$table.'` b INNER JOIN (SELECT MAX(`number`)`number` FROM `'.$table.'` WHERE `method` = :method  Group by `pointId`) a USING(`number`)', [
            $states = \DB::connection('hermes')->select('SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(),`created`)) diff FROM `'.$table.'` b INNER JOIN (SELECT MAX(`number`)`number` FROM `'.$table.'` WHERE `method` = :method  Group by `pointId`) a USING(`number`)', [
                'method'=>'state',
            ]);
            dd($states);
            return view('hermes::index');
	}
	
}