<?php

namespace Project;

require_once('vendor/autoload.php');

use Markenwerk\JsonHttpClient;
use Markenwerk\BasicHttpClient\Request\Authentication;
use Markenwerk\BasicHttpClient\Request\Message;

// Instantiating a basic HTTP client with the endpoints URL
// If the endpoint uses the `HTTPS` schema a `HttpsTransport` instance will be used automatically.
$client = new JsonHttpClient\JsonHttpClient('http://requestb.in/1aipzl31');

// Adding an authentication method
$client
	->getRequest()
	->addAuthentication(new Authentication\BasicAuthentication('username', 'password'));

$client->get(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2'
));

$client->post(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2',
	'paramName3' => array(
		'key1' => 'value1',
		'key2' => 'value2'
	)
));

print_r($client->getRequest()->getEffectiveRawHeader());