<?php
namespace Geniza\Request;

/**
 * Class Request Payload
 *
 * This class is the base class for all HTTP request bodies.
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since 0.1.0
 */
class Payload {

	/**
	 * Properties
	 * @var string[] $properties
	 */
	private array $properties = [];

	/**
	 * Constructor
	 *
	 * @param ?object|array $data Data Object
	 */
	public function __construct($data) {

		foreach($data as $key=>$value) {
			$this->__set($key, $value);
		}
	}

	/**
	 * JSON Encode object
	 *
	 * @return string JSON Encoded Object
	 */
	public function encode(): string {
		return json_encode($this, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Set Properties
	 *
	 * @param string $name Property name
	 * @param mixed $value Property value
	 * @return void
	 */
	public function __set(string $name, mixed $value): void {
		$this->properties[$name] = $value;
	}

	/**
	 * Get Properties
	 *
	 * @param string $name Property Name
	 * @return mixed Property Value
	 */
	public function __get(string $name): mixed {
		return $this->properties[$name];
	}

	/**
	 * Serialize object
	 *
	 * @return array
	 */
	public function __serialize(): array {
		return $this->properties;
	}
}