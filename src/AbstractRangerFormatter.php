<?php
namespace Krishna\DataValidator;

abstract class AbstractRangerFormatter {
	protected static array $STORE = [];
	protected static int $COUNTER = -1;
	protected ?int $id = null;
	public static function get(int|string $id) : ?static {
		return static::$STORE[$id] ?? null;
	}
	protected function getid() : int {
		if($this->id === null) {
			static::$STORE[] = $this;
			$this->id = ++static::$COUNTER;
		}
		return $this->id;
	}
	public function __toString(): string {
		return '\\' . static::class . ';' . $this->getid();
	}
	abstract public function exec($result) : Returner;
}