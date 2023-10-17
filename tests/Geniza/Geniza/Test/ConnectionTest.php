<?php
declare(strict_types=1);
namespace Geniza\Geniza\Test;

use Geniza\Geniza;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * @internal
 */
final class ConnectionTest extends TestCase {
	/**
	 * Geniza SDK Reference
	 */
	private Geniza $geniza;

	/**
	 * Setup test case
	 */
	protected function setup(): void {
		$this->geniza = new Geniza('a6c460e1c1d73e08915c151b4cabbe8e', '565c05c85232f0e5132c8b5015476cdd', true);
	}

	public function testSapientSquirrel(): void {
		$response = $this->geniza->askSapientSquirrel('Will I go to the ball this evening?');

		$this->assertIsString($response->answer);
		$this->assertSame(45, strlen($response->uuid), $response->uuid);
		$this->assertIsString($response->version);

		$this->assertStringStartsNotWith('Error:', $response->answer, $response->answer);

		$feedbackResponse = $this->geniza->provideFeedback($response->uuid, 0.9, 'This is test feedback');
		$this->assertTrue($feedbackResponse);

		try {
			$this->geniza->provideFeedback($response->uuid, 2.4, 'This is bad feedback');
			$this->fail('False Positive on feedback response');
		} catch (ValueError) {
			$this->assertTrue(true);
		}
	}
}
