<?php
declare(strict_types=1);
namespace Geniza\Request;

use Geniza\Config\Singleton;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class RequestHandler extends Singleton {
	private ?HandlerStack $handlerStack = null;
	private ?MockHandler $mock          = null;

	/**
	 * MiddleWare history container
	 */
	private array $historyContainer = [];

	/**
	 * {@inheritDoc}
	 */
	public function __construct(bool $mockHandler = false) {
		if ($mockHandler) {
			$this->mock         = new MockHandler([]);
			$this->handlerStack = HandlerStack::create($this->mock);

			// Add the history middleware to the handler stack.
			$history = Middleware::history($this->historyContainer);
			$this->handlerStack->push($history);
		}
	}

	public function getHandler(): ?MockHandler {
		return $this->mock;
	}

	public function guzzleOptions(): array {
		if (isset($this->mock)) {
			return ['handler' => $this->handlerStack];
		}

		return [];
	}

	public function historyCount(): int {
		return count($this->historyContainer);
	}

	public function getRequestHistoryBodies(): array {
		$history = [];

		foreach ($this->historyContainer as $transaction) {
			$history[] = (string) $transaction['request']->getBody();
		}

		return $history;
	}
}
