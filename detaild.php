<?php

namespace Project;

use Markenwerk\BasicHttpClient\Request\Authentication\BasicAuthentication;
use Markenwerk\BasicHttpClient\Request\Message\Cookie\Cookie;
use Markenwerk\BasicHttpClient\Request\Message\Header\Header;
use Markenwerk\BasicHttpClient\Request\Message\Message;
use Markenwerk\BasicHttpClient\Request\Transport\HttpsTransport;
use Markenwerk\JsonHttpClient\Request\Message\Body\JsonBody;
use Markenwerk\JsonHttpClient\Request\JsonRequest;
use Markenwerk\UrlUtil\Url;

require_once('vendor/autoload.php');

$transport = new HttpsTransport();
$transport
	->setHttpVersion(HttpsTransport::HTTP_VERSION_1_1)
	->setTimeout(5)
	->setReuseConnection(true)
	->setAllowCaching(true)
	->setFollowRedirects(true)
	->setMaxRedirects(10)
	->setVerifyPeer(true);

$messageBody = new JsonBody(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2',
	'paramName3' => array(
		'key1' => 'value1',
		'key2' => 'value2'
	)
));

$message = new Message();
$message
	->addHeader(new Header('Content-Type', array('application/json')))
	->addHeader(new Header('Accept', array('application/json')))
	->addHeader(new Header('Runscope-Bucket-Auth', array('7a64dde7-74d5-4eed-b170-a2ab406eff08')))
	->addCookie(new Cookie('PHPSESSID', '<MY_SESSION_ID>'))
	->setBody($messageBody);

$url = new Url('https://yourapihere-com-98yq3775xff0.runscope.net/');

$request = new JsonRequest();
$request
	->setUserAgent('PHP JSON HTTP Client Test 1.0')
	->setUrl($url)
	->addAuthentication(new BasicAuthentication('username', 'password'))
	->setMethod(JsonRequest::REQUEST_METHOD_POST)
	->setTransport($transport)
	->setMessage($message)
	->perform();

print_r($request->getEffectiveRawHeader());