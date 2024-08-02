<?php

declare(strict_types=1);

namespace ChromaX\JsonHttpClient\Request\Message\Body;

use ChromaX\BasicHttpClient\Exception\HttpRequestMessageException;
use ChromaX\BasicHttpClient\Request\Message\Body\BodyInterface;

/**
 * Class JsonBody
 *
 * @package ChromaX\JsonHttpClient\Request\Message\Body
 */
class JsonBody implements BodyInterface
{

	private array $bodyData;

	public function __construct(array $bodyData)
	{
		$this->bodyData = $bodyData;
	}

	public function configureCurl(\CurlHandle $curl): self
	{
		$jsonBody = json_encode($this->bodyData);
		if ($jsonBody === false) {
			throw new HttpRequestMessageException('JSON body data not serializable.');
		}
		curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBody);
		return $this;
	}

}
