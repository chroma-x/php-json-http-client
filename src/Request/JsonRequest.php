<?php

namespace Markenwerk\JsonHttpClient\Request;

use Markenwerk\BasicHttpClient\Request\AbstractRequest;
use Markenwerk\BasicHttpClient\Response\ResponseInterface;
use Markenwerk\JsonHttpClient\Response\JsonResponse;

/**
 * Class JsonRequest
 *
 * @package Markenwerk\JsonHttpClient\Request
 */
class JsonRequest extends AbstractRequest
{

	/**
	 * @return ResponseInterface
	 */
	protected function buildResponse(): ResponseInterface
	{
		return new JsonResponse($this);
	}

}
