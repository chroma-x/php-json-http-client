<?php

declare(strict_types=1);

namespace ChromaX\JsonHttpClient;

use ChromaX\BasicHttpClient\HttpClientInterface;
use ChromaX\BasicHttpClient\Request\Message\Header\Header;
use ChromaX\BasicHttpClient\Request\RequestInterface;
use ChromaX\BasicHttpClient\Request\Message\Message;
use ChromaX\BasicHttpClient\Request\Transport\HttpsTransport;
use ChromaX\BasicHttpClient\Request\Transport\HttpTransport;
use ChromaX\BasicHttpClient\Response\ResponseInterface;
use ChromaX\CommonException\NetworkException\Base\NetworkException;
use ChromaX\CommonException\NetworkException\ConnectionTimeoutException;
use ChromaX\JsonHttpClient\Request\JsonRequest;
use ChromaX\JsonHttpClient\Request\Message\Body\JsonBody;
use ChromaX\UrlUtil\Url;

/**
 * Class JsonHttpClient
 *
 * @package ChromaX\JsonHttpClient
 */
class JsonHttpClient implements HttpClientInterface
{

	private RequestInterface $request;

	public function __construct(string $endpoint)
	{
		$url = new Url($endpoint);
		$transport = new HttpTransport();
		if (mb_strtoupper($url->getScheme()) === 'HTTPS') {
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

	public function getRequest(): RequestInterface
	{
		return $this->request;
	}

	/**
	 * @param string[] $queryParameters
	 */
	public function get(array $queryParameters = []): ResponseInterface
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
	 */
	public function head(array $queryParameters = []): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_HEAD)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

	public function post(array $postData = []): ResponseInterface
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($postData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_POST)
			->perform();
		return $this->request->getResponse();
	}

	public function put(array $putData = []): ResponseInterface
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($putData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PUT)
			->perform();
		return $this->request->getResponse();
	}

	public function patch(array $patchData = []): ResponseInterface
	{
		$this->request
			->getMessage()
			->setBody(new JsonBody($patchData));
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_PATCH)
			->perform();
		return $this->request->getResponse();
	}

	public function delete(array $queryParameters = []): ResponseInterface
	{
		$this->request
			->setMethod(RequestInterface::REQUEST_METHOD_DELETE)
			->getUrl()
			->setQueryParametersFromArray($queryParameters);
		$this->request->perform();
		return $this->request->getResponse();
	}

}
