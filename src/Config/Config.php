<?php
declare(strict_types=1);
namespace Geniza\Config;

/**
 * Main Config settings
 *
 * @since V0.1.0
 */
class Config extends BaseConfig {
	/**
	 * SDK Version number
	 */
	public string $version = '0.1.2';

	/**
	 * Sandbox Base URI
	 */
	private string $sbBaseURI = 'https://sandbox.geniza.ai/';

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
		parent::__construct();
		$this->baseURI = $this->prodBaseURI . $this->basePath;
	}

	/**
	 * Set the base uri to reference the sandbox
	 */
	public function setAsSandbox(): void {
		$this->baseURI = $this->sbBaseURI . $this->basePath;
	}

	/**
	 * Set the base uri to reference the production system
	 */
	public function setAsProduction(): void {
		$this->baseURI = $this->prodBaseURI . $this->basePath;
	}
}
