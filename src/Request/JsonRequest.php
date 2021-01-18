<?php

namespace ChromaX\JsonHttpClient\Request;

use ChromaX\BasicHttpClient\Request\AbstractRequest;
use ChromaX\BasicHttpClient\Response\ResponseInterface;
use ChromaX\JsonHttpClient\Response\JsonResponse;

/**
 * Class JsonRequest
 *
 * @package ChromaX\JsonHttpClient\Request
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
