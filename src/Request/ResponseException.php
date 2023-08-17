<?php
namespace Geniza\Request;

use Exception;
use Throwable;

/**
 * Geniza Response Exception Error
 *
 * This class will be used for all http request errors
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since 0.1.0
 */
class ResponseException extends Exception {

	/**
	 * Response Payload
	 * @var ?string $responsePayload
	 */
	public ?string $responsePayload;

	/**
	 * Constructor
	 *
	 * @param string $message
	 * @param int $code
	 * @param ?string $responsePayload
	 * @param Throwable|null $previous
	 */
	public function __construct(string $message, int $code, ?string $responsePayload = null, ?Throwable $previous = null) {
		$this->responsePayload = $responsePayload;

		// make sure everything is assigned properly
		parent::__construct($message, $code, $previous);
	}
}