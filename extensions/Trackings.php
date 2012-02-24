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
 *         array('_trackPageview')
 *     )
 * ));
 * }}}
 */
class Trackings extends \lithium\core\Adaptable {
	/**
	 * Which session variable will be used to store the commands.
	 *
	 * @var string
	 */
	public static $name = 'Trackings';

	/**
	 * To be re-defined in sub-classes.
	 *
	 * @var object `Collection` of configurations, indexed by name.
	 */
	protected static $_configurations = array();

	/**
	 * Path where to look for tracking adapters.
	 *
	 * @var string
	 */
	protected static $_adapters = 'adapter.tracking';

	/**
	 * Class dependencies.
	 *
	 * @var array
	 */
	protected static $_classes = array(
		'session' => 'lithium\\storage\\Session'
	);
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
	 * Obtain the tracking from the configuration.
	 */
	public static function get() {
		$name = 'default';
		$session = static::$_classes['session'];

		$config = static::_config('default');

		$commands = $session::read(static::$name, compact('name')) ?: array();
		if ($config && $commands) {
			$config += array('commands' => array());
			$config['commands'] = array_merge(
				$config['commands'],
				$commands
			);
		}
		static::reset();

		$class = static::_class($config, static::$_adapters);
		return new $class($config);
	}

	/**
	 * Push a command to be run by the tracker.
	 *
	 * {{{
	 * Trackings::push('_setDomain', 'example.org');
	 * }}}
	 */
	public static function push(/* anything */) {
		$name = 'default';
		$session = static::$_classes['session'];

		$commands = $session::read(static::$name, compact('name')) ?: array();
		$commands[] = func_get_args();
		$session::write(static::$name, $commands, compact('name'));
	}

	/**
	 * Reset the stored commands using previous push.
	 */
	public static function reset() {
		$name = 'default';
		$session = static::$_classes['session'];
		$session::write(static::$name, array());
	}
}