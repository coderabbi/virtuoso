<?php
/**
 * @author Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 * @copyright Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 *
 * @package  Coderabbi\Virtuoso
 */


namespace Coderabbi\Virtuoso;


/**
 * Interface Composer
 *
 * @package  Coderabbi\Virtuoso
 */
interface Composer 
{

	/**
	 * Compose View
	 *
	 * @access public
	 * @param string $view
	 */
	public function compose($view);

}
