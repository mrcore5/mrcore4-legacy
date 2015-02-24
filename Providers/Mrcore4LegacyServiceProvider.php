<?php namespace Mreschke\Mrcore4Legacy\Providers;

use Mrcore\Modules\Foundation\Support\ServiceProvider;

class Mrcore4LegacyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Load our old legacy helpers api
		// This is for old mrcore4 app compatibility
		require __DIR__.'/../Legacy/api.php';
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
