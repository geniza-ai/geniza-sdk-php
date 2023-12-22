<?php
declare(strict_types=1);
namespace Geniza\Geniza\Test;

use Geniza\Request\ResponseException;
use GuzzleHttp\Psr7\Response;
use Tests\Support\BaseTestCase;
use ValueError;

/**
 * @internal
 */
final class SapientSquirrelTest extends BaseTestCase {
	/**
	 * Setup test case
	 */
	protected function setUp(): void {
		parent::setUp();
	}

	/**
	 * Test Sapient Squirrel and feedback Methods
	 */
	public function testSapientSquirrel(): void {
		$this->mock->reset();
		$this->mock->append(
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","uuid":"80fd58c78e782a7f950e10a713105e026557ea44d54fe","version":"1.0.1","answer":"You may rely on it"}'),
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","messages":[{"type":"info","title":"Thank you","message":"Thank you for your feedback!"}]}'),
			new Response(400, ['Content-Type' => 'application/json'], '{"env":"testing","messages":[{"type":"error","title":"Unable to process request":"See Error list"}],"errors":{"rating":"A valid rating is required 0.0 - 1.0"}}')
		);

		// Main request
		try {
			$response = $this->geniza->askSapientSquirrel('Will I go to the ball this evening?');
		} catch (ResponseException $e) {
			echo "Connection issue: {$e->getMessage()}\n";
			echo "Response Code: {$e->getCode()}\n";
			echo "Response Payload: {$e->responsePayload}\n";
			$this->fail('Request Failed');
		}

		$request     = $this->mock->getLastRequest();
		$requestBody = json_decode((string) $request->getBody());

		$this->assertTrue($requestBody->sandbox);

		$this->assertIsString($response->answer);
		$this->assertSame(45, strlen((string) $response->uuid), $response->uuid);
		$this->assertIsString($response->version);

		$this->assertStringStartsNotWith('Error:', $response->answer, $response->answer);

		// check feedback requests
		$feedbackResponse = $this->geniza->provideFeedback($response->uuid, 0.9, 'This is test feedback');
		$this->assertTrue($feedbackResponse);

		try {
			$this->geniza->provideFeedback($response->uuid, 2.4, 'This is bad feedback');
			$this->fail('False Positive on feedback response');
		} catch (ValueError) {
			$this->assertTrue(true);
		}

		// Check request structure
//		$this->assertSame(2, $this->rh->historyCount());

		$requestHistory = $this->rh->getRequestHistoryBodies();

		$this->assertSame('{"question":"Will I go to the ball this evening?","sandbox":true}', $requestHistory[0]);
		$this->assertSame('{"uuid":"80fd58c78e782a7f950e10a713105e026557ea44d54fe","rating":0.9,"feedback":"This is test feedback","sandbox":true}', $requestHistory[1]);
	}
}
