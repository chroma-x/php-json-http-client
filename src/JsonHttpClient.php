<?php

namespace Markenwerk\JsonHttpClient;

use Markenwerk\BasicHttpClient\HttpClientInterface;
use Markenwerk\BasicHttpClient\Request\Message\Header\Header;
use Markenwerk\BasicHttpClient\Request\RequestInterface;
use Markenwerk\BasicHttpClient\Request\Message\Message;
use Markenwerk\BasicHttpClient\Request\Transport\HttpsTransport;
use Markenwerk\BasicHttpClient\Request\Transport\HttpTransport;
use Markenwerk\BasicHttpClient\Response\ResponseInterface;
use Markenwerk\CommonException\NetworkException\Base\NetworkException;
use Markenwerk\CommonException\NetworkException\ConnectionTimeoutException;
use Markenwerk\JsonHttpClient\Request\JsonRequest;
use Markenwerk\JsonHttpClient\Request\Message\Body\JsonBody;
use Markenwerk\UrlUtil\Url;

/**
 * Class JsonHttpClient
 *
 * @package Markenwerk\JsonHttpClient
 */
class JsonHttpClient implements HttpClientInterface
{

	/**
	 * @var RequestInterface
	 */
	private $request;

	/**
	 * BasicHttpClient constructor.
	 *
	 * @param string $endpoint
	 */
	public function __construct($endpoint)
	{
		$url = new Url($endpoint);
		$transport = new HttpTransport();
		if (mb_strtoupper($url->getScheme()) == 'HTTPS') {
			$transport = new HttpsTransport();
		}
		$message = new Message();
		$message
			->addHeader(new Header('Accept', array('application/json')))
			->addHeader(new Header('Content-Type', array('application/json')));
		$this->request = new JsonRequest();
		$this->request
			->setTransport($transport)
			->setMessage($message)
			->setUrl($url);
	}

	/**
	 * @return RequestInterface
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function get(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_GET)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function head(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_HEAD)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function post(array $postData = null)
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($postData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_POST)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $putData
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function put(array $putData = null)
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($putData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PUT)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $patchData
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function patch(array $patchData = null)
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($patchData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PATCH)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 * @throws NetworkException
	 * @throws ConnectionTimeoutException
	 */
	public function delete(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_DELETE)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

}
