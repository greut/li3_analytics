<?php

namespace li3_analytics\tests\cases\extensions\adapter\tracking;

use li3_analytics\extensions\adapter\tracking\GoogleAnalytics;

class GoogleAnalyticsTest extends \lithium\test\Unit
{
	function test_default()
	{
		$ga = new GoogleAnalytics(array(
			'account' => 'test'
		));

		$this->assertEqual('test', $ga->account());
		$expected = array(
			array('_setAccount', 'test'),
			array('_trackPageView'),
		);
		$this->assertEqual($expected, $ga->commands());
	}

	function test_commands()
	{
		$ga = new GoogleAnalytics(array(
			'account' => 'test',
			'commands' => array(
				array('_hello'),
				array('a', 'b', 'c', 'd'),
			)
		));

		$expected = array(
			array('_setAccount', 'test'),
			array('_hello'),
			array('a', 'b', 'c', 'd'),
		);

		$this->assertEqual($expected, $ga->commands());
	}
}