<?php
declare(strict_types=1);
namespace Geniza\Config;

/**
 * Main Config settings
 *
 * @since V0.1.0
 *
 * @property-read bool $isSandbox;
 */
class Config extends BaseConfig {
	/**
	 * SDK Version number
	 */
	public string $version = '0.3.1';

	/**
	 * Sandbox mode
	 */
	private bool $isSandbox;

	/**
	 * Sandbox Base URI
	 */
	private string $prodBaseURI = 'https://api.geniza.ai/';

	/**
	 * Base Path
	 */
	private string $basePath = 'v1/';

	/**
	 * Base URI
	 */
	public string $baseURI;

	/**
	 * Constructor method for the config class
	 */
	protected function __construct() {
		$this->setAsProduction();
	}

	/**
	 * Set the base uri to reference the sandbox
	 */
	public function setAsSandbox(): void {
		$this->isSandbox = true;
	}

	/**
	 * Set the base uri to reference the production system
	 *
	 * This currently does not do anything and should not be used. It exists as a placeholder for the future when the
	 * sandbox system is a separate service.
	 */
	public function setAsProduction(): void {
		$this->isSandbox = false;
		$this->baseURI   = $this->prodBaseURI . $this->basePath;
	}

	/**
	 * Magic __get method
	 */
	public function __get(string $name): mixed {
		if ($name === 'isSandbox') {
			return $this->isSandbox;
		}

		return null;
	}
}
