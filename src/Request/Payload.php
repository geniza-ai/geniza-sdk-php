<?php
declare(strict_types=1);
namespace Geniza\Request;

use Geniza\Config\Config;
use JsonSerializable;

/**
 * Class Request Payload
 *
 * This class is the base class for all HTTP request bodies.
 *
 * @since 0.1.0
 */
class Payload implements JsonSerializable {
	/**
	 * Properties
	 *
	 * @var string[]
	 */
	private array $properties = [];

	/**
	 * Constructor
	 *
	 * @param array|object|null $data Data Object
	 */
	public function __construct($data) {
		foreach ($data as $key => $value) {
			$this->__set($key, $value);
		}
	}

	/**
	 * JSON Encode object
	 *
	 * @return string JSON Encoded Object
	 */
	public function encode(): string {
		return json_encode($this, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/**
	 * Set Properties
	 *
	 * @param string $name  Property name
	 * @param mixed  $value Property value
	 */
	public function __set(string $name, mixed $value): void {
		$this->properties[$name] = $value;
	}

	/**
	 * Get Properties
	 *
	 * @param string $name Property Name
	 *
	 * @return mixed Property Value
	 */
	public function __get(string $name): mixed {
		return $this->properties[$name];
	}

	/**
	 * JSON Serialize object
	 *
	 * @return array
	 */
	public function jsonSerialize(): mixed {
		if (! isset($this->properties['sandbox'])) {
			/** @var Config $config */
			$config = Config::getInstance();

			$this->properties['sandbox'] = $config->isSandbox;
		}

		return $this->properties;
	}
}
