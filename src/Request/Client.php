<?php
namespace Geniza\Request;

use Exception;
use Geniza\Config\Access;
use Geniza\Config\Config;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use JsonException;

/**
 * Request client
 *
 * This class is the base class for all HTTP requests.
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since 0.1.0
 */
class Client {

	/**
	 * Curl Request
	 *
	 * @param Url $url Request URL Object
	 * @param ?Payload $payload Object [default: null]
	 * @param ?array $additionalHeaders Additional headers as an associative array [default: []]
	 * @return ?Response
	 *
	 * @throws Exception
	 * @throws ResponseException
	 * @throws JsonException
	 */
	public function request(Url $url, ?Payload $payload = null, ?array $additionalHeaders = []): ?Response {

		/** @var Config $config **/
		$config = Config::getInstance();

		/** @var Access $access */
		$access = Access::getInstance();

		$client = new \GuzzleHttp\Client();

		$body = $this->getBody($payload);

		$request = new Request($url->method->value, $url->url);
		$requestConfig = [
			'timeout' => 2,
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => 'HMAC-SHA256 ' . $access->key . ':' . $this->getHMACHash($access->secretKey, $body),
				'Content-Type' => 'application/json',
			],
			'body' => $body,
			'verify' => false // TODO: remove this line
		];

		try {
			$response = $client->send($request, $requestConfig);
		} catch (GuzzleException $e) {
			throw new ResponseException($e->getMessage(), $e->getCode(), $e->getTraceAsString());
		}

		if($response->getStatusCode() != 200) {
			throw new ResponseException('Request Error', $response->getStatusCode(), $response->getBody());
		}

		$result = json_decode($response->getBody());
		if (!isset($result)) {
			return null;
		}

		return new Response($result);
	}

	/**
	 * Generate final body value
	 *
	 * @param ?Payload $fromPayload Payload
	 * @return string JSON string
	 * @throws JsonException
	 */
	private function getBody(?Payload $fromPayload = null): string {
		// if payload is null just return an empty string
		if(! isset($fromPayload)) {
			return '';
		}

		return json_encode($fromPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
	}

	/**
	 * Generate HMAC Hash value
	 *
	 * @param string $secretKey Secret Key
	 * @param string $body Request Body
	 * @return string
	 */
	private function getHMACHash(string $secretKey, string $body): string {
		return hash_hmac('sha256', $body, $secretKey);
	}
}