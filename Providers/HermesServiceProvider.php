<?php namespace Modules\Hermes\Providers;

use Illuminate\Support\ServiceProvider;

class HermesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the application events.
	 * 
	 * @return void
	 */
	public function boot()
	{
		$this->registerConfig();
		$this->registerTranslations();
		$this->registerViews();
	}

	/**
         *
         * @var type string
         * 
         * namespace for console scripts
         */
        protected $namespace = 'Modules\\Hermes\\Console\\';
        
        /**
         * 
         * @return type array
         * 
         * list classnames of console scripts
         */
        protected function getCommands(){
            $mask = realpath(__DIR__.'/../Console').'/*.php';
            $dir = glob($mask);
            $class = [];
            foreach($dir as $file){
                preg_match('/\/Console\/(.*)\.php/U', $file, $match);
                $class[] = $match[1];
            }
            return $class;
        }
        
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{		
            /**
             * Register the commands.
             */
            foreach ($this->getCommands() as $command) {
                $this->commands($this->namespace.$command);
            }
	}

	/**
	 * Register config.
	 * 
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->publishes([
		    __DIR__.'/../Config/config.php' => config_path('hermes.php'),
		]);
		$this->mergeConfigFrom(
		    __DIR__.'/../Config/config.php', 'hermes'
		);
	}

	/**
	 * Register views.
	 * 
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('views/modules/hermes');

		$sourcePath = __DIR__.'/../Resources/views';

		$this->publishes([
			$sourcePath => $viewPath
		]);

		$this->loadViewsFrom([$viewPath, $sourcePath], 'hermes');
	}

	/**
	 * Register translations.
	 * 
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = base_path('resources/lang/modules/hermes');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'hermes');
		} else {
			$this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'hermes');
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
