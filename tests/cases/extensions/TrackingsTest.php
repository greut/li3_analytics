<?php

namespace li3_analytics\tests\cases\extensions;

use li3_analytics\extensions\Trackings;

class TrackingsTest extends \lithium\test\Unit
{
	function test_config()
	{
		$config = array(
			true => array(
				'adapter' => 'GoogleAnalytics',
			),
			'test' => array(
				'account' => 'test',
			)
		);
		Trackings::config($config);

		$expected = array('default' => array(
			'adapter' => 'GoogleAnalytics',
			'account' => 'test',
			'filters' => array(),
		));
		$this->assertEqual($expected, Trackings::config());
	}

	function test_get()
	{
		Trackings::config(array(
			'adapter' => 'GoogleAnalytics',
			'account' => 'test'
		));

		$expected = array(
			array('_setAccount', 'test'),
			array('_trackPageView')
		);

		$tracking = Trackings::get();

		$this->assert('test', $tracking->account());
		$this->assert($expected, $tracking->commands());
	}

	function test_push_command()
	{
		Trackings::push('_setDomainName', 'example.org');
		Trackings::push('_trackPageView');

		Trackings::config(array('test' => array(
			'account' => 'test',
			'adapter' => 'GoogleAnalytics',
		)));

		$expected = array(
			array('_setAccount', 'test'),
			array('_setDomainName', 'example.org'),
			array('_trackPageView')
		);

		$tracking = Trackings::get();
		$this->assert($expected, $tracking->commands());
	}
}