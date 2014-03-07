<?php
$oRiak = Riak::getInstance();

/*
$lBucketTmp = $oRiak->listBuckets();
$lBucket = [];
foreach ($lBucketTmp as $sBucket) {
	$lBucket[$sBucket] = $oRiak->getBucketProps($sBucket);
}
*/

dump($oRiak->status());

// dump($lBucket);
$a = $oRiak->listKeys('test');
dump($a);

$a = $oRiak->fetchObj('test', 'LyqbotXYRkMBhqAaWCJ8YMZ5ci');
dump($a);

//$a = $oRiak->deleteObj('test', 'tk');
$a = $oRiak->storeObj('test', [date('Y-m-d H:i:s'), 'abc' => 1], 'tk');
dump($a);

$a = $oRiak->fetchObj('test', 'tk');
dump($a);

/*
foreach ($a as $s) {
	$a = $oRiak->fetchObj('test', $s);
	dump($a);
	$a = $oRiak->deleteObj('test', $s);
	dump($a);
}

$a = $oRiak->storeObj('test', date('Y-m-d H:i:s'), 'tk');

$a = $oRiak->storeObj('test', date('Y-m-d H:i:s'));
dump($a);

$a = $oRiak->listKeys('test');
foreach ($a as $s) {
	$a = $oRiak->fetchObj('test', $s);
	dump($a);
}
 */
