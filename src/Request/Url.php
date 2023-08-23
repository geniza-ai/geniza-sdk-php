<?php
namespace Geniza\Request;

use Geniza\Config\Config;

/**
 * Request URL
 *
 * This class holds the URL and the request type
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since 0.1.0
 */
class Url {

	/**
	 * Request URL
	 * @var string $url
	 */
	protected string $url;

	/**
	 * Request Type
	 * @var Method $method
	 */
	protected Method $method = Method::GET;

	/**
	 * Constructor class
	 *
	 * @param string $urlPath Full Url Path
	 * @param ?Method $method HTTP Request Method [default: Method::GET]
	 */
	public function __construct(string $urlPath, ?Method $method = Method::GET) {

		/** @var Config $config **/
		$config = Config::getInstance();

		$this->url = $config->baseURI . $urlPath;
		$this->method = $method;
	}

	/**
	 * Retrieve private/protected properties
	 *
	 * @param string $name Property Name
	 * @return mixed
	 */
	public function __get(string $name): mixed {
		return $this->$name;
	}
}