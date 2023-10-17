<?php
declare(strict_types=1);
namespace Geniza\Request;

use AllowDynamicProperties;

/**
 * HTTP Response Class
 *
 * This is the base class for handling all curl responses
 *
 * @since 0.1.0
 */
#[AllowDynamicProperties]
class Response {
	/**
	 * Properties
	 *
	 * @var array<string, mixed>
	 */
	private array $properties = [];

	/**
	 * Constructor class
	 *
	 * @param ?object $responseObject Base Object
	 */
	public function __construct(?object $responseObject = null) {
		if (isset($responseObject)) {
			foreach ($responseObject as $key => $value) {
				$this->{$key} = $value;
			}
		}
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
	 * Serialize object
	 */
	public function __serialize(): array {
		return $this->properties;
	}
}
