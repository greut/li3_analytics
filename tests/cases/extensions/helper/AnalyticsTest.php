<?php

namespace li3_analytics\tests\cases\extensions\helper;

use lithium\net\http\Router;
use lithium\action\Request;
use lithium\action\Response;
use lithium\tests\mocks\template\helper\MockHtmlRenderer;
use li3_analytics\extensions\helper\Analytics;

class AnalyticsTest extends \lithium\test\Unit
{
	/**
	 * Test object instance
	 *
	 * @var object
	 */
	public $analytics;

	protected $_routes = array();
	
	function setup()
	{
		$this->_routes = Router::get();
		Router::reset();
		Router::connect('/{:controller}/{:action}/{:id}.{:type}');
		Router::connect('/{:controller}/{:action}.{:type}');

		$this->context = new MockHtmlRenderer(array(
			'request' => new Request(array(
				'base' => '', 'env' => array('HTTP_HOST' => 'foo.local')
			)),
			'response' => new Response()
		));
		$this->analytics = new Analytics(array('context' => &$this->context));
	}

	function teardown()
	{
		Router::reset();

		foreach ($this->_routes as $route) {
			Router::connect($route);
		}
		unset($this->analytics);
	}

	function test_script()
	{
		$result = $this->analytics->script();
		$this->assertTags($result, array(
			'script' => array(
				'type' => 'text/javascript'
			),
			'regex:/.*_setAccount.*async.*google-analytics.com\/ga.js[^<]+/',
			'/script'
		));
	}
}