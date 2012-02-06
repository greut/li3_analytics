<?php

namespace li3_analytics\extensions\adapter\tracking;

/**
 * http://code.google.com/intl/en/apis/analytics/docs/tracking/asyncTracking.html
 * http://code.google.com/intl/en/apis/analytics/docs/gaJS/gaJSApi.html
 */
class GoogleAnalytics extends \lithium\core\Object
{
	/**
	 * Google Analytics account
	 *
	 * @var string
	 */
	protected $_account;

	/**
	 * The commands to be called
	 *
	 * @var array
	 */
	protected $_commands = array(
		array('_trackPageview')
	);

	protected $_autoConfig = array('account', 'commands');

	/**
	 * Tracking account used
	 *
	 * @return string tracking account
	 */
	public function account() {
		return $this->_account;
	}

	/**
	 * Builds the commands that have to be run on the tracker
	 *
	 * @return array list of commands to run on the tracker
	 */
	public function commands() {
		return array_merge(
			array(array('_setAccount', trim($this->_account))),
			$this->_commands
		);
	}
}
