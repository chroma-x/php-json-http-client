<?php

declare(strict_types=1);

namespace ChromaX\JsonHttpClient\Response;

use ChromaX\BasicHttpClient\Exception\HttpResponseException;
use ChromaX\BasicHttpClient\Response\AbstractResponse;

/**
 * Class JsonResponse
 *
 * @package ChromaX\JsonHttpClient\Response
 */
class JsonResponse extends AbstractResponse
{

	protected function setBody(mixed $body): self
	{
		if ($this->getStatusCode() === 204) {
			parent::setBody(null);
			return $this;
		}
		$body = json_decode($body, true);
		if ($body === null) {
			throw new HttpResponseException('Response data is no valid JSON string.');
		}
		parent::setBody($body);
		return $this;
	}
	
	public function getBody(): array
	{
		return parent::getBody();
	}

}
