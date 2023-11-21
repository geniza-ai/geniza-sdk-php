<?php
declare(strict_types=1);
namespace Geniza\Config;

use Exception;

/**
 * Abstract Singleton Class
 *
 * @since V0.1.0
 */
abstract class Singleton {
	/**
	 * Reference to self
	 *
	 * This is protected (as opposed to private) to allow access in subclasses.
	 *
	 * @var Singleton[]
	 */
	protected static array $instances = [];

	/**
	 * Remove access to __construct()
	 *
	 * This is allowed to be overridden in subclasses in order for
	 */
	abstract protected function __construct();

	/**
	 * Remove access to __clone() method
	 *
	 * @throws Exception
	 */
	final public function __clone() {
		throw new Exception('Singletons are not cloneable.');
	}

	/**
	 * Remove access to further __wakeup()
	 *
	 * @throws Exception
	 */
	final public function __wakeup(): void {
		throw new Exception('Cannot deserialize a singleton.');
	}

	/**
	 * Primary instantiation method
	 *
	 * While this can be overridden, it should be done ONLY in a rare exception.
	 */
	public static function getInstance(...$options): mixed {
		$class = static::class;
		if (! isset(static::$instances[$class])) {
			static::$instances[$class] = new static(...$options);
		}

		return static::$instances[$class];
	}
}
