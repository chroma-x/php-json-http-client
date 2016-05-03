# PHP JSON HTTP Client

[![Build Status](https://travis-ci.org/markenwerk/php-json-http-client.svg?branch=master)](https://travis-ci.org/markenwerk/php-json-http-client)
[![Test Coverage](https://codeclimate.com/github/markenwerk/php-json-http-client/badges/coverage.svg)](https://codeclimate.com/github/markenwerk/php-json-http-client/coverage)
[![Dependency Status](https://www.versioneye.com/user/projects/5728e298a0ca35004baf7cb7/badge.svg)](https://www.versioneye.com/user/projects/5728e298a0ca35004baf7cb7)
[![Code Climate](https://codeclimate.com/github/markenwerk/php-json-http-client/badges/gpa.svg)](https://codeclimate.com/github/markenwerk/php-json-http-client)
[![Latest Stable Version](https://poser.pugx.org/markenwerk/json-http-client/v/stable)](https://packagist.org/packages/markenwerk/json-http-client)
[![Total Downloads](https://poser.pugx.org/markenwerk/json-http-client/downloads)](https://packagist.org/packages/markenwerk/json-http-client)
[![License](https://poser.pugx.org/markenwerk/json-http-client/license)](https://packagist.org/packages/markenwerk/json-http-client)

A JSON HTTP client library. This project also is the reference implementation for extending the [PHP Basic HTTP Client](https://github.com/markenwerk/php-basic-http-client).

## Installation

```{json}
{
   	"require": {
        "markenwerk/json-http-client": "~2.0"
    }
}
```

## Usage

### Autoloading and namesapce

```{php}  
require_once('path/to/vendor/autoload.php');
```

### Simple usage

#### Preparing the HTTP client

```{php}
use JsonHttpClient;
use BasicHttpClient\Request\Authentication;
use BasicHttpClient\Request\Message;

// Instantiating a basic HTTP client with the endpoints URL
// If the endpoint uses the `HTTPS` schema a `HttpsTransport` instance will be used automatically.
$client = new JsonHttpClient\JsonHttpClient('http://requestb.in/1aipzl31');

// Adding an authentication method
$client
	->getRequest()
	->addAuthentication(new Authentication\BasicAuthentication('username', 'password'));
```

#### Performing requests and read the response

##### Body-less requests (GET, HEAD and DELETE)

Perfoming the following `GET` request with additional query parameters

```{php}
$response = $client->get(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2'
));
```

will result in the following HTTP request.

```{http}
GET /1aipzl31?paramName1=paramValue1&paramName2=paramValue2 HTTP/1.1
Host: requestb.in
Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=
User-Agent: PHP Basic HTTP Client 1.0
Accept: application/json
Content-Type: application/json
```

The same mechanic is offered to perform `HEAD` and `DELETE` requests wich all are body-less.

##### Body-full requests (POST, PUT, PATCH)

Perfoming the following `POST` request with body data

```{php}
$response = $client->post(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2',
	'paramName3' => array(
		'key1' => 'value1',
		'key2' => 'value2'
	)
));
```

will result in the following HTTP request.

```{http}
POST /1aipzl31?paramName1=paramValue1&paramName2=paramValue2 HTTP/1.1
Host: requestb.in
Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=
User-Agent: PHP Basic HTTP Client 1.0
Accept: application/json
Content-Type: application/json
Content-Length: 102

{"paramName1":"paramValue1","paramName2":"paramValue2","paramName3":{"key1":"value1","key2":"value2"}}
```

The same mechanic is offered to perform `PUT` and `PATCH` requests wich all are body-full.

---

### Detailed usage

The following example shows the usage with a more detailed configuration. 

#### Configuring a HTTP Transport instance

```{php}
use BasicHttpClient\Request\Transport\HttpTransport;

// Configuring a Transport instance
$transport = new HttpTransport();
$transport
	->setHttpVersion(HttpsTransport::HTTP_VERSION_1_1)
	->setTimeout(5)
	->setReuseConnection(true)
	->setAllowCaching(true)
	->setFollowRedirects(true)
	->setMaxRedirects(10);
```

#### Configuring a HTTPS Transport instance

```{php}
use BasicHttpClient\Request\Transport\HttpsTransport;

// Configuring a Transport instance
$transport = new HttpsTransport();
$transport
	->setHttpVersion(HttpsTransport::HTTP_VERSION_1_1)
	->setTimeout(5)
	->setReuseConnection(true)
	->setAllowCaching(true)
	->setFollowRedirects(true)
	->setMaxRedirects(10)
	->setVerifyPeer(true);
```

#### Configuring a Message instance with Body

```{php}
use BasicHttpClient\Request\Message\Cookie\Cookie;
use BasicHttpClient\Request\Message\Header\Header;
use BasicHttpClient\Request\Message\Message;
use JsonHttpClient\Request\Message\Body\JsonBody;

// Configuring a message Body instance
$messageBody = new JsonBody(array(
	'paramName1' => 'paramValue1',
	'paramName2' => 'paramValue2',
	'paramName3' => array(
		'key1' => 'value1',
		'key2' => 'value2'
	)
));

// Configuring a Message instance
$message = new Message();
$message
	->addHeader(new Header('Content-Type', array('application/json')))
	->addHeader(new Header('Accept', array('application/json')))
	->addHeader(new Header('Runscope-Bucket-Auth', array('7a64dde7-74d5-4eed-b170-a2ab406eff08')))
	->addCookie(new Cookie('PHPSESSID', '<MY_SESSION_ID>'))
	->setBody($messageBody);
```

##### Message and request Header instances

**Please note, that headers have some unusual behaviours.** Header names have an uniform way of nomenclature so the following three getter calls would have the same result.

```{php}
$header1 = $message->getHeaderByName('Content-Type');
$header2 = $message->getHeaderByName('content-type');
$header3 = $message->getHeaderByName('CONTENT-Type');
```

To allow multiple request headers using the same name, the method `addAdditionalHeader` provides such a logic.

```{php}
// Add or replace a request header
$message->addHeader(new Header('Custom-Header', array('CustomHeaderValue')));
// Add a request header and keep the existing one untouched
$message->addAdditionalHeader(new Header('Custom-Header', array('AnotherCustomHeaderValue')));
```

#### Configuring an endpoints URL, build the Request instance and perform the HTTP request

For more information about the usage of the URL object please take a look at the [PHP URL Util](https://github.com/markenwerk/php-url-util) project.

```{php}
use BasicHttpClient\Request\Authentication\BasicAuthentication;
use JsonHttpClient\Request\JsonRequest;
use Url\Url;

// Setting up the endpoints URL
$url = new Url('https://john:secret@yourapihere-com-98yq3775xff0.runscope.net:443/path/to/resource?arg1=123#fragment');

// Configuring and performing a Request
$request = new JsonRequest();
$request
	->setUserAgent('PHP JSON HTTP Client Test 1.0')
	->setUrl($url)
	->addAuthentication(new BasicAuthentication('username', 'password'))
	->setMethod(JsonRequest::REQUEST_METHOD_POST)
	->setTransport($transport)
	->setMessage($message)
	->perform();
```

The resulting HTTP request would be the following.

```{http}
POST /?paramName1=paramValue1&paramName2=paramValue2 HTTP/1.1
Host: yourapihere-com-98yq3775xff0.runscope.net
Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=
User-Agent: PHP JSON HTTP Client Test 1.0
Cookie: PHPSESSID=<MY_SESSION_ID>
Content-Type: application/json
Accept: application/json
Runscope-Bucket-Auth: 7a64dde7-74d5-4eed-b170-a2ab406eff08
Content-Length: 102

{"paramName1":"paramValue1","paramName2":"paramValue2","paramName3":{"key1":"value1","key2":"value2"}}
```

### Usage of authentication methods

You can add one or more Authentication instances to every Request instance. At the moment this project provides classes for [HTTP Basic Authentication](https://en.wikipedia.org/wiki/Basic_access_authentication) and [SSL Client Certificate Authentication](https://en.wikipedia.org/wiki/Transport_Layer_Security#Client-authenticated_TLS_handshake).

#### HTTP Basic Authentication

Required credentials are a *username* and a *password* that get provided to the class constructor as arguments.

```{php}
use BasicHttpClient\Request\Authentication\BasicAuthentication;
use JsonHttpClient\Request\JsonRequest;

// Configuring the authentication
$basicAuthentication = new BasicAuthentication('username', 'password');

// Adding the authentication instance to the Request
$request = new JsonRequest();
$response = $request->addAuthentication($basicAuthentication);
```

#### SSL Client Certificate Authentication

Required credentials are a *Certificate Authority Certificate*, a *Client Certificate* and the password that is used to decrypt the Client Certificate that get provided to the class constructor as arguments.

```{php}
use BasicHttpClient\Request\Authentication\ClientCertificateAuthentication;
use JsonHttpClient\Request\JsonRequest;

// Configuring the authentication
$clientCertificateAuthentication = new ClientCertificateAuthentication(
	'/var/www/project/clientCert/ca.crt',
	'/var/www/project/clientCert/client.crt',
	'clientCertPassword'
);

// Adding the authentication instance to the Request
$request = new JsonRequest();
$response = $request->addAuthentication($clientCertificateAuthentication);
```

---

## Reading from the resulting Response object

### Getting the response object

If using the `JsonHttpClient` the response object is returned by the termination methods listed above. If directly using the JsonRequest instance, you can get the JsonResponse object via a getter.

```{php}
// Getting the response BasicHttpClient\Response\JsonResponse object
$response = $request->getResponse();

// Reading the HTTP status code as integer; will return `200`
echo print_r($response->getStatusCode(), true) . PHP_EOL;

// Reading the HTTP status text as string; will return `HTTP/1.1 200 OK`
echo print_r($response->getStatusText(), true) . PHP_EOL;

// Reading the HTTP response headers as array of BasicHttpClient\Response\Header\Header objects
echo print_r($response->getHeaders(), true) . PHP_EOL;

// Reading the HTTP response body as associative array
echo print_r($response->getBody(), true) . PHP_EOL;
```

---

## Getting effective Request information

After successful performing the request, the effective request information is tracked back to the JsonRequest object. They can get accessed as follows.

```{php}
// Getting the effective endpoint URL including the query parameters
echo print_r($request->getEffectiveEndpoint(), true) . PHP_EOL;

// Getting the effective HTTP status, f.e. `POST /?paramName1=paramValue1&paramName2=paramValue2&paramName3=1&paramName4=42 HTTP/1.1`
echo print_r($request->getEffectiveStatus(), true) . PHP_EOL;

// Getting the effective raw request headers as string
echo print_r($request->getEffectiveRawHeader(), true) . PHP_EOL;

// Getting the effective request headers as array of `BasicHttpClient\Request\Message\Header\Header` objects
echo print_r($request->getEffectiveHeaders(), true) . PHP_EOL.PHP_EOL;
```

---

## Getting some transactional statistics

```{php}
// Getting the statistics BasicHttpClient\Response\Statistics\Statistics object
$statistics = $request->getResponse()->getStatistics();

// Reading the redirection URL if the server responds with an redirect HTTP header and 
// followRedirects is set to false
echo print_r($statistics->getRedirectEndpoint(), true).PHP_EOL;

// Reading the numbers of redirection as integer
echo print_r($statistics->getRedirectCount(), true).PHP_EOL;

// Getting the time in seconds the redirect utilized as float
echo print_r($statistics->getRedirectTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized until the connection was established
echo print_r($statistics->getConnectionEstablishTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized until the DNS hostname lookup was done
echo print_r($statistics->getHostLookupTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized before the first data was sent
echo print_r($statistics->getPreTransferTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized before the first data was received
echo print_r($statistics->getStartTransferTime(), true).PHP_EOL;

// Getting the time in seconds that was utilized to perfom the request an read the response
echo print_r($statistics->getTotalTime(), true).PHP_EOL;
```

---

## Exception handling

PHP JSON HTTP Client provides different exceptions – also provided by the PHP Common Exceptions project – for proper handling.  
You can find more information about [PHP Common Exceptions at Github](https://github.com/markenwerk/php-common-exceptions).

### Exceptions to be expected

In general you should expect that any setter method could thrown an `\InvalidArgumentException`. The following exceptions could get thrown while using PHP Basic HTTP Client.

- `CommonException\IoException\FileNotFoundException` on configuring a `ClientCertificateAuthentication`instance
- `CommonException\IoException\FileReadableException` on configuring a `ClientCertificateAuthentication`instance
- `BasicHttpClient\Exception\HttpRequestAuthenticationException` on performing a request
- `BasicHttpClient\Exception\HttpRequestException` on performing a request
- `CommonException\NetworkException\ConnectionTimeoutException` on performing a request
- `CommonException\NetworkException\CurlException` on performing a request
- `BasicHttpClient\Exception\HttpResponseException` if parsing the JSON response body fails

---

## Contribution

Contributing to our projects is always very appreciated.  
**But: please follow the contribution guidelines written down in the [CONTRIBUTING.md](https://github.com/markenwerk/php-json-http-client/blob/master/CONTRIBUTING.md) document.**

## License

PHP JSON HTTP Client is under the MIT license.