<?php
namespace Geniza\Config;

/**
 * Access Key Values
 *
 * @author Tim Swagger <tim@geniza.ai>
 * @since V0.1.0
 */
class Access extends BaseConfig {

	/**
	 * API security Key
	 * @var string $key
	 */
	public string $key;

	/**
	 * API Secret Key
	 * @var string $secretKey
	 */
	public string $secretKey;

}