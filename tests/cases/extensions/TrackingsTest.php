<?php

namespace li3_analytics\tests\cases\extensions;

use li3_analytics\extensions\Trackings;
use lithium\storage\Session;

class TrackingsTest extends \lithium\test\Unit {
	function teardown() {
		Trackings::reset();
	}

	function test_config() {
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

	function test_get() {
		Trackings::config(array(
			'adapter' => 'GoogleAnalytics',
			'account' => 'test'
		));

		$expected = array(
			array('_setAccount', 'test'),
			array('_trackPageview')
		);

		$tracking = Trackings::get();

		$this->assert('test', $tracking->account());
		$this->assert($expected, $tracking->commands());
	}

	function test_push_command() {
		Trackings::push('_setDomainName', 'example.org');
		Trackings::push('_trackPageview');

		Trackings::config(array('test' => array(
			'account' => 'test',
			'adapter' => 'GoogleAnalytics',
		)));

		$expected = array(
			array('_setAccount', 'test'),
			array('_setDomainName', 'example.org'),
			array('_trackPageview')
		);

		$tracking = Trackings::get();
		$this->assert($expected, $tracking->commands());
	}

	function test_commands_are_loaded_from_the_session() {
		Session::write(
			Trackings::$name,
			array(
				array('_setDomainName', 'example.org'),
				array('_trackPageview')
			),
			array('name' => 'default')
		);

		Trackings::config(array('test' => array(
			'account' => 'test',
			'adapter' => 'GoogleAnalytics',
		)));

		$expected = array(
			array('_setAccount', 'test'),
			array('_setDomainName', 'example.org'),
			array('_trackPageview')
		);

		$tracking = Trackings::get();
		$this->assert($expected, $tracking->commands());
	}
}