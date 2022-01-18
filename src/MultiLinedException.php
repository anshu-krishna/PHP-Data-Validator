<?php
namespace Krishna\DataValidator;

class MultiLinedException extends \Exception {
	private array $info = [];
	public function __construct(array|string $message, ?string $prefix = null, int $code = 0, ?\Throwable $previous = null) {
		parent::__construct('Use getInfo() for messages', $code, $previous);
		if(is_string($message)) {
			$message = [$message];
		}
		$prefix = $prefix === null ? '' : "[{$prefix}]: ";
		foreach($message as $m) {
			$this->info[] = "{$prefix}{$m}";
		}
		$this->message = '[' . implode(", ", $this->info) . ']';
	}
	public function getInfo() :array {
		return $this->info;
	}
	public function __toString(): string {
		return $this->message;
	}
}