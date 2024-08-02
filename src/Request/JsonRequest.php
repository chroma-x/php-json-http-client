<?php

declare(strict_types=1);

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

	protected function buildResponse(): ResponseInterface
	{
		return new JsonResponse($this);
	}

}
