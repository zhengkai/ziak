<?php
function dump() {
	$sOut = "";
	foreach (func_get_args() as $mVar) {
		if (is_array($mVar)) {
			$sDump = print_r($mVar, TRUE);
		} else {
			$sDump = var_export($mVar, TRUE);
		}
		$sDump = preg_replace("/\\)\n\n/", ")\n", $sDump);
		//$sDump = preg_replace("/Array\n(\\s+)(/", ")\n", $sDump);
		if (PHP_SAPI !== "cli") {
			$sDump = trim($sDump);
			$sDump = \Tango\Core\HTML::escape($sDump);
			$sDump = str_replace(["\n", "\r\n", "\r"], "<br />", $sDump);
			$sDump = str_replace("  ", "&#160; ", $sDump);
			$sDump = '<pre style="cursor: default; text-align: left; font-weight: normal; font-size: 14px; overflow: auto; max-height: 600px; font-family: &#034;Droid Sans Mono&#034;, monospace; line-height: 22px; margin: 4px auto; max-width: 940px; min-width: 300px; border: 1px solid teal; background-color: #efe; color: teal; padding: 9px;">'
				.$sDump."\n</pre>";
		}
		$sOut .= $sDump;
	}
	echo $sOut;
}
