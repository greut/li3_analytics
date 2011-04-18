<?php

namespace li3_analytics\extensions;

class Trackings extends \lithium\core\Adaptable
{
	/**
	 * A Collection of the configurations you add through Trackings::config().
	 *
	 * @var Collection
	 */
	protected static $_configurations = array();

	/**
	 * An array of calls to be made by the tracker
	 *
	 * @var array
	 */
	protected static $_commands = array();

	protected static $_adapters = 'adapter.tracking';

	public static function get($name='GoogleAnalytics') {
		$config = static::_config($name);
		if (static::$_commands) {
			$config += array('commands' => array());
			$config['commands'] = array_merge(
				$config['commands'],
				static::$_commands
			);
		}
		$class = static::_class($config, static::$_adapters);
		return new $class($config);
	}

	public static function push() {
		static::$_commands[] = func_get_args();
	}
}