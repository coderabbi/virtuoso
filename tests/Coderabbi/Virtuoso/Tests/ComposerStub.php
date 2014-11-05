<?php
/**
 * @author Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 * @copyright Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 *
 * @package  Coderabbi\Virtuoso
 */


namespace Coderabbi\Virtuoso\Tests;


use Coderabbi\Virtuoso\Composer;


/**
 * Composer Stub for testing
 *
 * @package  Coderabbi\Virtuoso\Tests
 */
class ComposerStub implements Composer
{

	/**
	 * View that was used in compose()
	 *
	 * @access public
	 * @var string
	 */
	public $composedView;


	/**
	 * Compose view. 
	 * Tracks view used for composition.
	 *
	 * @access public
	 */
	public function compose($view)
	{
		$this->composedView = $view;
	}

}
