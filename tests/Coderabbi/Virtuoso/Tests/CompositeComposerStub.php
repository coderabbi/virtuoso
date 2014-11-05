<?php
/**
 * @author Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 * @copyright Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 *
 * @package  Coderabbi\Virtuoso
 */


namespace Coderabbi\Virtuoso\Tests;


use Coderabbi\Virtuoso\CompositeComposer;


/**
 * CompositeComposer Stub for testing
 *
 * @package  Coderabbi\Virtuoso\Tests
 */
class CompositeComposerStub extends CompositeComposer
{

	/**
	 * Set array of composers
	 *
	 * @access public
	 */
	public function setComposers($newComposers)
	{
		$this->composers = $newComposers;
	}

}
