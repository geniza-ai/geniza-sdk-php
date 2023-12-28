<?php
declare(strict_types=1);
namespace Geniza\Geniza\Test;

use Geniza\Request\ResponseException;
use GuzzleHttp\Psr7\Response;
use Tests\Support\BaseTestCase;

/**
 * @internal
 */
final class AnalyzerTest extends BaseTestCase {
	/**
	 * Setup test case
	 */
	protected function setUp(): void {
		parent::setUp();
	}

	/**
	 * Test Language Detector
	 */
	public function testProductFeedbackAnalyzer(): void {
		$this->mock->reset();
		$this->mock->append(
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","version":"0.1.2","messages":null,"uuid":"80fd58c78e782a7f950e10a713105e026557ea44d54fe","language":{"code":"en", "name":"English", "localName":"English"}}')
		);

		// Main request
		$textBlock = "Big Tech on Trial reporter Lee Hepner—who also serves as antitrust legal counsel for the nonprofit the American Economic Liberties Project—posted on X (formerly Twitter) to summarize Murphy's testimony as arguing, \"Google's Search monopoly is good for you, consumer choice is 'irrational,' and privacy is bad quality.\"\nOn the day prior, Murphy potentially bolstered the DOJ's case by accidentally leaking a key figure that both Google and Apple had specifically requested remain confidential—confirming that Apple gets a 36 percent cut of search ad revenue from its Safari deal with Google.";

		try {
			$response = $this->geniza->analyzeProductFeedback($textBlock);
		} catch (ResponseException $e) {
			echo "Connection issue: {$e->getMessage()}\n";
			echo "Response Code: {$e->getCode()}\n";
			echo "Response Payload: {$e->responsePayload}\n";
			$this->fail('Request Failed');
		}

		$this->assertNotEmpty($response->language);

		$this->assertSame('en', $response->language->code);
		$this->assertSame('English', $response->language->name);
		$this->assertSame('English', $response->language->localName);

		$this->assertSame(45, strlen((string) $response->uuid), $response->uuid);
		$this->assertIsString($response->version);

		$requestHistory = $this->rh->getRequestHistoryBodies();
		$this->assertNotEmpty($requestHistory);

		$this->assertSame('{"text":"Big Tech on Trial reporter Lee Hepner—who also serves as antitrust legal counsel for the nonprofit the American Economic Liberties Project—posted on X (formerly Twitter) to summarize Murphy\u0027s testimony as arguing, \u0022Google\u0027s Search monopoly is good for you, consumer choice is \u0027irrational,\u0027 and privacy is bad quality.\u0022\nOn the day prior, Murphy potentially bolstered the DOJ\u0027s case by accidentally leaking a key figure that both Google and Apple had specifically requested remain confidential—confirming that Apple gets a 36 percent cut of search ad revenue from its Safari deal with Google."}', $requestHistory[0]);
	}
}
