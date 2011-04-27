<?php

namespace li3_analytics\extensions\helper;

use li3_analytics\extensions\Trackings;

class Analytics extends \lithium\template\Helper
{
	// http://code.google.com/intl/fr/apis/analytics/docs/tracking/asyncTracking.html
	function script($tracking='default')
	{
		$tracking = Trackings::get($tracking);
		$commands = array_map(function($item) {
			return '_gaq.push('.json_encode($item).')';
		}, $tracking->commands());
		$commands = implode(';', $commands);

		return <<<EOS
<script type="text/javascript">

  var _gaq = _gaq || [];
  {$commands}
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
EOS;
	}

	// TODO:
	// http://code.google.com/intl/fr/apis/analytics/docs/gaJS/gaJSApiBasicConfiguration.html#_gat.GA_Tracker_._trackPageview
}