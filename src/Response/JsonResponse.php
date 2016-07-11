<?php

namespace Markenwerk\JsonHttpClient\Response;

use Markenwerk\BasicHttpClient\Exception\HttpResponseException;
use Markenwerk\BasicHttpClient\Response\AbstractResponse;

/**
 * Class JsonResponse
 *
 * @package Markenwerk\JsonHttpClient\Response
 */
class JsonResponse extends AbstractResponse
{

	/**
	 * @param mixed $body
	 * @return $this
	 * @throws HttpResponseException
	 */
	protected function setBody($body)
	{
		$body = @json_decode($body, true);
		if (is_null($body)) {
			throw new HttpResponseException('Response data is no valid JSON string.');
		}
		return parent::setBody($body);
	}

}
