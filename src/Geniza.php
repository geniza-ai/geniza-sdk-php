<?php
namespace Geniza;

use Geniza\Config\Access;
use Geniza\Config\Config;

/**
 * Geniza.ai PHP SKD
 *
 * @package Geniza\Geniza
 * @author Tim Swagger <tim@geniza.ai>
 * @version 0.1.0
 */
class Geniza {

	/**
	 * Config object reference
	 * @var Config $config
	 */
	protected Config $config;

	/**
	 * Geniza Constructor
	 *
	 * @param string $key API Key
	 * @param string $secretKey API Secret Key
	 * @param bool $sandBoxMode Sand Box Mode [default: false]
	 */
	public function __construct(string $key, string $secretKey, bool $sandBoxMode = false) {
		/** @var Access $access */
		$access = Access::getInstance();

		$access->key = $key;
		$access->secretKey = $secretKey;

		/** @var Config $this->config */
		$this->config = Config::getInstance();
		if($sandBoxMode) {
			$this->config->setAsSandbox();
		}
	}
}