<?php

namespace Geniza\Request;

/**
 * HTTP Response Class
 *
 * This is the base class for handling all curl responses
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since 0.1.0
 */
class Response {

	/**
	 * Properties
	 * @var string[] $properties
	 */
	private array $properties = [];

	/**
	 * Constructor class
	 *
	 * @param ?object $responseObject Base Object
	 */
	public function __construct(?object $responseObject = null) {
		if(isset($responseObject)) {
			foreach ($responseObject as $key => $value) {
				$this->$key = $value;
			}
		}
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