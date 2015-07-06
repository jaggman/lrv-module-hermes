<?php namespace Modules\Hermes\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Modules\Hermes\Models\Payment;

class PayUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hermes:payupdate';
	//protected $signature = 'hermes:payupdate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update state of payments in DB.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
        
        public function fire()
	{
            ob_start();
            $txn = $data = [];
            //$payment = Payment::select('txn')->where('proc', false)->get();
            foreach(Payment::select(['sum','txn'])
                    ->where('proc', false)
                    ->get() as $payment)
                $txn[$payment->txn] = [
                    'txn'=>$payment->txn,
                    'sum'=>$payment->sum,
                ];
            foreach($txn as $tx) $data[] = $this->getPay($tx);
                //var_dump($txn);
            //var_dump($this->curl('https://ya.ru/'));
            //var_dump($this->getPay());
            Payment::select()
                     ->whereIn('txn', $data)
                     ->update(['proc' => true]);
            //var_dump($data);
            //var_dump($this->curl('http://laravel.tur8.ru/hermes'));
            $ob = ob_get_clean();
            $this->comment($ob);
	}
        
        private function getPay($data){
            $url = 'https://globalprofi.ru/import/osmp/index';
            $param = [
                'command'=>'pay',
                'account'=>'055_1111',
                'txn_id'=>$data['txn'],
                'sum'=>$data['sum'],
            ];
            $query = http_build_query($param);
            //$this->comment($query);
            $curl = $this->curl($url.'?'.$query);
            preg_match("/<comment>OK<\/comment>/",$curl->body,$match);
            if(strlen($curl->error) == 0 && count($match)>0){
                $this->comment($query.' <info>[OK]</info> ');
                return $data['txn'];
            }
            $this->comment($query.' <error>[Error]</error> '.$curl->error);
            return false;
        }
        
        private function curl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch,CURLOPT_NOBODY,false);
            curl_setopt($ch,CURLOPT_HEADER,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                //    'LoginName: Search_GLOBAL',
                //    'Password: 20Brco15'
            ]);
            $body = explode("\r\n\r\n",curl_exec($ch));
            $response = (object) [
                'headers'=>  array_shift($body),
                'body'=>implode("\r\n\r\n",$body),
                'error'=>curl_error($ch),
            ];
            //if(curl_error($ch)) $this->addError('curl',null,curl_error($ch));
            curl_close($ch);
            //$this->body = $body;
            //return $body;
            return $response;
            
        }
        
        

    /*
    public function handle()
    
    {
            //$this->info('Инфо');
            //$this->comment('Коммент');
            //$this->error('Ошибка');
            //$name = $this->ask('Как вас зовут?');
            //$password = $this->secret('Какой у вас пароль?');
            //$this->info('Привет, '.$name."\nPass: ".$password);
            ob_start();
            $txn = [];
            //$payment = Payment::select('txn')->where('proc', false)->get();
            foreach(Payment::select(['sum','txn'])->where('proc', false)->get() as $payment)
                $txn[$payment->txn] = $payment->sum;
            var_dump($txn);
            $ob = ob_get_clean();
            $this->info($ob);
    }
     * 
     */
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
                /*return [
			['example', InputArgument::REQUIRED, 'An example argument.'],
		];*/
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
