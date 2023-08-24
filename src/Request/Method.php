<?php
declare(strict_types=1);
namespace Geniza\Request;

/**
 * Enum RequestMethod
 *
 * Pre-defined list of HTTP Request Methods/Types
 *
 * @since 0.1.0
 */
enum Method: string {
	case GET     = 'GET';
	case HEAD    = 'HEAD';
	case POST    = 'POST';
	case PATCH   = 'PATCH';
	case PUT     = 'PUT';
	case DELETE  = 'DELETE';
	case CONNECT = 'CONNECT';
	case OPTIONS = 'OPTIONS';
	case TRACE   = 'TRACE';
}
