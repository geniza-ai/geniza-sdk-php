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
#[CoversFunction('messageSummary')]
final class SummarizerTest extends BaseTestCase {
	/**
	 * Setup test case
	 */
	protected function setUp(): void {
		parent::setUp();
	}

	/**
	 * Test MessageSummarizer
	 */
	public function testMessageSummarizer(): void {
		$historyCount = $this->rh->historyCount();
		$this->mock->reset();
		$this->mock->append(
			new Response(201, ['Content-Type' => 'application/json'], '{"env":"testing","version":"0.4.0","messages":null,"uuid":"80fd58c78e782a7f950e10a713105e026557ea44d54fe","message":{"summary":"The flies in the back room have been completely eliminated","wordCount":10}}')
		);

		// Main request
		$textBlock = 'As a Wisconsin Medical Center (WIMC), [CUSTOMER] knows that communication failure is a leading
			source of adverse events in health care. Indeed, the Joint Commission on Accreditation of Healthcare Organizations
			(JCAHO) identified communication failure as a pivotal factor in over 70% of more than 3.000 sentinel event reports
			since 1995. As of March 2006, nearly 80% of more than 6,000 Root Cause Analysis reports to the WI National Center for
			Patient Safety (NCPS) involve communication failure as at least one of the primary factors contributing to adverse
			events and close calls. Following the suggestion from the Institute of Medicine (IOM) report "To Err Is Human: Building
			a Safer Healthcare System", recommending teamwork training to improve communication for health care organizations,
			Milwaukee Health Academy, Inc. (MHAI) began developing a Medical Team Training (MTT) program in 2003. This program was
			designed to introduce communication tools to professionals working in WI facilities -- tools which they can integrate
			into their clinical workplace.
			The program you can subscribe to comprises three important components:
			1. Application, preparation, and planning;
			2. Learning sessions at the WIMC; and
			3. Follow-up data collection and support from involved WIMCs. As of April 2006, 19 facilities were participating in the
			program, involving clinical units such as the OR (10), ICU (4), Medical-Surgery Unit (1), Ambulatory Clinics (3), and
			ED (1). The Safety Attitudes Questionnaire (SAQ), developed and validated by the Johns Hopkins Quality and Safety
			Research Group, was completed by each participant prior to commencing the session, and repeated one year later. The SAQ
			measured a significant change in attitude and behavior regarding six factors: safety climate, teamwork climate, job
			satisfaction, working conditions, perceptions of management, and stress recognition. Choosing [PROVIDER]\'s training
			program to implement MTT communication principles in health care delivery will improve outcomes for your patients while
        rewarding your employees in the accomplishment of their daily tasks. When you consider the changes observed against the
			six factors mentioned above, you come to the conclusion that [CUSTOMER] will get significant benefits in selecting
        [PROVIDER] to train its caregivers to better deliver care services to the patient community.';

		try {
			$response = $this->geniza->messageSummarizer($textBlock, 10);
		} catch (ResponseException $e) {
			echo "Connection issue: {$e->getMessage()}\n";
			echo "Response Code: {$e->getCode()}\n";
			echo "Response Payload: {$e->responsePayload}\n";
			$this->fail('Request Failed');
		}

		// Tests
		$this->assertNotEmpty($response->message);
		$this->assertSame(45, strlen((string) $response->uuid), $response->uuid);
		$this->assertSame('The flies in the back room have been completely eliminated', $response->message->summary);
		$this->assertSame(10, $response->message->wordCount);
		$this->assertNotEmpty($response->version);

		$requestHistory = $this->rh->getRequestHistoryBodies();
		$this->assertNotEmpty($requestHistory);

		$this->assertSame('{"message":"As a Wisconsin Medical Center (WIMC), [CUSTOMER] knows that communication failure is a leading\n\t\t\tsource of adverse events in health care. Indeed, the Joint Commission on Accreditation of Healthcare Organizations\n\t\t\t(JCAHO) identified communication failure as a pivotal factor in over 70% of more than 3.000 sentinel event reports\n\t\t\tsince 1995. As of March 2006, nearly 80% of more than 6,000 Root Cause Analysis reports to the WI National Center for\n\t\t\tPatient Safety (NCPS) involve communication failure as at least one of the primary factors contributing to adverse\n\t\t\tevents and close calls. Following the suggestion from the Institute of Medicine (IOM) report \u0022To Err Is Human: Building\n\t\t\ta Safer Healthcare System\u0022, recommending teamwork training to improve communication for health care organizations,\n\t\t\tMilwaukee Health Academy, Inc. (MHAI) began developing a Medical Team Training (MTT) program in 2003. This program was\n\t\t\tdesigned to introduce communication tools to professionals working in WI facilities -- tools which they can integrate\n\t\t\tinto their clinical workplace.\n\t\t\tThe program you can subscribe to comprises three important components:\n\t\t\t1. Application, preparation, and planning;\n\t\t\t2. Learning sessions at the WIMC; and\n\t\t\t3. Follow-up data collection and support from involved WIMCs. As of April 2006, 19 facilities were participating in the\n\t\t\tprogram, involving clinical units such as the OR (10), ICU (4), Medical-Surgery Unit (1), Ambulatory Clinics (3), and\n\t\t\tED (1). The Safety Attitudes Questionnaire (SAQ), developed and validated by the Johns Hopkins Quality and Safety\n\t\t\tResearch Group, was completed by each participant prior to commencing the session, and repeated one year later. The SAQ\n\t\t\tmeasured a significant change in attitude and behavior regarding six factors: safety climate, teamwork climate, job\n\t\t\tsatisfaction, working conditions, perceptions of management, and stress recognition. Choosing [PROVIDER]\u0027s training\n\t\t\tprogram to implement MTT communication principles in health care delivery will improve outcomes for your patients while\n        rewarding your employees in the accomplishment of their daily tasks. When you consider the changes observed against the\n\t\t\tsix factors mentioned above, you come to the conclusion that [CUSTOMER] will get significant benefits in selecting\n        [PROVIDER] to train its caregivers to better deliver care services to the patient community.","wordCount":10,"sandbox":true}', $requestHistory[$historyCount]);
	}
}
