<?php

namespace JsonHttpClient\Request;

use BasicHttpClient\Request\AbstractRequest;
use BasicHttpClient\Response\ResponseInterface;
use JsonHttpClient\Response\JsonResponse;

/**
 * Class JsonRequest
 *
 * @package BasicHttpClient\Request
 */
class JsonRequest extends AbstractRequest
{

	/**
	 * @return ResponseInterface
	 */
	protected function buildResponse()
	{
		return new JsonResponse($this);
	}

}
