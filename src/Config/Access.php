<?php
declare(strict_types=1);
namespace Geniza\Config;

/**
 * Access Key Values
 *
 * @since V0.1.0
 */
class Access extends BaseConfig {
	/**
	 * API security Key
	 */
	public string $key;

	/**
	 * API Secret Key
	 */
	public string $secretKey;
}
