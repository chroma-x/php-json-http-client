<?php

namespace JsonHttpClient\Request\Message\Body;

use BasicHttpClient\Exception\HttpRequestMessageException;
use BasicHttpClient\Request\Message\Body\BodyInterface;

/**
 * Class JsonBody
 *
 * @package BasicHttpClient\Request\Message\Body
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
