<?php
namespace Krishna\DataValidator;

class ErrorReader {
	public array $errors = [];
	private ?string $_errorstring = null;
	public function __construct(array $errors) {
		$this->_traverse($errors);
	}
	private function _traverse(array $a, string $pre = '') {
		foreach($a as $k=>$v) {
			if(is_array($v)) {
				$this->_traverse($v, "{$pre}[${k}]");
			} else {
				$this->errors[] = "{$pre}[{$k}]: {$v}";
			}
		}
	}
	public function __toString() : string {
		$this->_errorstring ??= json_encode($this->errors, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR);
		return $this->_errorstring;
	}
	public function __debugInfo() {
		return $this->errors;
	}
}