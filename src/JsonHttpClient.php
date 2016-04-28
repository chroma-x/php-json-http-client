<?php

namespace JsonHttpClient;

use BasicHttpClient\HttpClientInterface;
use BasicHttpClient\Request\Message\Header\Header;
use BasicHttpClient\Request\RequestInterface;
use BasicHttpClient\Request\Message\Message;
use BasicHttpClient\Request\Transport\HttpsTransport;
use BasicHttpClient\Request\Transport\HttpTransport;
use BasicHttpClient\Response\ResponseInterface;
use BasicHttpClient\Util\UrlUtil;
use JsonHttpClient\Request\JsonRequest;
use JsonHttpClient\Request\Message\Body\JsonBody;

/**
 * Class JsonHttpClient
 *
 * @package BasicHttpClient
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
		$urlUtil = new UrlUtil();
		$transport = new HttpTransport();
		if ($urlUtil->getScheme($endpoint) == 'HTTPS') {
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
			->setEndpoint($endpoint);
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
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function get(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_GET)
			->setQueryParameters($queryParameters)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param string[] $queryParameters
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function head(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_HEAD)
			->setQueryParameters($queryParameters)
			->perform();
		return $this->request->getResponse();
	}

	/**
	 * @param array $postData
	 * @return ResponseInterface
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
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
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
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
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
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
	 * @throws \CommonException\NetworkException\Base\NetworkException
	 * @throws \CommonException\NetworkException\ConnectionTimeoutException
	 */
	public function delete(array $queryParameters = null)
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_DELETE)
			->setQueryParameters($queryParameters)
			->perform();
		return $this->request->getResponse();
	}

}
