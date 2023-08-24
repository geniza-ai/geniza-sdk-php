<?php
declare(strict_types=1);
namespace Geniza\Request;

use Geniza\Config\Config;

/**
 * Request URL
 *
 * This class holds the URL and the request type
 *
 * @since 0.1.0
 */
class Url {
	/**
	 * Request URL
	 */
	protected string $url;

	/**
	 * Request Type
	 */
	protected Method $method = Method::GET;

	/**
	 * Constructor class
	 *
	 * @param string  $urlPath Full Url Path
	 * @param ?Method $method  HTTP Request Method [default: Method::GET]
	 */
	public function __construct(string $urlPath, ?Method $method = Method::GET) {
		/** @var Config $config */
		$config = Config::getInstance();

		$this->url    = $config->baseURI . $urlPath;
		$this->method = $method;
	}

	/**
	 * Retrieve private/protected properties
	 *
	 * @param string $name Property Name
	 */
	public function __get(string $name): mixed {
		return $this->{$name};
	}
}
