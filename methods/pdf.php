<?php

global $seo_rets_plugin;
$sr = $seo_rets_plugin;

ini_set('memory_limit', '1024M');

$request = $sr->api_request('get_pdf', array(
	'url' => home_url(),
	'type' => $_GET['type'],
	'query' => array(
		'boolopr' => 'AND',
		'conditions' => array(array(
			'field' => 'mls_id',
			'operator' => '=',
			'value' => $_GET['mls']
		))
	)
));
header("Content-Type: application/pdf");

if ( !isset($_GET['view']) ) header('Content-Disposition: attachment; filename="' . $sr->pretty_url($_GET['address']) . '.pdf"');

echo base64_decode($request->pdf);

exit;
