<?php
declare(strict_types=1);
namespace Geniza;

use Exception;
use Geniza\Config\Access;
use Geniza\Config\Config;
use Geniza\Request\Client;
use Geniza\Request\Method;
use Geniza\Request\Payload;
use Geniza\Request\Response;
use Geniza\Request\ResponseException;
use Geniza\Request\Url;
use JsonException;
use ValueError;

/**
 * Geniza.ai PHP SKD
 *
 * @version 0.1.0
 */
class Geniza {
	/**
	 * Config object reference
	 */
	protected Config $config;

	/**
	 * Geniza Constructor
	 *
	 * @param string $key         API Key
	 * @param string $secretKey   API Secret Key
	 * @param bool   $sandBoxMode Sand Box Mode [default: false]
	 */
	public function __construct(string $key, string $secretKey, bool $sandBoxMode = false) {
		/** @var Access $access */
		$access = Access::getInstance();

		$access->key       = $key;
		$access->secretKey = $secretKey;

		$this->config = Config::getInstance();
		if ($sandBoxMode) {
			$this->config->setAsSandbox();
		}
	}

	/**
	 * the Sapient Squirrel
	 *
	 * @param string $question The question you would like to ask the Sapient Squirrel
	 *
	 * @return Response The response from the Sapient Squirrel
	 *
	 * @throws ResponseException
	 */
	public function askSapientSquirrel(string $question): Response {
		$requestClient = new Client();
		$url           = new Url('sapientSquirrel', Method::POST);
		$payload       = new Payload(['question' => $question]);

		try {
			$response = $requestClient->request($url, $payload);
		} catch (ResponseException|JsonException|Exception $e) {
			throw new ResponseException('Error: ' . $e->getMessage(), $e->getCode(), $e->responsePayload ?? null);
		}

		return $response;
	}

	/**
	 * Stock Symbol Extractor
	 *
	 * @param string $text Text block containing Company names/references
	 *
	 * @return Response A Response object containing a list of company names and their stock symbols
	 *
	 * @throws ResponseException
	 */
	public function extractStockSymbols(string $text): Response {
		$requestClient = new Client();
		$url           = new Url('extractors/stockSymbols', Method::POST);
		$payload       = new Payload(['text' => $text]);

		try {
			$response = $requestClient->request($url, $payload);
		} catch (ResponseException|JsonException|Exception $e) {
			throw new ResponseException('Error: ' . $e->getMessage(), $e->getCode(), $e->responsePayload ?? null);
		}

		return $response;
	}

	/**
	 * Language Detector
	 *
	 * @param string $text Text block which needs the written language identified.
	 *
	 * @return Response A Response object containing information on the language
	 *
	 * @throws ResponseException
	 */
	public function detectLanguage(string $text): Response {
		$requestClient = new Client();
		$url           = new Url('detectors/language', Method::POST);
		$payload       = new Payload(['text' => $text]);

		try {
			$response = $requestClient->request($url, $payload);
		} catch (ResponseException|JsonException|Exception $e) {
			throw new ResponseException('Error: ' . $e->getMessage(), $e->getCode(), $e->responsePayload ?? null);
		}

		return $response;
	}

	/**
	 * PII Detector
	 *
	 * @param string $text Text block containing information that may contain PII
	 *
	 * @return Response A Response object containing a list identified PII
	 *
	 * @throws ResponseException
	 */
	public function detectPii(string $text): Response {
		$requestClient = new Client();
		$url           = new Url('detectors/pii', Method::POST);
		$payload       = new Payload(['text' => $text]);

		try {
			$response = $requestClient->request($url, $payload);
		} catch (ResponseException|JsonException|Exception $e) {
			throw new ResponseException('Error: ' . $e->getMessage(), $e->getCode(), $e->responsePayload ?? null);
		}

		return $response;
	}

	/**
	 * Provide feedback on Geniza.ai Response
	 *
	 * @param string  $uuid               Unique Request ID
	 * @param float   $rating             Rating on feedback 0.0 (poor) to 1.0 (good) [default: 0]
	 * @param ?string $additionalFeedback additional feedback you wish to provide us with [default: null]
	 */
	public function provideFeedback(string $uuid, float $rating = 1.0, ?string $additionalFeedback = null): bool {
		if ($rating < 0.0 || $rating > 1.0) {
			throw new ValueError('Rating must be between 0.0 and 1.0.');
		}

		$requestClient = new Client();
		$url           = new Url('feedback', Method::POST);
		$payload       = new Payload([
			'uuid'     => $uuid,
			'rating'   => $rating,
			'feedback' => $additionalFeedback,
		]);

		try {
			return (bool) $requestClient->request($url, $payload);
		} catch (ResponseException|JsonException|Exception) {
			return false;
		}
	}
}
