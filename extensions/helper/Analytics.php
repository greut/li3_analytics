<?php

namespace li3_analytics\extensions\helper;

use li3_analytics\extensions\Trackings;
use lithium\template\View;
use lithium\g11n\Message;
use lithium\core\Environment;
use lithium\core\ConfigException;

class Analytics extends \lithium\template\Helper
{
	protected $_view;

	// http://code.google.com/intl/fr/apis/analytics/docs/tracking/asyncTracking.html
	function script() {
		try {
			$tracking = Trackings::get();
			$class = get_class($tracking);
			$adapter = mb_substr($class, mb_strrpos($class, '\\')+1);
			$template = mb_strtolower($adapter);
			$library = 'li3_analytics';
			$view = $this->build_view();

			return $view->render(
				'element',
				compact('tracking'),
				compact('template', 'library')
			);
		} catch (ConfigException $e) {
			if (!Environment::is('production')) {
				return '<!-- li3_analytics: '.$e->getMessage().' -->';
			}
		}
	}

	// TODO:
	// http://code.google.com/intl/fr/apis/analytics/docs/gaJS/gaJSApiBasicConfiguration.html#_gat.GA_Tracker_._trackPageview

	protected function build_view() {
		if (!isset($this->_view)) {
			$this->_view = new View(array(
				'paths' => array(
					'element' => '{:library}/views/elements/{:template}.{:type}.php',
				),
				'outputFilters' => Message::aliases(),
			));
		}
		return $this->_view;
	}
}