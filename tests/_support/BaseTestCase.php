<?php
declare(strict_types=1);
namespace Tests\Support;

use Geniza\Geniza;
use Geniza\Request\RequestHandler;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
abstract class BaseTestCase extends TestCase {
	/**
	 * Geniza SDK Reference
	 */
	protected Geniza $geniza;

	protected RequestHandler $rh;
	protected MockHandler $mock;

	protected function setUp(): void {
		parent::setUp();

		$this->geniza = new Geniza('a6c460e1c1d73e08915c151b4cabbe8e', '565c05c85232f0e5132c8b5015476cdd', true);

		$this->rh   = RequestHandler::getInstance(true);
		$this->mock = $this->rh->getHandler();
	}
}
