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
#[CoversFunction('detectLanguage')]
#[CoversFunction('detectPii')]
final class DetectorTest extends BaseTestCase {
	/**
	 * Setup test case
	 */
	protected function setUp(): void {
		parent::setUp();
	}

	/**
	 * Test Language Detector
	 */
	public function testLanguageDetector(): void {
		$historyCount = $this->rh->historyCount();
		$this->mock->reset();
		$this->mock->append(
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","version":"0.1.2","messages":null,"uuid":"80fd58c78e782a7f950e10a713105e026557ea44d54fe","language":{"code":"en", "name":"English", "localName":"English"}}')
		);

		// Main request
		$textBlock = "Big Tech on Trial reporter Lee Hepner—who also serves as antitrust legal counsel for the nonprofit the American Economic Liberties Project—posted on X (formerly Twitter) to summarize Murphy's testimony as arguing, \"Google's Search monopoly is good for you, consumer choice is 'irrational,' and privacy is bad quality.\"\nOn the day prior, Murphy potentially bolstered the DOJ's case by accidentally leaking a key figure that both Google and Apple had specifically requested remain confidential—confirming that Apple gets a 36 percent cut of search ad revenue from its Safari deal with Google.";

		try {
			$response = $this->geniza->extractStockSymbols($textBlock);
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

		$this->assertSame('{"text":"Big Tech on Trial reporter Lee Hepner—who also serves as antitrust legal counsel for the nonprofit the American Economic Liberties Project—posted on X (formerly Twitter) to summarize Murphy\u0027s testimony as arguing, \u0022Google\u0027s Search monopoly is good for you, consumer choice is \u0027irrational,\u0027 and privacy is bad quality.\u0022\nOn the day prior, Murphy potentially bolstered the DOJ\u0027s case by accidentally leaking a key figure that both Google and Apple had specifically requested remain confidential—confirming that Apple gets a 36 percent cut of search ad revenue from its Safari deal with Google.","sandbox":true}', $requestHistory[$historyCount]);
	}

	/**
	 * Test PII Detector
	 */
	public function testPiiDetector(): void {
		$historyCount = $this->rh->historyCount();
		$this->mock->reset();
		$this->mock->append(
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","version":"0.1.2","messages":null,"uuid":"80fd58c78e782a7f950e10a713105e026557ea44d54fe","pii":[{"category":"financial_identifiers","subcategory":"payment schedule","identifier":"$74.37 USD on February 2, 2024"},{"category":"financial_identifiers","subcategory":"bank account number","identifier":"x-1234"},{"category":"financial_identifiers","subcategory":"bank name","identifier":"BANK OF AMERICA, N.A."}]}')
		);

		// Main request
		$textBlock = "We've paid The Home Depot for your purchase in full so you can spread your $297.46 USD purchase
			into 4 payments. Here's your payment schedule:
			$74.37 USD due today
			$74.36 USD on January 3, 2024
			$74.36 USD on January 18, 2024
			$74.37 USD on February 2, 2024
			Both the total purchase and your down payment amounts will appear in your PayPal activity, but you're not being
			charged twice. If dates and amounts change for any reason, we’ll let you know.
			There's nothing else you need to do right now. We'll automatically withdraw your payments from:
			BANK OF AMERICA, N.A.
			Bank Account x-1234";

		try {
			$response = $this->geniza->extractStockSymbols($textBlock);
		} catch (ResponseException $e) {
			echo "Connection issue: {$e->getMessage()}\n";
			echo "Response Code: {$e->getCode()}\n";
			echo "Response Payload: {$e->responsePayload}\n";
			$this->fail('Request Failed');
		}

		$this->assertNotEmpty($response->pii);

		$found = false;

		foreach ($response->pii as $pii) {
			if (($pii->category === 'financial_identifiers' || $pii->category === 'personal_identifiers')
				&& str_contains((string) $pii->subcategory, 'account')) {
				$found = true;
				break;
			}
		}
		if (! $found) {
			$this->fail('Unable to identify PII');
		}

		$this->assertSame(45, strlen((string) $response->uuid), $response->uuid);
		$this->assertIsString($response->version);

		$requestHistory = $this->rh->getRequestHistoryBodies();
		$this->assertNotEmpty($requestHistory);

		$this->assertSame('{"text":"We\u0027ve paid The Home Depot for your purchase in full so you can spread your $297.46 USD purchase\n\t\t\tinto 4 payments. Here\u0027s your payment schedule:\n\t\t\t$74.37 USD due today\n\t\t\t$74.36 USD on January 3, 2024\n\t\t\t$74.36 USD on January 18, 2024\n\t\t\t$74.37 USD on February 2, 2024\n\t\t\tBoth the total purchase and your down payment amounts will appear in your PayPal activity, but you\u0027re not being\n\t\t\tcharged twice. If dates and amounts change for any reason, we’ll let you know.\n\t\t\tThere\u0027s nothing else you need to do right now. We\u0027ll automatically withdraw your payments from:\n\t\t\tBANK OF AMERICA, N.A.\n\t\t\tBank Account x-1234","sandbox":true}', $requestHistory[$historyCount]);
	}
}
