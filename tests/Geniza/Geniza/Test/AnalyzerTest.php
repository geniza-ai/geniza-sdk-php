<?php
declare(strict_types=1);
namespace Geniza\Geniza\Test;

use Geniza\Geniza;
use Geniza\Request\ResponseException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Tests\Support\BaseTestCase;

/**
 * @internal
 */
#[CoversClass(Geniza::class)]
#[CoversFunction('analyzeProductFeedback')]
final class AnalyzerTest extends BaseTestCase {
	/**
	 * Setup test case
	 */
	protected function setUp(): void {
		parent::setUp();
	}

	/**
	 * Test analyzeProductFeedback
	 */
	public function testProductFeedbackAnalyzer(): void {
		$historyCount = $this->rh->historyCount();
		$this->mock->reset();
		$this->mock->append(
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","version":"0.1.2","messages":null,"uuid":"80fd58c78e782a7f950e10a713105e026557ea44d54fe","feedback":{"classification":"neutral", "confidence":73}}'),
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","version":"0.1.2","messages":null,"uuid":"80fd58c78e782a7f945e10a713105e026557ea44d54fe","feedback":{"classification":"positive", "confidence":61}}')
		);

		// Main request
		$feedback = 'Actually, I was looking for some other methodology. Nothing wrong with the book, may be I could not understand exactly what I wanted.';

		try {
			$response = $this->geniza->analyzeProductFeedback($feedback);
		} catch (ResponseException $e) {
			echo "Connection issue: {$e->getMessage()}\n";
			echo "Response Code: {$e->getCode()}\n";
			echo "Response Payload: {$e->responsePayload}\n";
			$this->fail('Request Failed');
		}

		$this->assertNotEmpty($response->feedback);

		$this->assertSame('neutral', $response->feedback->classification);
		$this->assertSame(73, $response->feedback->confidence);

		$this->assertSame(45, strlen((string) $response->uuid), $response->uuid);
		$this->assertIsString($response->version);

		$requestHistory = $this->rh->getRequestHistoryBodies();
		$this->assertNotEmpty($requestHistory);

		$this->assertSame('{"feedback":"Actually, I was looking for some other methodology. Nothing wrong with the book, may be I could not understand exactly what I wanted.","sandbox":true}', $requestHistory[$historyCount]);

		// Test with title

		$title = 'I really liked this book';

		try {
			$response = $this->geniza->analyzeProductFeedback($feedback, $title);
		} catch (ResponseException $e) {
			echo "Connection issue: {$e->getMessage()}\n";
			echo "Response Code: {$e->getCode()}\n";
			echo "Response Payload: {$e->responsePayload}\n";
			$this->fail('Request Failed');
		}

		$this->assertNotEmpty($response->feedback);

		$this->assertSame('positive', $response->feedback->classification);
		$this->assertSame(61, $response->feedback->confidence);

		$this->assertSame(45, strlen((string) $response->uuid), $response->uuid);
		$this->assertIsString($response->version);

		$requestHistory = $this->rh->getRequestHistoryBodies();
		$this->assertNotEmpty($requestHistory);

		$this->assertSame('{"feedback":"Title: I really liked this book\n\nActually, I was looking for some other methodology. Nothing wrong with the book, may be I could not understand exactly what I wanted.","sandbox":true}', $requestHistory[++$historyCount]);
	}
}
