<?php
declare(strict_types=1);
namespace Geniza;

use Exception;
use Geniza\Config\Access;
use Geniza\Config\Config;
use Geniza\Request\Client;
use Geniza\Request\Method;
use Geniza\Request\Payload;
use Geniza\Request\Url;
use JsonException;

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

		/** @var Config $this->config */
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
	 * @return string The response from the Sapient Squirrel
	 */
	public function askSapientSquirrel(string $question): string {
		$requestClient = new Client();
		$url           = new Url('sapientSquirrel', Method::POST);
		$payload       = new Payload(['question' => $question]);

		try {
			$response = $requestClient->request($url, $payload);
		} catch (Request\ResponseException|JsonException|Exception $e) {
			return 'Error: ' . $e->getCode() . '; ' . $e->getMessage() . ";\n\n" . $e->responsePayload;
		}

		return $response->answer;
	}
}
