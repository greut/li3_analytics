<?php

namespace li3_analytics\extensions;

/**
 * Configuration class for li3_analytics
 *
 * Adapter path is `adapter.tracking`
 * {{{
 * Trackings::config(array(
 *     'adapter' => 'GoogleAnalytics',
 *     'account' => 'UA-XXXXX-X',
 *     'commands' => array(
 *         array('_setDomainName', 'example.org'),
 *         array('_trackPageView')
 *     )
 * ));
 * }}}
 */
class Trackings extends \lithium\core\Adaptable
{
	/**
	 * To be re-defined in sub-classes.
	 *
	 * @var object `Collection` of configurations, indexed by name.
	 */
	protected static $_configurations = array();

	/**
	 * An array of calls to be made by the tracker
	 *
	 * @var array
	 */
	protected static $_commands = array();

	protected static $_adapters = 'adapter.tracking';

	/**
	 * Override the default config to have only one
	 * kind of config split by environment
	 *
	 * @param array $config configuration to set for 'default'
	 * @return given configuration
	 * @see lithium\core\Adaptable::config()
	 */
	public static function config($config=null) {
		if ($config && is_array($config)) {
			return parent::config(array('default' => $config));
		}
		return parent::config($config);
	}

	/**
	 * Obtain the tracking from the configuration
	 */
	public static function get() {
		$config = static::_config('default');
		if ($config && static::$_commands) {
			$config += array('commands' => array());
			$config['commands'] = array_merge(
				$config['commands'], static::$_commands
			);
		}

		$class = static::_class($config, static::$_adapters);
		return new $class($config);
	}

	/**
	 * Push a command to be run by the tracker
	 *
	 * {{{
	 * Trackings::push('_setDomain', 'example.org');
	 * }}}
	 */
	public static function push(/* anything */) {
		static::$_commands[] = func_get_args();
	}
}