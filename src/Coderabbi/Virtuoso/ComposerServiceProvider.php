<?php
/**
 * @author Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 * @copyright Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 *
 * @package  Coderabbi\Virtuoso
 */


namespace Coderabbi\Virtuoso;


use Illuminate\Support\ServiceProvider;


/**
 * Class ComposerServiceProvider
 *
 * @package  Coderabbi\Virtuoso
 * @final
 */
final class ComposerServiceProvider
	extends ServiceProvider
{

	/**
	 * Configuration String
	 */
	const CONFIG_STRING = 'view.composers';


	/**
	 * Register View Composer Collection
	 *
	 * @access public
	 */
	public function register()
	{
		$configs = $this->getComposerConfigs();

		if (count($configs))
		{
			$this->registerComposers($configs);
		}
	}


	/**
	 * Get Composer Configs
	 *
	 * @access private
	 * @return array
	 */
	private function getComposerConfigs()
	{
	    return $this->app['config']->get(self::CONFIG_STRING);
	}


	/**
	 * Register Individual Composers
	 *
	 * @access private
	 * @param array $configs
	 */
	private function registerComposers(array $configs)
	{
		foreach ($configs as $view => $composer)
		{
			$this->app->view->composer($view, $composer);
		}
	}

}
