<?php

namespace Markenwerk\JsonHttpClient\Request\Message\Body;

use Markenwerk\BasicHttpClient\Exception\HttpRequestMessageException;
use Markenwerk\BasicHttpClient\Request\Message\Body\BodyInterface;

/**
 * Class JsonBody
 *
 * @package Markenwerk\JsonHttpClient\Request\Message\Body
 */
class JsonBody implements BodyInterface
{

	/**
	 * @var array
	 */
	private $bodyData;

	/**
	 * Body constructor.
	 *
	 * @param array $bodyData
	 */
	public function __construct(array $bodyData)
	{
		$this->bodyData = $bodyData;
	}

	/**
	 * @param resource $curl
	 * @return $this
	 * @throws HttpRequestMessageException
	 */
	public function configureCurl($curl)
	{
		$jsonBody = @json_encode($this->bodyData);
		if ($jsonBody === false) {
			throw new HttpRequestMessageException('JSON body data not serializable.');
		}
		curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBody);
		return $this;
	}

}
