<?php namespace Modules\Hermes\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\Hermes\Models\Point;
use Modules\Hermes\Models\Entityt;
use Modules\Hermes\Models\Entity;

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
            return view('hermes::index', ['state'=>$this->data()]);
	}
        public function postIndex()
        {
            return view('hermes::indexPost', ['state'=>$this->data()]);
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
        public function getTerminal()
	{
            $id = \Input::get('id');
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