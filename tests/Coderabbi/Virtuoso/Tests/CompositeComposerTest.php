<?php
/**
 * @author Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 * @copyright Yitzchok Willroth (@coderabbi) <coderabbi@gmail.com>
 *
 * @package  Coderabbi\Virtuoso
 */


namespace Coderabbi\Virtuoso\Tests;


use PHPUnit_Framework_TestCase;
use Coderabbi\Virtuoso\CompositeComposer;
use Coderabbi\Virtuoso\Tests\ComposerStub;
use Coderabbi\Virtuoso\Tests\CompositeComposerStub;
use Illuminate\Foundation\Application;


/**
 * Tests behavior of CompositeComposer Abstract Class.
 *
 * @package  Coderabbi\Virtuoso\Tests
 */
class CompositeComposerTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Application
	 *
	 * @access private
	 * @var Application
	 */
	private $app;


	/**
	 * View Composers resolved by $app
	 *
	 * @access private
	 * @var array
	 */
	private $composers;


	/**
	 * PHPUnit Test Set Up
	 *
	 * @access public
	 */
	public function setUp()
	{
		$this->app = new Application;
		$this->composers = [];
	}


	/** @test */
	public function it_composes_a_view_using_each_view_composer()
	{
		$view = 'view.test';

		$this->app->bind('Coderabbi\\Virtuoso\\Tests\\ComposerStub', function() 
		{
			$composer = new ComposerStub;
			$this->composers[] = $composer;
			return $composer;
		});

		$compositeComposer = new CompositeComposerStub($this->app);
		$compositeComposer->setComposers([
			'Coderabbi\\Virtuoso\\Tests\\ComposerStub',
			'Coderabbi\\Virtuoso\\Tests\\ComposerStub',
		]);

		$compositeComposer->compose($view);

		// Should create two composers
		$this->assertEquals(2, count($this->composers));

		// Each composer should compose $view
		foreach ($this->composers as $composer) {
			$this->assertEquals($view, $composer->composedView);
		}
	}

}
