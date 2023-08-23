<?php

namespace Geniza\Geniza\Test;

use Geniza\Geniza;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase {

	/**
	 * Geniza SDK Reference
	 * @var Geniza $geniza
	 */
	private Geniza $geniza;

	/**
	 * Setup test case
	 *
	 * @return void
	 */
	protected function setup(): void {
		$this->geniza = new Geniza('a6c460e1c1d73e08915c151b4cabbe8e', '565c05c85232f0e5132c8b5015476cdd', true);
	}

	public function testSapientSquirrel(): void {
		$response = $this->geniza->askSapientSquirrel('Will I go to the ball this evening?');

		$this->assertIsString($response);
		$this->assertStringStartsNotWith('Error:', $response, $response);
		echo "Response: {$response}";
	}

}