<?php

namespace li3_analytics\tests\cases\extensions;

use li3_analytics\extensions\Trackings;

class TrackingsTest extends \lithium\test\Unit
{
	function test_default_config()
	{
		Trackings::config(array('test' => array(
			'account' => 'test',
			'adapter' => 'GoogleAnalytics',
		)));

		$expected = array(
			array('_setAccount', 'test'),
			array('_trackPageView')
		);

		$tracking = Trackings::get('test');

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

		$tracking = Trackings::get('test');
		$this->assert($expected, $tracking->commands());
	}
}