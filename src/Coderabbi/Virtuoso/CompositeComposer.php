<?php
/**
 * @author Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 * @copyright Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 *
 * @package  Coderabbi\Virtuoso
 */


namespace Coderabbi\Virtuoso;


use Coderabbi\Virtuoso\Composer;
use Illuminate\Foundation\Application;


/**
 * Class CompositeComposer
 *
 * @package  Coderabbi\Virtuoso
 * @abstract
 */
abstract class CompositeComposer
	implements Composer
{

	/**
	 * Application
	 *
	 * @access private
	 * @var Application
	 */
	private $app;


	/**
	 * Composers
	 *
	 * @access protected
	 * @var array
	 */
	protected $composers = array();


	/**
	 * Constructor
	 *
	 * @access public
	 * @return self
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}


	/**
	 * Compose View
	 *
	 * @access public
	 * @param string $view
	 */
	public function compose($view)
	{
		foreach ($this->composers as $composer)
		{
			$this->app->make($composer)->compose($view);
		}
	}

}
 