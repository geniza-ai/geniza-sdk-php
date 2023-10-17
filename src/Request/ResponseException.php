<?php
declare(strict_types=1);
namespace Geniza\Request;

use Exception;
use Throwable;

/**
 * Geniza Response Exception Error
 *
 * This class will be used for all http request errors
 *
 * @since 0.1.0
 */
class ResponseException extends Exception {
	/**
	 * Response Payload
	 */
	public ?string $responsePayload;

	/**
	 * Constructor
	 */
	public function __construct(string $message, int $code, ?string $responsePayload = null, ?Throwable $previous = null) {
		$this->responsePayload = $responsePayload;

		// make sure everything is assigned properly
		parent::__construct($message, $code, $previous);
	}
}
