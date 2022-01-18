<?php
namespace Krishna\DataValidator;

class ArrayHandler {
	public readonly array $list;
	public readonly bool $single;
	public function __construct(array $list, public readonly bool $trimOutOfBound = false) {
		$error = [];
		for ($i = 0, $j = count($list); $i < $j; $i++) {
			$item = &$list[$i];
			try {
				$item = match(true) {
					is_string($item) => new TypeHandler($item),
					is_array($item) => new static($item, $this->trimOutOfBound),
					is_object($item) => new ObjectHandler($item, $this->trimOutOfBound),
					default => throw new MultiLinedException('{' . strval($item) . '}; Invalid value', $i)
				};
			} catch(MultiLinedException $th) {
				foreach($th->getInfo() as $m) {
					$error[] = "[{$i}]: {$m}";
				}
			}
		}
		if(count($error) > 0) {
			throw new MultiLinedException($error);
		}
		$this->list = $list;
		$this->single = count($this->list) === 1;
	}
	public function validate($vals) : Returner {
		if(!is_array($vals)) {
			return Returner::invalid('Expecting array');
		}
		$ret = [];
		$error = [];
		$found = [];
		foreach($vals as $key => $val) {
			$found[] = $key;
			$handler = $this->list[$this->single ? 0 : $key] ?? false;
			if($handler === false) {
				if(!$this->trimOutOfBound) {
					$error[$key] = 'Out of bounds';
				}
				continue;
			}
			$res = $handler->validate($vals[$key]);
			if($res->valid) {
				$ret[$key] = $res->value;
			} else {
				$error[$key] = $res->error;
			}
		}
		if(!$this->single) {
			foreach(array_diff(range(0, count($this->list) - 1), $found) as $k) {
				$error[$k] = "Missing";
			}
		}
		if(count($error) === 0) {
			return Returner::valid($ret);
		}
		ksort($error);
		return Returner::invalid($error);
	}
}