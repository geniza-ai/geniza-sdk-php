<?php
namespace Geniza\Config;

/**
 * Abstract Singleton Class
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since V0.1.0
 */
abstract class Singleton {
	/**
	 * Reference to self
	 *
	 * This is protected (as opposed to private) to allow access in subclasses.
	 *
	 * @var Singleton[] $instances
	 */
	protected static array $instances = [];

	/**
	 * Remove access to __construct()
	 *
	 * This is allowed to be overridden in subclasses in order for
	 */
	protected function __construct(){}

	/**
	 * Remove access to __clone() method
	 * @throws \Exception
	 */
	final protected function __clone(){
		throw new \Exception("Singletons are not cloneable.");
	}

	/**
	 * Remove access to further __wakeup()
	 * @throws \Exception
	 */
	final protected function __wakeup(){
		throw new \Exception("Cannot deserialize a singleton.");
	}

	/**
	 * Primary instantiation method
	 *
	 * While this can be overridden, it should be done ONLY in a rare exception.
	 *
	 * @return Singleton
	 */
	public static function getInstance(): Singleton {
		$class = static::class;
		if (! isset(static::$instances[$class])) {
			static::$instances[$class] = new static();
		}

		return static::$instances[$class];
	}
}