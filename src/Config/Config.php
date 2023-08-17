<?php
namespace Geniza\Config;

/**
 * Main Config settings
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since V0.1.0
 */
class Config extends BaseConfig {

	/**
	 * Sandbox Base URI
	 * @var string $sbBaseURI
	 */
	private string $sbBaseURI = 'https://sandbox.geniza.ai/';

	/**
	 * Sandbox Base URI
	 * @var string $prodBaseURI
	 */
	private string $prodBaseURI = 'https://api.geniza.ai/';

	/**
	 * Base Path
	 * @var string $basePath
	 */
	private string $basePath = 'v1/';

	/**
	 * Base URI
	 * @var string $baseURI
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